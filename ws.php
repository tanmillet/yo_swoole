<?php

/**
 * Class WS
 * Chrome/Firefox/高版本IE/Safari等浏览器内置了JS语言的WebSocket客户端
 * 微信小程序开发框架内置的WebSocket客户端
 * 异步的PHP程序中可以使用Swoole\Http\Client作为WebSocket客户端
 * apache/php-fpm或其他同步阻塞的PHP程序中可以使用swoole/framework提供的同步WebSocket客户端
 * 非WebSocket客户端不能与WebSocket服务器通信
 */
class WS {
    protected $ws = null;
    const HOST = '0.0.0.0';
    const PORT = '9510';

    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => '/ws/soft/app/yo/data',
            'task_worker_num' => 4,
        ]);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        //加入task
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);

        $this->ws->on('close', [$this, 'onClose']);
//        $this->ws->on('request', [$this, 'onRequest']);

        $this->ws->start();
    }

    /**
     *
     * 客户端进行握手之后回调
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数。
     *
     * @param $ws_server
     * @param $request
     * $req 是一个Http请求对象，包含了客户端发来的握手请求信息
     * onOpen事件函数中可以调用push向客户端发送数据或者调用close关闭连接
     * onOpen事件回调是可选的
     */
    public function onOpen($ws_server, $request)
    {
        if ($request->fd == 1) {
            swoole_timer_tick(2000, function ($time_id) {
                echo "2 second timerid：" . $time_id . PHP_EOL;
            });
        }
        echo "server: handshake success with fd {$request->fd}", PHP_EOL;
    }

    /**
     *
     * 当服务器收到来自客户端的数据帧时会回调此函数。
     *
     * @param $ws_server
     * @param $frame
     * $frame 是swoole_websocket_frame对象，包含了客户端发来的数据帧信息
     * onMessage回调必须被设置，未设置服务器将无法启动
     * 客户端发送的ping帧不会触发onMessage，底层会自动回复pong包
     *
     * $frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
     * $frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
     * $frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
     * $frame->finish， 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送（底层已经实现了自动合并数据帧，现在不用担心接收到的数据帧不完整）
     */
    public function onMessage($ws_server, $frame)
    {
        echo 'Client fd : ' . $frame->fd . '  Client Data : ' . $frame->data . ' WebSocket OpCode : ' . $frame->opcode . ' Finish:' . $frame->finish . PHP_EOL;

        //加入task
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        $ws_server->task($data);

        swoole_timer_after(5000 , function () use ($ws_server , $frame){
            $ws_server->push($frame->fd, '5 second after timerid');
        });

        $ws_server->push($frame->fd, 'this ws server data');
    }

    /**
     * function onTask(swoole_server $serv, int $task_id, int $src_worker_id, mixed $data);
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
     * 当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，
     * 这时将不再接收新的Task，当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task。
     *
     * @param $server
     * @param $task_id
     * @param $worker_id
     * @param $data
     * @return string
     */
    public function onTask($server, $task_id, $worker_id, $data)
    {
        print_r($data);
        sleep(10);

        return 'on task is finish';
    }

    /**
     * void onFinish(swoole_server $serv, int $task_id, string $data)
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程
     * $task_id是任务的ID
     * $data是任务处理的结果内容
     *
     * @param $server
     * @param $task_id
     * @param $data
     */
    public function onFinish($server, $task_id, $data)
    {
        print_r($data);

        echo "task id : " . $task_id . " is  finished" . PHP_EOL;
    }

    /**
     *关闭客户端连接.
     *
     * @param $ws_server
     * @param $fd
     *
     * 操作成功返回true，失败返回false.
     * Server主动close连接，也一样会触发onClose事件。
     * 不要在close之后写清理逻辑。应当放置到onClose回调中处理
     * $reset设置为true会强制关闭连接，丢弃发送队列中的数据
     */
    public function onClose($ws_server, $fd)
    {
        echo "Client {$fd} closed" . PHP_EOL;
    }

    /**
     * @param $request
     * @param $response
     * WebSocket服务器除了提供WebSocket功能之外，实际上也可以处理Http长连接。只需要增加onRequest事件监听即可实现Comet方案Http长轮询。
     */
    public function onRequest($request, $response)
    {

    }

    /**
     * @return null|swoole_websocket_server
     */
    public function getWs(): swoole_websocket_server
    {
        return $this->ws;
    }
}

$ws = new WS();