<?php

namespace App\Services\User\Task;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskAddService
{
    public function createTask(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::guard('user')->id();

        $task = Task::create($data);
        $task->load('farm'); 

        return response()->json([
            'message' => 'تم إنشاء المهمة بنجاح',
            'data' => new TaskResource($task),
        ], 201);
    }
}
