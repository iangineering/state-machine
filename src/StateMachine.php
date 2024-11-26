<?php

namespace Iangineering\StateMachine;

use ReflectionAttribute;
use Iangineering\StateMachine\Transitions;

abstract class StateMachine {
    private array $actions;
    abstract function registerActions(): array;

    public function __construct(public mixed $context, public mixed $state) {
        $this->actions = $this->registerActions();
    }

    final public function transition(mixed $to, null|callable $before = null, null|callable $after = null): void {
        $transition = $this->getTransition(currentState: $this->state, futureState: $to);
        if (!$transition) {
            throw new \Exception($this->state->name . " does not transition to " . $to->name);
        }

        $this->performActions($transition->before, $before);
        $this->state = $to;
        $this->performActions($transition->after, $after);
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

    private function performActions(null|string|array $actionNames, ?callable $overrideAction): void {
        if ($overrideAction) {
            $overrideAction();
            return;
        }
        if (!$actionNames) {
            return;
        }
        if (is_string($actionNames)) {
            $actionNames = [$actionNames];
        }

        foreach ($actionNames as $actionName) {
            if (!empty($this->actions[$actionName])) {
                $this->actions[$actionName]();
            } else {
                //TODO Emit MissingAction warning
                // trigger_error(string $message, int $error_type = E_USER_WARNING);
            }
        }
    }

}