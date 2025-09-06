<?php

namespace App\Services\User\CropSale;

use App\Http\Resources\CropSaleResource;
use App\Models\CropSale;
use Illuminate\Support\Facades\Auth;

class CropSaleListService
{

    public function listUserCropSales()
    {
        $sales = CropSale::where('user_id', Auth::guard('user')->id())
            ->orderBy('sale_date', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة عمليات البيع',
            'data'    => CropSaleResource::collection($sales),
        ]);
    }
}
