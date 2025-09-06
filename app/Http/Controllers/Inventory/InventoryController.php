<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Services\User\Inventory\InventoryAddService;
use App\Services\User\Inventory\InventoryEditService;
use App\Services\User\Inventory\InventoryListService;
use App\Services\User\Inventory\InventoryFilterService;
use App\Services\User\Inventory\InventoryDeleteService;

use Illuminate\Http\Request;


class InventoryController extends Controller
{
    protected InventoryAddService $inventoryAddService;
    protected InventoryEditService $inventoryEditService;
    protected InventoryListService $inventoryListService;
    protected InventoryFilterService $inventoryFilterService;
    protected InventoryDeleteService $inventoryDeleteService;

    public function __construct(InventoryAddService $inventoryAddService,
    InventoryEditService $inventoryEditService,
    InventoryListService $inventoryListService,
    InventoryFilterService $inventoryFilterService,
    InventoryDeleteService $inventoryDeleteService)
    {
        $this->inventoryAddService = $inventoryAddService;
        $this->inventoryEditService = $inventoryEditService;
        $this->inventoryListService = $inventoryListService;
        $this->inventoryFilterService = $inventoryFilterService;
        $this->inventoryDeleteService = $inventoryDeleteService;

    }

    public function store(InventoryRequest $request)
    {
        return $this->inventoryAddService->createInventory($request);
    }

        public function update(InventoryRequest $request, $id)
    {
        return $this->inventoryEditService->updateInventory($request, $id);
    }

    public function index()
    {
        return $this->inventoryListService->listUserInventory();
    }

        public function filter(Request $request)
    {
        return $this->inventoryFilterService->filter($request->all());
    }

        public function delete($id)
    {
        return $this->inventoryDeleteService->delete($id);
    }

}
