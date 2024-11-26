<?php

namespace Example\Actions;

use Example\TrafficLight;
use Exception;

class ReportFaultAction {
    public function __construct(TrafficLight $trafficLight, ?Exception $e = null) {
        // ...
        echo "Fault Successfully Reported!\n";
    }
}