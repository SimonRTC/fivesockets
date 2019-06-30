<?php

    namespace Tunker;

    class Tunker {

        private $token;
        private $global;
        private $container;

        public function __construct() {
            $this->global   = [
                'PDO'           => function($host = 'localhost', $database = 'fivesockets', $charset = 'utf8mb4', $username = 'root', $password = null, $options = null) { return new \PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $options); },
                'encryption'    => [
                    'openssl'       => [
                        'private'        => false,
                        'private_iv'     => '34857d973953e44afb49ea9d61104d8c'
                    ],
                    'hash'      => function($txt) { return hash('sha256', $txt); }
                ],
            ];
        }

        private function formatCallback($response, $type = 200) {
            $response = [
                'http_response' => $type,
                'datetime'      => date('Y-m-d h:i:s'),
                'headers'       => [ 'Content-Type' => 'application/json' ],
                'response'      => $response
            ];
            $response = \json_encode($response, true);
            return $response;
        }

        public function getHttpResponseObject() {
            return function($response) {
                echo $this->encrypt($this->formatCallback($response));
            };
        }

        private function getCredentialsFromDatabase($public = false) {
            if ($this->global['PDO']) {
                $PDO        = $this->global['PDO']();
                $response   = $PDO->query('SELECT * FROM tunker');
                $results    = [];
                while ($data = $response->fetch()) {
                    if ($public == false) { $data = [ 'public-credential' => $data['public-credential'], 'allowed_ip' => $data['allowed_ip'], 'method' => $data['method'] ]; }
                    if ($public != false && $data['public-credential'] == $public) {
                        $found = $data;
                        break;
                    } else { $found = false; }
                    array_push($results, $data);
                }
                return ($public == false ? $results: $found);
            }
        }

        public function setToken($token) {
            $this->token = $token;
        }

        public function isGranted() {
            $found = $this->getCredentialsFromDatabase($this->token);
            ($found != false ? $this->container = $found: null);
            return ($found == false ? false: true);
        }

        private function IsConfigurationCompliant($global) {
            return true; /* In development */
        }

        public function setGlobalConfiguration($global) {
            if ($this->IsConfigurationCompliant($global)) {
                $this->global = $global;
            }
        }

        public function getRealRequest($request) {       
            if (empty($this->container)) { $this->getCredentialsFromDatabase($this->token); }
            return $this->decrypt($request);
        }

        private function encrypt($value) {
            if (!empty($this->container)) {
                return urlencode(rtrim(
                    base64_encode(
                        openssl_encrypt(
                            $value,
                            $this->container['method'],
                            $this->container['secret-credential'],
                            OPENSSL_RAW_DATA,
                            hex2bin($this->global['encryption']['openssl']['private_iv'])
                        )
                    ), "\0"
                ));
            }
        }
        
        private function decrypt($value) {
            if (!empty($this->container)) {
                $value = urldecode($value);
                return rtrim(
                    openssl_decrypt(
                        base64_decode($value),
                        $this->container['method'],
                        $this->container['secret-credential'],
                        OPENSSL_RAW_DATA,
                        hex2bin($this->global['encryption']['openssl']['private_iv'])
                    )
                );
            }
        }

    }

?>