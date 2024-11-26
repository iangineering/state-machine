<?php

namespace Example;

use Example\Actions\ChangeColorAction;
use Example\Actions\FixFaultAction;
use Ianpsgrant\EnumStateMachine\StateMachine;

class TrafficLightState extends StateMachine
{
    function registerActions(): array {
        return [
            "ChangeColor" => fn() => new ChangeColorAction($this->context),
            "FixFault" => fn() => new FixFaultAction($this->context),
        ];
    }
}