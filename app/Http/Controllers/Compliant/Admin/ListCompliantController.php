<?php

namespace App\Http\Controllers\Compliant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListCompliantController extends Controller
{
    public function index()
    {
        $complaints = Complaint::get();

        return response()->json([
            'message' => 'قائمة الشكاوى',
            'data'    => $complaints,
        ]);
    }
}
