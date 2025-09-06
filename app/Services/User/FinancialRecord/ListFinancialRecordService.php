<?php

namespace App\Services\User\FinancialRecord;

use App\Http\Resources\FinancialRecordResource;
use App\Models\FinancialRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ListFinancialRecordService
{
    /**
     * Get all financial records for the authenticated user.
     *
     * @return JsonResponse
     */
    public function listFinancialRecords(): JsonResponse
    {
        $records = FinancialRecord::where('user_id', Auth::guard('user')->id())
            ->orderBy('date', 'desc')  // الأحدث أولًا
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'تم جلب السجلات المالية بنجاح.',
            'data'    => FinancialRecordResource::collection($records),
            'total'   => $records->count(),
        ], 200);
    }
}
