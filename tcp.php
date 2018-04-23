<?php

class TCP {
    protected $tcp = null;
    const HOST = '0.0.0.0';
    const PORT = 9501;

    public function __construct()
    {
        $this->tcp = new swoole_server(self::HOST, self::PORT);

        $this->tcp->on('connect', [$this, 'onConnect']);
        $this->tcp->on('receive', [$this, 'onReceive']);
        $this->tcp->on('close', [$this, 'onClose']);

        $this->tcp->start();
    }

    /**
     * @param $tcp 服务
     * @param $fd 客户端唯一标识
     * @param $reactor_id 线程ID master Master进程为主进程，该进程会创建Manager进程、Reactor线程等工作进/线程
     *
     *Reactor线程实际运行epoll实例，用于accept客户端连接以及接收客户端数据；
     * Manager进程为管理进程，该进程的作用是创建、管理所有的Worker进程和TaskWorker进程
     */
    public function onConnect($tcp, $fd, $reactor_id)
    {
        echo "Client: {$fd} Connect." . PHP_EOL;
    }

    /**
     * @param $tcp
     * @param $fd
     * @param $from_id
     * @param $data
     */
    public function onReceive($tcp, $fd, $from_id, $data)
    {
        $tcp->send($fd, "Server: " . $data);
    }

    /**
     * @param $tcp
     * @param $fd
     */
    public function onClose($tcp, $fd)
    {
        echo "Client: {$fd} Close." . PHP_EOL;
    }
}

$tcp = new TCP();