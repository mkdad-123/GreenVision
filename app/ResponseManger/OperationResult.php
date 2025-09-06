<?php

namespace App\ResponseManger;

class OperationResult
{
    public $message;

    public $data;

    public $status;

    public $token; 

    public function __construct($message = 'Successfully',$data = null,$token = null ,$status = 200)
    {
        $this->data = $data;
        $this->message = $message;
        $this->token = $token;
        $this->status = $status;
    }

}
