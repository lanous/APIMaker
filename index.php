<?php
include_once("vendor/autoload.php");
 
$route = $_GET['route'] ?? null;
$RestAPI = new Lanous\APIMaker\RestAPI(__DIR__);

$RestAPI->Authorization("Bearer",function ($token) {
    if($token != "azad") {
        return false;
    }
    return true;
});

$RestAPI->Route(Calculate::class,$route);

// http://127.0.0.1/api/sum?X=10&Y=20

/*
    {
        "status": true,
        "data": {
            "calculate": 30
        }
    }
*/