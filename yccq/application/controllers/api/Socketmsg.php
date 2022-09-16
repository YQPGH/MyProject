
<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Date: 2020/03/22
 *
 */
use PHPSocketIO\SocketIO;
use Workerman\Worker;
require_once "././Workerman/vendor/autoload.php";
// 创建socket.io服务端，监听2021端口
//$io = new SocketIO(2021);

// 初始化一个worker容器，监听1234端口
$worker = new Worker('websocket://0.0.0.0:1234');

class Socketmsg extends CI_Controller{
    function __construct(){
        parent::__construct();


    }

}
//// 当有客户端连接时打印一行文字
//$io->on('connection', function($connection)use($io){
//    echo "new connection coming\n";
//});
//
//Worker::runAll();
/*
 * 注意这里进程数必须设置为1，否则会报端口占用错误
 * (php 7可以设置进程数大于1，前提是$inner_text_worker->reusePort=true)
 */

$worker->count = 1;

// worker进程启动后创建一个text Worker以便打开一个内部通讯端口

$worker->onWorkerStart = function($worker)

{

    // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符

    $inner_text_worker = new Worker('text://0.0.0.0:5678');

    $inner_text_worker->onMessage = function($connection, $buffer)

    {

        // $data数组格式，里面有uid，表示向那个uid的页面推送数据
        $data = json_decode($buffer, true);

        $uid = $data['uid'];

        // 通过workerman，向uid的页面推送数据
        $ret = sendMessageByUid($uid, $buffer);

        // 返回推送结果
        $connection->send($ret ? 'ok' : 'fail');

    };

    // ## 执行监听 ##
    $inner_text_worker->listen();

};

// 新增加一个属性，用来保存uid到connection的映射
$worker->uidConnections = array();

// 当有客户端发来消息时执行的回调函数
$worker->onMessage = function($connection, $data)

{

    global $worker;
    // 判断当前客户端是否已经验证,既是否设置了uid
    if(!isset($connection->uid))
    {

        // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
        $connection->uid = $data;

        /* 保存uid到connection的映射，这样可以方便的通过uid查找connection，
         * 实现针对特定uid推送数据
         */
        $worker->uidConnections[$connection->uid] = $connection;

        return;

    }

};



// 当有客户端连接断开时
$worker->onClose = function($connection)
{

    global $worker;
    if(isset($connection->uid))
    {

        // 连接断开时删除映射
        unset($worker->uidConnections[$connection->uid]);

    }

};



// 向所有验证的用户推送数据
function broadcast($message)
{

    global $worker;
    foreach($worker->uidConnections as $connection)
    {

        $connection->send($message);

    }

}



// 针对uid推送数据
function sendMessageByUid($uid, $message)
{

    global $worker;

    if(isset($worker->uidConnections[$uid]))
    {

        $connection = $worker->uidConnections[$uid];

        $connection->send($message);

        return true;

    }

    return false;

}



// 运行所有的worker
Worker::runAll();
//启动后端服务 php push.php start -d

//前端接收推送的js代码

//var ws = new WebSocket('ws://127.0.0.1:1234');
//
//ws.onopen = function(){
//    var uid = 'uid1';
//    ws.send(uid);
//};

//ws.onmessage = function(e){
//    alert(e.data);
//};

//后端推送消息的代码



// 建立socket连接到内部推送端口
$client = stream_socket_client('tcp://127.0.0.1:5678', $errno, $errmsg, 1);

// 推送的数据，包含uid字段，表示是给这个uid推送
$data = array('uid'=>'uid1', 'percent'=>'88%');

// 发送数据，注意5678端口是Text协议的端口，Text协议需要在数据末尾加上换行符
fwrite($client, json_encode($data)."\n");

// 读取推送结果
echo fread($client, 8192);