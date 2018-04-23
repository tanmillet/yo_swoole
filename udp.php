<?php

/**
 * Class UDP
 * netcat -u 127.0.0.1 9502
 */
class UDP {
    protected $udp = null;
    const HOST = '0.0.0.0';
    const PORT = 9502;

    /**
     * UDP服务器与TCP服务器不同，UDP没有连接的概念。启动Server后，客户端无需Connect，直接可以向Server监听的9502端口发送数据包。对应的事件为onPacket。
     * UDP constructor.
     */
    public function __construct()
    {
        $this->udp = new swoole_server(self::HOST, self::PORT, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

        $this->udp->on('Packet', [$this, 'onPacket']);

        $this->udp->start();
    }

    /**
     * @param $udp
     * @param $data
     * @param $client_info
     * $clientInfo是客户端的相关信息，是一个数组，有客户端的IP和端口等内容
     * 调用 $server->sendto 方法向客户端发送数据
     */
    public function onPacket($udp, $data, $client_info)
    {
        $udp->sendto($client_info['address'], $client_info['port'], "Server " . $data);
    }
}

$udp = new UDP();