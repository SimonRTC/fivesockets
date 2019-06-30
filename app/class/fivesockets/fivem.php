<?php

    namespace fivesockets;

    class FiveM {

        public $request;
        public $http_response;

        public function __construct() {
            $this->global   = [
                'PDO'           => function($host = 'localhost', $database = 'fivesockets', $charset = 'utf8mb4', $username = 'root', $password = null, $options = null) { return new \PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $options); },
            ];
        }

        public function getKeyFromIds($key, $ids) {
            foreach ($ids as $key=>$id){
                if ($key == 'steam') {
                    return $key . ':' . $id;
                    break;
                }
            }
        }

        public function interpret($request) {
            if ($request == "" || $request == false) { return false; }
            $request        = \json_decode($request, true);
            $this->request  = $request;
            return $request;
        }

        public function setHttpResponse($http_response) {
            $this->http_response = $http_response;
        }

        public function needReponseObject() {
            if ($this->request['identifiers'] == null) {
                return false;
            } else {
                return true;
            }
        }

        public function getReponseObjects() {
            $ids = $this->request['identifiers'];
            $identifiers = [];
            foreach ($ids as $id) {
                $id         = explode(':', $id);
                $array      = [ "$id[0]" => $id[1] ];
                $identifiers[$id[0]] = $id[1];
            }
            return $identifiers;
        }
        
        public function getReponse() {
            return __CLASS__ . '\\' . $this->request['type'];
        }

        private function IsConfigurationCompliant($global) {
            return true; /* In development */
        }

        public function setGlobalConfiguration($global) {
            if ($this->IsConfigurationCompliant($global)) {
                $this->global = $global;
            }
        }

    }

?>