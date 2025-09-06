<?php

namespace App\Services\User\Task;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskEditService
{
    public function updateTask(TaskRequest $request, $id)
    {
        $task = Task::where('id', $id)
                    ->where('user_id', Auth::guard('user')->id())
                    ->first();

        if (! $task) {
            return response()->json(['message' => 'المهمة غير موجودة أو لا تملك صلاحية التعديل'], 404);
        }

        $task->update($request->validated());
        $task->refresh()->load('farm');

        return response()->json([
            'message' => 'تم تعديل المهمة بنجاح',
            'data' => new TaskResource($task)
        ]);
    }
}
