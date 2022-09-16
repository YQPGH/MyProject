<?php defined('BASEPATH') OR exit('No direct script access allowed ');
/**社群养龙
 * Created by Y
 * Date: 2020/3/2
 * Time: 12:30
 */
use Workerman\Worker;
use PHPSocketIO\SocketIO;
require_once '././webmsgsender/vendor/autoload.php';

// require 'workerman/phpsocket.io';
//require '././Workerman/vendor/workerman';

require_once '././GatewayWorker/GatewayClient/Gateway.php';
use GatewayClient\Gateway;
include_once  'Base_model.php';

class Dragon_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_dragon_player';
        $this->load->model('api/user_model');

    }



    //活动时间
    function activity_time(){
        $time = config_item('dragon_time');
        $starttime = strtotime($time['start_time']);
        $endtime = strtotime($time['end_time']);
        if(time()>$starttime && time()<$endtime)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *初始化用户
     */
    function init($uid)
    {

        $sql = "select * from $this->table WHERE uid=?";
        $user = $this->db->query($sql,[$uid])->row_array();
        if(!$user)
        {
            $this->insert(
                [
                    'uid'=>$uid,
                    'addtime' => t_time(),

                ]

            );
            $this->table_insert(
                'zy_dragon_task_today',
                [
                    'uid'=>$uid,
                    'add_time' => t_time(),

                ]

            );
        }
    }

    //报名
    function  sign_up()
    {

    }

    /**
     * 发送邀请
     */
    function invite($uid)
    {

    }

    /**
     * 是否有邀请
     */
    function is_friend_invite($uid,$code)
    {

    }

    /**
     * 接受邀请
     */
    function accepting_invitation($uid)
    {

    }

    //队长
    function team($uid){

        $sql = "select * from $this->table WHERE uid=? ";
        $row = $this->db->query($sql,[$uid])->row_array();
        if(!$row) t_error(2,'该用户不存在');
        if($row['team_identity'] == 1) t_error(3,'队伍已存在');
        // 事务开始
        $this->db->trans_start();
        $time = time();
        $insert_id = $this->table_insert(
            'zy_dragon_team',
            [
                'addtime' => t_time($time),
                'endtime' => t_time($time+24*10*60*60)
            ]
        );
        if($insert_id)
        {

            $this->update(
                [
                    'team_id' => $insert_id,
                    'team_identity' => 1,
                    'updatetime' => t_time(),

                ],
                [
                    'uid' => $uid
                ]
            );
        }
        $this->db->trans_complete();
    }

    //个人排名
    function user_detail($uid)
    {

        $user = $this->user_model->detail($uid);
        $sql = "select * from `$this->table` WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        $team = $this->db->query("select growth_value,addtime,endtime from `zy_dragon_team` WHERE id='$row[team_id]'")->row_array();

        $res = $this->db->query("SELECT b.energy_total as total,b.uid,b.rownum from
                                (SELECT t.energy_total,t.uid, @rownum := @rownum + 1 AS rownum
                                FROM (SELECT @rownum := 0) r, `$this->table` AS t where
                                `team_id`='$row[team_id]'
                                ORDER BY t.energy_total DESC
                                ) as b where b.uid = ?;",[$uid])->row_array();

        $result['nickname'] = $user['nickname'];
        $result['head_img'] = $user['head_img'];
        $result['consumption'] = $row['energy_total'];
        $result['energy'] = $row['energy'];
        $result['growth_value'] = $team['growth_value'];
        $result['startime'] = $team['addtime'];
        $result['endtime'] = $team['endtime'];
        $result['rank'] = $res['rownum'];
        return $result;

    }

    //喂养
    function feed($uid)
    {
        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        $team = $this->db->query("select growth_value,endtime from `zy_dragon_team` WHERE id='$row[team_id]'")->row_array();
        if(strtotime($team['endtime'])<time()) t_error(4,'活动已结束');
//        if($team['growth_value']) t_error(5,'能量已满，恭喜养成神龙');
        $this->db->trans_start();
        if($row['energy']>0)
        {
            $this->table_insert(
                'zy_dragon_feed_record',
                [
                    'uid' => $uid,
                    'num' => $row['energy'],
                    'addtime' => t_time()
                ]
            );

            $this->db->set('energy',0)
                ->set('energy_total','energy_total+'.$row['energy'],false)
                ->set('updatetime',t_time())
                ->where('uid',$uid)
                ->update($this->table);

            if($row['team_id'])
            {
                $this->db->set('growth_value','growth_value+'.$row['energy'],false)
                    ->set('updatetime',t_time())
                    ->where('id',$row['team_id'])
                    ->update('zy_dragon_team');
            }

        }
        else
        {
            t_error(3,'能量不足');
        }
        $this->db->trans_complete();
    }

    //任务列表
    function task_list($uid)
    {

        $user_sql = "select energy from $this->table WHERE uid=?";
        $row = $this->db->query($user_sql,[$uid])->row_array();
        $list['energy'] = $row['energy'];
        $sql = "select id,prize,`type`,`limit` from zy_dragon_task_config ";
        $list = $this->db->query($sql,[$uid])->result_array();
        $user = $this->task_player($uid);

        foreach($list as &$value)
        {

             $value['current_value'] = $user['task'.$value['id'].'prized']*$value['prize'];
             $value['total_value'] = $user['task'.$value['id']]*$value['prize'];

            if($value['type'] == 'yanye')
            {

                $sql = "select s.shopid,t.total from zy_shop s,zy_store t WHERE s.type1=? AND s.type2=? AND t.uid=? AND s.shopid=t.shopid";
                $yanye = $this->db->query($sql,[$value['type'],1,$uid])->result_array();
                $value['yanye'][] = $yanye;

            }
            if($value['type'] == 'yan')
            {
                $sql = "select s.shopid,t.total from zy_shop s,zy_store t WHERE s.type1=? AND s.type2=? AND t.uid=? AND s.shopid=t.shopid";
                $yan = $this->db->query($sql,[$value['type'],1,$uid])->result_array();
                $value['yan'][] = $yan;
            }
             unset($value['type'],$value['prize'],$value['limit']);

        }
        return $list;
    }

    //任务领取
    function task_receive($uid, $taskid, $shop='',$num=0)
    {

        $sql = "select * from zy_dragon_task_config WHERE id=?";
        $row = $this->db->query($sql,[$taskid])->row_array();
        $user = $this->task_player($uid);
        if($user['task' . $taskid]>=$row['limit'])  t_error(3,'今日任务已完成');
        $this->db->trans_start();//事务开启
         if($num>0)
         {
             if($user['task' . $taskid]>=$row['limit'] || $num > $row['limit'])  t_error(2,'兑换次数不能再多啦');
             $sql = "select total from zy_store WHERE shopid=?";
             $store = $this->db->query($sql,[$shop])->row_array();
             if($store['total']<$num) t_error(4,'你的库存数量不够了');
             $this->load->model('api/store_model');
             $this->store_model->update_total(-$num,$uid,$shop);
             $this->db->set('task' . $taskid, 'task' . $taskid . '+' .$num, FALSE);
             $energy = $row['prize'] * $num;
         }
        else
        {
            $this->db->set('task' . $taskid . '_prized', 0);
            $energy = $user['task' . $taskid . '_prized'] * $row['prize'];

            if(!$energy) t_error(5,'任务未完成');
        }

        $this->db->set('update_time', t_time());
        $this->db->where('uid', $uid);
        $this->db->update('zy_dragon_task_today');

        $this->db->set('energy', 'energy +' .$energy, FALSE)
            ->set('updatetime', t_time())
            ->where('uid', $uid)
            ->update('zy_dragon_player');

        $this->table_insert(
            'zy_dragon_log_task',
            [
                'uid' => $uid,
                'task_id' => $taskid,
                'addtime' => t_time()
            ]
        );
        $this->db->trans_complete();//事务结束

    }

    //队伍排名
    function team_ranking($uid)
    {

        $sql = "select * from `$this->table` where uid=? ";
        $res =$this->db->query($sql,[$uid])->row_array();

        $list = $this->db->query("SELECT obj.uid,obj.nickname,obj.head_img,obj.addtime,obj.energy_total AS total,@rownum := @rownum + 1 AS rownum FROM
              (SELECT a.uid,a.energy_total,a.addtime,b.nickname,b.head_img FROM `zy_dragon_player` a,`zy_user` b
              WHERE b.uid=a.uid AND a.team_id='$res[team_id]' ORDER BY a.`energy_total` DESC limit 0,10) AS obj,
              (SELECT @rownum := 0) r;")->result_array();


        if(count($list)>0)
        {
            foreach($list as &$v)
            {
                $sql = "select addtime from `zy_dragon_feed_record` where uid=? ORDER BY id DESC ";
                $row =$this->db->query($sql,[$v['uid']])->row_array();
                $v['last_time'] = $row?$row['addtime']:$v['addtime'];
                unset($v['uid'],$v['addtime']);
            }
        }


        return $list;

    }

    // 更新今日任务进度
    function update_today($uid, $taskid)
    {
        $sql = "select * from zy_dragon_task_config WHERE id=?";
        $row = $this->db->query($sql,[$taskid])->row_array();
        $user = $this->task_player($uid);
        if($user['task' . $taskid]<$row['limit'])
        {
               $result = $this->db->set('task' . $taskid, 'task' . $taskid . '+1', FALSE)
                 ->set('task' . $taskid . '_prized', 'task' . $taskid . '_prized+1', FALSE)
                 ->set('update_time', t_time())
                 ->where('uid', $uid)
                 ->update('zy_dragon_task_today');

            return $result;
        }

    }

    function task_player($uid)
    {
        $user_sql = "select * from zy_dragon_task_today WHERE uid=?";
        $user = $this->db->query($user_sql,[$uid])->row_array();
        return $user;
    }

    /**
     * 奖励
     */
    function prize($uid)
    {

    }

    /**
     * 领取奖励
     */
    function get_prize($uid)
    {

    }

    /**
     * 退队
     */
    function leave($uid)
    {

        $row = $this->db->query("SELECT * FROM  `$this->table` WHERE uid=? ;",[$uid])->row_array();
        if(!$row['team_id']) t_error(2,'请加入队伍！');
        $this->update(
            [
            'team_id' =>0
        ],
            [
            'uid'=>$uid
        ]);
        $sql = "select * from `$this->table` WHERE  team_id=?";
        $list = $this->db->query($sql,[$row['team_id']])->result_array();
        $uid_array = [];
        if(count($list)>0)
        {
            foreach($list as $value)
            {
                $uid_array[] = $value['uid'];
            }

            $message = [
                "type"=>"leave",
//                'uid' => $uid_array,
                "list"=>$list
            ];

            $req_data = json_encode($message, true);
            //向当前队伍发送数据
            Gateway::sendToUid($uid_array, $req_data);
        }

    }

    /**
     * 队伍列表
     */
    function team_list($uid)
    {
        $row = $this->db->query("SELECT * FROM  `$this->table` WHERE uid=? ;",[$uid])->row_array();
        $sql = "select * from `$this->table` WHERE team_id=?";
        $list = $this->db->query($sql,[$row['team_id']])->result_array();

        return $list;
    }

     function test(){
         $ch = curl_init ();
         // 指明给谁推送，为空表示向所有在线用户推送
         $to_uid = '';

//         foreach ($list as $val) {
//             $to_uid = $val['uid'];
//             // 推送的url地址，使用自己的服务器地址
//             $push_api_url = "http://localhost:2121/";
//             $post_data = array(
//                 "type" => "publish",
//                 "content" => json_encode($list,true),
//                 "to" => $to_uid,
//             );
//
//             curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
//             curl_setopt ( $ch, CURLOPT_POST, 1 );
//             curl_setopt ( $ch, CURLOPT_HEADER, 0 );
//             curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//             curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
//             curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
//             $return = curl_exec ( $ch );
//         }
//
//         curl_close ( $ch );

//        var_export($return);
     }


    public  function onMessage($uid)
    {

        $row = $this->db->query("SELECT * FROM  `$this->table` WHERE uid=? ;",[$uid])->row_array();
        $sql = "select * from `$this->table` WHERE  team_id=?";
        $list = $this->db->query($sql,[$row['team_id']])->result_array();



    }


    function push_msg($uid){
//        $this->load->model('api/websocket_model');

        // 创建socket.io服务端，监听3120端口
        $io = new SocketIO(3120);
// 当有客户端连接时打印一行文字
        $io->on('connection', function($socket)use($io){
            echo "new connection coming\n";
        });

        Worker::runAll();
        // 指明给谁推送，为空表示向所有在线用户推送
        $to_uid = "";
        // 推送的url地址，使用自己的服务器地址
        $push_api_url = "http:/192.168.1.38:2121/";
        $post_data = array(
            "type" => "publish",
            "content" => "这个是推送的测试数据",
            "to" => $to_uid,
        );

        $res = $this->http_send($push_api_url,$post_data);

        if($res=='ok'){

        }

    }

    function http_send($push_api_url,$post_data){

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
var_dump(curl_error( $ch));exit;
//        return $return;
        var_export($return);
    }



    function Shareimg()
    {
        define('IMAGES',$_SERVER['DOCUMENT_ROOT'] .'/yccq/static/');
        $user = $this->user_model->detail('abcc');
        $data = [];
        $data['nickname_content'] = $user['nickname'];
        $data['announ_content']   = '这是测试';
        $data['prompt_content']   = '长按此图识别二维码';
    //    $data['filename']         = QRCODE.date('YmdHis',time()).rand(1000,9999).'.png';
        $data['background']       = IMAGES.'bg.png';

    //    $data['posters']          = IMAGES.'whitebg.png';
        $data['head_img']           = IMAGES.'avatar.jpg';
    //    $data['qrcode']           = IMAGES.'qrcode.jpg';

    //    $data['head_img'] = $user['head_img'];
    //print_r($data);exit;
        $this->load->model('api/createsharepng_model');
        $this->createsharepng_model->getQrcode($data);

    }


}