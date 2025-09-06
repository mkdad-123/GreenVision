<?php


namespace App\Services\User\Task;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskDeleteService
{
    public function delete($id)
    {
        $task = Task::where('id', $id)
                    ->where('user_id', Auth::guard('user')->id())
                    ->first();

        if (!$task) {
            return response()->json(['message' => ' المهمة غير موجودة أو لا تملك صلاحية الحذف'], 404);
        }

        $task->delete();

        return response()->json(['message' => ' تم حذف المهمة بنجاح']);
    }
}

