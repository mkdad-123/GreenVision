<?php

namespace App\Http\Controllers\CropSale;

use App\Http\Controllers\Controller;
use App\Http\Requests\CropSaleRequest;
use App\Services\User\CropSale\CropSaleAddService;
use App\Services\User\CropSale\CropSaleEditService;
use App\Http\Requests\CropSaleUpdateRequest;
use App\Services\User\CropSale\CropSaleFilterService;
use Illuminate\Http\Request;
use App\Services\User\CropSale\CropSaleListService;
use App\Services\User\CropSale\CropSaleDeleteService;

class CropSaleController extends Controller
{
    protected CropSaleAddService $cropSaleAddService;
    protected CropSaleEditService $cropSaleEditService;
    protected CropSaleFilterService $cropSaleFilterService;
    protected CropSaleListService $cropSaleListService;
    private CropSaleDeleteService $cropSaleDeleteService;


    public function __construct(CropSaleAddService $cropSaleAddService,
    CropSaleEditService $cropSaleEditService,
    CropSaleFilterService $cropSaleFilterService,
    CropSaleListService $cropSaleListService,
    CropSaleDeleteService $cropSaleDeleteService)
    {
        $this->cropSaleAddService = $cropSaleAddService;
        $this->cropSaleEditService = $cropSaleEditService;
        $this->cropSaleFilterService = $cropSaleFilterService;
        $this->cropSaleListService = $cropSaleListService;
        $this->cropSaleDeleteService = $cropSaleDeleteService;
    }


    public function store(CropSaleRequest $request)
    {
        return $this->cropSaleAddService->create($request);
    }

    public function update(CropSaleUpdateRequest $request, $id)
    {
        return $this->cropSaleEditService->update($request, $id);
    }

        public function filter(Request $request)
    {
        return $this->cropSaleFilterService->filter($request->all());
    }

        public function index()
    {
        return $this->cropSaleListService->listUserCropSales();
    }

    public function delete($id)
    {
        return $this->cropSaleDeleteService->delete($id);
    }
}
