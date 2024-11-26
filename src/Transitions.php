<?php

namespace Ianpsgrant\EnumStateMachine;

#[\Attribute]
class Transitions
{
    
    public function __construct(
        private mixed $from = null,
        private mixed $to = null,
        public ?string $before = null,
        public ?string $after = null
    )
    {
    }

    public function transitionsTo(mixed $toState): bool {
        if (is_bool($this->to)) {
            return $this->to;
        }
        if (is_iterable($this->to)) {
            foreach ($this->to as $to) {
                if ($to === $toState) {
                    return true;
                }
            }
            return false;
        }
        return $this->to === $toState;
    }
    
    public function isTransitionedFrom(mixed $fromState): bool {
        if (is_bool($this->from)) {
            return $this->from;
        }
        if (is_iterable($this->from)) {
            foreach ($this->from as $from) {
                if ($from === $fromState) {
                    return true;
                }
            }
            return false;
        }
        return $this->to === $fromState;
    }
}
