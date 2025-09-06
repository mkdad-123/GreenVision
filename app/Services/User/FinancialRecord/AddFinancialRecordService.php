<?php

namespace App\Services\User\FinancialRecord;

use App\Http\Requests\FinancialRecordRequest;
use App\Http\Resources\FinancialRecordResource;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AddFinancialRecordService
{
    /**
     * Create a new financial record.
     *
     * @param FinancialRecordRequest $request
     * @return JsonResponse
     */
    public function createFinancialRecord(FinancialRecordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::guard('user')->id(); // ربط السجل بالمستخدم الحالي

        $financialRecord = FinancialRecord::create($data);

        return response()->json([
            'message' => 'تم إنشاء السجل المالي بنجاح.',
            'data'    => new FinancialRecordResource($financialRecord),
        ], 201);
    }
}
