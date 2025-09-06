<?php

namespace App\Services\User\Auth;

use App\Models\User;
use App\ResponseManger\OperationResult;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginService
{
    public OperationResult $result;

    public function login($request)
    {
        try {
            DB::beginTransaction();

            $credentials = $request->only('email', 'password');
            $remember    = (bool) $request->boolean('remember');

            // محاولة تسجيل الدخول عبر جلسة
            if (! Auth::guard('user')->attempt($credentials, $remember)) {
                return $this->result = new OperationResult('كلمة المرور غير صحيحة', response(), 401);
            }

            // لأمان الجلسة
            $request->session()->regenerate();

            $user = Auth::guard('user')->user();

            DB::commit();

            // لا نُرجع توكن الآن؛ فقط بيانات المستخدم ورسالة نجاح
            return $this->result = new OperationResult('Login successfully', [
                'user' => $user,
            ], 200);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->result = new OperationResult('Database error: ' . $e->getMessage(), response(), 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->result = new OperationResult('An error occurred: ' . $e->getMessage(), response(), 500);
        }
    }
}
