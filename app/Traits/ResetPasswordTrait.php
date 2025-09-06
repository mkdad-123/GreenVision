<?php

namespace App\Traits;

use App\Http\Requests\ForgetPasswordRequest;
use Illuminate\Support\Facades\Password;

trait ResetPasswordTrait{


    public function forgotPassword(ForgetPasswordRequest $request , $broker){

        $input = $request->only('email');

        $response = Password::broker($broker)->sendResetLink($input);

        $responseData = $response == Password::RESET_LINK_SENT ?
            ['message'=> 'Mail send successfully' ,'status' => 200 ] :
            ['message' => 'The email was not sent. Please check the validity of your email ' , 'status' => 401] ;

        return response()->json([
            'data' => response(),
            'message' => $responseData['message'],
        ],$responseData['status']);
    }
}
