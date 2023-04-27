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
stream_set_blocking($client, 1);
// 文件名
$file_name = basename($file_to_transfer);
// 文件二进制数据
$file_data = file_get_contents($file_to_transfer);
// base64编码
$file_data = base64_encode($file_data);
// 数据包
$package_data = array(
    'file_name' => $file_name,
    'file_data' => $file_data,
);
// 协议包 json+回车
$package = json_encode($package_data)."\n";
// 执行上传
fwrite($client, $package);
// 打印结果
echo fread($client, 8192),"\n";
