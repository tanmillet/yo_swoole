<?php

namespace app\demo;


class chris_client2 {

    private $client;

    public function __construct()
    {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
        if (!$this->client->connect('127.0.0.1', 9505, 1)) {

            echo "Error {$this->client->errCode}[{$this->client->errMsg}]";
        }

        fwrite(STDOUT, "请输入消息 Please input msg：");
        $msg = trim(fgets(STDIN));

        $this->client->send($msg);

        $message = $this->client->recv();

        echo "Get Message From Server:{$message}\n";
    }
}

$client_chris = new chris_client2();
$client_chris->connect();