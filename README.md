# Enum State Machine

This package adds finite state machine capabilities that
* Are framework agnostic 
* Integrate enums into the state
* Incorporate PHP Attributes for designing your state

```php
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
}
```

You can register actions that take part in the transition process. These can activate before or after the state-change:

```php
use Ianpsgrant\EnumStateMachine\StateMachine;

class TrafficLightState extends StateMachine
{
    function registerActions(): array {
        return [
            "ChangeColor" => 
                fn() => new ChangeColorAction($this->context),
            "FixFault" => 
                fn() => new FixFaultAction($this->context),
        ];
    }
}
```

Add state to your Model or Data-Object by adding a state variable:

```php
class TrafficLight
{
    public StateMachine $state;

    public function __construct()
    {
        $this->state = new TrafficLightState(
            context: $this, 
            state: TrafficLightEnum::RED
        );
    }
}
```

And transition your state as follows:
```php

$trafficLight = new TrafficLight;
while (true) {
    $trafficLight->state->transition(TrafficLightEnum::GREEN);
    $trafficLight->state->transition(TrafficLightEnum::YELLOW);
    $trafficLight->state->transition(TrafficLightEnum::RED);
}
```