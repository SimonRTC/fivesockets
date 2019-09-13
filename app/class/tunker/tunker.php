<?php

    namespace tunker;

    class tunker {

        private $token;
        private $global;
        private $container;

        /**
       * 
       * Constructor
       *
       */

        public function __construct(Object $PDO) {
            $this->global   = [
                'PDO'           => $PDO,
                'encryption'    => [
                    'openssl'       => [
                        'private'        => false,
                        'private_iv'     => '34857d973953e44afb49ea9d61104d8c'
                    ],
                    'hash'      => function($txt) { return hash('sha256', $txt); }
                ],
            ];
        }

        /**
         * 
         * Generate json reponse for client
         * 
         * @param array $response Client request response
         * @param int $type Http reponse code (200, 404, 403, ...)
         */

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

        /**
         * 
         * Return http callback object for call in "fivesockets/functions"
         * 
         */

        public function getHttpResponseObject() {
            return function($response) {
                echo $this->encrypt($this->formatCallback($response));
            };
        }

        /**
         * 
         * Get credentials on database
         * 
         * @param string $public Public credentials key (can be "false" but not return servers secrets !)
         */

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

        /**
         * Use for define token in "index.php"
         */

        public function setToken($token) {
            $this->token = $token;
        }

        /**
         * Check if the token (public-credential) is valid
         */

        public function isGranted() {
            $found = $this->getCredentialsFromDatabase($this->token);
            ($found != false ? $this->container = $found: null);
            return ($found == false ? false: true);
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

        /**
         *
         * Decrypt the client request
         *  
         * @param string $response "GET" crypted request
         */

        public function getRealRequest($request) {       
            if (empty($this->container)) { $this->getCredentialsFromDatabase($this->token); }
            return $this->decrypt($request);
        }

        /**
         * 
         * encrypt data
         * 
         * @param string $value encrypt
         */

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

        /**
         * 
         * deencrypt data
         * 
         * @param string $value deencrypt
         */
        
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