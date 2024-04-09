<?php

namespace Lanous\APIMaker\RestAPI;

trait MethodManagment {
    const GET = 'get';
    const POST = 'post';
    const typeINT = 'integer';
    const typeBoolean = 'bool';
    const typeString = 'string';
    const typeJson = 'json';
    const typeFloat = 'float';
    protected static function Allowed(string $field,string $description,string|array $data_type,string $method,bool $requirement=true) {
        return [
            'field'=>$field,
            'description'=>$description,
            'data_type'=>$data_type,
            'requirement'=>$requirement,
            'method'=>$method,
        ];
    }
}