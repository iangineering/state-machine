<?php

namespace Example;

require __DIR__ . '/../vendor/autoload.php';

$trafficLight = new TrafficLight;

while (true) {
    $trafficLight->state->transition(TrafficLightEnum::GREEN);
    $trafficLight->state->transition(TrafficLightEnum::YELLOW);
    $trafficLight->state->transition(TrafficLightEnum::RED);

    // Introduce error
    if(rand(0, 1)) {
        $e = new \Exception("Fault Occurred!");
        $trafficLight->state->transition(TrafficLightEnum::FAULT, before: $this->reportFault($e));
        break;
    }
}