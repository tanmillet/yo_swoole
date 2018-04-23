<?php

/**
 * Class Http
 */
class Http {
    /**
     * @var null|swoole_http_server
     */
    protected $http = null;

    /**
     * 监听 全部网络
     */
    const HOST = '0.0.0.0';
    /**
     *监听 端口
     */
    const PORT = 9502;

    /**
     * $host参数用来指定监听的ip地址，如127.0.0.1，或者外网地址，或者0.0.0.0监听全部地址
     * IPv4使用 127.0.0.1表示监听本机，0.0.0.0表示监听所有地址
     * IPv6使用::1表示监听本机，:: (相当于0:0:0:0:0:0:0:0) 表示监听所有地址
     * $port监听的端口，如9501
     * 如果$sock_type为UnixSocket Stream/Dgram，此参数将被忽略
     * 监听小于1024端口需要root权限
     * 如果此端口被占用server->start时会失败
     * $mode运行的模式
     * SWOOLE_PROCESS多进程模式（默认）
     * SWOOLE_BASE基本模式
     * $sock_type指定Socket的类型，支持TCP、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 6种
     * 使用$sock_type | SWOOLE_SSL可以启用SSL隧道加密。启用SSL后必须配置ssl_key_file和ssl_cert_f
     * Http constructor.
     */
    public function __construct()
    {
        $this->http = new swoole_http_server(self::HOST, self::PORT);
        $this->http->set([
            'worker_num' => 4,
            'max_request' => 10000,
        ]);
        $this->http->on('request', [$this, 'onRequest']);

        $this->http->start();
    }

    /**
     * @param $request
     * Http请求对象，保存了Http客户端请求的相关信息，包括GET、POST、COOKIE、Header等。
     * @param $response
     * Http响应对象，通过调用此对象的方法，实现Http响应发送。
     */
    public function onRequest($request, $response)
    {
        // $request->get();
        // $request->get('pram');
        //发送Http响应体，并结束请求处理。
        $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
    }

    /**
     * @param null $http
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * @return null
     */
    public function getHttp()
    {
        return $this->http;
    }
}

$http = new Http();