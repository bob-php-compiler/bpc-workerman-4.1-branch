<?php

namespace Protocols {
    class BinaryTransfer
    {
        // 协议头长度
        const PACKAGE_HEAD_LEN = 5;

        public static function input($recv_buffer)
        {
            // 如果不够一个协议头的长度，则继续等待
            if(strlen($recv_buffer) < self::PACKAGE_HEAD_LEN)
            {
                return 0;
            }
            // 解包
            $package_data = unpack('Ntotal_len/Cname_len', $recv_buffer);
            // 返回包长
            return $package_data['total_len'];
        }

        public static function decode($recv_buffer)
        {
            // 解包
            $package_data = unpack('Ntotal_len/Cname_len', $recv_buffer);
            // 文件名长度
            $name_len = $package_data['name_len'];
            // 从数据流中截取出文件名
            $file_name = substr($recv_buffer, self::PACKAGE_HEAD_LEN, $name_len);
            // 从数据流中截取出文件二进制数据
            $file_data = substr($recv_buffer, self::PACKAGE_HEAD_LEN + $name_len);
             return array(
                 'file_name' => $file_name,
                 'file_data' => $file_data,
             );
        }

        public static function encode($data)
        {
            // 可以根据自己的需要编码发送给客户端的数据，这里只是当做文本原样返回
            return $data;
        }
    }
}

namespace {
    use Workerman\Worker;
    use Workerman\Connection\TcpConnection;
    require_once __DIR__ . '/autoload.inc';

    if (defined('__BPC__')) {
        Worker::$processTitle = $argv[0];
    }

    $worker = new Worker('BinaryTransfer://0.0.0.0:8333');
    // 保存文件到tmp下
    $worker->onMessage = function(TcpConnection $connection, $data)
    {
        $save_path = '/tmp/'.$data['file_name'];
        file_put_contents($save_path, $data['file_data']);
        $connection->send("upload success. save path $save_path");
    };

    Worker::runAll();
}
