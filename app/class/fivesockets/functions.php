<?php

    namespace fivesockets;

    class functions {

        private $FiveM;

        public function __construct($FiveM) {
            $this->FiveM = $FiveM;
        }

        public function GetVehiclesList($ids) {
            $http_response = $this->FiveM->http_response;
            $http_response('Test de fonctionnement @@@pl');
        }

    }

?>