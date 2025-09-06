<?php

namespace App\Services\User\Inventory;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryFilterService
{
    public function filter(array $filters)
    {
        $userId = Auth::guard('user')->id();

        // أعمدة LIKE النصية
        $likeColumns = ['name', 'type', 'unit', 'supplier', 'storage_location', 'notes'];

        $query = Inventory::query()->where('user_id', $userId);

        // 1) بحث عام q عبر أعمدة نصية
        if (!empty($filters['q']) && is_string($filters['q'])) {
            $q = trim($filters['q']);
            $q = str_replace('%', '\%', $q);
            $query->where(function ($w) use ($likeColumns, $q) {
                foreach ($likeColumns as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            });
        }

        // 2) فلاتر نصية منفصلة
        foreach ($likeColumns as $col) {
            if (array_key_exists($col, $filters)) {
                $v = is_string($filters[$col]) ? trim($filters[$col]) : null;
                if ($v !== null && $v !== '') {
                    $v = str_replace('%', '\%', $v);
                    $query->where($col, 'like', "%{$v}%");
                }
            }
        }

        // 3) مدى الكمية
        if (isset($filters['min_quantity']) && $filters['min_quantity'] !== '') {
            $query->where('quantity', '>=', (float)$filters['min_quantity']);
        }
        if (isset($filters['max_quantity']) && $filters['max_quantity'] !== '') {
            $query->where('quantity', '<=', (float)$filters['max_quantity']);
        }

        // 4) مدى تاريخ الشراء
        if (!empty($filters['purchase_from'])) {
            $query->whereDate('purchase_date', '>=', $filters['purchase_from']);
        }
        if (!empty($filters['purchase_to'])) {
            $query->whereDate('purchase_date', '<=', $filters['purchase_to']);
        }

        // 5) مدى تاريخ الانتهاء
        if (!empty($filters['expiry_from'])) {
            $query->whereDate('expiry_date', '>=', $filters['expiry_from']);
        }
        if (!empty($filters['expiry_to'])) {
            $query->whereDate('expiry_date', '<=', $filters['expiry_to']);
        }

        // 6) الترتيب
        $sortable = ['name', 'quantity', 'purchase_date', 'expiry_date', 'created_at', 'updated_at'];
        $sortBy  = in_array(($filters['sort_by'] ?? ''), $sortable, true) ? $filters['sort_by'] : 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // 7) حد أقصى
        if (isset($filters['limit']) && (int)$filters['limit'] > 0) {
            $query->limit((int)$filters['limit']);
        }

        $items = $query->get();

        return response()->json([
            'message' => 'نتائج الفلترة',
            'data'    => InventoryResource::collection($items),
            'filters' => $filters,
        ]);
    }
}
