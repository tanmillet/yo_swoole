<?php

namespace app\demo;

class chris_timer {

    private $str = 'Say Timer Hello';

    public function onAfter()
    {

        echo $this->str . PHP_EOL;
    }

}

$chris_timer = new chris_timer();

$work_id = swoole_timer_after(1000, [$chris_timer, 'onAfter']);


swoole_timer_clear($work_id);