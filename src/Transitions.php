<?php

namespace Ianpsgrant\EnumStateMachine;

#[\Attribute]
class Transitions
{
    
    public function __construct(public mixed $to, public $before = null, public $after = null)
    {
    }

    public function transitionsTo(mixed $to): bool {
        return $this->to === $to;
    }
}
