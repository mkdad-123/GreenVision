<?php

namespace App\Services\User\Equipment;

use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentDeleteService
{
    public function delete($id)
    {
        $equipment = Equipment::where('user_id', Auth::guard('user')->id())->find($id);
            if (!$equipment){
            return response()->json([
            'message' => ' لا تملك الصلاحيات للقيام بالحذف على هذه الأداة',
            'data' => []
        ]);
            }
        $equipment->delete();

        return response()->json([
            'message' => 'تم حذف المعدة بنجاح'
        ], 200);
    }
}
