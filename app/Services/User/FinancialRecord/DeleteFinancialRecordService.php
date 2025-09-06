<?php

namespace App\Services\User\FinancialRecord;

use App\Models\FinancialRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteFinancialRecordService
{
    /**
     * Delete a financial record by ID if it belongs to the authenticated user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $financialRecord = FinancialRecord::where('user_id',Auth::guard('user')->id())->find($id);

        if (!$financialRecord) {
            return response()->json([
                'message' => 'السجل المالي غير موجود أو ليس لديك صلاحية لحذفه.'
            ], 404);
        }

        $financialRecord->delete();

        return response()->json([
            'message' => 'تم حذف السجل المالي بنجاح.'
        ], 200);
    }
}
