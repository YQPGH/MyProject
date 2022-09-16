<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 模型 基类，其他模型需要先继承本类
 */
include_once('Base_model.php');

class Stat_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_stat_day'; // 数据库表名称
    }

    // 统计每天的数据
    function day($yesterday = 0)
    {
        $today = $this->time->today();
        if ($yesterday) $today = $this->time->yesterday(); // 统计昨天

        $row = $this->row_sql("SELECT COUNT(*) active, SUM(logins) logins,sum(zhiyan_total) zhiyan_total FROM zy_user
WHERE last_time LIKE '{$today}%'");
        $new_user = $this->row_sql("SELECT COUNT(*) as total FROM zy_user
                    WHERE add_time like '{$today}%'");
        $money = $this->row_sql("SELECT SUM(money) money,SUM(ledou) ledou FROM log_shop
                  WHERE add_time like '{$today}%'");
        $ticket = $this->row_sql("SELECT COUNT(*) num FROM zy_ticket_record
                  WHERE from_unixtime(addtime) like '{$today}%'");
        $total = $this->row_sql(" SELECT a.num + b.num num FROM
                (SELECT count(*) num FROM xxl_record WHERE add_time like '{$today}%') a,
                (SELECT count(*) num FROM zy_hunt_record WHERE add_time like '{$today}%') b;");
        $day_total = $this->row_sql("SELECT COUNT(*) total FROM zy_user_login
                WHERE add_time LIKE '{$today}%'");

        $run_total = $this->row_sql("SELECT COUNT(*) total FROM zy_coolrun_player
                WHERE lasttime LIKE '{$today}%'");
        $data = [
            'active' => (int)$row['active'],
            'new_user' => (int)$new_user['total'],
            'logins' => (int)$day_total['total'],
            'money' => (int)$money['money'],
            'ledou' => (int)$money['ledou'],
            'zhiyan' => (int)$row['zhiyan_total'],
            'ticket' => (int)$ticket['num'],
            'guanka' => (int)$total['num'],
            'update_time' => $this->time->now(),
            'activity_num' => (int)$run_total['total'],
        ];


        $row = $this->row(['stat_day' => $today]);
        if ($row) {
            $this->update($data, ['stat_day' => $today]);
        } else {
            $data['stat_day'] = $today;
            $this->insert($data);
        }
    }

    // 制烟次数+1
    function update_zhiyan()
    {
        $today = $this->time->today();
        $row = $this->row(['stat_day' => $today]);
        if (!$row) {
            $this->insert(['stat_day' => $today]);
        }

        $this->db->set('zhiyan', 'zhiyan+1', FALSE);
        $this->db->where('stat_day', $today);
        $this->db->update($this->table);
    }

//    // 香烟升级次数+1
//    function update_shengji()
//    {
//        $today = $this->time->today();
//        $row = $this->row(['stat_day' => $today]);
//        if (!$row) {
//            $this->insert(['stat_day' => $today]);
//        }
//        $this->db->set('shengji', 'shengji+1', FALSE);
//        $this->db->where('stat_day', $today);
//        $this->db->update($this->table);
//    }



    // 统计数据，更新月表
    function month()
    {
        $month = date('Y-m');
        $users = $this->table_count('zy_user');
        $total_row = $this->row_sql("SELECT COUNT(*) active, SUM(logins) logins FROM zy_user WHERE last_time LIKE '{$month}%'");
        $strmonth =strtotime($month);
        $row = $this->row_sql("SELECT SUM(active) active, SUM(logins) logins FROM $this->table
WHERE UNIX_TIMESTAMP(stat_day) >='$strmonth'");
        $total = $this->row_sql("SELECT COUNT(*) num FROM zy_user
WHERE game_lv>2 AND last_time LIKE '{$month}%'");



        $this->table_update('zy_stat_month', [
            'users' => $users,
            'active' => $row['active'],
            'logins' => $row['logins'],
            'total_active' =>$total_row['active'],
            'total_logins'=>$total_row['logins'],
            'user_gamelv' => $total['num'],
            'update_time' => $this->time->now()],
            ['dates' => $month]);
    }

    // 统计异常数据，昨天乐豆花费超过500个的用户写入异常表
    function unusual()
    {
        $day = $this->time->yesterday(); // 统计昨天的
        $list = $this->lists_sql("SELECT uid,SUM(ledou) ledou_sum FROM `log_shop` 
                                    WHERE  ledou<0 AND add_time>'{$day}'
                                    GROUP BY uid
                                    HAVING ledou_sum<-300
                                    ORDER BY ledou_sum
                                    LIMIT 100");
        foreach ($list as $value) {
            $data = [
                'uid' => $value['uid'],
                'typeid' => 3,
                'title' => '今日花费乐豆数' . $value['ledou_sum'],
                'add_time' => t_time(),
            ];
            $this->db->insert('zy_unusual', $data);
        }
    }

    // 每日题目统计
    function question_set()
    {
        $ids = [];
        $list = $this->lists_sql("SELECT id FROM zy_question_config WHERE status=0 LIMIT 100");
        $rands = array_rand($list, 5);
        foreach ($rands as $key) {
            $ids[] = $list[$key]['id'];
        }
        $str = join(',', $ids);

        $this->table_update('zy_setting', ['mvalue' => $str,'add_time'=>t_time()], ['mkey' => 'question_today']);
    }

    // 订单刷新
    function orders_set()
    {
        $ids = [];
        $list = $this->lists_sql("SELECT id FROM zy_orders_config WHERE status=0 LIMIT 100");
        $rands = array_rand($list, 2);
        foreach ($rands as $key) {
            $ids[] = $list[$key]['id'];
        }
        $str = join(',', $ids);

        $this->table_update('zy_setting', ['mvalue' => $str], ['mkey' => 'order_today']);
    }

    // 神秘商店刷新
    function shop_set()
    {
        $list = $this->lists_sql("SELECT shopid FROM zy_shop WHERE status=1 AND type2 IN (2,3) LIMIT 1000");
        $list_building = $this->lists_sql("SELECT * FROM zy_shop WHERE status=1 AND type1='building' LIMIT 3");//建筑升级材料
        //随机取出8个
        //$rand_key = array_rand($list, 8);
        //随机取5个，剩下3个留给建筑升级材料
        $rand_key = array_rand($list, 5);
        foreach ($rand_key as $r) {
            $list_rand[] = $list[$r];
        }

        $list_rand[] = $list_building[0];
        $list_rand[] = $list_building[1];
        $list_rand[] = $list_building[2];


        $str = '';
        foreach ($list_rand as $key => $value) {
            $str .= $value['shopid'] . ',';
        }
        $str = rtrim($str, ',');

        $this->table_update('zy_setting',
            ['mvalue' => $str, 'add_time' => t_time()],
            ['mkey' => 'shenmi_shop']);
    }

    // 每日任务刷新
    function task_set()
    {
        $ids = [];
        $list = $this->lists_sql("SELECT id FROM zy_task WHERE id NOT IN(11,12) AND type='' LIMIT 100");

        $rands = array_rand($list, 3);
        foreach ($rands as $key) {
            $ids[] = $list[$key]['id'];
        }
        array_push($ids,13);
        $str = join(',', $ids);
        $this->table_update('zy_setting', ['mvalue' => $str,'add_time'=>t_time()], ['mkey' => 'task_today']);
    }

    //每周签到、抽奖奖励随机值
    function sign_rank(){
        $row = $this->table_row('zy_setting',['mkey'=>'type2']);
        if($row['mvalue'] < 7){
            $row['mvalue'] = $row['mvalue']+1;
        }else{
            $row['mvalue'] = 1;
        }
        $this->table_update('zy_setting', ['mvalue'=>$row['mvalue'],'add_time'=>t_time()], ['mkey' => 'type2']);
    }

    //每周签到、抽奖奖励随机值(1显示种植榜，2显示制烟榜)
    function show_type(){
        $week_num = $this->table_row('zy_setting',['mkey'=>'week_num']);
        if($week_num['mvalue']<5){
            $num = $week_num['mvalue']+1;
            $this->table_update('zy_setting', ['mvalue'=>$num,'add_time'=>t_time()], ['mkey' => 'week_num']);
        }else if($week_num['mvalue'] == 5){
            $num = 1;
            $this->table_update('zy_setting', ['mvalue'=>$num,'add_time'=>t_time()], ['mkey' => 'week_num']);
            $row = $this->table_row('zy_setting',['mkey'=>'show_type']);
            if($row['mvalue'] == 1){
                $row['mvalue'] = 2;
            }else{
                $row['mvalue'] = 1;
            }
            $this->table_update('zy_setting', ['mvalue'=>$row['mvalue'],'add_time'=>t_time()], ['mkey' => 'show_type']);
        }
    }

    //定时周一更新排行榜的奖品
    public function updateRankingPrizeConfig(){
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $res = $this->column_sql('money,ledou,shandian,json_data',['type1'=>10,'type2'=>''],'zy_prize',1);
        if(!empty($res)){
            foreach($res as $key=>&$value){
                if($value['json_data']){
                    $temp = json_decode($value['json_data'], true);
                    //print_r($temp);
                    $value = array_merge($value, $temp);
                    //unset($value['json_data']);
                }
            }
            foreach($res as $k=>$v){
                //查询本周是否在zy_ranking_prize_config添加相应记录
                $count = $this->db->query("select count(*) as num from zy_ranking_prize_config WHERE rank_start=$v[rank_start] AND rank_end=$v[rank_end] AND UNIX_TIMESTAMP(add_time) > $start_time")->row_array();
                if(!$count['num']){
                    $insert['rank_start'] = $v['rank_start'];
                    $insert['rank_end'] = $v['rank_end'];
                    $insert['money'] = $v['money'];
                    $insert['ledou'] = $v['ledou'];
                    $insert['shandian'] = $v['shandian'];
                    $insert['shop1_total'] = $v['shop1_total'];
                    $insert['shop2_total'] = $v['shop2_total'];
                    if($v['shop1_total']){
                        $insert['shop1_id'] = $this->randShop($v['shop1_type1'],$v['shop1_type2']);
                    }else{
                        $insert['shop1_id'] = 0;
                    }
                    if($v['shop2_total']){
                        $insert['shop2_id'] = $this->randShop($v['shop2_type1'],$v['shop2_type2']);
                    }else{
                        $insert['shop2_id'] = 0;
                    }
                    $insert['add_time'] = t_time();
                    $this->table_insert('zy_ranking_prize_config',$insert);
                }
            }
        }

    }

    //定时周一更新积分排行榜的奖品
    public function updateRankingJfPrizeConfig(){
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $res = $this->column_sql('money,ledou,shandian,json_data',['type1'=>11],'zy_prize',1);
        if(!empty($res)){
            foreach($res as $key=>&$value){
                if($value['json_data']){
                    $temp = json_decode($value['json_data'], true);
                    //print_r($temp);
                    $value = array_merge($value, $temp);
                    //unset($value['json_data']);
                }
            }
            foreach($res as $k=>$v){
                //查询本周是否在zy_ranking_prize_config添加相应记录
                $count = $this->db->query("select count(*) as num from zy_ranking_jf_prize_config WHERE rank_start=$v[rank_start] AND rank_end=$v[rank_end] AND UNIX_TIMESTAMP(add_time) > $start_time")->row_array();
                if(!$count['num']){
                    $insert['rank_start'] = $v['rank_start'];
                    $insert['rank_end'] = $v['rank_end'];
                    $insert['money'] = $v['money'];
                    $insert['ledou'] = $v['ledou'];
                    $insert['shandian'] = $v['shandian'];
                    $insert['shop1_total'] = $v['shop1_total'];
                    $insert['shop2_total'] = $v['shop2_total'];
                    $insert['shop3_total'] = $v['shop3_total']?$v['shop3_total']:0;
                    if($v['shop1_total']){
                        $insert['shop1_id'] = $this->randShop($v['shop1_type1'],$v['shop1_type2']);
                    }else{
                        $insert['shop1_id'] = 0;
                    }
                    if($v['shop2_total']){
                        $insert['shop2_id'] = $this->randShop($v['shop2_type1'],$v['shop2_type2']);
                    }else{
                        $insert['shop2_id'] = 0;
                    }
                    if($v['shop3_total']){
                        $insert['shop3_id'] = $v['shop3_id'];
                    }else{
                        $insert['shop3_id'] = 0;
                    }

                    $insert['add_time'] = t_time();
                    $this->table_insert('zy_ranking_jf_prize_config',$insert);
                }
            }
        }

    }

    /**
     * 根据商品类型type1，商品等级type2随机获取某个商品
     *
     */
    function randShop($type1,$type2){
        $res = $this->column_sql('shopid',['type1'=>$type1,'type2'=>$type2],'zy_shop',1);
        $key = rand(0,count($res)-1);
        if(!empty($res)){
            return $res[$key]['shopid'];
        }
    }


    /**
     * 好运临门奖品 设置
     */
//  public  function turntable_prize(){
//      $lists = $this->column_sql('*',array('id'=>8,'give_num'=>0,'rate' =>0),'zy_turntable_prize_config',1);
////        $lists = $this->column_sql('*',array('give_num'=>0,'rate' =>0),'zy_turntable_prize_config',1);
//        $prize = [
////            2=>['num'=>1,'rate'=>0.10],
////            6=>['num'=>1,'rate'=>0.10],
//            8=>['num'=>28,'rate'=>0.82],
//        ];
//        foreach($lists as $value){
//
//            $this->table_update(
//                'zy_turntable_prize_config',
//                ['give_num'=>$prize[$value['id']]['num'],'rate' => $prize[$value['id']]['rate'],'update_time'=>t_time()],
//                ['id' => $value['id']]
//            );
//        }
//
//
//    }

    //每日扫码任务奖励
    function taskprize_scan(){
        $type = [1,2,3,4,5];
        $id = array_rand($type,1);
        $this->table_update('zy_setting', ['mvalue'=>$type[$id],'add_time'=>t_time()], ['mkey' => 'suipian_type']);

        $lists = $this->column_sql('id,type3',array('type1'=>'5','type2'=>'scan'),'zy_prize',1);
        foreach($lists as &$value){
            if($value['type3'] == 1){
                $arr1[] = $value['id'];
            }else{
                $arr2[] = $value['id'];
            }
        }
        $key1 = array_rand($arr1,1);
        $key2 = array_rand($arr2,1);
        $this->table_update('zy_task', ['prizeid'=>$arr1[$key1]], ['id' => 11]);
        $this->table_update('zy_task', ['prizeid'=>$arr2[$key2]], ['id' => 12]);

    }

//重置每天获得碎片数量
    public function resetNum(){
        $this->db->set('max_num',0)
            ->set('is_receive',0)
            ->set('today_num',0)
            ->set('update_time',t_time())
            ->update('zy_fragment');

    }


    public function fragment_prize_day1(){
        $row = $this->db->query("select start_time,end_time,update_time,update_time3 from zy_fragment_prize_config WHERE id=1;")->row_array();
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);
        if(time()>$start && time()<$end){
            $time = strtotime($row['update_time3']) + 6*86400;
            if(time()>$time){
                $this->db->set('num_3', 'num_3+1', FALSE);
                $this->db->set('update_time3', t_time());
            }
            $time_day2 = strtotime($row['update_time']) + 2*86400;
            if(time()>$time_day2){
                $this->db->set('num_2', 'num_2+1', FALSE);
                $this->db->set('update_time', t_time());
            }
            $this->db->set('num_1', 'num_1+5', FALSE);
            $this->db->where('id' , 1);
            $this->db->update('zy_fragment_prize_config');
        }
    }

    function init_scan_today_task_times(){

        $this->db->set('task11',0)
            ->set('task11_prized',0)
            ->set('task12',0)
            ->set('task12_prized',0)
            ->set('scan_updatetime',t_time())
            ->update('zy_task_today');

    }

    //建筑物升级
    function rand_shop(){
        $row = $this->table_row('zy_setting',['mkey'=>'building']);
        if($row['mvalue'] < 3){
            $row['mvalue'] = $row['mvalue']+1;
        }else{
            $row['mvalue'] = 1;
        }
        $this->table_update('zy_setting', ['mvalue'=>$row['mvalue'],'add_time'=>t_time()], ['mkey' => 'building']);
        $prize = $this->table_row('zy_prize',['type1'=>$row['mvalue'],'type2'=>$row['mkey']]);
        $this->table_update('zy_task', ['prizeid'=>$prize['id']], ['type'=>$row['mkey']]);
    }

    function init_today_task_times()
    {
        $this->db->set('task1', 0)
            ->set('task_total', 0)
            ->set('current_value1', 0)
            ->set('total_value1', 0)
            ->set('task2', 0)
            ->set('current_value2', 0)
            ->set('total_value2', 0)
            ->set('task3', 0)
            ->set('current_value3', 0)
            ->set('total_value3', 0)
            ->set('update_time', t_time())
            ->update('zy_leaf_task');

        $this->db->set('max_value', 0)
            ->update('zy_leaf');
    }

    function init_task()
    {
        $this->db->set('task1', 0)
            ->set('task1_total', 0)
            ->set('current_value1', 0)
            ->set('task2', 0)
            ->set('task2_total', 0)
            ->set('current_value2', 0)
            ->set('task3', 0)
            ->set('task3_total', 0)
            ->set('current_value3', 0)
            ->set('task4', 0)
            ->set('task4_total', 0)
            ->set('current_value4', 0)
            ->set('update_time', t_time())
            ->update('zy_task_detail');

        $this->db->set('type1', 0)
            ->set('type2', 0)
            ->update('zy_qixi');

    }

    function mid_autumn_task()
    {
        $this->db->set('task1', 0)
            ->set('task2', 0)
            ->set('skin',0)
            ->set('stuffing',0)
            ->set('update_time',t_time())
            ->update('zy_mid_autumn_task');
        $this->mid_autumn_randshop();
    }

    function mid_autumn_randshop()
    {
        $row = $this->table_row('zy_setting',['mkey'=>'mid_autumn']);
        if($row['mvalue'] < 1){
            $row['mvalue'] = $row['mvalue']+1;
        }else{
            $row['mvalue'] = 0;
        }
        $this->table_update('zy_setting', ['mvalue'=>$row['mvalue'],'add_time'=>t_time()], ['mkey' => 'mid_autumn']);
    }

    function runSign()
    {
        $this->db->set('sign', 0)
            ->set('updatetime', t_time())
            ->update('zy_coolrun_player');

    }
    function updateTrees()
    {
        $this->db->set('times', 0)
            ->set('updatetime', time())
            ->update('zy_trees_player');
    }
}
