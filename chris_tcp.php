<?php
//创建一个服务
$serv = new swoole_server("127.0.0.1", 9503);

$serv->set([
    'worker_num' => 4,    //worker process num 1-4
    'max_request' => 10000,
]);
//$reactor_id  线程
$serv->on('connect', function ($serv, $fd, $reactor_id) {
    echo "connection open: {$fd} --- {$reactor_id} -- {$fd}\n";
});

$serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "swoole: {$data} reactor {$reactor_id}");
});

$serv->on('close', function ($serv, $fd) {
    echo "connection close: {$fd}\n";
});

$serv->start();