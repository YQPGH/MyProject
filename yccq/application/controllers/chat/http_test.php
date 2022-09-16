<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/10/23
 * Time: 10:06
 */
use Workerman\Worker;

//print_r(__DIR__);exit;
require_once __DIR__ . '/Workerman/Autoloader.php';

// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker("http://0.0.0.0:2345");

// 启动4个进程对外提供服务
$http_worker->count = 4;

// 接收到浏览器发送的数据时回复hello world给浏览器
$http_worker->onMessage = function($connection, $data)
{
    // 向浏览器发送hello world
    $connection->send('hello world');
};

// 运行worker
Worker::runAll();
