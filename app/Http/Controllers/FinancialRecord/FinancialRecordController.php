<?php

namespace App\Http\Controllers\FinancialRecord;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialRecordRequest;
use App\Services\User\FinancialRecord\AddFinancialRecordService;
use App\Services\User\FinancialRecord\UpdateFinancialRecordService;
use App\Services\User\FinancialRecord\DeleteFinancialRecordService;
use App\Services\User\FinancialRecord\ListFinancialRecordService;
use App\Services\User\FinancialRecord\FilterFinancialRecordService;
use Illuminate\Http\Request;

class FinancialRecordController extends Controller
{
    protected AddFinancialRecordService $addFinancialRecordService;
    protected UpdateFinancialRecordService $updateFinancialRecordService;
    protected DeleteFinancialRecordService $deleteFinancialRecordService;
    protected ListFinancialRecordService $listFinancialRecordService;
    protected FilterFinancialRecordService $filterFinancialRecordService;

    public function __construct(
        AddFinancialRecordService $addFinancialRecordService,
        UpdateFinancialRecordService $updateFinancialRecordService,
        DeleteFinancialRecordService $deleteFinancialRecordService,
        ListFinancialRecordService $listFinancialRecordService,
        FilterFinancialRecordService $filterFinancialRecordService
    ) {
        $this->addFinancialRecordService = $addFinancialRecordService;
        $this->updateFinancialRecordService = $updateFinancialRecordService;
        $this->deleteFinancialRecordService = $deleteFinancialRecordService;
        $this->listFinancialRecordService = $listFinancialRecordService;
        $this->filterFinancialRecordService = $filterFinancialRecordService;
    }

    /**
     * إنشاء سجل مالي جديد
     */
    public function store(FinancialRecordRequest $request)
    {
        return $this->addFinancialRecordService->createFinancialRecord($request);
    }

    /**
     * تحديث سجل مالي حسب المعرف
     */
    public function update(FinancialRecordRequest $request, $id)
    {
        return $this->updateFinancialRecordService->updateFinancialRecord($request, $id);
    }

    /**
     * حذف سجل مالي
     */
    public function destroy($id)
    {
        return $this->deleteFinancialRecordService->delete($id);
    }

    /**
     * عرض جميع السجلات للمستخدم الحالي
     */
    public function index()
    {
        return $this->listFinancialRecordService->listFinancialRecords();
    }

    /**
     * تصفية السجلات حسب المعطيات (مثل التاريخ، الاتجاه، التصنيف)
     */
    public function filter(Request $request)
    {
        return $this->filterFinancialRecordService->filter($request->all());
    }
}
