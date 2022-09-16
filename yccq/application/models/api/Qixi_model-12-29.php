<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User: Administrator
 * Date: 2020/6/23
 * Time: 10:46
 */

include_once 'Base_model.php';
class Qixi_model extends Base_model
{
    function  __construct()
    {
        parent::__construct();
        $this->table = 'zy_qixi';
        $this->load->model('api/user_model');
    }

    //活动时间
    function activity_time(){



        $sql = "select UNIX_TIMESTAMP(start_time) start_time,UNIX_TIMESTAMP(end_time) end_time from zy_activity_config WHERE `name`=?";
        $time = $this->db->query($sql,['qixi'])->row_array();
        if(time()>$time['start_time'] && time()<$time['end_time'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //初始化用户信息
    function initdata($uid,$status){

        $time = $this->activity_time();
        $openid = model('user_model')->queryOpenidByUid($uid);
        if(!$openid) t_error(9,'该用户不存在');
        if($time)
        {
            if($uid=='undefined') t_error(2,'操作有误');
            $sql = "select * from $this->table WHERE uid=?";
            $res = $this->db->query($sql,[$uid])->row_array();

            if(empty($res))
            {
                $this->insert([
                    'uid' => $uid,
                    'is_newer' => $status,
                    'addtime'=> t_time(),
                    'updatetime'=> t_time()
                ]);
            }


            $task_row = $this->table_row('zy_task_detail', ['uid' => $uid]);
            if(empty($task_row))
            {

                $this->table_insert('zy_task_detail',[
                    'uid'=>$uid,
                    'add_time'=>t_time()
                ]);
            }

        }

    }


    //邀请
    function mentor_invite($uid){
        $code = t_rand_str();
        $data = [
            'uid' => $uid,
            'code' => $code,
            'add_time' => t_time()
        ];
        $result['id'] = $this->table_insert('zy_qixi_invite_record',$data);

        $url = base_url().'api/Qixi/invite?incode='.$result['id'];

        return ['url'=>$url];
    }

    //是否有好友邀请
    function is_friend_invite($uid,$code){

        $is_exist = $this->column_sql('code',['id'=>$code],'zy_qixi_invite_record',0);
        $row['is_invite'] = $is_exist?1:0;
        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a,zy_qixi_invite_record b WHERE code='$is_exist[code]' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }

    //接受邀请
    function invite_accept($uid,$code){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $is_exist = $this->column_sql('*',['id'=>$code],'zy_qixi_invite_record',0);
        if(!$is_exist) t_error(1, '暂无邀请');
        if ($uid == $is_exist['uid']) t_error(3, '不能邀请自己');

        //每个人每天最多只能帮忙同一名好友助力1次
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $row = $this->table_row('zy_qixi_invite',['uid'=>$is_exist['uid'],'invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end]);

        if ($row) t_error(2, '今日已接受该好友邀请！');

        $this->db->trans_start();
        $data = [
            'uid' => $is_exist['uid'],
            'invited_uid' => $uid,
            'code' => $is_exist['code'],
            'add_time'=> time()
        ];
        $this->table_insert('zy_qixi_invite',$data);
        $user = $this->row(['uid'=>$uid]);

        //更新用户任务
        $this->update_total($uid,3,$user['is_newer']);
        $this->update_total($is_exist['uid'],3,$user['is_newer']);
        if($user['is_newer'])
        {
            $this->update(['is_newer'=>0],['uid'=>$uid]);
        }

        $this->db->trans_complete();
    }

    function get_user($uid)
    {
        $this->initdata($uid,0);
        $sql = "select a.nickname,a.head_img,b.role,b.position step,b.status,b.num from zy_user a,zy_qixi b WHERE b.uid=? AND a.uid=b.uid";
        $res = $this->db->query($sql,[$uid])->row_array();

        return $res;
    }

    /**
     * 投骰子移动步数
     */
    function move($uid)
    {
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $sql = "select a.role,a.status,a.num,a.position,a.prop_num,a.draw_total,b.number step from $this->table a,zy_qixi_config b where a.uid=? AND b.type=?";
        $res = $this->db->query($sql,[$uid,'steps'])->row_array();
        if(!$res['role']) t_error(4,'请先选择角色');
        if($res['num']<1) t_error(2,'你还未获得骰子');
        if($res['status']) t_error(3,'已相会');
        //10n-5,40n-20
        $array = $this->db->query("select number id,rate from zy_qixi_config WHERE type=''")->result_array();

        foreach($array as $value)
        {
            $proArr[$value['id']] = $value['rate'];
        }
        $this->db->trans_start();
        $index = $this->get_rand_num($proArr);

        $n = 10*($res['prop_num']+1)-5;
        $add_num = $res['position']+$index;
        $prop_num = 0;
        $draw_num = 0;
        $times = 40*($res['draw_total']+1)-20;
        if($res['step'] > $add_num) //行走步数在指定范围内
        {
            $prop_num = $add_num >= $n ? 1 : 0;
            $draw_num = $add_num >= $times ? 1 : 0;

        }
        else
        {
            $index = $res['step'] - $res['position'];
            $draw_num = 2;
        }

        $status = ($res['step'] <= $add_num)?1:0;

        $this->db->set('num', 'num - 1', FALSE)
                ->set('position', 'position + ' .$index, FALSE)
                ->set('status', $status, false)
                ->set('prop_num', 'prop_num + '.$prop_num, FALSE)
                ->set('draw_times', 'draw_times + '.$draw_num, FALSE)
                ->set('draw_total', 'draw_total + '.$draw_num, FALSE)
                ->set('updatetime',t_time())
                ->where('uid', $uid)
                ->update($this->table);

        $this->table_insert('zy_qixi_dice_record',[
            'uid' => $uid,
            'dice_number' => $index,
            'addtime' => t_time()
        ]);

        if($prop_num)
        {

            $type = ($res['prop_num']%2 == 0)?0:1;
            $sql = "select money,shandian from zy_prize where type2=? and type3=?";
            $prize =  $this->db->query($sql,[$type,'qixi'])->row_array();

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
        }
        $this->db->trans_complete();
        $result['index'] = $index;
        $result['shandian'] = $prize['shandian']?$prize['shandian']:0;
        $result['money'] = $prize['money']?$prize['money']:0;
        $result['times'] = $draw_num?$draw_num:0;
        return $result;
    }



    // 开始签到,返回签到次数
    function sign($uid,$usertb = '',$signtb = '',$type = '')
    {
        $this->db->trans_start();
        $signtb = $signtb?$signtb:'zy_qixi_sign';
        $usertb = $usertb?$usertb:$this->table;
        $type = $type?$type:'qixi';
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        // 判断今天是否已经签到
        $last_row = $this->table_row($signtb,['uid' => $uid]);
        if ($last_row)
        {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) t_error(1, '今天你已经签到过了，请明天再来。');
        }

        // 写入签到表
        $this->table_insert($signtb,[
            'uid' => $uid,
            'add_time' => t_time(),
        ]);

        // 判断昨天是否签到，是更新用户连续签到数+1
        if ($last_row && $last_sign_day == $this->time->yesterday())
        {
            $this->update_sign($uid,'-1',$usertb);

            $user = $this->table_row($usertb,['uid' => $uid]);
            if ($user['sign_total'] == 8)
            { // 如果到7天奖励更多
                $this->update_sign($uid, 1,$usertb);
                $result['sign_total'] = 1;
            }
            else
            { // 单日签到
                $result['sign_total'] = $user['sign_total'];
            }

        }
        else
        { // 不是连续签到，重新1开始
            $this->update_sign($uid, 1,$usertb);
            $result['sign_total'] = 1;
        }

        // 奖励
        $prize = $this->db->query("select shopid,shop_num,json_data  from zy_sign_prize WHERE title=? AND type1=?;",[$type,$result['sign_total']])->row_array();
        $num = json_decode($prize['json_data']);
        $prize['num'] = $num->num;
        unset($prize['json_data']);
        if($type == 'qixi')
        {
            $this->update_value($uid,$prize['num'],$usertb);
        }

        model('store_model')->update_total($prize['shop_num'], $uid, $prize['shopid']);

        $result['shop'] = $prize['shopid'];
        $result['shop_num']  = $prize['shop_num'];
        $result['number'] = $prize['num'];
        $this->db->trans_complete();
        return $result;

    }

    // 签到列表, 返回最近连续签到的列表
    function list_my($uid,$usertb = '',$type = '')
    {

        $usertb = $usertb?$usertb:$this->table;
        $type = $type?$type:'qixi';
        $this->update_sign_status($uid,$usertb);
        $list = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];
        $user =  $this->table_row($usertb,['uid' => $uid]);
        $sign_total = $user['sign_total'];
        foreach ($list as $key => &$value)
        {
            if ($key <= $sign_total) $value = 1;
        }
        $result = [
            'sign_today' => 0,
            'sign_total' => $user['sign_total'],
            'sign_list' => $list,
        ];
        $last_row = $this->table_row($usertb,['uid' => $uid]);

        if ($last_row)
        {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) $result['sign_today'] = 1;
        }
        //获取每日签到的奖励
        $prize = $this->db->query("select shopid,shop_num,json_data num  from zy_sign_prize WHERE title=?;",[$type])->result_array();
        foreach($prize as $v)
        {
            $num = json_decode($v['num']);
            $v['num'] = $num->num;
            $result['prize'][] = $v;
        }

        return $result;
    }

    // 更新连续签到,
    function update_sign_status($uid,$table)
    {

        $user = $this->row(['uid' => $uid]);
        if ($user['sign_total'] > 0)
        {

            $last_row = $this->table_row($table,['uid' => $uid]);
            // 如果最后签到日不等于昨天或者今天，清零
            $last_sign_day = $this->time->day($last_row['add_time']);
            if (($last_sign_day != $this->time->yesterday() && $last_sign_day != $this->time->today())||($user['sign_total'] == 7 && $last_sign_day == $this->time->yesterday()))
            {
                $this->update_sign($uid, 0,$table);
            }

        }
    }

    function update_value($uid,$total,$table)
    {

        $this->db->set('num','num+'.$total,false)
            ->set('updatetime',t_time())
            ->where('uid',$uid)
            ->update($table);

    }

    function update_sign($uid, $number = -1,$table)
    {

        $str = $number == -1 ? 'sign_total+1' : $number;

        $this->db->set('sign_total', $str, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($table);
        return $this->db->affected_rows();
    }

    /**
     *  任务领取
     *
     */
    function task_receive($uid, $id)
    {
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $task = $this->db->query("select task_num from zy_task  WHERE type=? and task_id=?;",['qixi',$id])->row_array();
        $sql = "select * from zy_task_detail where uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();

        // 判断是否已领奖
        if ($row['task' . $id ] >= $task['task_num']) t_error(4, '今日任务已完成');
        // 判断是否已领奖
        if (!$row['task'.$id.'_total']) t_error(3, '任务未完成');

        $this->db->trans_start();

        $num = $id == 1?$row['current_value'.$id]:0;
        $this->db->set('task'.$id, 'task'.$id.'+'.$row['task'.$id.'_total'], FALSE)
                ->set('current_value'.$id, $num, FALSE)
                ->set('task'.$id.'_total', 0, FALSE)
                ->set('update_time',t_time())
                ->where('uid', $uid)
                ->update('zy_task_detail');

        $this->db->set('num', 'num + '.$row['task'.$id.'_total'], FALSE)
                ->where('uid', $uid)
                ->update($this->table);

        $this->db->trans_complete();
        $return = $this->table_row('zy_task_detail', ['uid' => $uid]);
        $number = $this->row(['uid'=>$uid]);
        $result['id'] = $id;
        $result['is_recevie'] = 1;
        $result['finish_num'] = $return['task' . $id ];
        $result['total'] = $number['num'];

        return $result;
    }

    //任务
    function task_list($uid)
    {
        $today_row = $this->table_row('zy_task_detail', ['uid' => $uid]);
        $list = $this->db->query( "select task_id id,task_num from zy_task where  type=?",['qixi'])->result_array();

        foreach($list as $key=>&$value)
        {

            $value['is_recevie'] = 0;

            for($i =1; $i<=3; $i++)
            {
                if($value['id'] == $i)
                {
                    $total = intval(floor($today_row['current_value'.$i]/10));
                    if($value['id'] == 1)
                    {
                        $remainder = $total>0?intval($today_row['current_value'.$i]%10):$today_row['current_value'.$i];
                        $number  =  $today_row['task'.$i.'_total'];
                        if($total>0)
                        {
                            $number = $today_row['task'.$i]+$total >= $value['task_num']?abs($total-$today_row['task'.$i]):$total;
                        }
                    }
                    else
                    {
                        $remainder = $today_row['current_value'.$i];
                        $number =   $today_row['current_value'.$i]?$today_row['current_value'.$i]:0 ;

                    }

                    if ($today_row['task'.$i] >= $value['task_num'] || empty($number))
                    {
                        $value['is_recevie'] = 1;
                    }

                   $value['finish_num'] = $today_row['task'.$i]+$number;

                    $this->db->set('task'.$i.'_total', $number, FALSE);
                    $this->db->set('current_value'.$i, $remainder, FALSE);
                    $this->db->where('uid', $uid);
                    $this->db->update('zy_task_detail');

                }
            }

            unset($value['task_num'],$value['uid'],$value['add_time'],$value['update_time']);
        }

        return $list;
    }

    function update_total($uid,$id,$newer=0)
    {

        $time = model('qixi_model')->activity_time();

        if($time)
        {
            $list = $this->db->query( "select task_id id,task_num from zy_task where  type=?",['qixi'])->result_array();

            foreach($list as $key=>&$value)
            {

                $row = $this->db->query("select a.task$id,a.current_value$id,b.type1,b.type2 from zy_task_detail a,zy_qixi b WHERE a.uid=? and a.uid=b.uid;",[$uid])->row_array();

                if($value['id'] == $id)
                {
                    $number = $value['id'] == 1?intval($row['current_value'.$value['id']]/10):$row['current_value'.$value['id']];

                    if($row['task'.$id] >= $value['task_num']) return;
                    if($number  >= $value['task_num']) return;
                    $num = 1;
                    if($value['id'] == 3 )
                    {

                        if($newer)//新用户
                        {
                            $type1 = $row['type1'] == $value['id'] ? 0: 1;//新用户
                            $num = $row['type1'] == $value['id'] ? 0: 1;
                            $type2 = 0;

                        }
                        else
                        {
                            $type1 = 0;
                            $type2 = $row['type2']?0:1; //老用户
                            $num = $row['type2']  ? 0: 1;
                        }

                        $this->db->set('type1', 'type1 + '.$type1, FALSE);
                        $this->db->set('type2', 'type2 + '.$type2, FALSE);
                        $this->db->where('uid', $uid);
                        $this->db->update('zy_qixi');

                    }

                    $this->db->set('current_value'.$id, 'current_value'.$id.' + '.$num, FALSE);
                    $this->db->where('uid', $uid);
                    $this->db->update('zy_task_detail');

                }
            }

        }

    }

    /**
     * 奖品列表
     */
    function prize_list($uid)
    {
        $get_sql = "SELECT money,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? ORDER BY type3+0  ASC ";
        $list = $this->db->query($get_sql, array('qixi_prize'))->result_array();

        $row = $this->row(['uid'=>$uid]);
        $result['luck_times'] = $row['draw_times'];
        $result['prize'] = $list;
        return $result;
    }

    /**
     * 抽奖
     */
    function get_prize($uid)
    {
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $row = $this->db->query("select draw_times from $this->table WHERE uid=?;",[$uid])->row_array();
        if(!$row['draw_times']) t_error(2,'您还未获得抽奖机会！');

        // 事务开始
        $this->db->trans_start();

        $sql = "select COUNT(*) as num from zy_qixi_prize_record WHERE  uid=? AND type=? ";
        $count =  $this->db->query($sql,[$uid,1])->row_array();

        $is_prize_back = model('prize_model')->is_prize_black($uid);

        $prize = $this->prize_return();
        $is_limit = $this->limit_player($uid,$prize['shop']);

        if($count['num']>=2 || !$is_prize_back || $is_limit)
        {
            $prize = $this->prize_shop();
        }
//        if($uid=="13743a92d20938357f42088c689219a9"){
//
//            $prize = $this->prize_test();
//
//        }
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

//        $type = 0;
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
                        'addtime' => time()
                    );
                    $insert_id = $this->table_insert('zy_ticket_record', $data);
                }
                //更新奖品数量
                $this->db->set('total','total-1',false)
                    ->where('id',$prize['id'])
                    ->update('zy_prize');
//                $type = 1;
            }
//            else
//            {
//                $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
//            }
        }

