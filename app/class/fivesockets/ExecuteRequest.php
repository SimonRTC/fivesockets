<?php

    namespace fivesockets;

    /**
    * ExecuteRequest
    * 
    * 
    * @author     Simon Malpel <simon.malpel@orange.fr>
    */
    class ExecuteRequest extends functions {

        public $exec;
        public $func;
        private $functions;
        
      /**
       * 
       * Constructor
       *
       * @param string $exec Executable closure
       */

        public function __construct($exec) {
            $exec = $this->getExecFunctionName($exec);
            $functions = $this->functions();
            if (!empty($functions[$exec])) {
                $this->setUsageFunction($functions[$exec]);
            } else { $this->setUsageFunction($functions['FunctionNotFound']); }
        }

        /**
         * 
         * Add current closure to constructor
         * 
         * @param object $func Function ready for set 
         */

        private function setUsageFunction($func) {
            $this->func = $func;
        }

        /**
         * 
         * Return all function available for API
         * 
         */

        private function functions() {
            return [
                'FunctionNotFound'          => function($functions, $ids) { $functions->FunctionNotFound($ids); },
                'GetAllPlayers'             => function($functions, $ids) { $functions->GetAllPlayers($ids); },
                'GetWhitelist'              => function($functions, $ids) { $functions->GetWhitelist($ids); },
                'SetBankAccountAmout'       => function($functions, $ids) { $functions->SetBankAccountAmout($ids); },
                'GetAddonAccountData'       => function($functions, $ids) { $functions->GetAddonAccountData($ids); },
                'GetJobsAvailable'          => function($functions, $ids) { $functions->GetJobsAvailable($ids); },
                'GetVehiclesList'           => function($functions, $ids) { $functions->GetVehiclesList($ids); },
                'GetVehiclesServiceList'    => function($functions, $ids) { $functions->GetVehiclesServiceList($ids); },
                'SendTaxeToCompanies'       => function($functions, $ids) { $functions->SendTaxeToCompanies($ids); }
            ];
        }

        /**
         * 
         * Get executable name of function
         * 
         * @param string $exec Real function path
         */

        private function getExecFunctionName($exec) {
            $exec = explode('\\', $exec);
            return $exec[2];
        }

        /**
         * 
         * Call current set function
         * 
         */

        public function call() {
            $exec = $this->func;
            return $exec;
        }

    }

?>