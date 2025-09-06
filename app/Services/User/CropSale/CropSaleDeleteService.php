<?php

namespace App\Services\User\CropSale;

use App\Models\CropSale;
use Illuminate\Support\Facades\Auth;

class CropSaleDeleteService
{
    /**
     * حذف عملية بيع
     */
    public function delete($id)
    {
        $sale = CropSale::where('user_id', Auth::guard('user')->id())->find($id);

        if (!$sale) {
            return response()->json([
                'message' => 'عملية البيع غير موجودة أو لا تملك صلاحية لحذفها'
            ], 404);
        }

        $sale->delete();

        return response()->json([
            'message' => 'تم حذف عملية البيع بنجاح'
        ], 200);
    }
}
