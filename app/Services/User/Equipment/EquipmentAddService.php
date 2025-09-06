<?php

namespace App\Services\User\Equipment;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentAddService
{

    public function createEquipment(EquipmentRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::guard('user')->id();

        $equipment = Equipment::create($data);

        return response()->json([
            'message' => 'تم إضافة المعدّة بنجاح',
            'data'    => new EquipmentResource($equipment),
        ], 201);
    }
}
