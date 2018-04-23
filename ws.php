<?php

class WS {
    const HOST = '0.0.0.0';
    const PORT = '9503';

    public function __construct()
    {
        $this->server = new swoole_websocket_server(self::HOST, self::PORT);
        $this->server->set([
            'worker_num' => 4,

//            'task_worker_num' => 2,
        ]);

        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('close', [$this, 'onClose']);

        $this->server->start();
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
        echo "server: handshake success with fd{$request->fd}", PHP_EOL;
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

        $ws_server->push($frame->fd, 'this ws server data');
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
     * @return null|swoole_websocket_server
     */
    public function getWs(): swoole_websocket_server
    {
        return $this->ws;
    }
}

$ws = new WS();