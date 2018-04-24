<?php

$workers = [];
$urls = [
    'https://laravel-china.org/',
    'https://www.baidu.com/',
    'https://mbd.baidu.com/newspage/data/landingsuper?context=%7B%22nid%22%3A%22news_15946221637208035039%22%7D&n_type=0&p_from=1',
    'https://www.baidu.com/s?cl=3&tn=baidutop10&fr=top1000&wd=%E7%8E%8B%E5%AE%9D%E5%BC%BA+%E5%88%98%E8%8B%A5%E8%8B%B1&rsv_idx=2',
];
echo "process-start-time" . date('Ymd H:i:s', time()) . PHP_EOL;

for ($i = 0; $i < 5; $i++) {
    $process = new swoole_process(function (swoole_process $process) use ($i, $urls) {
        $content = curlData($urls[$i]);
        echo $content . PHP_EOL;
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

echo "process-end-time" . date('Ymd H:i:s', time()) . PHP_EOL;

//而是写入到主进程管道。读取键盘输入将变为从管道中读取数据。默认为阻塞读取。
foreach ($workers as $process) {
    echo $process->read();
}

function curlData($url)
{
    sleep(1);
    return $url . ' success' . PHP_EOL;
}