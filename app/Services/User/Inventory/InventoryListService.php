<?php

namespace App\Services\User\Inventory;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryListService
{
    /**
     * عرض كل المخزون الخاص بالمستخدم الحالي
     */
    public function listUserInventory()
    {
        // جلب المخزون للمستخدم الحالي
        $inventories = Inventory::where('user_id', Auth::guard('user')->id())->get();

        return response()->json([
            'message' => 'قائمة المخزون',
            'data'    => InventoryResource::collection($inventories)
        ]);
    }
}
