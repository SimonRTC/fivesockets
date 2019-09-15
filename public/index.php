<?php

    require realpath(__DIR__ . '/..') . '/app/autoload.php';

    if (!empty($_GET['token']) && !empty($_GET['request'])) {
        $token      = $_GET['token'];
        $request    = $_GET['request'];
        $encryption     = new encryption\encryption($PDO);
        $encryption->setToken($token);
        if ($encryption->isGranted()) {
            $fivem      = new fivesockets\fivem($PDO);
            $request    = $encryption->getRealRequest(urlencode($request));
            $interpret  = $fivem->interpret($request);
            if ($interpret != false) {
                $response = new fivesockets\ExecuteRequest($fivem->getReponse());
                $response = $response->call();
                $HttpResponse = $encryption->getHttpResponseObject();
                $fivem->setHttpResponse($HttpResponse);               
                $response(new fivesockets\functions($fivem), ($fivem->needReponseObject() ? $fivem->getReponseObjects(): false));
                exit();
            }
        }
        $response   = $encryption->getHttpResponseObject();
        $request    = $response(false);
    }

?>