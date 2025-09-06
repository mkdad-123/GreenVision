<?php

namespace App\Services\User\CropSale;

use App\Http\Requests\CropSaleUpdateRequest;
use App\Http\Resources\CropSaleResource;
use App\Models\CropSale;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class CropSaleEditService
{
    public function update(CropSaleUpdateRequest $request, $id)
    {
        // نجيب السجل ونتأكد أنه ملك للمستخدم
        $sale = CropSale::where('id', $id)
            ->where('user_id',Auth::guard('user')->id())
            ->first();

        if (!$sale) {
            return response()->json([
                'message' => 'عملية البيع غير موجودة أو لا تملك صلاحية التعديل'
            ], 404);
        }

        $data = $request->validated();

        // لو تم تمرير farm_id، تأكد أنها ملك لنفس المستخدم
        if (isset($data['farm_id'])) {
            $farm = Farm::where('id', $data['farm_id'])
                ->where('user_id', Auth::guard('user')->id())
                ->first();

            if (!$farm) {
                return response()->json([
                    'message' => 'لا يمكنك ربط العملية بمزرعة لا تملكها'
                ], 422);
            }
        }

        // اسمح بتعديل أي حقل (كل ما يسمح به الـ Request)
        $sale->fill($data);

        // إن لم يرسل total_price لكن تغيّر quantity أو price_per_unit—احسب تلقائياً
        $quantity       = $data['quantity']       ?? $sale->quantity;
        $pricePerUnit   = $data['price_per_unit'] ?? $sale->price_per_unit;

        if (!isset($data['total_price']) && ($request->has('quantity') || $request->has('price_per_unit'))) {
            $sale->total_price = (float) $quantity * (float) $pricePerUnit;
        }

        $sale->save();
        $sale->load('farm');


        return response()->json([
            'message' => 'تم تحديث عملية البيع بنجاح',
            'data'    => new CropSaleResource($sale),
        ], 200);
    }
}
