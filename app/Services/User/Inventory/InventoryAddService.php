<?php

namespace App\Services\User\Inventory;

use App\Http\Requests\InventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryAddService
{
    /**
     * إضافة مخزون جديد للمستخدم.
     */
    public function createInventory(InventoryRequest $request)
    {
        // إنشاء المخزون
        $inventory = Inventory::create([
            'user_id'         => Auth::guard('user')->id(),
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
            'message' => 'تمت إضافة المخزون بنجاح',
            'data'    => new InventoryResource($inventory)
        ], 201);
    }
}
