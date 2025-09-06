<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Http\Requests\FarmRequest;
use App\Services\User\Farm\AddFarmService;
use App\Services\User\Farm\EditFarmService;
use App\Services\User\Farm\DeleteFarmService;
use App\Services\User\Farm\FarmFilterService;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    protected AddFarmService $addFarmService;
    protected EditFarmService $editFarmService;
    protected DeleteFarmService $deleteFarmService;
    protected FarmFilterService $farmFilterService;

    public function __construct(
        AddFarmService $addFarmService,
        EditFarmService $editFarmService,
        DeleteFarmService $deleteFarmService,
        FarmFilterService $farmFilterService
    ) {
        $this->addFarmService = $addFarmService;
        $this->editFarmService = $editFarmService;
        $this->deleteFarmService = $deleteFarmService;
        $this->farmFilterService = $farmFilterService;

    }

    /**
     * إنشاء مزرعة جديدة
     */
    public function store(FarmRequest $request)
    {
        return $this->addFarmService->createFarm($request);
    }


    public function update(FarmRequest $request, $id)
    {
        return $this->editFarmService->updateFarm($request, $id);
    }

    /**
     * عرض مزارع المستخدم الحالي
     */
    public function index()
    {
        return $this->addFarmService->listUserFarms();
    }

        public function destroy($id)
    {
        return $this->deleteFarmService->deleteFarm($id);
    }

    public function filter(Request $request)
    {
        return $this->farmFilterService->filter($request->all());
    }
}
