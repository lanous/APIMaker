<?php
namespace Lanous\APIMaker\RestAPI;
trait Results {
    private function falseResult(string $error_message,int $error_code) {
        return [
            'status'=>false,
            'error'=>$error_message,
            'error_code'=>$error_code
        ];
    }
    private function trueResult(array $data) {
        return [
            'status'=>true,
            'data'=>$data
        ];
    }
    private function ReturnJson(array $data) {
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode($data,128|256));
    }
}