<?php

namespace App\Services\User\Farm;

use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class FarmFilterService
{
    public function filter(array $filters)
    {
        $userId = Auth::guard('user')->id();

        // أعمدة LIKE (نصية)
        $likeColumns = ['name', 'location', 'crop_type', 'irrigation_type', 'soil_type', 'notes'];

        $query = Farm::query()
            ->where('user_id', $userId);

        // 1) بحث عام across columns: q
        if (!empty($filters['q']) && is_string($filters['q'])) {
            $q = trim($filters['q']);
            $q = str_replace('%', '\%', $q); // هروب %
            $query->where(function ($w) use ($likeColumns, $q) {
                foreach ($likeColumns as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            });
        }

        // 2) فلاتر نصية لكل عمود (LIKE)
        foreach ($likeColumns as $col) {
            if (array_key_exists($col, $filters)) {
                $v = is_string($filters[$col]) ? trim($filters[$col]) : null;
                if ($v !== null && $v !== '') {
                    $v = str_replace('%', '\%', $v);
                    $query->where($col, 'like', "%{$v}%");
                }
            }
        }

        // 3) مدى المساحة
        if (isset($filters['area_min']) && $filters['area_min'] !== '') {
            $query->where('area', '>=', (float) $filters['area_min']);
        }
        if (isset($filters['area_max']) && $filters['area_max'] !== '') {
            $query->where('area', '<=', (float) $filters['area_max']);
        }

        // 4) الحالة: قيمة واحدة أو مصفوفة
        if (array_key_exists('status', $filters)) {
            $status = $filters['status'];
            if (is_array($status)) {
                $status = array_values(array_filter($status, fn($v) => is_string($v) && $v !== ''));
                if ($status) {
                    $query->whereIn('status', $status);
                }
            } else {
                $status = trim((string) $status);
                if ($status !== '') {
                    $query->where('status', $status); // أو like لو حاب
                }
            }
        }

        // 5) مدى التاريخ (الإنشاء)
        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        // 6) الترتيب
        $sortable = ['name', 'area', 'created_at', 'updated_at'];
        $sortBy  = in_array(($filters['sort_by'] ?? ''), $sortable, true) ? $filters['sort_by'] : 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // 7) حد أقصى اختياري
        if (isset($filters['limit']) && (int) $filters['limit'] > 0) {
            $query->limit((int) $filters['limit']);
        }

        $farms = $query->get();

        return response()->json([
            'message' => 'نتائج الفلترة',
            'data'    => FarmResource::collection($farms),
            'filters' => $filters,
        ]);
    }
}
