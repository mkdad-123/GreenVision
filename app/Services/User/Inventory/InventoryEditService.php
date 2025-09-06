<?php

namespace App\Services\User\Inventory;

use App\Http\Requests\InventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryEditService
{
    /**
     * تعديل مخزون موجود للمستخدم.
     */
    public function updateInventory(InventoryRequest $request, $id)
    {
        // البحث عن المخزون والتأكد أنه للمستخدم الحالي
        $inventory = Inventory::where('user_id', Auth::guard('user')->id())->find($id);
        if (!$inventory){
        return response()->json([
            'message' => 'هذا المخزون غير موجود أو لا تملك صلاحيات التعديل',
            'data'    => []
        ]);
        }
        // التعديل
        $inventory->update([
            'name'            => $request->name,
            'type'            => $request->type,
            'quantity'        => $request->quantity,
            'unit'            => $request->unit,
            'purchase_date'   => $request->purchase_date,
            'expiry_date'     => $request->expiry_date,
            'min_threshold'   => $request->min_threshold ?? 0,
            'supplier'        => $request->supplier,
            'storage_location'=> $request->storage_location,
            'notes'           => $request->notes,
        ]);

        return response()->json([
            'message' => 'تم تعديل المخزون بنجاح',
            'data'    => new InventoryResource($inventory)
        ]);
    }
}
