<?php

namespace App\Services\User\Equipment;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentFilterService
{
    public function filter($filters)
    {
        $userId = Auth::guard('user')->id();

        $query = Equipment::query()->where('user_id', $userId);

        // الأعمدة المسموح البحث/الفلترة عليها
        $searchable = [
            'name',
            'serial_number',
            'type',
            'location',
            'status',
            'usage_hours',
            'purchase_date',
            'last_maintenance',
            'next_maintenance',
            'notes',
        ];

        // بحث عام q على جميع الحقول القابلة للبحث (OR)
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $query->where(function ($sub) use ($q, $searchable) {
                foreach ($searchable as $col) {
                    $sub->orWhere($col, 'like', "%{$q}%");
                }
            });
        }

        // فلاتر مفاتيح محددة (AND)
        foreach ($searchable as $key) {
            if (isset($filters[$key]) && $filters[$key] !== '' && $filters[$key] !== null) {
                $value = $filters[$key];

                // لو أردت مطابقة التاريخ بدقة استخدم whereDate، وإلا اترك like
                if (in_array($key, ['purchase_date', 'last_maintenance', 'next_maintenance'], true)) {
                    // مطابقة دقيقة على تاريخ YYYY-MM-DD إن أُرسِل كذلك
                    $query->whereDate($key, $value);
                } else {
                    $query->where($key, 'like', "%{$value}%");
                }
            }
        }

        $equipments = $query->get();

        return EquipmentResource::collection($equipments);
    }
}
