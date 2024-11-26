<?php

namespace Example;

require __DIR__ . '/../vendor/autoload.php';

// $order = new Order;

// $before = fn() => var_dump($order->state->state);
// $after = fn() => var_dump($order->state->state);

// // $order->state->transition(to: OrderStateEnum::IN_PROGRESS, before: $before, after: $after);

// $order->state->transition(to: OrderStateEnum::IN_PROGRESS);
// $order->state->transition(to: OrderStateEnum::WAITING);


$trafficLight = new TrafficLight;

$trafficLight->run();