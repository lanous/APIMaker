<?php
include_once("src/RestAPI/Results.php");
include_once("src/RestAPI/Allowed.php");
include_once("src/RestAPI/Structure.php");
include_once("src/RestAPI.php");
 
$route = $_GET['route'] ?? null;
$RestAPI = new Lanous\APIMaker\RestAPI(__DIR__);

$RestAPI->Authorization("Bearer",function ($token) {
    if($token != "azad") {
        return false;
    }
    return true;
});

$RestAPI->Route(Calculate::class,$route);