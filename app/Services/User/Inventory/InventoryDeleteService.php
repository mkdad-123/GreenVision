<?php

namespace App\Services\User\Inventory;

use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InventoryDeleteService
{
    /**
     * حذف عنصر من المخزون
     */
    public function delete($id)
    {
        $inventory = Inventory::where('id', $id)
            ->where('user_id', Auth::guard('user')->id())
            ->first();

        if (!$inventory) {
            return response()->json([
                'message' => 'المخزون غير موجود أو لا تملك صلاحية لحذفه'
            ], 404);
        }

        $inventory->delete();

        return response()->json([
            'message' => 'تم حذف المخزون بنجاح'
        ]);
    }
}
