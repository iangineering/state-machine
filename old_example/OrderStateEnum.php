<?php

namespace Example;

use Ianpsgrant\EnumStateMachine\Transitions;

enum OrderStateEnum {
    #[Transitions(to: OrderStateEnum::IN_PROGRESS, before: "PrepareInProgressTransition", after: "CompleteInProgressTransition")]
    case WAITING;
    #[Transitions(to: OrderStateEnum::WAITING, before: "ChangeOrder")]
    case IN_PROGRESS;
}