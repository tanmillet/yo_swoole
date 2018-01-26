<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 18.1.26
 * Time 10:37
 */


$http = new swoole_http_server('0.0.0.0', 9503);


$http->on('request', function ($request, $response) {
    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->send('<h1>Hello Swoole'.rand(1000, 9999), '</h1>');
});


$http->start();