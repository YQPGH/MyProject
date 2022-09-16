<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/4/18
 * Time: 9:51
 */
include_once 'Base_model.php';

class Leaf_model extends Base_model{

    function __construct(){
        parent::__construct();
        $this->table = 'zy_leaf';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->library('user_agent');
        $this->type2  = 'spring_fest';

    }

    //初始化用户信息
    function initdata($uid,$newer=0){
        $time = $this->activity_time();
        if($time)
        {
            if($uid=='undefined') t_error(2,'操作有误');
            $sql = "select * from $this->table WHERE uid=?";
            $res = $this->db->query($sql,[$uid])->row_array();
            if(empty($res))
            {
                $this->insert([
                    'uid'=>$uid,
                    'energy_value' => 500,
                    'grade' => 1,
                    'status'=>$newer,
                    'addtime'=>t_time(),
                    'updatetime'=>t_time()
                ]);
            }
            $task_row = $this->table_row('zy_leaf_task', ['uid' => $uid]);
            if(empty($task_row))
            {
                $this->table_insert('zy_leaf_task',[
                    'uid'=>$uid,
                    'add_time'=>t_time()
                ]);
            }
            $user = $this->table_row('zy_leaf_position',['uid'=>$uid]);
            if(empty($user))
            {
                for($i=0;$i<12;$i++)
                {
                    $this->table_insert('zy_leaf_position',[
                        'uid'=>$uid,
                        'index'=>$i,
                        'add_time'=>t_time()
                    ]);
                }
            }
        }
    }

