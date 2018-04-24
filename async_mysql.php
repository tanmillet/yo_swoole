<?php

class Async_Mysql {

    /**
     * @var null|swoole_mysql
     */
    protected $async_myql = null;

    /**
     * @var array
     */
    protected $set_configs = [];

    /**
     * Async_Mysql constructor.
     * @throws \Swoole\Mysql\Exception
     */
    public function __construct()
    {
        $this->async_myql = new swoole_mysql();
        $this->set_configs = [
            'host' => '127.0.0.1',
            'port' => 3307,
            'user' => 'root',
            'password' => '',
            'database' => 'test',
            'charset' => 'utf8', //指定字符集
            'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
        ];
        $this->async_myql->connect($this->set_configs, function (swoole_mysql $db, $result) {
            if ($result === false) {
                print_r($db->connect_errno, $db->connect_error);
                die;
            }
            $sql = 'SELECT * FROM `comments` WHERE `id` = \'1\' LIMIT 0, 1000';
            $db->query($sql, function (swoole_mysql $db, $res) {
                if ($res === false) {
                    print_r($db->error, $db->errno);
                } elseif ($res === true) {
                    print_r($db->affected_rows, $db->insert_id);
                } else {
                    print_r($res);
                }
                $db->close();
            });
        });
    }
}

$async = new Async_Mysql();