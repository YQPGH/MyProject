<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2020/12/28
 * Time: 10:29
 */

include_once  'Base_model.php';

class Coolrun_model extends Base_model
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_coolrun_player';
        $this->load->model('api/user_model');
        $this->load->model('api/shop_model');
        $this->name = 'run';
        $this->score = 3000;
        $this->second = 60;
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

    function initData($uid)
    {
        $this->db->trans_start();
        $row = $this->row(['uid' => $uid]);
        if(empty($row))
        {
            $this->insert([
                'uid'=>$uid,
                'value' => 1,
                'addtime'=>t_time()
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

        $this->db->trans_complete();
    }

    function getUserdata($uid)
    {
        $this->initData($uid);

        $sql = "select a.nickname,a.head_img,b.value times,b.score  from zy_user a,zy_coolrun_player b WHERE b.uid=? AND a.uid=b.uid";
        $res = $this->db->query($sql,[$uid])->row_array();
        $this->update(['lasttime'=>t_time()],['uid' => $uid]);


        return $res;
    }

    function startGame($uid)
    {
        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $row = $this->db->query("select `value` from $this->table where uid=?",[$uid])->row_array();
        if($row['value']<1) t_error(3,'你的奔跑机会不够了！');
        $starttime = t_time();
        $endtime = t_time(strtotime($starttime)+$this->second);
        $this->db->trans_start();
        $code = t_rand_str();
        $this->table_insert('zy_coolrun_record',[
            'uid'=>$uid,
            'code' => $code,
            'starttime'=>$starttime,
            'endtime' => $endtime

        ]);

        $this->db->set('value','value-1',false)
            ->set('updatetime',$starttime)
            ->where('uid',$uid)
            ->update($this->table);

        $this->db->trans_complete();

        $result['start_time'] = $starttime;
        $result['end_time'] = $endtime;
        $result['value'] = $row['value']-1;
        $result['code'] = $code;
        return $result;
    }

    function gameOver($data)
    {

        $record = $this->table_row('zy_coolrun_record',['code' => $data['code']]);

        if (!$record['code']) t_error(3, '操作错误');
        if($record['score'] || $record['ip']) t_error(4,'不能重复提交');
        // 检查时间
        $time = time()-strtotime($record['starttime']); //游戏时长
        $livetime = time()>strtotime($record['endtime'])?$this->second:abs($time);
//        $this->db->trans_start();

        if($data['score']> 2500)
        {
            $this->table_update('zy_coolrun_record',[
                'score' => $data['score'],
                'updatetime' => t_time(),
                'live_time' =>  $livetime,
                'status' => 1,
                'ip'=> ip(),
                'user_agent' => get_agent()
            ],[
                'uid' => $data['uid'],
                'code' => $data['code'],
            ]);

            $this->db->set('value','value + 1',false)
                ->set('updatetime',t_time())
                ->where('uid',$data['uid'])
                ->update($this->table);
            t_error(6,'数据有误，请稍后再试');
        }
        else
        {
            $this->table_update('zy_coolrun_record',[
                'score' => $data['score'],
                'updatetime' => t_time(),
                'live_time' =>  $livetime,
                'ip'=> ip(),
                'user_agent' => get_agent()
            ],[
                'uid' => $data['uid'],
                'code' => $data['code'],
            ]);
        }



        $this->db->set('score','score + '.$data['score'],false)
            ->set('updatetime',t_time())
            ->where('uid',$data['uid'])
            ->update($this->table);
//        $this->db->trans_complete();
        $result['score'] = intval($data['score']);
        $result['time'] = $livetime;
        return $result;
    }



    function getIdnum()
    {
        $sql = "select id from zy_activity_config WHERE `name`=?";
        $id = $this->db->query($sql,[$this->name])->row_array();
        return $id['id'];
    }

    //邀请
    function mentor_invite($uid){
        $code = t_rand_str();
        $data = [
            'uid' => $uid,
            'code' => $code,
            'add_time' => t_time()
        ];
        $result['id'] = $this->table_insert('zy_invite_record',$data);

        $url = base_url().'api/Coolrun/invite?incode='.$result['id'];

        return ['url'=>$url];
    }

    //是否有好友邀请
    function is_friend_invite($uid,$code){

        $is_exist = $this->column_sql('code',['id'=>$code],'zy_invite_record',0);
        $row['is_invite'] = $is_exist?1:0;
        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a,zy_invite_record b WHERE code='$is_exist[code]' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }

    //接受邀请
    function invite_accept($uid,$code){
        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $this->initData($uid);
        $is_exist = $this->column_sql('*',['id'=>$code],'zy_invite_record',0);
        if(!$is_exist) t_error(3, '暂无邀请');
        if ($uid == $is_exist['uid']) t_error(4, '不能邀请自己');

        //每个人每天最多只能帮忙同一名好友助力1次
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $type = $this->getIdnum();
        $row = $this->table_row('zy_invite',['uid'=>$is_exist['uid'],'type'=>$type,'invited_uid'=>$uid,'code'=>$code,'add_time>'=>$today_start,'add_time<'=>$today_end]);

        if ($row) t_error(5, '今日已接受该好友邀请！');
        $friend_help = $this->table_row('zy_invite',['type'=>$type,'invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end]);
        if($friend_help) t_error(6,'今日好友助力已上限！');
        $this->db->trans_start();


        $data = [
            'uid' => $is_exist['uid'],
            'invited_uid' => $uid,
            'code' => $is_exist['code'],
            'add_time'=> time(),
            'type' => $type
        ];
        $this->table_insert('zy_invite',$data);
       //更新用户任务
        $this->update_total($uid,3,'task');
        $this->update_total($is_exist['uid'],3,'task');
        $this->db->trans_complete();
    }

    /**
     * 奖品列表
     */
    function prize_list($uid)
    {
        $get_sql = "SELECT money,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? ";
        $list = $this->db->query($get_sql, array($this->name))->result_array();

        $result['prize'] = $list;
        return $result;
    }

    /**
     * 抽奖
     */
    function get_prize($uid)
    {

        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
        $row = $this->db->query("select score from $this->table WHERE uid=?;",[$uid])->row_array();
        if($row['score']< $this->score) t_error(3,'您的积分不足');
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;

        $dayprize = $this->db->query("select COUNT(*) num from zy_prize_record WHERE uid=? AND UNIX_TIMESTAMP(add_time)>? AND  UNIX_TIMESTAMP(add_time)<?",[$uid,$today_start,$today_end])->row_array();
        if($dayprize['num']>=3) t_error(4,'今日抽奖已上限，明天再来吧');
        // 事务开始
        $this->db->trans_start();

        $result = [];
        $is_prize_back = model('prize_model')->is_prize_black($uid);

        $sql = "select COUNT(*) as num from zy_prize_record WHERE  uid=? AND type=? ";
        $count =  $this->db->query($sql,[$uid,1])->row_array();
        $prize = $this->prize_return();

        $ip = get_real_ip();
        $ip_limit = $this->db->query("select COUNT(ip) num from zy_prize_record WHERE uid=? and `type`=1 AND ip=? GROUP  BY ip",[$uid,$ip])->row_array();

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
                $this->load->model('api/store_model');
                $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
            }
        }


        $data = [
            'uid'=>$uid,
            'pid' => $prize['id'],
            'title' => $this->getIdnum(),
            'ticket_id'=>$insert_id,
            'type'=> $prize['type2'],
            'add_time'=>t_time(),
            'ip' => $ip,
            'user_agent' => get_agent()
        ];
        $this->table_insert('zy_prize_record',$data);
        unset($prize['id'],$prize['type2']);

        $result['prize'] = $prize;

        //更新用户积分
        $this->db->set('score', 'score - '.$this->score, FALSE)
            ->set('updatetime',t_time())
            ->where('uid', $uid)
            ->update($this->table);

        $this->db->trans_complete();

        return $result;
    }


    function prize_return(){


        $prize_array = $this->db->query("select id,get_rate,total,type2 from zy_prize WHERE type1='$this->name'")->result_array();

        foreach($prize_array  as $value)
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

        $num = [2,3,4,5,7,8];
        $type = $num[array_rand($num,1)];
        $result = $this->db->query("select id,shandian,money,shop1 shop,shop1_total shop_num,type2 from zy_prize WHERE type1='$this->name' AND type2=0 AND json_data LIKE '%$type%'")->row_array();

        $result['index'] = $type;

        return $result;
    }

    /**
     * 抽奖记录
     */
    function prize_record($uid){

        $list = $this->lists_sql("SELECT p.*,c.money,c.shandian,c.shop1 shop,c.shop1_total shop_num FROM zy_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1=? AND p.uid=? AND p.title=?
                                  ORDER BY p.type DESC,p.add_time DESC LIMIT 30", [$this->name,$uid,$this->getIdnum()]);

        foreach($list as $k => &$v)
        {
            $v['prop'] = $v['type']?1:0;
            $v['type1'] = $this->name;

            if($v['shop'])
            {
                $shop = $this->shop_model->detail($v['shop']);
                if($shop['type1']=='peifang')
                {
                    unset($v['id']);
                }
                else
                {
                    $sql = "select * from zy_message WHERE uid=? AND  pid=? AND type=?";
                    $row  = $this->db->query($sql,[$uid,$v['id'],$this->name])->row_array();
                    $ticket = $this->column_sql('stat status',['type'=>1,'id'=>$v['ticket_id']],'zy_ticket_record',0);

                    if($ticket)
                    {
                        $v['status'] = $ticket['status'];
                    }
                    else
                    {
                        $v['status'] = $row? 1:0;
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
                }
                unset($v['ticket_id']);
            }
            else
            {
                unset($v['id']);
            }

            unset($v['title'],$v['pid'],$v['uid'],$v['type'],$v['ip'],$v['user_agent']);
        }
        return $list;
    }



    //任务
    function task_list($uid)
    {
        $today_row = $this->count_task($uid);
        $list = $this->db->query( "select task_id id,task_num from zy_task where  type=?",[$this->name])->result_array();

        foreach($list as $key=>&$value)
        {
//            $value['is_receive'] = 0;
            $value['finish_num'] = 0;
            $value['is_finish'] = 0;
            $value['id'] = $value['id']+1 ;

            for($i =1; $i<=3; $i++)
            {
                if($i == $value['id']-1)
                {
                    if($today_row['current_value'.$i]) $value['is_finish'] = 1;
                    if($today_row['task'.$i]) $value['finish_num'] = $today_row['task'.$i];
                }
            }
            unset($value['task_num'],$value['uid'],$value['add_time'],$value['update_time']);
        }
        $row = $this->row(['uid'=>$uid]);
        $data['id'] = 1;
        $data['finish_num'] = $row['sign']?$row['sign']:0;
        $data['is_finish'] = $row['sign']?0:1;

        $list[] = $data;

        array_multisort(array_column($list, 'id'),SORT_ASC,$list);
        return $list;
    }



    /**
     *  任务领取
     *
     */
    function task_receive($uid, $id){
        $time = $this->activity_time();
        if(!$time) t_error(3,'活动已结束');
        $this->db->trans_start();
        $today_row = $this->table_row('zy_task_detail', ['uid' => $uid]);
        $result['id'] = $id;
        // 判断是否完成
        if($id==1)
        {
            $sign = $this->row(['uid'=>$uid]);

            if ($sign['sign'])  t_error(5,'已领取');

            $this->db->set('value','value+1',false)
                ->set('sign',1)
                ->where('uid' , $uid)
                ->update($this->table);

            $today_row['task' . $id] =  1;
        }
        else
        {
            $id = $id-1;
            $res = $this->db->query("select task_num from zy_task where type=? and task_id=?",[$this->name,$id])->row_array();
            if($today_row['task'.$id]>=$res['task_num']) t_error(5,'已领取');
            if ($today_row['task'.$id]<$res['task_num'] && $today_row['current_value' . $id])
            {
                // 更新领奖状态
                $this->db->set('task' . $id,'task' . $id . '+'.$today_row['current_value' . $id],false)
                    ->set('current_value'.$id,0)
                    ->set('update_time',t_time())
                    ->where('uid' , $uid)
                    ->update('zy_task_detail');

                $this->db->set('value','value+'.$today_row['current_value' . $id],false)
                    ->where('uid' , $uid)
                    ->update($this->table);
            }
            else
            {
                t_error(6,'请先完成任务');
            }
            $today_row = $this->db->query("select * from zy_task_detail where uid=?",[$uid])->row_array();
        }

        $this->db->trans_complete();
        $value = $this->row(['uid'=>$uid]);
        $result['is_finish'] = 0;
        $result['finish_num'] = $today_row['task' . $id];
        $result['times'] = $value['value'];
        return $result;
    }

    /**
     * 统计任务收获完成情况
     */
    function count_task($uid){
        $id = 1;
        $sql = "select task_id id,task_num from zy_task WHERE task_id=? AND `type`=?";
        $res = $this->db->query($sql,[$id,$this->name])->row_array();

        $row = $this->table_row('zy_task_detail', ['uid' => $uid]);

        $this->db->trans_start();
        $res['limit_times'] = 20;//每20次收货可得1次抽奖机会
        $value = abs($res['task_num']-$row['task'.$id]);
        $task1 = 0;
        $task1 = floor($row['task'.$id.'_total']/$res['limit_times'])+ $row['current_value'.$id];
        if($value)
        {

            $remainder = $row['task'.$id.'_total']%$res['limit_times'];
            $value1 = $task1>= $value?$value:$task1;

            $this->db->set('task'.$id.'_total',$remainder,false)
                    ->set('current_value'.$id,$value1,false)
                    ->where('uid' , $uid)
                    ->update('zy_task_detail');
        }

        $this->db->trans_complete();
        return $this->table_row('zy_task_detail', ['uid' => $uid]);
    }

    //更新任务奖励
    function update_total($uid,$id,$type='')
    {
        $time = $this->activity_time();
        $this->db->trans_start();
        if($time)
        {
            $this->initData($uid);
            $sql = "select task_num from zy_task WHERE `type`=? and task_id=?";
            $task = $this->db->query($sql,[$this->name,$id])->row_array();
            $row =  $this->count_task($uid);

            if(!$type)
            {
                $this->db->set('task'.$id.'_total','task'.$id.'_total+1',false)
                    ->where('uid' , $uid)
                    ->update('zy_task_detail');
            }
            else
            {
                if($row['task'.$id.'_total']<$task['task_num'])
                {
                    $this->db->set('current_value'.$id,'current_value'.$id.'+1',false)
                        ->set('task'.$id.'_total','task'.$id.'_total+1',false)
                        ->where('uid' , $uid)
                        ->update('zy_task_detail');
                }
            }
        }
        $this->db->trans_complete();
    }

    function test()
    {



    }



}
