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