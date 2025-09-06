<?php

namespace App\Services\User\Task;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskListService
{
    public function listUserTasks()
    {
        $tasks = Task::with('farm')
            ->where('user_id', Auth::guard('user')->id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة المهام الخاصة بك',
            'data'    => TaskResource::collection($tasks),
        ]);
    }
}
