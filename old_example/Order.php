<?php

namespace Example;

use Ianpsgrant\EnumStateMachine\StateMachine;

class Order
{
    public StateMachine $state;

    public function __construct() {
        $this->state = new OrderState($this, OrderStateEnum::WAITING);
    }
}