<?php

namespace App\Services\User\CropSale;

use App\Http\Requests\CropSaleRequest;
use App\Http\Resources\CropSaleResource;
use App\Models\CropSale;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CropSaleAddService
{
    /**
     * إنشاء عملية بيع محصول جديدة للمستخدم الحالي
     */
    public function create(CropSaleRequest $request)
    {
        $data = $request->validated();

        // تأكيد ملكية المزرعة للمستخدم الحالي
        $farm = Farm::where('id', $data['farm_id'])
                    ->where('user_id', Auth::guard('user')->id())
                    ->first();

        if (!$farm) {
            return response()->json([
                'message' => 'المزرعة غير موجودة أو لا تملك صلاحية عليها',
            ], 403);
        }

        // حساب السعر الإجمالي إن لم يُرسل
        if (empty($data['total_price'])) {
            $data['total_price'] = (float)$data['quantity'] * (float)$data['price_per_unit'];
        }

        // ربط العملية بالمستخدم الحالي
        $data['user_id'] = Auth::guard('user')->id();

        try {
            return DB::transaction(function () use ($data) {
                $sale = CropSale::create($data);
                $sale->load('farm'); // << مهم


                return response()->json([
                    'message' => 'تم إضافة عملية البيع بنجاح',
                    'data'    => new CropSaleResource($sale),
                ], 201);
            });
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء الحفظ',
                'error'   => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
            ], 500);
        }
    }
}
