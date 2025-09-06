<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Services\User\Equipment\EquipmentAddService;
use App\Services\User\Equipment\EquipmentEditService;
use App\Services\User\Equipment\EquipmentFilterService;
use Illuminate\Http\Request;
use App\Services\User\Equipment\EquipmentListService;
use App\Services\User\Equipment\EquipmentDeleteService;

class EquipmentController extends Controller
{
    protected EquipmentAddService $equipmentAddService;
    protected EquipmentEditService $equipmentEditService;
    protected EquipmentFilterService $equipmentFilterService;
    protected EquipmentListService $equipmentListService;
    protected EquipmentDeleteService $equipmentDeleteService;

    public function __construct(
        EquipmentAddService $equipmentAddService,
        EquipmentEditService $equipmentEditService,
        EquipmentFilterService $equipmentFilterService,
        EquipmentListService $equipmentListService,
        EquipmentDeleteService $equipmentDeleteService
    ) {
        $this->equipmentAddService = $equipmentAddService;
        $this->equipmentEditService = $equipmentEditService;
        $this->equipmentFilterService = $equipmentFilterService;
        $this->equipmentListService = $equipmentListService;
        $this->equipmentDeleteService = $equipmentDeleteService;
    }

    public function store(EquipmentRequest $request)
    {
        return $this->equipmentAddService->createEquipment($request);
    }

        public function update(EquipmentRequest $request, $id)
    {
        return $this->equipmentEditService->updateEquipment($request, $id);
    }

        public function filter(Request $request)
    {
        return $this->equipmentFilterService->filter($request->all());
    }

    public function index()
    {
        return $this->equipmentListService->list();
    }

    public function destroy($id)
    {
        return $this->equipmentDeleteService->delete($id);
    }
}
