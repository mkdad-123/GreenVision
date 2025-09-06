<?php

namespace App\Services\User\Farm;

use App\Http\Requests\FarmRequest;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class EditFarmService
{
    /**
     * تعديل مزرعة موجودة
     */
    public function updateFarm(FarmRequest $request, $id)
    {
        $farm = Farm::where('id', $id)
                    ->where('user_id', Auth::guard('user')->id())
                    ->first();

        if (!$farm) {
            return response()->json(['message' => 'المزرعة غير موجودة أو لا تملك صلاحية التعديل'], 404);
        }

        $farm->update($request->validated());

        return response()->json([
            'message' => 'تم تحديث بيانات المزرعة بنجاح',
            'data' => new FarmResource($farm),
        ]);
    }
}
