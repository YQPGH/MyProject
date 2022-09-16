<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  种树收集能量
 */
include_once 'Base_model.php';

class Energytrees_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_trees_player';
        $this->name = 'trees';
        $this->score = 800;//每次抽奖所消耗能量
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/user_model');
    }

    function activity_time(){

        $time = model('user_model')->query_holiday_time($this->name);
        if(time()>$time['start_time'] && time()<$time['end_time'])
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    function init($uid)
    {
        $user = $this->row(['uid' => $uid]);

        $this->db->trans_start();
        $time = time();
        if(!$user)
        {
            $this->insert([
                'uid' => $uid,
                'addtime' => $time,
                'updatetime' => $time
            ]);
        }

        $ballindex  = $this->db->query("select * from zy_trees_ball where uid=? ",[$uid])->row_array();
        if(!$ballindex)
        {
            for($i=1;$i<5;$i++)
            {
                $this->table_insert('zy_trees_ball',[
                    'uid' => $uid,
                    'index' => $i,
                    'addtime' => $time,
                    'updatetime' => $time,

                ]);
            }
        }

        $task_row = $this->table_row('zy_task_detail', ['uid' => $uid]);
        if(empty($task_row))
        {
            $this->table_insert('zy_task_detail',[
                'uid'=>$uid,
                'add_time'=>t_time()
            ]);
        }
        $this->db->trans_complete();
    }


    //是否有好友邀请
    function friendInvite($uid,$code){
        $row['is_invite'] = 0;
        $row['nickname'] =  '';
        $row['insert_id'] = 0;
        $user = $this->column_sql('uid,nickname',['fid'=>$code],'zy_user',0);
        if($user)
        {
            $code = t_rand_str();
            $data = [
                'uid' => $user['uid'],
                'code' => $code,
                'add_time' => t_time()
            ];
           $inset_id = $this->table_insert('zy_invite_record',$data);
            // 写入好友表
            $this->table_insert('zy_friend_invite', [
                'uid' =>  $user['uid'],
                'code' => $code,
                'add_time' => t_time(),
            ]);
            $row['is_invite'] =1;
            $row['nickname'] = $user['nickname'];
            $row['insert_id'] = $inset_id;
        }

        return $row;
    }

    //接受邀请
    function inviteAccept($uid,$code){

        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $this->init($uid);
        $is_exist = $this->column_sql('*',['id'=>$code],'zy_invite_record',0);

        if(!$is_exist) t_error(3, '暂无邀请');
        if ($uid == $is_exist['uid']) t_error(4, '不能邀请自己');

        //每个人每天最多只能帮忙同一名好友助力1次
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $type = $this->getIdnum();
        $row = $this->table_row('zy_invite',['uid'=>$is_exist['uid'],'type'=>$type,'invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end]);

        if ($row) t_error(5, '今日已接受该好友邀请！');
        $friend_help = $this->table_row('zy_invite',['type'=>$type,'invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end]);
        if($friend_help) t_error(6,'今日好友助力已上限！');
        $this->db->trans_start();
        if($is_exist)
        {
            $invite = $this->table_row('zy_friend_invite', ['code' => $is_exist['code']]);

            if ($invite)
            {
                $row = $this->table_row('zy_friend',['uid' => $uid, 'friend_uid' => $invite['uid']]);
                $count = $this->table_count('zy_friend',['uid' => $uid]);

                if (!$row && $uid != $invite['uid'] && $count < 100)
                {
                    //判断是否已经添加过
                    $is_friend = $this->table_count('zy_friend',['uid' => $uid,'friend_uid'=>$invite['uid']]);

                    if(!$is_friend){
                        $time = t_time();
                        $new_code = t_rand_str();
                        // 插入两条数据
                        $this->table_insert('zy_friend',[
                            'uid' => $uid,
                            'friend_uid' => $invite['uid'],
                            'code' => $new_code,
                            'add_time' => $time,
                        ]);

                        $this->table_insert('zy_friend',[
                            'uid' => $invite['uid'],
                            'friend_uid' => $uid,
                            'code' => $new_code,
                            'add_time' => $time,
                        ]);
                    }
                }
            }
        }

        $data = [
            'uid' => $is_exist['uid'],
            'invited_uid' => $uid,
            'code' => $is_exist['code'],
            'add_time'=> time(),
            'type' => $type
        ];
        $this->table_insert('zy_invite',$data);

        //更新用户任务

        $this->updateTotal($uid,2);
        $this->updateTotal($is_exist['uid'],2);
        $this->db->trans_complete();
    }


    function queryInfo($uid)
    {

        $this->init($uid);
        $row = $this->row(['uid' => $uid]);
        $list = $this->db->query("select `index`,`total`,starttime,endtime from zy_trees_ball where uid=?;",[$uid])->result_array();

       foreach($list as &$value)
       {
           $value['status'] = 1;
           if($value['endtime'])
           {
               $value['status'] = 0;
           }

       }
        $result['list'] = $list;
        $result['total'] = $row['total'];

        return $result;
    }

    function friendInfo($uid,$code)
    {
        $row['total'] = 0;
        $friend_uid  = $this->column_sql('*',['uid' => $uid,'code'=>$code],'zy_friend',0);
        $row = $this->row(['uid' => $friend_uid['friend_uid']]);

        $list = $this->db->query("select `index`,`total`,starttime,endtime from zy_trees_ball where uid=?;",[$row['uid']])->result_array();

        foreach($list as &$value)
        {
            $value['status'] = 1;
            if($value['endtime'])
            {
                $value['status'] = 0;
            }

        }
        $result['list'] = $list;
        $result['total'] = $row['total'];

        return $result;
    }

    /**
     *  任务领奖
     *
     */
    function getTaskprize($uid, $id)
    {

        $activity = $this->activity_time();
        if(!$activity) t_error(7,'活动已结束');
        $row = $this->table_row('zy_trees_ball',['uid' => $uid,'index'=>$id]);

        $today_row = $this->db->query( "select a.*,b.task_num from zy_task_detail a,zy_task b where a.uid=? and b.task_id=? and b.type=?",[$uid,$id,$this->name])->row_array();
        if($today_row['task'.$id]) t_error(3,'已领取');
        if(!$today_row['current_value'.$id]) t_error(5,'请先完成任务');
        if($today_row['current_value'.$id]<$today_row['task_num']) t_error(6,'请先完成任务');
        if($row['total']>0 && $row['endtime']<time()) t_error(4,'请先收取能量');

        $this->db->trans_start();
        $this->table_update('zy_task_detail',['task'.$id=>1,'current_value'.$id=>0],['uid'=>$uid]);
        $item =  config_item('trees_config');
        $hour = $item['trees_time'];
        $time = time()+$hour;
        $this->table_update('zy_trees_ball',['starttime'=>time(),'endtime' => $time],['id'=>$row['id']]);
        //  日志
        $data = array(
            'uid' => $uid,
            'task_id' => $id,
            'type' => $this->name,
            'addtime' => time()
        );
        $this->db->insert('log_task', $data);
        $this->db->trans_complete();

        $result['starttime'] = time();
        $result['endtime'] = $time;
        $result['index'] = $id;
        $result['total'] = 0;
        return $result;
    }

    /**
     *  任务列表
     *
     */
    function taskList($uid)
    {
        $today_row = $this->table_row('zy_task_detail', ['uid' => $uid]);
        $list = $this->db->query( "select task_id id,task_num from zy_task where  type=?",[$this->name])->result_array();
        foreach($list as &$value)
        {
//            $row = $this->table_row('zy_trees_ball',['uid' => $uid,'index'=>$value['id']]);
            $value['is_received'] = 0;
            $value['is_finish'] = 0;
            for($i =1; $i<=4; $i++)
            {
                if($i == $value['id'])
                {
                    if($value['id'] ==1)
                    {
                        if(empty($today_row['task'.$i]))
                        {
                            $this->table_update('zy_task_detail',['current_value'.$i=>1,'update_time' => t_time()],['uid'=>$today_row['uid']]);
                            $today_row['current_value'.$i] = 1;
                            $value['is_finish'] = 1;
                        }

                    }
                    else
                    {

                        if($today_row['current_value'.$i] >= $value['task_num'] ) $value['is_finish'] = 1;
                    }
                    if($today_row['task'.$i])
                    {
                        $value['is_received'] = 1;
                        $value['is_finish'] = 1;
                    }
                }
            }
            unset($value['task_num']);
        }

        return $list;
    }

    function prizeList()
    {
        $get_sql = "SELECT money,shandian,shop1 shopid,shop1_total shop_num,json_data FROM zy_prize WHERE type1=? ";
        $list = $this->db->query($get_sql, array($this->name))->result_array();
        foreach($list as &$value)
        {
            $value['index'] = json_decode($value['json_data'])->index;
            unset($value['json_data']);
            $result[] = $value;
        }
        $idArr = array_column($result, 'index');
        array_multisort($idArr,SORT_ASC,$result);
        foreach($result as &$val)
        {
            unset($val['index']);
        }
        $data['prize'] = $result;
        return $data;
    }

    function getPrize($uid)
    {

        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $row = $this->db->query("select total from $this->table WHERE uid=?;",[$uid])->row_array();
        if($row['total']< $this->score) t_error(3,'能量不足');

        // 事务开始
        $this->db->trans_start();
        $title = $this->getIdnum();
        $result = [];
        $is_prize_back = model('prize_model')->is_prize_black($uid);
        $times = model('user_model')->query_holiday_time($this->name);
        $sql = "select COUNT(*) as num from zy_prize_record WHERE  uid=? AND type=? and title=? and  UNIX_TIMESTAMP(add_time)>=?";
        $count =  $this->db->query($sql,[$uid,1,$title,$times['start_time']])->row_array();
        $prize = $this->prize_return();

        $ip = get_real_ip();

        $ip_limit = $this->db->query("select COUNT(ip) num from zy_prize_record
WHERE uid=? and title=? and  `type`=1 AND ip=? and UNIX_TIMESTAMP(add_time)>=? GROUP  BY ip",[$uid,$title,$ip,$times['start_time']])->row_array();

        if($ip_limit['num']>=1 ||  $count['num'] || !$is_prize_back )
        {
            $prize = $this->prize_shop();
        }

        // 奖品入库
        if ($prize['money'])
        {
            $this->user_model->money($uid, $prize['money']);
        }
        // 奖品入库
        if ($prize['shandian'])
        {
            $this->user_model->shandian($uid, $prize['shandian']);
        }
        $insert_id = 0;
        if($prize['shop'])
        {
            if($prize['type2'])
            {
                $shop = $this->shop_model->detail($prize['shop']);
                $openid = $this->user_model->queryOpenidByUid($uid);
                //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
                if($shop['type4'] == 'quan')
                {
                    //根据uid获取openid
                    $data = array(
                        'shopid' => $prize['shop'],
                        'ticket_id' => t_rand_str($uid),
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'type' => 1,
                        'addtime' => time()
                    );
                    $insert_id = $this->table_insert('zy_ticket_record', $data);
                }

                //更新奖品数量
                $this->db->set('total','total-1',false)
                    ->set('update_time',t_time())
                    ->where('id',$prize['id'])
                    ->update('zy_prize');
            }
            else
            {
                //抽中商品
                $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
            }
        }
        if(!$prize['type2'])
        {
            // 写入奖品日志表
            model('prize_model')->log_save($uid, $prize['id']);
        }


        $data = [
            'uid'=>$uid,
            'pid' => $prize['id'],
            'title' => $title,
            'ticket_id'=>$insert_id,
            'type'=> $prize['type2'],
            'add_time'=>t_time(),
            'ip' => $ip,
            'user_agent' => get_agent()
        ];
        $this->table_insert('zy_prize_record',$data);


        unset($prize['id'],$prize['type2']);
        $result['prize'] = $prize;

        //更新用户能量
        $this->db->set('total', 'total - '.$this->score, FALSE)
            ->set('updatetime',time())
            ->where('uid', $uid)
            ->update($this->table);

        $this->db->trans_complete();

        return $result;
    }

    function getIdnum()
    {
        $sql = "select id from zy_activity_config WHERE `name`=?";
        $id = $this->db->query($sql,[$this->name])->row_array();
        return $id['id'];
    }

    function prize_return(){

        $prize_array = $this->db->query("select id,get_rate,total,type2 from zy_prize WHERE type1=?",[$this->name])->result_array();
        foreach($prize_array  as $key=>$value)
        {
            $proArr[$value['id']] = $value['get_rate'];
            if($value['type2'])
            {
                if($value['get_rate']==0 || $value['total']==0)
                {
                    unset($proArr[$value['id']]);
                }
            }
        }

        $prize_id = get_rand_num($proArr);
        $result = $this->column_sql(" id,shandian,money,shop1 shop,shop1_total shop_num,type2,json_data",array('id'=>$prize_id),'zy_prize',0);
        $index = json_decode($result['json_data']);
        $result['index'] = $index->index;
        unset($result['json_data']);
        return $result;
    }

    //如果奖品数量已抽完或者奖品拥有数量上限，则随机返回其他奖品
    function prize_shop(){

        $list = $this->db->query("select json_data
from zy_prize
WHERE type1=? AND type2=? ",[$this->name,0])->result_array();
        $number = [];
        foreach($list as &$value)
        {
            $index = json_decode($value['json_data']);
            $value['index'] = $index->index;
            array_push($number,$value['index']);
        }

        $type = $number[array_rand($number,1)];
        $result = $this->db->query("select id,shandian,money,shop1 shop,shop1_total shop_num,type2
from zy_prize
WHERE type1='$this->name' AND type2=0 AND json_data LIKE '%$type%'")->row_array();
        $result['index'] = $type;
        return $result;
    }


    /**
     * 抽奖记录
     */
    function prize_record($uid,$type = '')
    {

        $title = $this->getIdnum();
        $times = model('user_model')->query_holiday_time($this->name);

        if($type)
        {
            $list = $this->lists_sql("SELECT c.type2,p.ticket_id,p.id,p.pid,p.add_time log_time,c.shop1 shopid,c.shandian,c.money,c.shop1_total shop_num
                                  FROM zy_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1=? AND p.uid=? and p.title=? and UNIX_TIMESTAMP(p.add_time)>?
                                  ORDER BY p.id DESC LIMIT 30", [$this->name,$uid, $title,$times['start_time']]);

            $data = [];
            foreach($list as $k => &$v)
            {
                if($v['type2'])
                {
                    if($v['shopid'])
                    {
                        $shop = $this->shop_model->detail($v['shopid']);

                        if($shop['type4']=='quan')
                        {
                            $sql = "select stat from zy_ticket_record WHERE uid=? AND  id=? ";
                            $row  = $this->db->query($sql,[$uid,$v['ticket_id']])->row_array();

                            $v['status'] = $row['stat'];
                            $obj = json_decode($shop['json_data']);
                            $v['vali'] = date('Y-m-d H:i:s',strtotime($v['log_time'])+($obj->vali)*24*3600);
                            $v['is_overtime'] = 0;
                            if((strtotime($v['log_time'])+($obj->vali)*24*3600) < time())
                            {
                                $v['is_overtime'] = 1;
                            }
                            $v['type'] =$shop['type4'];

                        }
                        else
                        {
                            $sql = "select * from zy_message WHERE uid=? AND  pid=? ";
                            $row  = $this->db->query($sql,[$uid,$v['id']])->row_array();
                            $v['status'] = $row? 1: 0;
                            $v['url'] = site_url('api/address/getUsermessage?id='.$v['id'].'&type1='.$this->name);
                            $obj = json_decode($shop['json_data']);
                            $v['vali'] = date('Y-m-d H:i:s',strtotime($v['log_time'])+($obj->vali)*24*3600);
                            $v['is_overtime'] = 0;
                            if((strtotime($v['log_time'])+($obj->vali)*24*3600) < time())
                            {
                                $v['is_overtime'] = 1;
                            }
                            $v['type'] = 'address';

                        }

                    }

                }
                else
                {


                    $v['type'] = 'daoju';
                }
                unset($v['pid'],$v['type2'],$v['id'],$v['ticket_id']);

            }


            return $list ;
        }
        else
        {
            $list = $this->lists_sql("SELECT p.id,p.pid,p.add_time log_time,c.shop1 shopid
                                  FROM zy_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1=? AND p.uid=? AND p.type=? AND p.ticket_id=? and p.title=?  and UNIX_TIMESTAMP(p.add_time)>?
                                  ORDER BY p.id DESC LIMIT 30", [$this->name,$uid,1,0,$title,$times['start_time']]);

            foreach($list as $k => &$v)
            {

                if($v['shopid'])
                {
                    $shop = $this->shop_model->detail($v['shopid']);
                    $sql = "select * from zy_message WHERE uid=? AND  pid=? and type=?";
                    $row  = $this->db->query($sql,[$uid,intval($v['id']),$this->name])->row_array();
           ;
                    $v['status'] = $row? 1: 0;
                    $v['url'] = site_url('api/address/getUsermessage?id='.$v['id'].'&type1='.$this->name);

                    $obj = json_decode($shop['json_data']);
                    $v['vali'] = date('Y-m-d H:i:s',strtotime($v['log_time'])+($obj->vali)*24*3600);
                    if((strtotime($v['log_time'])+($obj->vali)*24*3600) < time())
                    {
                        $v['is_overtime'] = 1;
                    }
                    else
                    {
                        $v['is_overtime'] = 0;
                    }
                }
                unset($v['pid']);
            }
            return $list;
        }


    }

    function myEnergylist($uid)
    {
        $times = model('user_model')->query_holiday_time($this->name);
        $list = $this->db->query("select uid,friend_uid,total,addtime from zy_trees_gatherrecord WHERE uid=? and addtime>=? ORDER BY id DESC",[$uid,$times['start_time']])->result_array();

        foreach($list as &$value)
        {
            $value['nickname'] = '';
            $value['type'] = 0;
            if($value['friend_uid'])
            {
                $value['type'] = 1;
                $row =  $this->db->query("select nickname from zy_user where uid=?",[$value['friend_uid']])->row_array();
                $value['nickname'] = $row['nickname'];
            }
            $value['addtime'] = t_time($value['addtime']);
            unset($value['uid'],$value['friend_uid']);
        }
        return $list;
    }

    function myLostlist($uid)
    {
        $times = model('user_model')->query_holiday_time($this->name);
        $list = $this->db->query("select uid,friend_uid,total,addtime from zy_trees_gatherrecord WHERE friend_uid=? and addtime>=? ORDER BY id DESC ",[$uid,$times['start_time']])->result_array();

        foreach($list as &$value)
        {
            $value['nickname'] = '';
            if($value['uid'])
            {

                $row =  $this->db->query("select nickname from zy_user where uid=?",[$value['uid']])->row_array();
                $value['nickname'] = $row['nickname'];
            }
            $value['addtime'] = t_time($value['addtime']);
            unset($value['uid'],$value['friend_uid']);
        }
        return $list;
    }

    function ranking($uid)
    {
        $list = $this->db->query("select a.friend_uid from zy_friend a,zy_trees_player b WHERE a.uid=? and a.status=? AND a.friend_uid=b.uid",[$uid,0])->result_array();
        $user =  $this->db->query(" select uid friend_uid FROM zy_user WHERE uid=?",[$uid])->result_array();
        $list  = array_merge($list,$user);
        $uid_array = array_column($list, 'friend_uid');
        $item = "'".str_replace( ",","','", implode(',',$uid_array)). "'";
        $data = $this->db->query("SELECT obj.uid,obj.nickname,obj.rank_total AS total,@rownum := @rownum + 1 AS rank FROM
(SELECT b.uid,a.rank_total,b.nickname FROM `zy_trees_player` a,`zy_user` b WHERE a.rank_total>0 AND b.uid=a.uid  AND a.uid IN ($item)
ORDER BY a.rank_total DESC) AS obj,(SELECT @rownum := 0) r;")->result_array();

        foreach($data as &$value)
        {
            if($value['uid'] == $uid)
            {
                $value['code'] = '';
            }
            else
            {
                $code = $this->db->query("select code from zy_friend WHERE uid=? and friend_uid=? and status=?",[$uid,$value['uid'],0])->row_array();

                $value['code'] = $code['code'];
            }
            unset($value['uid']);
        }
        $result['list'] = $data;
        return $result;

    }

    /**
     * 收取能量球
     */
    function myReceive($uid,$index)
    {
        $time = $this->activity_time();
        if($time)
        {
            $result['total'] = 0;
            $row = $this->table_row('zy_trees_ball',['uid'=>$uid,'index'=>$index]);
            $this->db->trans_start();
            if($row['endtime']>time())
            {
                $remain = $row['endtime']-time();

                $msg = floor(($remain/3600)).'小时'.floor($remain/60).'分钟后可收取';
                t_error(2,$msg);
            }
            $this->db->set('total','total+'.$row['total'],false)
                ->set('rank_total','rank_total+'.$row['total'],false)
                ->set('updatetime',time())
                ->where('uid',$uid)
                ->update($this->table);
            $this->table_update('zy_trees_ball',['total'=>0,'starttime'=>0,'endtime' => 0],['uid'=>$uid,'id' => $row['id']]);

            $this->table_insert('zy_trees_gatherrecord',[
                'uid'=>$uid,
                'index'=>$index,
                'total'=>$row['total'],
                'addtime' => time(),
                'updatetime' => time()
            ]);
            $result['total'] = $row['total'];
            $this->db->trans_complete();
            return $result;
        }
        else
        {
            t_error(3,'活动已结束');
        }

    }

    function friendReceive($uid,$index,$code)
    {

        $time = $this->activity_time();
        $item = config_item('trees_config');
        if(!$time) t_error(3,'活动已结束');
        $friend_uid = $this->table_row('zy_friend',['uid'=>$uid,'code'=>$code]);
        if(!$friend_uid) t_error(4,'网络开小差了');
        $myself = $this->row(['uid'=>$uid]);
        if($myself['times']>=3) t_error(7,'今日拾取好友能量已上限');
        $row = $this->table_row('zy_trees_ball',['uid'=>$friend_uid['friend_uid'],'index'=>$index]);//查找好友能量球记录
        $is_receive = $this->table_row('zy_trees_gatherrecord',['uid'=>$uid,'friend_uid'=>$row['uid'],'index'=>$index]);
        if(date('Ymd', $is_receive['addtime']) == date('Ymd')) t_error(5,'今日已领取过了');
        if($row['total']<=$item['trees_min']) t_error(6,'能量不够啦');
        if($row['endtime']>time())
        {
            $remain = $row['endtime']-time();
            $msg = floor(($remain/3600)).'小时'.floor($remain/60).'分钟后可收取';
            t_error(8,$msg);
        }
        $this->db->trans_start();
        $rand = rand(1,20);

        $num = floor(($rand/100)*$row['total']);
        $num = $row['total']-$num<$item['trees_min']?$row['total']-$item['trees_min']:$num;
        $this->db->set('times','times+1',false)
            ->set('total','total+'.$num ,false)
            ->set('rank_total','rank_total+'.$num,false)
            ->set('updatetime',time())
            ->where('uid',$uid)
            ->update($this->table);

        $this->db->set('total','total-'.$num,false)
            ->set('updatetime',time())
            ->where('uid',$row['uid'])
            ->where('id',$row['id'])
            ->update('zy_trees_ball');

        $this->table_insert('zy_trees_gatherrecord',[
            'uid'=>$uid,
            'friend_uid'=>$row['uid'],
            'index'=>$index,
            'total'=>$num,
            'addtime' => time(),
            'updatetime' => time()
        ]);
        $result['total'] = $num;
        $this->db->trans_complete();
        return $result;


    }

    /**
     * 更新任务
     */
    function updateTotal($uid,$id)
    {
        $time = $this->activity_time();

        if($time)
        {
            $row = $this->db->query("select a.*,b.task_num from zy_task_detail a,zy_task b WHERE  a.uid=? and b.task_id=? and b.type=?",[$uid,$id,$this->name])->row_array();

            if($row['task'.$id] || $row['current_value'.$id] >= $row['task_num'])
            {
                $this->db->set('task'.$id.'_total','task'.$id.'_total+1',false)
                    ->set('update_time',t_time())
                    ->where('uid' , $uid)
                    ->update('zy_task_detail');
            }
            else
            {
                $this->db->set('current_value'.$id,'current_value'.$id.'+1',false)
                    ->set('task'.$id.'_total','task'.$id.'_total+1',false)
                    ->set('update_time',t_time())
                    ->where('uid' , $uid)
                    ->update('zy_task_detail');
            }
        }

    }

    function updateEnergy($uid,$type2)
    {

        $time = $this->activity_time();
        $result['number'] = [];
        if($time)
        {
            $item = config_item('trees_config');
            $type = config_item('trees');
            $total = $type[$type2['type2']]['number'];

            $row = $this->db->query("select * from zy_trees_ball where uid=? AND total<? AND starttime>? AND endtime>? ORDER BY endtime ASC ",[$uid,$item['trees_max'],0,time()])->row_array();

            $this->db->trans_start();
            $data = [];
            if($row)
            {

                if($row['total']+$total>=$item['trees_max'])
                {
                    $number = $item['trees_max']-$row['total'];
                    $array = [
                        'index'=>$row['index'],
                        'total' => $number
                    ];
                    array_push($data,$array);

                    $this->db->set('total','total+'. $number,false )
                        ->set('updatetime',time())
                        ->where('uid',$uid)
                        ->where('id',$row['id'])
                        ->update('zy_trees_ball');

                    $num =  $total- ($item['trees_max']-$row['total']);
                    $row = $this->db->query("select * from zy_trees_ball where uid=? AND total<? AND starttime>? ORDER BY endtime ASC ",[$uid,$item['trees_max'],0])->row_array();
                    $this->db->set('total','total+'.$num,false )
                        ->set('updatetime',time())
                        ->where('uid',$uid)
                        ->where('id',$row['id'])
                        ->update('zy_trees_ball');
                    $array = [
                        'index'=>$row['index'],
                        'total' => $num
                    ];
                    array_push($data,$array);
                    $result['number'] = $data;


                }
                else
                {

                    $this->db->set('total','total+'. $total,false )
                        ->set('updatetime',time())
                        ->where('uid',$uid)
                        ->where('id',$row['id'])
                        ->update('zy_trees_ball');
                    $data = [
                        'index'=>$row['index'],
                        'total' => $total
                    ];

                    $result['number'][] = $data;
                }

                $this->table_insert('zy_trees_plantrecored',[
                    'uid' => $uid,
                    'total' => $total,
                    'addtime' => time()
                ]);

            }
            else
            {
                $result['number'] = [];
            }
            $this->db->trans_complete();


        }

        return $result;
    }

    function querylist($uid)
    {
        $time = $this->activity_time();
        $list = [];
        if($time)
        {
            $list = $this->db->query("select total,starttime,endtime from zy_trees_ball WHERE uid=?",[$uid])->result_array();
        }

        return $list;
    }
}
