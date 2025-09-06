<?php

namespace App\Services\User\Task;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskFilterService
{
    public function filter(array $filters)
    {
        $userId = Auth::guard('user')->id();

        // اجلب المهام مع علاقة farm
        $query = Task::with('farm')->where('user_id', Auth::guard('user')->id());

        // بحث عام q عبر أعمدة نصّية
        $likeCols = ['type', 'description', 'repeat_interval', 'status', 'priority'];
        if (!empty($filters['q']) && is_string($filters['q'])) {
            $q = trim($filters['q']);
            $q = str_replace('%', '\%', $q);
            $query->where(function ($w) use ($likeCols, $q) {
                foreach ($likeCols as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
                // بحث تقريبي في التاريخ كسلسلة
                $w->orWhere('date', 'like', "%{$q}%");
            });
        }

        // فلاتر محددة
        if (!empty($filters['farm_id'])) {
            $query->where('farm_id', (int) $filters['farm_id']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', 'like', '%' . str_replace('%','\%',$filters['type']) . '%');
        }
        // status يدعم قيمة واحدة أو مصفوفة
        if (array_key_exists('status', $filters) && $filters['status'] !== '' && $filters['status'] !== null) {
            if (is_array($filters['status'])) {
                $vals = array_values(array_filter($filters['status'], fn($v)=>is_string($v)&&$v!==''));
                if ($vals) $query->whereIn('status', $vals);
            } else {
                $query->where('status', $filters['status']);
            }
        }
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (!empty($filters['repeat_interval'])) {
            $query->where('repeat_interval', 'like', '%' . str_replace('%','\%',$filters['repeat_interval']) . '%');
        }

        // مدى التاريخ
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        // الفرز
        $allowedSorts = ['id','date','priority','status','type','created_at','updated_at'];
        $sortBy  = in_array($filters['sort_by'] ?? '', $allowedSorts, true) ? $filters['sort_by'] : 'date';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // الباجينيشن (يدعم per_page)
        if (!empty($filters['per_page'])) {
            $perPage = max(1, (int) $filters['per_page']);
            $paginator = $query->paginate($perPage); // تلقائيًا يأخذ ?page=
            return response()->json([
                'message' => 'نتائج فلترة المهام (مقسمة صفحات)',
                'meta'    => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                ],
                'data' => TaskResource::collection($paginator->items()),
            ]);
        }

        $tasks = $query->get();
        return response()->json([
            'message' => 'نتائج فلترة المهام',
            'data'    => TaskResource::collection($tasks),
        ]);
    }
}
