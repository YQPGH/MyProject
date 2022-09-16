<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
require_once __DIR__ .'/../../mysql-master/src/Connection.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    /**
     * 新建一个类的静态成员，用来保存数据库实例
     */
    public static $db = null;


    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
        self::$db = new \Workerman\MySQL\Connection('localhost', '3306', 'root', '', 'zy_yccq');
//        self::$db = new \Workerman\MySQL\Connection('118.126.115.191', '3306', 'root', 'zymysql^abc..', 'zy_yccq1');
//        self::$db = new \Workerman\MySQL\Connection('5937726050685.gz.cdb.myqcloud.com', '13506', 'cdb_outerroot', 'us5ht%d4n&vy', 'zy_yccq1');
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
     public static function onConnect($client_id)
     {

//         $new_message = array(
//             'type'=>'init',
//             'client_id'=>$client_id,
//         );
//         // 向当前client_id发送数据
//         Gateway::sendToClient($client_id, json_encode($new_message));
//         $new_message = array('type'=>'Connect','client_id'=>$client_id,'time'=>date('Y-m-d H:i:s'),'msg'=>"$client_id 登录了");
         // 向所有人发送
//         Gateway::sendToAll(json_encode($new_message));
     }

   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {


       // debug
//       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}
//        client_id:$client_id  time:".date('Y-m-d H:i:s')." session:".$_SESSION." onMessage:".$message."\n";

       // 客户端传递的是json数据
        $message_data = json_decode($message, true);

        if(!$message_data)
        {

            return ;
        }

        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            //心跳
            case 'heart':
                Gateway::sendToClient($client_id, json_encode(["type"=>"pong"]));
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端
            case 'login':
                //$uid = $message_data['uid'];

                //绑定bid
                Gateway::bindUid($client_id, $message_data['uid']);
                $new_message = array('type'=>"notice",'client_id'=>$client_id,'uid'=>$message_data['uid'],'time'=>date('Y-m-d H:i:s'),'msg'=>"欢迎登录香草传奇！");
                // 向当前client_id发送数据
                Gateway::sendToClient($client_id, json_encode($new_message));
                return;
            case 'bind':
                //绑定bid
                Gateway::bindUid($client_id,  $message_data['fromid']);
                $_SESSION['fid'] = $message_data['fromid'];

                $list = self::$db->query("SELECT a.uid,a.friend_uid,a.content,a.is_read,a.addtime FROM chat_detail a,zy_user b WHERE  a.friend_uid= b.uid and b.fid='$message_data[fromid]' and a.is_read=0 ORDER BY a.id DESC ");
                if(count($list)>0)
                {
                    foreach($list as &$value)
                    {
                        $row = self::$db->select('fid')->from('zy_user')->where("uid= '$value[uid]'")->row();
                        $value['fromid'] = $row['fid'];
                        $value['toid'] = $message_data['fromid'];
                        $value['type'] = 'save';
                        unset($value['uid'],$value['friend_uid']);
                        Gateway::sendToUid($message_data['fromid'], json_encode($value));
                    }
                }

                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'say':

                 //私聊
                   $text = $message_data['content'];
                   $time = date('Y-m-d H:i:s');

                    $new_message = array(
                        'type'=>'save',
                        'content'=>$text,
                        'fromid'=>$message_data['fromid'],
//                        'friend_uid'=>$friend_uid['friend_uid'],
                        'addtime'=> $time,
                    );
                    // 如果不在线就先存起来
//                    if(Gateway::isUidOnline($friend_uid['friend_uid']))
//                    {
//                        $new_message['is_read'] = 0;
//                        // 向当前用户发送数据
//                        Gateway::sendToUid($friend_uid['friend_uid'], json_encode($new_message));
//                    }
//                    else
//                    {
                        $new_message['is_read'] = 0;
//                    }


                     Gateway::sendToUid($message_data['fromid'], json_encode($new_message));
                     return ;

        }
   }





   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {

//       $new_message = array('type'=>'logout','client_id'=>$client_id,'time'=>date('Y-m-d H:i:s'),'msg'=>"$client_id 下线了");
//        // 向所有人发送
//        Gateway::sendToAll($client_id,7);
       date_default_timezone_set('PRC');
        if($_SESSION['fid'])
        {
            $time = date('Y-m-d H:i:s');
            $today_start = strtotime(date('Y-m-d',time()));
            $row = self::$db->row("SELECT uid FROM zy_user  WHERE  fid= '$_SESSION[fid]' ");
            $minutes = self::$db->row("select add_time from zy_user_login WHERE uid='$row[uid]' and UNIX_TIMESTAMP(add_time)>= '$today_start' ORDER BY id DESC ");
            $min = floor((strtotime($time) - strtotime($minutes['add_time']))/60);
            if($min)
            {
                $insert_id = self::$db->insert('zy_online_minutes')->cols(array(
                    'uid'=>$row['uid'],
                    'minutes'=> $min,
                    'addtime'=> $time))->query();
            }

        }

   }
}