        $data = [
            'uid'=>$uid,
            'pid' => $prize['id'],
            'ticket_id'=>$insert_id,
            'shandian' => $prize['shandian'],
            'money' => $prize['money'],
            'type'=> $prize['type2'],
            'add_time'=>t_time()
        ];
        $this->table_insert('zy_qixi_prize_record',$data);
        //更新用户抽奖次数
        $this->db->set('draw_times', 'draw_times -1 ', FALSE)
            ->set('updatetime',t_time())
            ->where('uid', $uid)
            ->update($this->table);

        unset($prize['id'],$prize['type2']);
        $this->db->trans_complete();

        return $prize;
    }

    /**
     * 抽奖记录
     */
    function prize_record($uid)
    {

        $list = $this->lists_sql("SELECT p.*,c.shop1 shop,c.shop1_total shop_num FROM zy_qixi_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1='qixi_prize' AND p.uid=?
                                  ORDER BY p.type DESC,p.add_time DESC LIMIT 30", [$uid]);

        foreach($list as $k => &$v)
        {
            $v['prop'] = $v['type']?1:0;
            if($v['shop'])
            {
                $shop = $this->shop_model->detail($v['shop']);
//                if($shop['type1']=='peifang')
//                {
//                    unset($v['id']);
//                }
//                else
//                {
//                    $row = $this->column_sql('*',['pid'=>$v['id']],'zy_message',0);
                    $sql = "select * from zy_message WHERE uid=? AND  pid=? ";
                    $row  = $this->db->query($sql,[$uid,$v['id']])->row_array();
                    $ticket = $this->column_sql('stat status',['id'=>$v['ticket_id']],'zy_ticket_record',0);
                    if($ticket)
                    {
                        $v['status'] = $ticket['status'];
                    }
                    else
                    {
                        $v['status'] = $row? $v['status'] = 1:$v['status'] = 0;
                    }

                    $v['id'] = $ticket?$v['ticket_id']:$v['id'];
                    $v['is_ticket'] = $ticket?1:0;
                    $obj = json_decode($shop['json_data']);
                    $v['vali'] = date('Y-m-d H:i:s',strtotime($v['add_time'])+($obj->vali)*24*3600);
                    if((strtotime($v['add_time'])+($obj->vali)*24*3600) < time())
                    {
                        $v['is_overtime'] = 1;
                    }
                    else
                    {
                        $v['is_overtime'] = 0;
                    }
//                }
                unset($v['ticket_id']);
            }
            else
            {
                unset($v['id']);
            }
            unset($v['pid'],$v['uid'],$v['type']);
        }
        return $list;
    }

    function prize_return(){


        $prize_array = $this->db->query("select id,get_rate,total,type2 from zy_prize WHERE type1='qixi_prize'")->result_array();

        foreach($prize_array  as $key=>$value)
        {

            $proArr[$value['id']] = $value['get_rate'];

            if($value['type2'] )
            {
                if($value['get_rate']==0 || $value['total']==0)
                {
                    unset($proArr[$value['id']]);
                }
            }
        }

        $prize_id = $this->get_rand_num($proArr);

        $result = $this->column_sql(" type3  index,id,shandian,money,shop1 shop,shop1_total shop_num,type2",array('id'=>$prize_id),'zy_prize',0);
        $array = [70,1000];
        $rand_key = array_rand($array,1);
        $type = $array[$rand_key];
        if($result['shandian'] == $type)
        {
            $result['money'] = 0;

        }
        else
        {
            $result['shandian'] = 0;

        }
        $result['index'] = $result['index']-1;

        return $result;
    }

    //如果奖品数量已抽完或者奖品拥有数量上限，则随机返回其他奖品
    function prize_shop(){
        $array = [70,1000];
        $rand_key = array_rand($array,1);
        $type = $array[$rand_key];

        $result = $this->column_sql('id,type3 index,shandian,money,shop1 shop,shop1_total shop_num,type2',array('type1'=>'qixi_prize','type2'=>0),'zy_prize',0);


        if($result['shandian'] == $type)
        {
            $result['money'] = 0;
        }
        else
        {
            $result['shandian'] = 0;
        }

        $result['index'] = $result['index']-1;
        return $result;
    }

    //奖品测试
    function prize_test(){

        $rand = 428;
        $result = $this->column_sql('id,type3 index,shandian,money,shop1 shop,shop1_total shop_num,type2',array('type1'=>'qixi_prize','id'=>$rand),'zy_prize',0);

        $result['index'] = $result['index']-1;

        return $result;
    }

    private function get_rand_num($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur)
        {

            $randNum = mt_rand(0, $proSum);
            if($randNum <= $proCur)
            {
                $result = $key;
                break;
            }
            else
            {
                $proSum -= $proCur;
            }
        }

        unset ($proArr);
        return $result;
    }



    function limit_player($uid,$shop)
    {

        $row = $this->db->query("select status,phone from zy_limit_prize_player WHERE uid='$uid'")->row_array();

        $result = $row ? 1:0;
        if($row && $row['status']<2 && $shop)
        {

             $this->db->set('status','status + 1',false)
                    ->where('phone',$row['phone'])
                    ->update('zy_limit_prize_player');

            $result = 0;
        }
       return $result;
    }


    function test()
    {



//        $result = $this->db->query("SELECT DISTINCT t.uid FROM (SELECT A.uid FROM zy_process_record A LEFT JOIN zy_process B ON A.uid=B.uid WHERE B.id IS NULL) t")->result_array();
//echo $this->db->last_query();exit;
//        $sql = "select count(*) num,phone from zy_leaf_message GROUP BY phone";
//        $list = $this->db->query($sql)->result_array();
//        foreach($list as $va)
//        {
//            if($va['num']>=10)
//            {
//                $sql = "select uid,truename from zy_leaf_message where  phone=?";
//                $list_uid = $this->db->query($sql,$va['phone'])->result_array();
//                foreach($list_uid as $v)
//                {
//                    $this->table_insert('zy_limit_prize_player',[
//                        'phone' => $va['phone'],
//                        'truename' => $v['truename'],
//                        'uid' => $v['uid'],
//                        'add_time' =>t_time()
//                   ]);
//                }
//
//            }
//
//        }
    }

    function message_update()
    {
        $time = strtotime(t_time())-86400;

        $sql = "select id,uid,status from zy_message WHERE status=0 AND UNIX_TIMESTAMP(add_time)<'$time'";
        $list = $this->db->query($sql)->result_array();


        foreach($list as $v)
        {
            $this->table_update('zy_message',[
                'status' => 1,
                'update_time' =>t_time()
            ],[
                'id'=>$v['id'],
                'uid' => $v['uid']
            ]);
        }
    }


  


}