<?php

/**
 * Class Table
 * $size参数指定表格的最大行数，如果$size不是为2的N次方，如1024、8192,65536等，底层会自动调整为接近的一个数字，如果小于1024则默认成1024，即1024是最小值
 * table占用的内存总数为 (结构体长度 + KEY长度64字节 + 行尺寸$size) * (1.2预留20%作为hash冲突) * (列尺寸)，如果机器内存不足table会创建失败
 * set操作能存储的最大行数与$size无关，如$size为1024实际可存储的行数小于1024
 * swoole_table基于行锁，所以单次set/get/del在多线程/多进程的环境下是安全的
 * set/get/del是原子操作，用户代码中不需要担心数据加锁和同步的问题
 */
class Table {
    protected $table = null;

    public function __construct()
    {
        $this->table = new  swoole_table(1024);

        $this->table->column('id', swoole_table::TYPE_INT, 4);
        $this->table->column('name', swoole_table::TYPE_STRING, 64);
        $this->table->column('money', swoole_table::TYPE_FLOAT, 12);
        $this->table->create();

        $this->table->set('chris_info', ['id' => 1, 'name' => 'chris', 'money' => '22222.22']);
        $this->table['chris_info_2'] = [
            'id' => 2,
            'name' => 'chris2',
            'money' => '6666.22'
        ];

        print_r($this->table->get('chris_info'));
        $this->table->incr('chris_info', 'money', '2.0');
        print_r($this->table->get('chris_info'));
        print_r($this->table['chris_info_2']);
    }

}

$table = new Table();
