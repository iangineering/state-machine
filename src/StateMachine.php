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

    final public function transition(mixed $to, null|callable|array $before = null, null|callable|array $after = null): void {
        $transition = $this->getTransition(currentState: $this->state, futureState: $to);
        if (!$transition) {
            throw new \Exception($this->state->name . " does not transition to " . $to->name);
        }

        $beforeAction = $before;
        if ($beforeAction === null && !empty($this->actions[$transition->before])) {
            $beforeAction = $this->actions[$transition->before];
        }
        $this->performActions($beforeAction);

        // Transition state
        $this->state = $to;

        $afterAction = $after;
        if ($afterAction === null && !empty($this->actions[$transition->after])) {
            $afterAction = $this->actions[$transition->after];
        }
        $this->performActions($afterAction);
    }

    private function getTransition($currentState, $futureState): ?Transitions {
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

    private function performActions(null|callable|array $actions): void {
        if (!$actions) {
            return;
        }
        if (is_callable($actions)) {
            $actions = [$actions];
        }
        foreach ($actions as $action) {
            $action();
        }
    }

}