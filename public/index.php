<?php

    require realpath(__DIR__ . '/..') . '/app/autoload.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!empty($_GET['token']) && !empty($_GET['request'])) {
        $token      = $_GET['token'];
        $request    = $_GET['request'];
        $PDO        = $PDO();
        $tunker     = new tunker\tunker($PDO);
        $tunker->setToken($token);
        if ($tunker->isGranted()) {
            $fivem      = new fivesockets\fivem($PDO);
            $request    = $tunker->getRealRequest(urlencode($request));
            $interpret  = $fivem->interpret($request);
            if ($interpret != false) {
                $response = new fivesockets\ExecuteRequest($fivem->getReponse());
                $response = $response->call();
                $HttpResponse = $tunker->getHttpResponseObject();
                $fivem->setHttpResponse($HttpResponse);               
                $response(new fivesockets\functions($fivem), ($fivem->needReponseObject() ? $fivem->getReponseObjects(): false));
                exit();
            }
        }
        $response = $tunker->getHttpResponseObject()(false);
    }

?>