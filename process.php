<?php

//swoole_process::__construct(callable $function, $redirect_stdin_stdout = false, $create_pipe = true);
// 启用命名空间
//Swoole\Process::__construct(callable $function, $redirect_stdin_stdout = false, $create_pipe = true)
/**
 * $function，子进程创建成功后要执行的函数，底层会自动将函数保存到对象的callback属性上。如果希望更改执行的函数，可赋值新的函数到对象的callback属性
 * $redirect_stdin_stdout，重定向子进程的标准输入和输出。启用此选项后，在子进程内输出内容将不是打印屏幕，而是写入到主进程管道。读取键盘输入将变为从管道中读取数据。默认为阻塞读取。
 * $create_pipe，是否创建管道，启用$redirect_stdin_stdout后，此选项将忽略用户参数，强制为true。如果子进程内没有进程间通信，可以设置为 false
 *
 * 自 1.7.22 版本起参数$create_pipe为int类型且允许设置管道的类型，其默认值为2，默认使用DGRAM管道。
 *
 * 参数 $create_pipe 小于等于0或为 false 时，不创建管道
 * 参数 $create_pipe 为1或为 true 时，管道类型将设置为 SOCK_STREAM
 * 参数$create_pipe为2时，管道类型将设置为SOCK_DGRAM
 * 启用$redirect_stdin_stdout 后，此选项将忽略用户参数，强制为1
 *
 */
$process = new swoole_process(function (swoole_process $process) {
    $process->exec("/usr/local/bin/php" , [__DIR__ . '/http.php']);
}, false);
$pid = $proces->start();
echo $pid . PHP_EOL;

swoole_process::wait();

