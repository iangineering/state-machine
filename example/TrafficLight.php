<?php

namespace Example;

use Example\Actions\ReportFaultAction;
use Ianpsgrant\EnumStateMachine\StateMachine;

class TrafficLight
{
    public StateMachine $stateM;
    public string $emoji;

    public function __construct() {
        $this->stateM = new TrafficLightState(context: $this, state: TrafficLightEnum::RED);
        $this->emoji = "🟥";
    }

    public function run(): void {
        try {
            while (true) {
                $this->stateM->transition(TrafficLightEnum::GREEN);
                $this->stateM->transition(TrafficLightEnum::YELLOW);
                $this->stateM->transition(TrafficLightEnum::RED);

                // Introduce errors
                if(rand(0, 1)) {
                    throw new \Exception("Fault Occurred!");
                }
            }
        } catch (\Exception $e) {
            $this->stateM->transition(TrafficLightEnum::FAULT, before: $this->reportFault($e));
        }
    }

    public function reportFault($e): callable {
        return fn() => new ReportFaultAction($this, $e);
    }

    public function getStatus(): bool {
        return true;
    }
}