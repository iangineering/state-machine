<?php

namespace Example;

use Ianpsgrant\EnumStateMachine\StateMachine;

class OrderState extends StateMachine
{
    function registerActions(): array {
        return [
            "PrepareInProgressTransition" => fn() => var_dump("PrepareInProgressTransition"),
            "CompleteInProgressTransition" => fn() => var_dump("CompleteInProgressTransition"),
            "ChangeOrder" => fn() => (new ChangeOrder())->execute($this->context),
        ];
    }
}