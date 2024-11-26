<?php

namespace Example\Actions;

use Example\TrafficLight;

class FixFaultAction {
    public function __construct(TrafficLight $trafficLight) {
        if ($trafficLight->getStatus()) {
            throw new \Exception("Traffic Light is working. No fault to fix.");
        }
        // ...

        echo "Fault Successfully Fixed!\n";
        $trafficLight->run();
    }
}