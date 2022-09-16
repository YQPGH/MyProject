<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/10/23
 * Time: 10:06
 */
//Workerman创建聊天室实例

// 标记是全局启动
define('GLOBAL_START', 1);

require_once __DIR__ . '/Workerman/Connection.php';
require_once __DIR__ . '/Workerman/Autoloader.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

// 心跳间隔25秒
define('HEARTBEAT_TIME', 25);

// 设置时区
date_default_timezone_set('PRC');

// 以websocket协议为例
// Create a Websocket server
$ws = new Worker("websocket://118.xxx.xxx.xx:4980");

// 启动4个进程对外提供服务
$ws->count = 4;

// 已连接客户端 ，便于统计在线用户
$ws->hasConnections = array();

// 当新客户端连上来时分配uid，并保存连接，并通知所有客户端
$ws->onConnect = function($connection)
{
    global $ws;
    // 为这个链接分配一个uid
    $connection->uid = ++$global_uid;

    foreach ($ws->connections as $conn) {
        $conn->send("用户{$connection->uid} 已上线");
    }
    $connection->close(); // 关闭socket连接
    // echo "New connection\n";
};

// 当客户端发送消息过来时，转发给所有人 (聊天主要使用的功能)
$ws->onMessage = function($connection, $message)
{
    global $ws,$db;
    $data = json_decode($message, true);

    $data['time'] = date('Y-m-d H:i:s');
    $insert = array(
        'uid'         =>$data['uid'],
        'avatar'      =>$data['avatar'],
        'name'        =>$data['name'],
        'type'        =>$data['type'],
        'content'     =>$data['content'],
        'img_path'    =>$data['img_path'],
        'voice_path'  =>$data['voice_path'],
        'topic_id'    =>$data['topic_id'],
        'is_question' =>$data['is_question'],
        'status'      =>$data['status'],
        'time'        =>$data['time']
    );

    switch ($data['type']) {
        case 'save':
            // 把房间和用户信息保存下来
            $ws->hasConnections[$connection->id] = array('name' => $data['name'], 'uid' => $data['uid'], 'topic_id' => $data['topic_id'], 'avatar' => $data['avatar']);
            sendMessage($data, $data['topic_id']);
            // $back_data = array('content' => $content, 'client_id' => $connection->id, 'client_name' => $data['name'], 'type' => 'login', 'clients' => $ws->hasConnections, 'time' => date('Y-m-d H:i:s'));

            break;

        case 'text':
            sendMessage($data, $data['topic_id']);
            $insert_id = $db->insert('chat_record')->cols($insert)->query();  // 存数据库 save data
            break;

        case 'img':
            sendMessage($data, $data['topic_id']);
            $insert_id = $db->insert('chat_record')->cols($insert)->query();  // 存数据库 save data
            break;

        case 'voice':
            sendMessage($data, $data['topic_id']);
            $insert_id = $db->insert('chat_record')->cols($insert)->query();  // 存数据库 save data
            break;
        default:
            break;
    }

    // $connection->close(); // 关闭socket连接
};

// 当客户端断开时，广播给所有客户端
// $ws->onClose = function($connection)
// {
//     global $ws;
//     foreach ($ws->connections as $conn) {
//         $conn->send("用户[{$connection->uid}] 消消走了");
//     }
//     $connection->close(); // 关闭socket连接
// };

// 给房间的每个人发消息
function sendMessage($data, $topic_id){
    global $ws;
    $data = json_encode($data);
    foreach ($ws->connections as $id => $conn) {
        if ($ws->hasConnections[$id]['topic_id'] == $topic_id) {
            $conn->send($data);
            // $conn->send($ws->hasConnections[$id]);
        }
    }
}

// 进程启动后设置一个每秒运行一次的定时器(心跳, 保持长连接)
$ws->onWorkerStart = function($ws) {
    global $db;
    // ip, 端口号, 账号, 密码, 库名
    $db = new \Workerman\MySQL\Connection('127.0.0.1', '3306', 'username', 'password', 'database');

    Timer::add(1, function()use($ws){
        // global $ws;
        $arr = array('type'=>'keep','content'=>'ping');
        $arr = json_encode($arr);
        foreach($ws->connections as $connection) {
            $connection->send($arr);
        }
    });
};


// Run worker
Worker::runAll();
