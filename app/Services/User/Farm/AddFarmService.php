<?php

namespace App\Services\User\Farm;

use App\Http\Requests\FarmRequest;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class AddFarmService
{
    /**
     * إنشاء مزرعة جديدة مرتبطة بالمستخدم الحالي
     */
    public function createFarm(FarmRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::guard('user')->id();

        $farm = Farm::create($data);

        return response()->json([
            'message' => 'تم إنشاء المزرعة بنجاح',
            'data' => new FarmResource($farm)
        ], 201);
    }

    /**
     * عرض كل مزارع المستخدم الحالي
     */
    public function listUserFarms()
    {

        $farms = Farm::where('user_id', Auth::guard('user')->id())->get();

        return response()->json([
            'message' => 'قائمة المزارع الخاصة بك',
            'data' => FarmResource::collection($farms)
        ]);
    }
}
