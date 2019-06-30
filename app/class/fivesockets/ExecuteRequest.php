<?php

    namespace fivesockets;

    class ExecuteRequest extends functions {

        public $exec;
        public $func;
        private $functions;

        public function __construct($exec) {
            $exec = $this->getExecFunctionName($exec);
            $functions = $this->functions();
            if (!empty($functions[$exec])) {
                $this->setUsageFunction($functions[$exec]);
            }
        }

        private function setUsageFunction($func) {
            $this->func = $func;
        }

        private function functions() {
            return [
                'GetVehiclesList'           => function($functions, $ids) { $functions->GetVehiclesList($ids); },
                'GetVehiclesServiceList'    => function($functions, $ids) { $functions->GetVehiclesServiceList($ids); }
            ];
        }

        private function getExecFunctionName($exec) {
            $exec = explode('\\', $exec);
            return $exec[2];
        }

        public function call() {
            $exec = $this->func;
            return $exec;
        }

    }

?>