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
         * Get all players on user table
         * 
         * @param array $ids Player identifiers (Not required can be "false")
         */

        public function GetAllPlayers($ids) {
            if ($ids != false) { $steam_id = $this->FiveM->getKeyFromIds('steam', $ids); }
            $query      = $this->PDO->query('SELECT * FROM users');
            $players    = [];
            while ($data = $query->fetch()) {
                if ($ids == false || $ids != false && $data['owner'] == $steam_id) {
                    array_push($players, $data);
                }
            }
            (empty($players) ? $players = false: $players = $players);
            $http_response = $this->FiveM->http_response;
            $http_response($players);
        }

        /**
         * Update player bank account by specific values
         * 
         * @param array $ids Player bank accounts informations and values for update
         */

        public function SetBankAccountAmout($ids) {
            
            /**
             * ids
             * 
             * @param bank_account bank account name
             * @param owner steam id
             * @param amount Init for new account amount
             * 
             */

            $http_response = $this->FiveM->http_response;
            $http_response(false);
        }

        /**
         * Get all players on user table
         * 
         * @param array $ids Player identifiers (Not required can be "false")
         */

        public function GetJobsAvailable($ids) {
            $query      = $this->PDO->query('SELECT * FROM jobs');
            $jobs       = [];
            while ($data = $query->fetch()) {
                array_push($jobs, $data);
            }
            (empty($jobs) ? $players = false: $jobs = $jobs);
            $http_response = $this->FiveM->http_response;
            $http_response($jobs);
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