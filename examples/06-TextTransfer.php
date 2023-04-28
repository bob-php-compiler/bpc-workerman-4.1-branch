<?php

namespace Protocols {
    class TextTransfer
    {
        public static function input($recv_buffer)
        {
            $recv_len = strlen($recv_buffer);
            if($recv_buffer[$recv_len-1] !== "\n")
            {
                return 0;
            }
            return strlen($recv_buffer);
        }

        public static function decode($recv_buffer)
        {
            // 解包
            $package_data = json_decode(trim($recv_buffer), true);
            // 取出文件名
            $file_name = $package_data['file_name'];
            // 取出base64_encode后的文件数据
            $file_data = $package_data['file_data'];
            // base64_decode还原回原来的二进制文件数据
            $file_data = base64_decode($file_data);
            // 返回数据
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

    $worker = new Worker('TextTransfer://0.0.0.0:8333');
    // 保存文件到tmp下
    $worker->onMessage = function(TcpConnection $connection, $data)
    {
        $save_path = '/tmp/'.$data['file_name'];
        file_put_contents($save_path, $data['file_data']);
        $connection->send("upload success. save path $save_path");
    };

    Worker::runAll();
}
