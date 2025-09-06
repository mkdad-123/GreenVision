<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    // ...

    /**
     * تحويل أخطاء عدم المصادقة.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // 👈 هذا يضمن JSON لطلبات AJAX أو التي ترسل Accept: application/json
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok'      => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // 👈 اختيار صفحة تسجيل الدخول حسب الحارس (إن رغبت)
        $guard = $exception->guards()[0] ?? null;
        switch ($guard) {
            case 'admin':
                $login = route('admin.login'); // إن كان لديك هذا الراوت
                break;
            case 'user':
            default:
                $login = route('login');       // أو route('user.login') إن كان اسمك هكذا
                break;
        }

        return redirect()->guest($login);
    }
}
