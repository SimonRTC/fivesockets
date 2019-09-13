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
                if ($ids == false || $ids != false && $data['identifier'] == $steam_id) {
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
            $sql = 'UPDATE users SET ' . ($ids['account'] == 'bank'? 'bank': 'money') . ' = "' . ((string)$ids['amount']) . '" WHERE identifier = "' . $ids['identifier'] . '"';
            $cb = $this->PDO->exec($sql);
            $cb = [
                'account'   => $ids['account'],
                'amount'    => $ids['amount'],
                'callback'  => ($cb != false? true: false)
            ];
            $http_response = $this->FiveM->http_response;
            $http_response($cb);
        }

        /**
         * Update player bank account by specific values
         * 
         * @param array $ids Player bank accounts informations and values for update
         */

        public function GetAddonAccountData($ids) {
            if ($ids != false) { $steam_id = $this->FiveM->getKeyFromIds('steam', $ids); }
            $query          = $this->PDO->query('SELECT * FROM addon_account_data');
            $AddonAccount   = [];
            while ($data = $query->fetch()) {
                if ($ids == false || $ids != false && $data['owner'] == $steam_id) {
                    array_push($AddonAccount, $data);
                }
            }
            (empty($AddonAccount) ? $AddonAccount = false: $AddonAccount = $AddonAccount);
            $http_response = $this->FiveM->http_response;
            $http_response($AddonAccount);
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

         /**
         * Send taxe to companies
         * 
         * @param array $ids companies and taxes
         */

        public function SendTaxeToCompanies($ids) {
            $success = [];
            foreach ($ids as $i=>$society) {
                $update = ((($society['money']*$society['taxe'])/100));
                $update = $society['money']-$update;
                $update = (string)round($update);
                if ($update != '0') {
                    $cb = $this->PDO->exec('UPDATE addon_account_data SET money = "' . $update . '" WHERE account_name = "' . $society['society'] . '"');
                } else { $cb = true; }
                $ids[$i] = array_merge($ids[$i], [ 'success' => ($cb != false? true: false) ]);
            }
            $http_response = $this->FiveM->http_response;
            $http_response($ids);
        }

    }

?>