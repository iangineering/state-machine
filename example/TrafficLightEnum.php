<?php

namespace Example;

use Ianpsgrant\EnumStateMachine\Transitions;

enum TrafficLightEnum
{
    #[Transitions(to: self::YELLOW, after: "ChangeColor")]
    case GREEN;

    #[Transitions(to: self::RED, after: "ChangeColor")]
    case YELLOW;

    #[Transitions(to: self::GREEN, after: "ChangeColor")]
    case RED;

    #[Transitions(from: true, to: true, before: "FixFault", after: "ChangeColor")]
    case FAULT;

    public function emoji(): string
    {
        return match ($this) {
            self::GREEN => "🟩",
            self::YELLOW => "🟨",
            self::RED => "🟥",
            self::FAULT => "🚨"
        };
    }
}
