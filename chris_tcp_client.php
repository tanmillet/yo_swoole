<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

$client->on("connect", function ($cli) {
    $cli->send("hello world\n");
    fwrite(STDOUT, '请输入消息');
    $msg = trim(fgets(STDIN));
    $cli->send($msg);
});


$client->on("receive", function ($cli, $data) {
    echo "received: {$data}\n";
});

$client->on("error", function ($cli) {
    echo "connect failed\n";
});

$client->on("close", function ($cli) {
    echo "connection close\n";
});

$client->connect("127.0.0.1", 9503, 0.5);