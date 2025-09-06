<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscripedCheck
{

    public function handle(Request $request, Closure $next): Response
    {
        $admin =  auth('admin')->user() ?: auth('employee')->user()->admin;

        if ($admin->subscribed()){
            return $next($request);
        }
        return response()->json([
            'message' => 'please subscribe first to continue'
        ]);
    }
}
