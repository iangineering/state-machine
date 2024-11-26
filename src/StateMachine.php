<?php

namespace Ianpsgrant\EnumStateMachine;

use Example\OrderStateEnum;
use ReflectionAttribute;
use Ianpsgrant\EnumStateMachine\Transitions;

abstract class StateMachine {
    private array $actions;

    public function __construct(public mixed $context, public mixed $state) {
        $this->actions = $this->registerActions();
    }

    abstract function registerActions(): array;

    final public function transition(mixed $to, ?callable $before = null, ?callable $after = null): void {

        if ($this->stateTransitionsTo($to)) {
            try {
                $this->performBeforeAction();
            } catch (\Exception) {}
            $this->state = $to;
            try {
                $this->performAfterAction();
            } catch (\Exception) {}
            
        }
        
        /** @var ReflectionAttribute[] */
        $transitionReflections = $reflection->getAttributes(Transitions::class);

        // See if current state can be transitioned from other states
        foreach ($transitionReflections as $transitionReflection) {
            $transition = $transitionReflection->newInstance();
            if ($transition->transitionsFrom()) {
                # code...
            }
        }

        // See if other states can transition to this one
        foreach ($transitionReflections as $transitionReflection) {
            /** @var Transitions */
            $transition = $transitionReflection->newInstance();
            if ($transition->transitionsTo($to)) {
                if ($before) {
                    $before();
                } else if ($transition->before && $this->actions[$transition->before]) {
                    ($this->actions[$transition->before])();
                }
                $this->state = $to;
                if ($after) {
                    $after();
                } else if ($transition->after && $this->actions[$transition->after]) {
                    ($this->actions[$transition->after])();
                }
                return;
            }
        }
        throw new \Exception($this->state->name . " does not transition to " . $to->name);
    }

    function stateTransitionFrom($from, $to) {
        $reflection = new \ReflectionEnumUnitCase($this->state::class, $to->name);

        /** @var ReflectionAttribute[] */
        $transitionReflections = $reflection->getAttributes(Transitions::class);

        foreach ($transitionReflections as $transitionReflection) {
            $transition = $transitionReflection->newInstance();
            if ($transition->transitionsFrom($to)) {
                return true;
            }
        }
        return false;
    }

    function stateTransitionTo($to, $from) {
        $reflection = new \ReflectionEnumUnitCase($this->state::class, $to->name);

        /** @var ReflectionAttribute[] */
        $transitionReflections = $reflection->getAttributes(Transitions::class);

        foreach ($transitionReflections as $transitionReflection) {
            /** @var Transitions */
            $transition = $transitionReflection->newInstance();
            if ($transition->transitionsTo($to)) {
                return true;
            }
        }
        return false;
    }

    function performBeforeAction($transition, $before): void {
        if ($before) {
            $before();
        } else if ($transition->before && $this->actions[$transition->before]) {
            ($this->actions[$transition->before])();
        }
    }

    function performAfterAction($transition, $after): void {
        if ($after) {
            $after();
        } else if ($transition->after && $this->actions[$transition->after]) {
            ($this->actions[$transition->after])();
        }
    }


}