    //活动时间
    function activity_time(){
        $time = model('user_model')->query_holiday_time('leaf');
        if(time()>$time['start_time'] && time()<$time['end_time'])
        {
            return true;
        }
        else
        {
            return false;
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
        $result['id'] = $this->table_insert('zy_leaf_invite',$data);

        $url = base_url().'api/Main/leafInvite?incode='.$result['id'];

        return ['url'=>$url];
    }

    //是否有好友邀请
    function is_friend_invite($uid,$code){

        $is_exit = $this->column_sql('code',['id'=>$code],'zy_leaf_invite',0);

        $row['is_invite'] = $is_exit?1:0;
        $row['is_invite'] = $this->table_count('zy_leaf_invite',['id'=>$code]);

        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a, zy_leaf_invite b WHERE code='$is_exit[code]' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }

    //绑定界面
    function mentor_binding($uid,$code){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $is_exit = $this->column_sql('*',['id'=>$code],'zy_leaf_invite',0);
        if(!$is_exit) t_error(1, '暂无邀请');
        if ($uid == $is_exit['uid']) t_error(3, '不能邀请自己');

        //每个人每天最多只能帮忙同一名好友助力1次
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $row = $this->table_row('zy_leaf_record',['uid'=>$is_exit['uid'],'invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end]);

        if ($row) t_error(2, '今日已接受该好友邀请！');

        $this->db->trans_start();
        $data = [
            'uid' => $is_exit['uid'],
            'invited_uid' => $uid,
            'code' => $is_exit['code'],
            'add_time'=> time()
        ];
        $this->table_insert('zy_leaf_record',$data);

        $user = $this->row(['uid'=> $uid]);

        //更新用户任务
        $this->task_update_today($is_exit['uid'], 3,$user['status']);
        $this->task_update_today($uid, 3,$user['status']);

        $this->db->trans_complete();
    }


    //获取用户福气值
    function queryLuckyValue($uid){
        $this->initdata($uid);
        $sql = "select a.nickname,a.head_img,b.energy_value,b.grade level from zy_user a,zy_leaf b WHERE b.uid=? AND a.uid=b.uid";
        $res = $this->db->query($sql,[$uid])->row_array();
        $list = $this->column_sql("index,level ",['uid'=>$uid],'zy_leaf_position',1);
        $result['user_detail'] = $res;
        $result['leaf_index'] = $list;
        return $result;
    }

    //合成 叠加烟叶
    function composition($uid,$leaf_index){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $this->db->trans_start();
        $leaf_index = explode(',',$leaf_index);
        foreach($leaf_index as $key=>$index)
        {

            $row = $this->column_sql('index,level',['uid'=>$uid,'index'=>$index],'zy_leaf_position',0);
            if(!$row['level']) t_error(2,'操作有误');
            if($row['level']==18) t_error(3,'当前已是最高级');

            if($key==1)
            {

                $this->db->set('level','level+1',false);
            }
            else
            {
                $this->db->set('level','0');
            }
            $this->db->set('update_time',t_time());
            $this->db->where('index',$row['index']);
            $this->db->where('uid',$uid);
            $this->db->update('zy_leaf_position');
            $level = $row['level']+1;
        }

        $prize['times'] = 0;
        $time = model('user_model')->query_holiday_time('leaf');
        $sql = "select uid from zy_leaf_peiyang_record WHERE  uid=? AND game_lv=? AND UNIX_TIMESTAMP(add_time)>?";
        $is_exit = $this->db->query($sql,[$uid,$level,$time['start_time']])->row_array();

        $prize = $this->column_sql("*",['leaf_lv'=>$level],'zy_leaf_peiyang_config',0);

        $result['energy'] = '';
        $result['money'] = '';
        $result['shandian'] = '';
        $result['shop'] = '';
        $result['shop_num'] = '';
        $result['lucky_times'] = '';

        if($level>1 && !$is_exit)
        {
            $this->update_value($uid,$prize['prize_value']);

            $this->db->set('grade','grade+1',false)
                ->set('luck_times','luck_times+'.$prize['times'],false)
                ->set('updatetime',t_time())
                ->where('uid',$uid)
                ->update($this->table);

            $this->table_insert('zy_leaf_peiyang_record',[
                'uid'=>$uid,
                'game_lv'=>$level,
                'add_time'=>t_time()
            ]);
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

            if($prize['shop'])
            {
                $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
            }
            $result['energy'] = $prize['prize_value'];
            $result['money'] = $prize['money'];
            $result['shandian'] = $prize['shandian'];
            $result['shop'] = $prize['shop'];
            $result['shop_num'] = $prize['shop_num'];
            $result['lucky_times'] = $prize['times'];
        }

        $this->db->trans_complete();
        $result['level'] = $level;
        return $result;


    }

    //回收
    function recovery($uid,$index){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $row = $this->column_sql('index,level',['uid'=>$uid,'index'=>$index],'zy_leaf_position',0);

        if($row)
        {
            $prize = $this->column_sql("use_value",['leaf_lv'=>$row['level']],'zy_leaf_peiyang_config',0);

            $this->update_value($uid,$prize['use_value']);
            $this->table_update('zy_leaf_position',['level'=>0,'update_time'=>t_time()],['uid'=>$uid,'index'=>$index]);
            $value = $this->row(['uid' => $uid]);
            $result['value'] = $value['energy_value'];
            return $result;
        }

    }

    function leafmove($uid,$start_index,$end_index)
    {
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $sql = "select `index`,`level` from zy_leaf_position WHERE uid=? and `index` in?";
        $list = $this->db->query($sql,[$uid,[$start_index,$end_index]])->result_array();
        $this->db->trans_start();
        if(count($list))
        {
            foreach($list as $value)
            {
                if($value['index']==$end_index && $value['level']) t_error(3,'操作有误');
                if($value['index']==$start_index && $value['level'])
                {
                    $this->table_update(
                        'zy_leaf_position',
                        [
                            'level'=>0,
                            'update_time'=>t_time()
                        ],
                        [
                            'uid'=>$uid,
                            'index'=>$start_index]
                    );
                    $this->table_update(
                        'zy_leaf_position',
                        [
                            'level'=>$value['level'],
                            'update_time'=>t_time()
                        ],
                        [
                            'uid'=>$uid,
                            'index'=>$end_index]
                    );
                }

            }
        }
        $this->db->trans_complete();

    }

    //培养叶子解锁等级
    function levelUnlock($uid,$level){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $row = $this->row(['uid'=>$uid]);
        $prize = $this->column_sql("*",['leaf_lv'=>$level],'zy_leaf_peiyang_config',0);
        if($row['grade']<$level) t_error(2,'您的等级不够');
        if($row['energy_value']<$prize['use_value']) t_error(3,'您的阳光值不足');
        $this->db->trans_start();
        $list = $this->column_sql('index,level',['uid'=>$uid],'zy_leaf_position',1);
        $array = [];
        foreach($list as $value)
        {

            if($value['level']==0)
            {
                $this->db->set('level',$level);
                $this->db->set('update_time',t_time());
                $this->db->where('index',$value['index']);
                $this->db->where('uid',$uid);
                $this->db->update('zy_leaf_position');

                $this->db->set('energy_value','energy_value-'.$prize['use_value'],false)
                    ->where('uid',$uid)
                    ->update($this->table);
                $index = $value['index'];
                break;
            }
            else
            {
                array_push($array,$value['level']);
                if(count($array)>=12) t_error(4,'没有空闲土地培养香叶');

            }

        }

        $this->db->trans_complete();
        $result =  $this->column_sql('index,level',['uid'=>$uid,'index'=>$index],'zy_leaf_position',0);

        return $result;
    }


    //奖品列表
    function Leaf_prize_list($uid){
        $prize = $this->db->query("select money,shandian,shop,shop_num from zy_leaf_prize_config where type2= '$this->type2' order by `index` asc")->result_array();

        $row = $this->row(['uid'=>$uid]);
        $result['luck_times'] = $row['luck_times'];
        $result['prize'] = $prize;
        return $result;
    }

    //抽奖
    function Leaf_get_prize($uid){

        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $res = $this->row(array('uid'=>$uid));
        if(!$res['luck_times']) t_error(2,'您还未获得抽奖机会！');

        // 事务开始
        $this->db->trans_start();
        $starttime = model('user_model')->query_holiday_time('leaf');
        $prize = $this->prize_return($res['grade']);
//        $count = $this->table_count('zy_leaf_prize_record',['uid'=>$uid,'type'=>1]);
        $sql = "select COUNT(*) as num from zy_leaf_prize_record WHERE  uid=? AND type=? AND UNIX_TIMESTAMP(add_time)>?";
        $count =  $this->db->query($sql,[$uid,1,$starttime['start_time']])->row_array();
        $ip = model('midautumn_model')->get_real_ip();
        $ip_limit = $this->db->query("select COUNT(ip) num from zy_leaf_prize_record WHERE  `type`=1 AND ip=? GROUP  BY ip",[$ip])->row_array();
        $is_prize_back = model('prize_model')->is_prize_black($uid);
//        $is_limit = model('qixi_model')->limit_player($uid,$prize['shop']);

        if($ip_limit['num']>=2 ||   $count['num']>=2 || !$is_prize_back)
        {
            $prize = $this->prize_shop();
        }

//        if($uid=="752393d5658218522c284ea3c58aea5d"){$prize = $this->prize_test();}
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

        $type = 0;
        $insert_id = 0;
        if($prize['shop'])
        {
            if($prize['type'])
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
                        'type' =>1,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $insert_id = $this->table_insert('zy_ticket_record', $data);
                }
                //更新奖品数量
                $this->db->set('total','total-1',false)
                    ->where('id',$prize['id'])
                    ->update('zy_leaf_prize_config');
                $type = 1;
            }
            else
            {
                $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
            }
        }

        $data = [
            'uid'=>$uid,
            'pid' => $prize['id'],
            'ticket_id'=>$insert_id,
            'shandian' => $prize['shandian'],
            'money' => $prize['money'],
            'type'=> $type,
            'add_time'=>t_time(),
            'ip' => $ip,
            'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() .
                $this->agent->version()
        ];
        $this->table_insert('zy_leaf_prize_record',$data);
        //更新用户抽奖次数
        $this->db->set('luck_times','luck_times-1',false)
            ->where('uid',$uid)
            ->update($this->table);

        unset($prize['id'],$prize['type']);
        $this->db->trans_complete();
        return $prize;
    }

    //如果奖品数量已抽完或者奖品拥有数量上限，则随机返回其他奖品
    function prize_shop(){
        $index = rand(0,6);
        $where = [
            'index' => $index,
            'type' => 0,
            'type2' =>$this->type2
        ];
        $result = $this->column_sql('id,index,shandian,money,shop,shop_num,type',$where,'zy_leaf_prize_config',0);

        return $result;
    }

    function prize_return($level){

        if($level<=13)
        {
            $type = 1;
        }
        else if($level<=16 && $level>=14)
        {
            $type = 2;
        }
        else if($level>=17)
        {
            $type = 3;
        }
        $prize_array = $this->db->query("select id,rate,total,`type` from zy_leaf_prize_config WHERE type2='$this->type2'")->result_array();

        foreach($prize_array  as $key=>$value)
        {

            $json = json_decode($value['rate']);
            $proArr[$value['id']] = $json->{'rate1'};

            if($value['type'] && $value['total']==0)
            {
                unset($proArr[$value['id']]);
            }
        }

        $prize_id = $this->get_rand_num($proArr);

        $result = $this->column_sql("`index`,id,shandian,money,shop,shop_num,type",array('id'=>$prize_id),'zy_leaf_prize_config',0);
//        $array = [200,2000];
//        $rand_key = array_rand($array,1);
//        $type = $array[$rand_key];
//        if($result['shandian'] == $type)
//        {
//            $result['money'] = 0;
//
//        }
//        else
//        {
//            $result['shandian'] = 0;
//
//        }


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

    /**
     * 抽奖记录
     */
    function prize_record($uid){
        $time = model('user_model')->query_holiday_time('leaf');
        $list = $this->lists_sql("SELECT p.*,c.shop,c.shop_num FROM zy_leaf_prize_record p,zy_leaf_prize_config c
                                  WHERE p.`pid` = c.`id` AND c.type2='$this->type2' AND p.uid=? AND UNIX_TIMESTAMP(p.add_time)>?
                                  ORDER BY p.type DESC,p.add_time DESC LIMIT 30", [$uid,$time['start_time']]);

        foreach($list as $k => &$v)
        {
            $v['prop'] = $v['type']?1:0;
            if($v['shop'])
            {
                $shop = $this->shop_model->detail($v['shop']);
                if($shop['type1']=='peifang')
                {
                    unset($v['id']);
                }
                else
                {
//                    $row = $this->column_sql('*',['pid'=>$v['id']],'zy_leaf_message',0);
                    $sql = "select * from zy_leaf_message WHERE uid=? AND  pid=? AND UNIX_TIMESTAMP(add_time)>?";
                    $row  = $this->db->query($sql,[$uid,$v['id'],$time['start_time']])->row_array();
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
                }
                unset($v['ticket_id']);
            }
            else
            {
                unset($v['id']);
            }
            unset($v['pid'],$v['uid'],$v['type'],$v['ip'],$v['user_agent']);
        }
        return $list;
    }

// 开始签到,返回签到次数
    function sign($uid)
    {
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        // 判断今天是否已经签到
        $last_row = $this->table_row('zy_leaf_sign',['uid' => $uid]);
        if ($last_row)
        {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) t_error(1, '今天你已经签到过了，请明天再来。');
        }
        $this->db->trans_start();
        // 写入签到表
        $this->table_insert('zy_leaf_sign',[
            'uid' => $uid,
            'add_time' => t_time(),
        ]);
        // 奖励阳光值
        $value = config_item('sunshine_value');
        // 判断昨天是否签到，是更新用户连续签到数+1
        if ($last_row && $last_sign_day == $this->time->yesterday())
        {
            $this->update_sign($uid);
            $user = $this->row(['uid' => $uid]);

            if ($user['sign_total'] == 8)
            { // 如果到7天奖励更多
                $this->update_sign($uid, 1);
                $prize = $value[1]['num'];
                $result['sign_total'] = 1;
                $result['value'] = $prize;
            }
            else
            { // 单日签到
                $prize = $value[$user['sign_total']]['num'];
                $result['sign_total'] = $user['sign_total'];
                $result['value'] = $prize;
            }

        }
        else
        { // 不是连续签到，重新1开始
            $this->update_sign($uid, 1);
            $prize = $value[1]['num'];
            $result['sign_total'] = 1;
            $result['value'] =  $prize;

        }

        $this->update_value($uid,$prize);
        $this->db->trans_complete();
        return $result;

    }

    // 签到列表, 返回最近连续签到的列表
    function list_my($uid)
    {

        $this->update_sign_status($uid);
        $list = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];
        $user =  $this->row(['uid' => $uid]);
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
        $last_row = $this->table_row('zy_leaf_sign',['uid' => $uid]);

        if ($last_row)
        {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) $result['sign_today'] = 1;
        }
        //获取每日签到的奖励
        $prize = config_item('sunshine_value');
        foreach($prize as $v)
        {
            $result['prize'][] = $v['num'];
        }

        return $result;
    }

