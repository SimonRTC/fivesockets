<?php

    namespace fivesockets;

    class FiveM {

        public $request;
        public $http_response;

        public function interpret($request) {
            if ($request == "" || $request == false) { return false; }
            $request        = \json_decode($request, true);
            $this->request  = $request;
            return $request;
        }

        function setHttpResponse($http_response) {
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
                array_push($identifiers, [ "$id[0]" => $id[1] ]);
            }
            return $identifiers;
        }
        
        public function getReponse() {
            return __CLASS__ . '\\' . $this->request['type'];
        }

    }

?>