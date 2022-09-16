<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  每日任务
 */
include_once 'Base_model.php';

class Task_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_task';
        $this->load->model('api/setting_model');
    }


    /**
     * 任务列表
     *
     * @return array
     */
    function list_all($uid)
    {

        // 查找今天开放的三个任务
        $task_today = $this->setting_model->get('task_today');

        // 查询今天的完成情况
        $today_row = $this->table_row('zy_task_today', ['uid' => $uid]);
        if($today_row['update_time'] < t_time(0,0)){
            $today_row = $this->init_today_task_times($uid);
        }

        //获取任务和奖品信息
        $list = $this->lists_sql("select a.* ,b.name,b.money,b.ledou,b.shandian,b.shop1 shopid,b.shop1_total shop_num
                                from zy_task a, zy_prize b
                                WHERE a.prizeid=b.id AND a.id in({$task_today})");
        $j = 0;
        // 合成
        foreach ($list as $key => &$value) {
            $value['finish_num'] = 0;
            $value['is_finish'] = 0;
            $value['is_recevie'] = 0;
            $value['shop'] = array();
            if($value['shopid'] && $value['shop_num']){
                $value['shop'][]['shopid'] = $value['shopid'];
                $value['shop'][0]['num'] = $value['shop_num'];
            }else{
                $value['shop'] = array();
            }

            for ($i = 1; $i <= 10; $i++) {
                if ($value['id'] == $i) {
                    if ($today_row['task' . $i]) $value['finish_num'] = $today_row['task' . $i];
                    if ($today_row['task' . $i . '_prized']) $value['is_recevie'] = 1;
                    if ($value['finish_num'] >= $value['task_num'])
                    {
                        $value['is_finish'] = 1;
                        $j ++;
                    }

                }
            }

            $task_row = $this->table_row('zy_task',['id'=>$value['id']]);
            if($task_row['type'])
            {
                if($today_row['task' .$value['id']]<$value['task_num'])
                {
                    $this->table_update('zy_task_today',[
                        'task13'=>$j,
                        'update_time'=>t_time()
                    ],
                        ['uid'=>$uid]
                    );
                }

                $value['finish_num'] = $j;
                if ($today_row['task' . $value['id'] . '_prized']) $value['is_recevie'] = 1;
                if ($value['finish_num'] >= $value['task_num']) $value['is_finish'] = 1;
            }
            unset($value['shopid']);
            unset($value['shop_num']);
        }

        return $list;
    }


    /**
     * 扫码任务列表
     *
     * @return array
     */
    function scan_list($uid)
    {

        // 查找今天开放的三个任务
        $task_today = $this->setting_model->get('scan_task_today');

        // 查询今天的完成情况
        $today_row = $this->table_row('zy_task_today', ['uid' => $uid]);
//        if($today_row['scan_updatetime'] < t_time(0,0)){
//            $today_row = $this->init_scan_today_task_times($uid);
//        }

        //获取任务和奖品信息
        $list = $this->lists_sql("select a.* ,b.name,b.money,b.ledou,b.shandian,b.shop1 shopid,b.shop1_total shop_num
                                from zy_task a, zy_prize b
                                WHERE a.prizeid=b.id AND a.id in({$task_today})");

        // 合成
        foreach ($list as $key => &$value) {
            $value['finish_num'] = 0;
            $value['is_finish'] = 0;
            $value['is_recevie'] = 0;
            $res = $this->row_sql("select a.shopid from zy_shop a,zy_setting b WHERE a.type1=? AND a.type2=? AND a.type3=b.mvalue AND b.mkey=? limit 1;", ['zhongzi',3,'suipian_type']);
            $value['shop'] = array();
            if($value['shopid'] && $value['shop_num']){
                $value['shop'][]['shopid'] = $value['shopid'];
                $value['shop'][0]['num'] = $value['shop_num'];
                if($res && $value['id']==12){
                    $value['shop'][]['shopid'] = $res['shopid'];
                    $value['shop'][1]['num'] = 1;
                }
            }else{
                $value['shop'] = array();
            }
            for ($i = 1; $i <= 13; $i++) {
                if ($value['id'] == $i) {
                    if ($today_row['task' . $i]) $value['finish_num'] = $today_row['task' . $i];
                    if ($today_row['task' . $i . '_prized']) $value['is_recevie'] = 1;
                    if ($value['finish_num'] >= $value['task_num']) $value['is_finish'] = 1;
                }
            }

            unset($value['shopid']);
            unset($value['shop_num']);
        }

        return $list;
    }

    /**
     *  领奖
     *
     */
    function get_task_prize($uid, $id)
    {
        $this->load->model('api/user_model');

        $today_row = $this->table_row('zy_task_today', ['uid' => $uid]);

        // 判断是否已领奖
        if ($today_row['task' . $id] < 1) t_error(1, '任务未完成');
        // 判断是否已领奖
        if ($today_row['task' . $id . '_prized']) t_error(2, '已领奖');

        $this->db->trans_start();
        // 更新领奖状态
        $this->table_update('zy_task_today', ['task' . $id . '_prized' => 1], ['uid' => $uid]);

        // 发放奖品
        $row = $this->row_sql("select a.prizeid,b.* from zy_task a,zy_prize b WHERE b.id=a.prizeid AND a.id=? limit 1;", [$id]);


        $is_scan = $this->row_sql("select * from zy_fragment_scan WHERE uid=? AND is_newer=0 AND type>0 AND status=0 ORDER BY add_time DESC limit 1;", [$uid]);

        if($is_scan){
            $res = $this->row_sql("select shopid from zy_shop WHERE type1=? AND type2=? AND type3='$is_scan[type]' limit 1;", ['zhongzi',3]);
        }



        // 银元、乐豆增加
        if ($row['money'] || $row['ledou']) {
            $this->user_model->money($uid, $row['money'], $row['ledou']);
            $result['money'] = $row['money'];
        }
        // 闪电
        if ($row['shandian']) {
            $this->user_model->shandian($uid, $row['shandian']);
            $result['shandian'] = $row['shandian'];
        }
        //奖励经验
        if ($row['xp']) {
            $this->user_model->xp($uid, $row['xp']);
            $result['xp'] = $row['xp'];
        }

        //奖励商品
        if ($row['shop1']) {
            if($row['type2']=='building')
            {
                model('building_model')->update_store($uid,$row['shop1'],'+'.$row['shop1_total']);
            }
            else
            {
                $this->load->model('api/store_model');
                if($res){
                    $result['shop'][]['shopid'] = $row['shop1'];
                    $result['shop'][0]['num'] = $row['shop1_total'];
                    $result['shop'][]['shopid'] = $res['shopid'];
                    $result['shop'][1]['num'] = 1;
                    $this->store_model->update_total(1, $uid, $res['shopid']);

                }else{
                    $result['shop'][]['shopid'] = $row['shop1'];
                    $result['shop'][0]['num'] = $row['shop1_total'];
                }

                $this->store_model->update_total($row['shop1_total'], $uid, $row['shop1']);
            }

        }
        //碎片
        if($row['type2'] == 'scan' && $row['type3'] == 3){
            model('fragment_model')->updatefragment($uid,$is_scan['type'],$is_scan['id']);

        }

        if($id==11 || $id==12)
        {
            $this->table_insert(
                'zy_scan_day',
                [
                    'uid' => $uid,
                    'task_id' => $id,
                    'add_time' => t_time()
                ]
            );
        }
        $this->load->model('api/fragment_model');
        $suipian = $this->fragment_model->get_fragment($uid,'task');

        if(count($suipian)>0) {
            $result['suipian'][] = $suipian;
        }else{
            $result['suipian'] = array();
        }
        //  日志
        if ($row) {
            $data = array(
                'uid' => $uid,
                'reward_type' => $row['type1'],
                'shopid' => $row['shop1'],
                'prize_id' => $row['prizeid'],
                'money' => $row['money'],
                'ledou' => $row['ledou'],
                'xp' => $row['xp'],
                'shandian' => $row['shandian'],
                'add_time' => t_time()
            );
            $this->db->insert('log_task_prize', $data);
        }
        $this->db->trans_complete();
        return $result;
    }

    function init_today_task_times($uid){

        $this->db->set('task1',0)
            ->set('task1_prized',0)
            ->set('task2',0)
            ->set('task2_prized',0)
            ->set('task3',0)
            ->set('task3_prized',0)
            ->set('task4',0)
            ->set('task4_prized',0)
            ->set('task5',0)
            ->set('task5_prized',0)
            ->set('task6',0)
            ->set('task6_prized',0)
            ->set('task7',0)
            ->set('task7_prized',0)
            ->set('task8',0)
            ->set('task8_prized',0)
            ->set('task9',0)
            ->set('task9_prized',0)
            ->set('task10',0)
            ->set('task10_prized',0)
            ->set('task13',0)
            ->set('task13_prized',0)
            ->set('update_time',t_time())
            ->where('uid',$uid)
            ->update('zy_task_today');
        return $this->table_row('zy_task_today', ['uid' => $uid]);
    }

    function init_scan_today_task_times($uid){

        $this->db->set('task11',0)
            ->set('task11_prized',0)
            ->set('task12',0)
            ->set('task12_prized',0)
            ->set('scan_updatetime',t_time())
            ->where('uid',$uid)
            ->update('zy_task_today');
        return $this->table_row('zy_task_today', ['uid' => $uid]);
    }


    // 更新今日任务进度
    function update_today($uid, $taskid)
    {
        $result = $this->db->set('task' . $taskid, 'task' . $taskid . '+1', FALSE)
            ->set('update_time', t_time())
            ->where('uid', $uid)
            ->update('zy_task_today');

        return $result;
    }


}