    // 更新连续签到,
    function update_sign_status($uid)
    {

        $user = $this->row(['uid' => $uid]);
        if ($user['sign_total'] > 0)
        {

            $last_row = $this->table_row('zy_leaf_sign',['uid' => $uid]);
            // 如果最后签到日不等于昨天或者今天，清零
            $last_sign_day = $this->time->day($last_row['add_time']);
            if (($last_sign_day != $this->time->yesterday() && $last_sign_day != $this->time->today())||($user['sign_total'] == 7 && $last_sign_day == $this->time->yesterday()))
            {
                $this->update_sign($uid, 0);
            }

        }
    }

    function update_value($uid,$total){

        $this->db->set('energy_value','energy_value+'.$total,false)
            ->set('updatetime',t_time())
            ->where('uid',$uid)
            ->update($this->table);

    }

    function update_sign($uid, $number = -1)
    {

        $str = $number == -1 ? 'sign_total+1' : $number;

        $this->db->set('sign_total', $str, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    //任务
    function task_list($uid){
        $this->count_task($uid);
        // 查询今天的完成情况
        $today_row = $this->table_row('zy_leaf_task', ['uid' => $uid]);

//        if($today_row['update_time'] < t_time(0,0))
//        {
//            $today_row = $this->init_today_task_times($uid);
//        }
        $sql = "select id from zy_leaf_task_config";
        $list = $this->db->query($sql)->result_array();
        foreach($list as $key=>&$value)
        {
            $value['current_value'] = $today_row['current_value'.$value['id']];
            $value['total_value'] = $today_row['total_value'.$value['id']];
        }
        return $list;

    }

    /**
     *  任务领取
     *
     */
    function task_receive($uid, $id){
        $time = $this->activity_time();
        if(!$time) t_error(6,'活动已结束');
        $today_row = $this->table_row('zy_leaf_task', ['uid' => $uid]);
        // 判断是否完成
        if (!$today_row['current_value' . $id]) t_error(3, '任务未完成');
        $this->db->trans_start();
        // 更新领奖状态
        $this->db->set('total_value' . $id,'total_value' . $id . '+'.$today_row['current_value' . $id],false)
            ->set('current_value'.$id,0)
            ->where('uid' , $uid)
            ->update('zy_leaf_task');
        // 发放奖品
        $this->update_value($uid,$today_row['current_value' . $id]);

        //记录任务领取信息
        $data = [
            'uid' => $uid,
            'task_id' => $id,
            'type' => $this->type2,
            'addtime' => time()
        ];
        $this->table_insert('log_task', $data);

        $this->db->trans_complete();
    }

    /**
     * 统计任务一完成情况
     */
    function count_task($uid){
        $row = $this->table_row('zy_leaf_task', ['uid' => $uid]);

        $task1 = floor($row['task_total']/10);
        $sql = "select * from zy_leaf_task_config WHERE id=1";
        $res = $this->db->query($sql)->row_array();

        if($task1>=$res['task_num'] && $row['task1']<$res['limit_times'])
        {
            $value = $task1*$res['prize_value'];
            if($row['total_value1']+$value>=$res['limit_times']*$res['prize_value'])
            {

                $task1 = $row['total_value1']?abs($res['limit_times']-$row['task1']):$res['limit_times'];
                $value  = $row['total_value1']?$res['prize_value']*$task1:$res['limit_times']*$res['prize_value'];
            }

            $this->db->set('task_total','task_total-'.$task1*10,false)
                ->set('task1','task1+'.$task1,false)
                ->set('current_value1','current_value1+'.$value,false)
                ->where('uid' , $uid)
                ->update('zy_leaf_task');
        }
    }

    //任务一
    function update_task_value($uid){
        $time = $this->activity_time();
        if($time)
        {
            $this->db->set('task_total','task_total+1',false)
                ->where('uid',$uid)
                ->update('zy_leaf_task');
        }

    }

    function init_today_task_times($uid){
        $this->db->set('task1',0)
            ->set('task_total',0)
            ->set('current_value1',0)
            ->set('total_value1',0)
            ->set('task2',0)
            ->set('current_value2',0)
            ->set('total_value2',0)
            ->set('task3',0)
            ->set('current_value3',0)
            ->set('total_value3',0)
            ->set('update_time',t_time())
            ->where('uid',$uid)
            ->update('zy_leaf_task');

        $this->db->set('max_value',0)
            ->where('uid',$uid)
            ->update('zy_leaf');
        return $this->table_row('zy_leaf_task', ['uid' => $uid]);
    }


    // 更新今日任务进度
    function task_update_today($uid, $taskid,$newer=0)
    {
        $time = $this->activity_time();
        if($time)
        {
            $row = $this->table_row('zy_leaf_task_config', ['id' => $taskid]);
            $res = $this->table_row('zy_leaf_task', ['uid' => $uid]);
            if($row['limit_times'] && $res['task' . $taskid]>$row['limit_times'])
            {
                $value = 0;
            }
            else
            {
                $user = $this->row(['uid' => $uid]);
                if($taskid==2)
                {
                    $max = $row['prize_value']*$row['limit_times'];
                    $mix = $max;
                }
                else
                {
                    $max = $row['prize_value']*3;
                    $mix = $row['prize_value']*6;
                }
                if(!$row['limit_times'] && !$newer && $user['max_value']>=$max)
                {
                    $value = 0 ;
                    $num = 0;
                }
                else
                {
                    $num = 1;
                    $value = $newer?$row['prize_value']*3:$row['prize_value'];
                    $data = $this->row(['uid'=>$uid]);

                    if(!$newer)//不是新用户,每日上限6000
                    {
                        if($data['max_value']<$mix)
                        {
                            $this->db->set('max_value' , 'max_value+'.$row['prize_value'] , FALSE)
                                ->where('uid', $uid)
                                ->update('zy_leaf');
                        }
                        else
                        {
                            $num = 0;
                            $value = 0;
                        }
                    }
                }

                $result =  $this->db->set('task' . $taskid, 'task' . $taskid .'+'.$num , FALSE)
                    ->set('current_value'. $taskid,'current_value'. $taskid.'+'.$value,false)
                    ->where('uid', $uid)
                    ->update('zy_leaf_task');
                if($result)
                {
                    $this->db->set('status' ,0 )
                        ->where('uid', $uid)
                        ->update('zy_leaf');
                }
                return $result;
            }
        }


    }


    //保存用户信息
    function savemessage($uid,$id,$truename,$phone,$address,$code){

        $get_sql = "select id,pid,status,add_time,update_time from zy_leaf_message where uid=? AND pid=?";
        $row = $this->db->query($get_sql,[$uid,$id])->row_array();

        if(strtotime($row['update_time'])>0)
        {

            $time = time() - strtotime($row['update_time']);
            $a_time = time()  - strtotime($row['add_time']);
            if( $time < 50 || $a_time<50) t_error(6,'提交时间过短，请稍后再试');
        }
        if($row && $row['status'] == 1) t_error(5,'不可修改');

        $check_sql = "select * from zy_leaf_prize_record where uid=? AND id=?";
        $check = $this->db->query($check_sql,[$uid,$id])->row_array();
        if(empty($check))  t_error(4,'保存失败！');
        $data = [
            'uid'=>$uid,
            'truename'=>$truename,
            'phone'=>$phone,
            'address'=>$address,
            'status'=>0,
            'pid'=>$id,
            'code'=>$code
        ];

        if($row)
        {
            $data['update_time']  = t_time();
            $this->table_update('zy_leaf_message',$data,['uid'=>$uid,'id'=>$row['id']]);
        }
        else
        {
            $data['add_time'] = t_time();
            $this->table_insert('zy_leaf_message', $data);
        }

    }

    //查询用户信息
    function getUsermessage($uid,$id){

        $sql = "select uid,truename,phone,address,status,add_time,code from zy_leaf_message where uid=? AND pid=?";
        $row = $this->db->query($sql,[$uid,$id])->row_array();

        $max_time = 86400;
        if($row)
        {
            if(time()-strtotime($row['add_time'])>$max_time)
            {
                $this->table_update('zy_leaf_message',['status'=>1],['uid'=>$uid,'pid'=>$id]);
                $result['status'] = 1;
            }
            else
            {
                $result['status'] = 0;
            }
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
        }
        else
        {

            $sql = "select truename,phone,address,status,add_time,code from zy_leaf_message where uid=? ORDER BY id desc";
            $row = $this->db->query($sql,[$uid])->row_array();
            $result['status'] = 0;
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
            if(empty($row)) $result = [];

        }

        return $result;
    }

    //奖品测试
    function prize_test(){

        $rand = 36;
        $result = $this->column_sql("`index`,id,shandian,money,shop,shop_num,type",array('id'=>$rand),'zy_leaf_prize_config',0);
        $result['index'] = 7;

        return $result;
    }


}
