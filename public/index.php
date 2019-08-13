<?php

    require realpath(__DIR__ . '/..') . '/app/autoload.php';

    if (!empty($_GET['token']) && !empty($_GET['request'])) {
        $token      = $_GET['token'];
        $request    = $_GET['request'];
        $tunker     = new tunker\tunker;
        $tunker->setToken($token);
        if ($tunker->isGranted()) {
            $fivem      = new fivesockets\fivem;
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