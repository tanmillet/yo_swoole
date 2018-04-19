<?php

namespace app\demo;


class chris_swoole_listener {
    private $serv;

    public function __construct()
    {
        $this->serv = new  \swoole_server('127.0.0.1', 9502);
        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1,
        ]);

        $this->serv->on('Start', [$this, 'onStart']);
        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Close', [$this, 'onClose']);

        $this->serv->addListener("127.0.0.1", 9505, SWOOLE_TCP);

        $this->serv->start();
    }


    public function onStart($serv)
    {
        echo "Start\n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        echo "Client {$fd} connect\n";
    }

    public function onReceive(\swoole_server $serv, $fd, $from_id, $data)
    {
        $info = $serv->connection_info($fd, $from_id);
        //来自9502的内网管理端口
        if ($info['from_port'] == 9502) {
            $serv->send($fd, "welcome admin\n");
        } //来自外网
        else {
            $serv->send($fd, 'Swoole: ' . $data);
        }
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }
}

$listener = new  chris_swoole_listener();