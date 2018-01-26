<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 18.1.26
 * Time 10:12
 */

$serv = new swoole_server("127.0.0.1", 9501);

$serv->on('connect', function ($serv, $fd) {
    echo 'Client: Connect.\n';
});

$serv->on('receive', function ($serv, $fd, $form_id, $data) {
    $serv->send($fd, 'Server: '.$data);
});

$serv->on('close', function ($serv, $fd) {
    echo 'Client: close.\n';
});

$serv->start();