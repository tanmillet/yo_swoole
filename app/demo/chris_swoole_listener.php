<?php

namespace app\demo;


class chris_swoole_listener {
    public function __construct()
    {
        $serv = new  \swoole_server('127.0.0.1', 9502);
        $port = $serv->listen("127.0.0.1", 9501, SWOOLE_TCP);
        $serv->start();
        $port->on('receive', function ($serv, $fd, $from_id, $data) {
            $serv->send($fd, 'Swoole: '.$data);
            $serv->close($fd);
        });
    }
}
$listener = new  chris_swoole_listener();