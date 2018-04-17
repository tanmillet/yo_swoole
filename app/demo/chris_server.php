<?php

namespace app\demo;


class chris_server {

    private $serv;

    public function __construct()
    {
        $this->serv = new \swoole_server('127.0.0.1', 9501);

        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
        ]);

        $this->serv->on('Start', [$this, 'onStart']);
        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Close', [$this, 'onClose']);

    }


    public function onStart($serv)
    {
        echo 'Start', PHP_EOL;
    }

    public function onConnect($serv, $fd, $from)
    {
        $serv->send($fd, "Hello {$fd}!");
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd, $data);
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

}

$chris = new chris_server();

