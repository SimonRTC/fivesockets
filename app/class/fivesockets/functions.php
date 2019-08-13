<?php

    namespace fivesockets;

    class functions {

        private $FiveM;

        /**
       * 
       * Constructor
       *
       * @param object $FiveM FiveM object class
       */

        public function __construct($FiveM) {
            $this->FiveM    = $FiveM;
            $this->PDO      = $FiveM->global['PDO']();
        }

        /**
         * Return error (function called not found)
         * 
         * @param array $ids Player identifiers (Not required can be "false")
         */

        public function FunctionNotFound($ids) {
            $http_response = $this->FiveM->http_response;
            $http_response([
                'error' => 'function not found !'
            ]);
        }

        /**
         * Get player(s) vehicles
         * 
         * @param array $ids Player identifiers (Not required can be "false")
         */

        public function GetVehiclesList($ids) {
            if ($ids != false) { $steam_id = $this->FiveM->getKeyFromIds('steam', $ids); }
            $query      = $this->PDO->query('SELECT * FROM owned_vehicles');
            $vehicles   = [];
            if ($query == false) { return false; }
            while ($data = $query->fetch()) {
                if ($ids == false || $ids != false && $data['owner'] == $steam_id) {
                    array_push($vehicles, $data);
                }
            }
            (empty($vehicles) ? $vehicles = false: $vehicles = $vehicles);
            $http_response = $this->FiveM->http_response;
            $http_response($vehicles);
        }

        /**
         * Get player(s) vehicles service (Job)
         * 
         * @param array $ids Player identifiers (Not required can be "false")
         */

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