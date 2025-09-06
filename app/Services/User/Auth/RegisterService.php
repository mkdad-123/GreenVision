<?php

namespace App\Services\User\Auth;

use App\Models\User;
use App\ResponseManger\OperationResult;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public OperationResult $result;

    public function register($request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            DB::commit();

            // لا توكن هنا
            return $this->result = new OperationResult(
                'User created successfully',
                ['user' => $user],
                201
            );

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->result = new OperationResult('Database error: ' . $e->getMessage(), response(), 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->result = new OperationResult('An error occurred: ' . $e->getMessage(), response(), 500);
        }
    }
}
