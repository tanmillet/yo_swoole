<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 18.1.26
 * Time 10:12
 */

// $serv = new swoole_server("127.0.0.1", 9501);
//
// $serv->set(['task_worker_num' => 4]);
//
// $serv->on('connect', function ($serv, $fd) {
//     echo 'Client: Connect.\n';
// });
//
//
// $serv->on('receive', function ($serv, $fd, $form_id, $data) {
//     $task_id = $serv->task($data);
//     // $serv->send($fd, 'Server: '.$data);
// });
//
// $serv->on('close', function ($serv, $fd) {
//     echo 'Client: close.\n';
// });
//
// $serv->start();


$serv = new swoole_server("127.0.0.1", 9501);

$serv->set(['task_worker_num' => 4]);

$serv->on('receive', function ($serv, $fd, $form_id, $data) {
    $task_id = $serv->task($data);
    echo "Dispath AsyncTask: id=$task_id\n";
});

//处理异步任务
$serv->on('task', function ($serv, $task_id, $from_id, $data) {
    echo "New AsyncTask[id=$task_id]".PHP_EOL;
    //返回任务执行的结果
    $serv->finish("$data -> OK");
});

//处理异步任务的结果
$serv->on('finish', function ($serv, $task_id, $data) {
    echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
});

$serv->start();