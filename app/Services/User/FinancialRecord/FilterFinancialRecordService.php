<?php

namespace App\Services\User\FinancialRecord;

use App\Http\Resources\FinancialRecordResource;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class FilterFinancialRecordService
{
    /**
     * فلترة السجلات المالية مع دعم:
     * - q بحث عام عبر أعمدة نصية (فقط الأعمدة الموجودة فعلًا)
     * - فلاتر أعمدة محددة (LIKE أو مساواة حسب العمود)
     * - مدى المبلغ (amount_min / amount_max)
     * - مدى التاريخ (date_from / date_to)
     * - فرز sort_by/sort_dir
     * - تقسيم صفحات per_page (+ meta)
     */
    public function filter(array $filters)
    {
        $userId = Auth::guard('user')->id();
        $table  = (new FinancialRecord)->getTable();

        $query = FinancialRecord::query()->where('user_id', $userId);

        // أعمدة نصية للبحث العام q (نتحقق من وجودها أولًا)
        $likeColumns = ['description','notes','category','payment_method','type','reference'];

        if (!empty($filters['q']) && is_string($filters['q'])) {
            $q = trim($filters['q']);
            $q = str_replace('%', '\%', $q);
            $query->where(function ($w) use ($likeColumns, $q, $table) {
                foreach ($likeColumns as $col) {
                    if (Schema::hasColumn($table, $col)) {
                        $w->orWhere($col, 'like', "%{$q}%");
                    }
                }
            });
        }

        // فلاتر نصية محددة
        foreach (['type','category','payment_method','description','notes','reference'] as $col) {
            if (!empty($filters[$col]) && Schema::hasColumn($table, $col)) {
                $v = trim((string)$filters[$col]);
                $query->where($col, 'like', "%{$v}%");
            }
        }

        // مدى المبلغ
        if (Schema::hasColumn($table, 'amount')) {
            if (isset($filters['amount_min']) && $filters['amount_min'] !== '') {
                $query->where('amount', '>=', (float)$filters['amount_min']);
            }
            if (isset($filters['amount_max']) && $filters['amount_max'] !== '') {
                $query->where('amount', '<=', (float)$filters['amount_max']);
            }
        }

        // مدى التاريخ
        if (Schema::hasColumn($table, 'date')) {
            if (!empty($filters['date_from'])) {
                $query->whereDate('date', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('date', '<=', $filters['date_to']);
            }
        }

        // الفرز
        $allowedSorts = ['id','date','amount','type','category','created_at','updated_at'];
        $sortBy  = in_array(($filters['sort_by'] ?? ''), $allowedSorts, true) ? $filters['sort_by'] : 'date';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');
        $sortDir = in_array($sortDir, ['asc','desc'], true) ? $sortDir : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // صفحات
        if (!empty($filters['per_page'])) {
            $perPage   = max(1, (int)$filters['per_page']);
            $paginator = $query->paginate($perPage);

            return response()->json([
                'message' => 'نتائج الفلترة (مقسمة لصفحات)',
                'meta'    => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                ],
                'data'    => FinancialRecordResource::collection($paginator->items()),
            ]);
        }

        $records = $query->get();

        return response()->json([
            'message' => 'نتائج الفلترة',
            'data'    => FinancialRecordResource::collection($records),
        ]);
    }
}
