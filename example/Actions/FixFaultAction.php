<?php

namespace Example\Actions;

use Example\TrafficLight;

class FixFaultAction {
    public function __construct(TrafficLight $trafficLight) {
        // ...
        echo "Fault Failed to be Fixed.\n";
    }
}