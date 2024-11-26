<?php

namespace Example\Actions;

use Example\TrafficLight;

class ChangeColorAction {
    public function __construct(TrafficLight $trafficLight) {
        $trafficLight->emoji = $trafficLight->stateM->state->emoji();
        echo $trafficLight->emoji . "\n";
        sleep(1);
    }
}