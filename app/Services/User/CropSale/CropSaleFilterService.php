<?php

namespace App\Services\User\CropSale;

use App\Http\Resources\CropSaleResource;
use App\Models\CropSale;
use Illuminate\Support\Facades\Auth;

class CropSaleFilterService
{
    public function filter(array $filters)
    {
        $query = CropSale::with('farm')
            ->where('user_id', Auth::guard('user')->id());

        // 1) بحث عام q عبر أعمدة نصية
        if (!empty($filters['q']) && is_string($filters['q'])) {
            $q = trim($filters['q']);
            $q = str_replace('%', '\%', $q);
            $textCols = ['crop_name', 'buyer_name', 'delivery_location', 'unit', 'status', 'notes'];
            $query->where(function ($w) use ($textCols, $q) {
                foreach ($textCols as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            });
        }

        // 2) فلاتر نصية محددة (LIKE أو = حسب المناسب)
        if (!empty($filters['crop_name'])) {
            $query->where('crop_name', 'like', '%' . str_replace('%','\%',$filters['crop_name']) . '%');
        }
        if (!empty($filters['unit'])) {
            $query->where('unit', 'like', '%' . str_replace('%','\%',$filters['unit']) . '%');
        }
        if (!empty($filters['buyer_name'])) {
            $query->where('buyer_name', 'like', '%' . str_replace('%','\%',$filters['buyer_name']) . '%');
        }
        if (!empty($filters['delivery_location'])) {
            $query->where('delivery_location', 'like', '%' . str_replace('%','\%',$filters['delivery_location']) . '%');
        }
        if (isset($filters['status']) && $filters['status']!=='') {
            $status = $filters['status'];
            if (is_array($status)) $query->whereIn('status', $status);
            else $query->where('status', $status);
        }

        // 3) مفاتيح أجنبية
        if (!empty($filters['farm_id'])) {
            $query->where('farm_id', (int)$filters['farm_id']);
        }

        // 4) نطاق التاريخ (sale_date)
        if (!empty($filters['sale_date_from'])) {
            $query->whereDate('sale_date', '>=', $filters['sale_date_from']);
        }
        if (!empty($filters['sale_date_to'])) {
            $query->whereDate('sale_date', '<=', $filters['sale_date_to']);
        }

        // 5) نطاقات رقمية
        if ($filters['quantity_min'] ?? null)      $query->where('quantity', '>=', (float)$filters['quantity_min']);
        if ($filters['quantity_max'] ?? null)      $query->where('quantity', '<=', (float)$filters['quantity_max']);
        if ($filters['price_per_unit_min'] ?? null)$query->where('price_per_unit', '>=', (float)$filters['price_per_unit_min']);
        if ($filters['price_per_unit_max'] ?? null)$query->where('price_per_unit', '<=', (float)$filters['price_per_unit_max']);
        if ($filters['total_price_min'] ?? null)   $query->where('total_price', '>=', (float)$filters['total_price_min']);
        if ($filters['total_price_max'] ?? null)   $query->where('total_price', '<=', (float)$filters['total_price_max']);

        // 6) الترتيب
        $allowedSorts = ['id','sale_date','quantity','price_per_unit','total_price','crop_name','status','updated_at','created_at'];
        $sortBy  = in_array($filters['sort_by'] ?? '', $allowedSorts, true) ? $filters['sort_by'] : 'sale_date';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc'); $sortDir = in_array($sortDir, ['asc','desc'], true) ? $sortDir : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // 7) صفحات (افتراضي 5 كما في الواجهة)
        $perPage = (int)($filters['per_page'] ?? 5);
        if ($perPage > 0) {
            $paginator = $query->paginate($perPage)->appends($filters);
            return response()->json([
                'message' => 'نتائج الفلترة (مقسمة صفحات)',
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                ],
                'data' => CropSaleResource::collection($paginator->items()),
            ]);
        }

        // بدون صفحات
        $sales = $query->get();
        return response()->json([
            'message' => 'نتائج الفلترة',
            'data'    => CropSaleResource::collection($sales),
        ]);
    }
}
