<?php

    namespace fivesockets;

    class functions {

        private $FiveM;

        public function __construct($FiveM) {
            $this->FiveM    = $FiveM;
            $this->PDO      = $FiveM->global['PDO']();
        }

        public function GetVehiclesList($ids) {
            if ($ids != false) { $steam_id = $this->FiveM->getKeyFromIds('steam', $ids); }
            $query      = $this->PDO->query('SELECT * FROM owned_vehicles');
            $vehicles   = [];
            while ($data = $query->fetch()) {
                if ($ids == false || $ids != false && $data['owner'] == $steam_id) {
                    array_push($vehicles, $data);
                }
            }
            (empty($vehicles) ? $vehicles = false: $vehicles = $vehicles);
            $http_response = $this->FiveM->http_response;
            $http_response($vehicles);
        }

        public function GetVehiclesServiceList($ids) {
            if ($ids != false) { $steam_id = $this->FiveM->getKeyFromIds('steam', $ids); }
            $query      = $this->PDO->query('SELECT * FROM owned_vehicles_service');
            $vehicles   = [];
            while ($data = $query->fetch()) {
                if ($ids == false || $ids != false && $data['owner'] == $steam_id) {
                    array_push($vehicles, $data);
                }
            }
            (empty($vehicles) ? $vehicles = false: $vehicles = $vehicles);
            $http_response = $this->FiveM->http_response;
            $http_response($vehicles);
        }

    }

?>