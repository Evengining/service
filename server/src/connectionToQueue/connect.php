<?php

try {
    $config = json_decode(file_get_contents('config/config.json'), true);
    $queue = new Redis();
    $queue->connect($config['host'], $config['port']);
} catch (Exception $e) {
    exit($e->getMessage());
}
