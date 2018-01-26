<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 18.1.26
 * Time 10:37
 */


$ws = new swoole_websocket_server('0.0.0.0', 9054);


$ws->on('open', function ($ws, $request) {
    $ws->push($request->fd, 'hello welcome\n');
});

$ws->on('message', function ($ws, $frame) {
    $ws->push($frame->fd, "sever: {$frame->data}");
});

$ws->on('close', function ($ws, $fd) {
    echo "client {$fd} is closed";
});

$ws->start();