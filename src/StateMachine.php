<?php

namespace Ianpsgrant\EnumStateMachine;

use Example\OrderStateEnum;
use ReflectionAttribute;
use Ianpsgrant\EnumStateMachine\Transitions;

abstract class StateMachine {
    private array $actions;
    
    abstract function registerActions(): array;

    public function __construct(public mixed $context, public mixed $state) {
        $this->actions = $this->registerActions();
    }

    final public function transition(mixed $to, ?callable $before = null, ?callable $after = null): void {

        $transition = $this->getTransition(currentState: $this->state, futureState: $to);
        if (!$transition) {
            throw new \Exception($this->state->name . " does not transition to " . $to->name);
        }

        // Perform 'before' action
        if ($before) {
            $before();
        } else if ($transition->before && $this->actions[$transition->before]) {
            ($this->actions[$transition->before])();
        }
        // Transition state
        $this->state = $to;
        // Perform 'after' action
        if ($after) {
            $after();
        } else if ($transition->after && $this->actions[$transition->after]) {
            ($this->actions[$transition->after])();
        }
    }

    function getTransition($currentState, $futureState): ?Transitions {
        /** @var ReflectionAttribute[] */
        $currentStateAttributes = (new \ReflectionEnumUnitCase($this->state::class, $currentState->name))->getAttributes(Transitions::class);

        // First check 'to' links of currentState
        foreach ($currentStateAttributes as $currentStateAttribute) {
            /** @var Transitions */
            $transition = $currentStateAttribute->newInstance();
            if ($transition->transitionsTo($futureState)) {
                return $transition;
            }
        }

        /** @var ReflectionAttribute[] */
        $futureStateAttributes = (new \ReflectionEnumUnitCase($this->state::class, $futureState->name))->getAttributes(Transitions::class);

        // Then check 'from' links of futureState
        foreach ($futureStateAttributes as $futureStateAttribute) {
            /** @var Transitions */
            $transition = $futureStateAttribute->newInstance();
            if ($transition->isTransitionedFrom($currentState)) {
                return $transition;
            }
        }
        return null;
    }
}