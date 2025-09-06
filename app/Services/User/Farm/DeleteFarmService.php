<?php

namespace App\Services\User\Farm;

use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class DeleteFarmService
{
    public function deleteFarm($id)
    {
        $farm = Farm::where('id', $id)
                    ->where('user_id', Auth::guard('user')->id())
                    ->first();

        if (!$farm) {
            return response()->json([
                'message' => 'المزرعة غير موجودة أو لا تملك صلاحية الحذف'
            ], 404);
        }

        $farm->delete();

        return response()->json([
            'message' => 'تم حذف المزرعة بنجاح'
        ]);
    }
}
