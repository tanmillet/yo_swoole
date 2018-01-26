<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 18.1.26
 * Time 10:37
 */

$serv = new swoole_server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);


$serv->on('Packet', function ($serv, $data, $clinet_info) {
    $serv->sendto($clinet_info['address'], $clinet_info['port'], 'Server: '.$data);
});

$serv->start();