<?php
/** 上传文件客户端 **/
// 上传地址
$address = "127.0.0.1:8333";
// 检查上传文件路径参数
if(!isset($argv[1]))
{
   exit("use php client.php \$file_path\n");
}
// 上传文件路径
$file_to_transfer = trim($argv[1]);
// 上传的文件本地不存在
if(!is_file($file_to_transfer))
{
    exit("$file_to_transfer not exist\n");
}
// 建立socket连接
$client = stream_socket_client($address, $errno, $errmsg);
if(!$client)
{
    exit("$errmsg\n");
}
// 设置成阻塞
stream_set_blocking($client, 1);
// 文件名
$file_name = basename($file_to_transfer);
// 文件名长度
$name_len = strlen($file_name);
// 文件二进制数据
$file_data = file_get_contents($file_to_transfer);
// 协议头长度 4字节包长+1字节文件名长度
$PACKAGE_HEAD_LEN = 5;
// 协议包
$package = pack('NC', $PACKAGE_HEAD_LEN  + strlen($file_name) + strlen($file_data), $name_len) . $file_name . $file_data;
// 执行上传
fwrite($client, $package);
// 打印结果
echo fread($client, 8192),"\n";
