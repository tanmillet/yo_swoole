<?php

/**
 * Class Swoole
 */
class Swoole {
    /**
     * @var null
     */
    protected $server = null;

    /**
     *
     */
    const HOST = '0.0.0.0';

    /**
     * @var int|string
     */
    protected $port = '';

    /**
     * Swoole constructor.
     */
    public function __construct()
    {
        $this->port = 9501;
    }
}