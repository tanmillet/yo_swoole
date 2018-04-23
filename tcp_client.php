<?php

class Tcp_Client {

    protected $tcp_client = null;
    const HOST = '127.0.0.1';
    const PORT = 9501;

    public function __construct()
    {
        $this->tcp_client = new swoole_client(self::HOST, self::PORT);

        $this->connect(self::HOST, self::PORT);
        $this->send('client send data : this is test client');
        $tcp_server_data = $this->receive();

        $this->close();
    }

    /**
     * 连接到远程服务器
     * $host是远程服务器的地址，1.10.0或更高版本已支持自动异步解析域名，$host可直接传入域名
     * $port是远程服务器端口
     * $timeout是网络IO的超时，包括connect/send/recv，单位是s，支持浮点数。默认为0.5s，即500ms
     * $flag参数在UDP类型时表示是否启用udp_connect 设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包。
     * $flag参数在TCP类型,$flag=1表示设置为非阻塞socket，connect会立即返回。如果将$flag设置为1，那么在send/recv前必须使用swoole_client_select来检测是否完成了连接
     */
    public function connect($host, $port, $timeout = 0.5, $flag = 0)
    {
        if (!$this->tcp_client->connect($host, $port, $timeout, $flag)) {
            echo 'connect failed.';
            die();
        };
    }

    /**
     * 发送数据到远程服务器，必须在建立连接后，才可向Server发送数据。
     * $data参数为字符串，支持二进制数据
     * 成功发送返回的已发数据长度
     * 失败返回false，并设置$swoole_client->errCode
     *
     * @param $data
     */
    public function send($data)
    {
        if (!$this->tcp_client->send($data)) {
            echo 'client send data failed.';
            die();
        }
    }

    /**
     * recv方法用于从服务器端接收数据.
     *
     * $size，接收数据的缓存区最大长度，此参数不要设置过大，否则会占用较大内存
     * $waitall，是否等待所有数据到达后返回
     * @return string
     */
    public function receive()
    {
        $tcp_server_data = $this->tcp_client->recv();
        if (!$tcp_server_data) {
            die('recv data failed.');
        }
        return $tcp_server_data;
    }

    /**
     * 关闭连接
     * 操作成功返回 true。当一个swoole_client连接被close后不要再次发起connect。正确的做法是销毁当前的swoole_client，重新创建一个swoole_client并发起新的连接。
     */
    public function close()
    {
        $this->tcp_client->close();
    }
}

$tcp_client = new Tcp_Client();