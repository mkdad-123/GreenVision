<?php

namespace App\Services\User\FinancialRecord;

use App\Http\Requests\FinancialRecordRequest;
use App\Http\Resources\FinancialRecordResource;
use App\Models\FinancialRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateFinancialRecordService
{
    /**
     * Update a financial record by ID.
     *
     * @param FinancialRecordRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateFinancialRecord(FinancialRecordRequest $request, int $id): JsonResponse
    {
        $financialRecord = FinancialRecord::where('user_id', Auth::guard('user')->id())->find($id);

        if (!$financialRecord) {
            return response()->json([
                'message' => 'السجل المالي غير موجود أو ليس لديك صلاحية للوصول إليه.'
            ], 404);
        }

        $data = $request->validated();

        $financialRecord->update($data);

        return response()->json([
            'message' => 'تم تحديث السجل المالي بنجاح.',
            'data'    => new FinancialRecordResource($financialRecord),
        ], 200);
    }
}
