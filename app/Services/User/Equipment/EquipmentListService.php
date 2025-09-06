<?php

namespace App\Services\User\Equipment;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentListService
{
    public function list()
    {
        $equipments = Equipment::where('user_id', Auth::guard('user')->id())->get();
        return EquipmentResource::collection($equipments);
    }
}
