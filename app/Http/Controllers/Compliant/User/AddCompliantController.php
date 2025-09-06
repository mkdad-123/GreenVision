<?php

namespace App\Http\Controllers\Compliant\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddCompliantController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $data['user_id'] = Auth::guard('user')->id();

        $complaint = Complaint::create($data);

        return response()->json([
            'message' => 'تم إنشاء الشكوى بنجاح',
            'data'    => $complaint,
        ], 201);
    }
}
