<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\User\Task\TaskAddService;
use App\Services\User\Task\TaskEditService;
use App\Services\User\Task\TaskListService;
use App\Services\User\Task\TaskFilterService;
use App\Services\User\Task\TaskDeleteService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskAddService $taskAddService;
    protected TaskEditService $taskEditService;
    protected TaskListService $taskListService;
    protected TaskFilterService $taskFilterService;
    protected TaskDeleteService $taskDeleteService;

    public function __construct(TaskAddService $taskAddService,
    TaskEditService $taskEditService,
    TaskListService $taskListService,
    TaskFilterService $taskFilterService,
    TaskDeleteService $taskDeleteService)
    {
        $this->taskAddService = $taskAddService;
        $this->taskEditService = $taskEditService;
        $this->taskListService = $taskListService;
        $this->taskFilterService = $taskFilterService;
        $this->taskDeleteService = $taskDeleteService;
    }

    public function store(TaskRequest $request)
    {
        return $this->taskAddService->createTask($request);
    }

    public function update(TaskRequest $request, $id)
    {
        return $this->taskEditService->updateTask($request, $id);
    }

    public function index()
    {
        return $this->taskListService->listUserTasks();
    }

    public function filter(Request $request)
    {
        return $this->taskFilterService->filter($request->all());
    }

    public function delete($id)
    {
        return $this->taskDeleteService->delete($id);
    }
}
