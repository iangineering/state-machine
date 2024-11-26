<?php

namespace Example;

use Example\Actions\ReportFaultAction;
use Iangineering\StateMachine\StateMachine;

class TrafficLight
{
    public StateMachine $state;
    public string $emoji;

    public function __construct()
    {
        $this->state = new TrafficLightState(context: $this, state: TrafficLightEnum::RED);
        $this->emoji = "🟥";
    }

    public function reportFault($e): callable
    {
        return fn() => new ReportFaultAction($this, $e);
    }
}
