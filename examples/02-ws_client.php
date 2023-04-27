<?php
use Workerman\Worker;
use Workerman\Connection\SyncTcpConnection;
require_once __DIR__ . '/autoload.inc';

if (defined('__BPC__')) {
    Worker::$processTitle = $argv[0];
}

$worker = new Worker();

$worker->onWorkerStart = function($worker){

    $con = new SyncTcpConnection('ws://127.0.0.1:2000');

    $con->onConnect = function(SyncTcpConnection $con) {
        $con->send('hello');
    };

    $con->onMessage = function(SyncTcpConnection $con, $data) {
        echo $data;
    };

    $con->connect();
};

Worker::runAll();
