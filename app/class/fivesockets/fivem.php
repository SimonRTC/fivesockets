<?php

    namespace fivesockets;

    /**
    * FiveM
    * 
    * 
    * @author     Simon Malpel <simon.malpel@orange.fr>
    */
    class fivem {

        public $request;
        public $http_response;

      /**
       * 
       * Constructor
       *
       */

        public function __construct() {
            $this->global   = [
                'PDO'           => function($host = 'localhost', $database = 'gta_db', $charset = 'utf8mb4', $username = 'fivesockets', $password = null, $options = null) { return new \PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $options); },
            ];
        }

        /**
         * Get identifier with key (steam/discord/live/...)
         * 
         * @param string Key
         * @param array Player identifiers
         */

        public function getKeyFromIds($key, $ids) {
            foreach ($ids as $k=>$id){
                if ($key == $k) {
                    return $key . ':' . $id;
                    break;
                }
            }
        }

        /**
         * 
         * Decode the request and add to constructor
         * 
         * @param string Json request
         */

        public function interpret($request) {
            if ($request == "" || $request == false) { return false; }
            $request        = \json_decode($request, true);
            $this->request  = $request;
            return $request;
        }

        /**
         *
         * Add http callback to constructor
         * 
         * @param object Http Tunker response object  
         */

        public function setHttpResponse($http_response) {
            $this->http_response = $http_response;
        }

        /**
         * 
         * Check if the query need identifiers
         * 
         */

        public function needReponseObject() {
            if ($this->request['identifiers'] == null) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * 
         * Get current reponse object in constructor (call only if "needReponseObject" is true)
         * 
         */

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
        
        /**
         * 
         * Get current response path in constructor
         * 
         */

        public function getReponse() {
            return __CLASS__ . '\\' . $this->request['type'];
        }

        /**
         * Check if "setGlobalConfiguration" as format error
         * @ (!!! In development !!!) @
         */

        private function IsConfigurationCompliant($global) {
            return true; /* In development */
        }

        /**
         * 
         * Set configuration
         * 
         * @param array $global Global configuraion
         */

        public function setGlobalConfiguration($global) {
            if ($this->IsConfigurationCompliant($global)) {
                $this->global = $global;
            }
        }

    }

?>