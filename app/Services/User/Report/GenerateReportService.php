<?php

namespace App\Services\User\Report;

use App\Models\FinancialRecord;
use App\Models\CropSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenerateReportService
{
    /**
     * ملاحظة: لو كنت تخزّن تلميح المزرعة داخل الوصف بشكل "farm:ID"
     * يمكنك تفعيل السطر التالي ليُستخدم كخطة بديلة عندما لا يوجد عمود farm_id.
     */
    private const USE_DESC_FARM_HINT = false;

    /**
     * إنشاء تقرير مالي وتجاري بين تاريخين مع خيار التصفية بمزرعة/حالة المبيع.
     *
     * @param  string      $from        تاريخ البداية (Y-m-d)
     * @param  string      $to          تاريخ النهاية (Y-m-d)
     * @param  int|null    $farmId      (اختياري) فلترة على مزرعة معينة
     * @param  string|null $saleStatus  (اختياري) افتراضي "تم البيع"
     * @return array
     */
    public function generate(string $from, string $to, ?int $farmId = null, ?string $saleStatus = 'تم البيع'): array
    {
        $userId = Auth::guard('user')->id() ?? Auth::id();

        // توحيد القيم المقبولة لحقل direction تفاديًا لاختلاف الصياغة
        $incomeDirs  = ['دخل', 'الدخل', 'income', 'INCOME'];
        $expenseDirs = ['نفقات', 'مصروف', 'المصروفات', 'expenses', 'expense', 'EXPENSE'];

        // ====== Queries أساسيات ======
        // الدخل
        $incomeQuery = FinancialRecord::query()
            ->where('user_id', $userId)
            ->whereIn('direction', $incomeDirs)
            ->whereBetween('date', [$from, $to]);
        $this->applyFarmFilterToFinancial($incomeQuery, $farmId);

        // النفقات
        $expenseQuery = FinancialRecord::query()
            ->where('user_id', $userId)
            ->whereIn('direction', $expenseDirs)
            ->whereBetween('date', [$from, $to]);
        $this->applyFarmFilterToFinancial($expenseQuery, $farmId);

        // المبيعات
        $salesQuery = CropSale::query()
            ->where('user_id', $userId)
            ->whereBetween('sale_date', [$from, $to])
            ->when($farmId, fn($q) => $q->where('farm_id', $farmId))
            ->when($saleStatus, fn($q) => $q->where('status', $saleStatus));

        // ====== إجماليات ======
        $totalIncome    = (float) (clone $incomeQuery)->sum('amount');
        $totalExpense   = (float) (clone $expenseQuery)->sum('amount');
        $totalSalesAmt  = (float) (clone $salesQuery)->sum('total_price');
        $salesCount     = (int)   (clone $salesQuery)->count();

        $grossRevenue   = $totalIncome + $totalSalesAmt;
        $netProfit      = $grossRevenue - $totalExpense;

        // ====== تفصيلات ======
        $incomeByCategory  = $this->groupFinancialByCategory($userId, $from, $to, $incomeDirs,  $farmId);
        $expenseByCategory = $this->groupFinancialByCategory($userId, $from, $to, $expenseDirs, $farmId);

        $salesByCrop = (clone $salesQuery)
            ->select(
                'crop_name',
                DB::raw('SUM(total_price) as total_sales'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('crop_name')
            ->orderByDesc('total_sales')
            ->get()
            ->toArray();

        // ====== نعيد مصفوفة موحّدة ======
        return [
            'message' => 'تم توليد التقرير بنجاح',
            'period'  => [
                'from' => $from,
                'to'   => $to,
            ],
            'filters' => [
                'farm_id'     => $farmId,
                'sale_status' => $saleStatus,
            ],
            'totals'  => [
                'sales_count'     => $salesCount,
                'income'          => $totalIncome,
                'expenses'        => $totalExpense,
                'sales'           => $totalSalesAmt,
                'gross_revenue'   => $grossRevenue,     // = income + sales
                'net_profit'      => $netProfit,        // = gross_revenue - expenses
                'net_is_positive' => $netProfit >= 0,
            ],
            'breakdowns' => [
                'income_by_category'  => $incomeByCategory,
                'expense_by_category' => $expenseByCategory,
                'sales_by_crop'       => $salesByCrop,
            ],
        ];
    }

    /**
     * تجميع السجلات المالية حسب التصنيف.
     */
    private function groupFinancialByCategory(
        int $userId,
        string $from,
        string $to,
        array $directions,
        ?int $farmId = null
    ): array {
        $q = FinancialRecord::query()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->whereIn('direction', $directions)
            ->whereBetween('date', [$from, $to]);

        $this->applyFarmFilterToFinancial($q, $farmId);

        return $q->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    /**
     * تطبيق فلترة المزرعة على السجلات المالية:
     * - إن وُجد عمود farm_id نستخدمه.
     * - وإلا (اختياريًا) يمكن استخدام تلميح داخل الوصف "farm:ID" إذا كانت الخاصية مفعّلة.
     */
    private function applyFarmFilterToFinancial($query, ?int $farmId): void
    {
        if (!$farmId) {
            return;
        }

        if (Schema::hasColumn('financial_records', 'farm_id')) {
            $query->where('farm_id', $farmId);
            return;
        }

        if (self::USE_DESC_FARM_HINT) {
            $query->where('description', 'like', "%farm:$farmId%");
        }
        // إن لم يكن هناك عمود farm_id ولم نفعل التلميح النصّي، نتجنب الفلترة كي لا نخسر بيانات الدخل/النفقات.
    }
}
