<?php

try {
    $config = json_decode(file_get_contents('config/config.json'), true);
    $queue = new Redis();
    $queue->connect($config['host'], $config['port']);
    $queue->setOption(Redis::OPT_READ_TIMEOUT, -1);
} catch (Exception $e) {
    exit($e->getMessage());
}
