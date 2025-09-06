<?php

namespace App\Services\User\Equipment;

use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentEditService
{
    public function updateEquipment($request, $id)
    {
        $equipment = Equipment::where('user_id', Auth::guard('user')->id())->find($id);

        if (!$equipment){
            return response()->json([
            'message' => ' لا تملك الصلاحيات للتعديل على هذه الأداة',
            'data' => []
        ]);
            }

        $equipment->update($request->only([
            'name',
            'serial_number',
            'purchase_date',
            'last_maintenance',
            'next_maintenance',
            'status',
            'type',
            'location',
            'usage_hours',
            'notes'
        ]));

        return response()->json([
            'message' => 'تم تعديل بيانات المعدة بنجاح',
            'data' => $equipment
        ]);
    }
}
