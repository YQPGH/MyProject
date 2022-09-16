<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  烘烤
 */
include_once 'Base_model.php';

class Bake_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_bake';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/user_model');
    }

    /**
     *  获取烘烤室各个槽的状态
     *
     * @return int
     */
    function lists_status($uid)
    {
        $lists = $this->lists_sql("SELECT bake_index,before_shopid,after_shopid,status,start_time,stop_time
                                   FROM zy_bake
                                   WHERE uid='{$uid}'
                                   LIMIT 100;");

        return $lists;
    }

    /**
     * 开始烘烤
     *
     * @return int
     */
    function bake_start($uid, $bake_index,$before_shopid)
    {
        // 更新仓库表
        $this->db->trans_start();
        $bake_index = explode(',', $bake_index);
        $ids = explode(',', $before_shopid);
        //判断烘烤烟叶数量是否大于5片
        if (count($bake_index) > 4) t_error(1, '每次最多有4个烘烤槽');
        if (count($ids) > 4) t_error(1, '每次最多可烘烤4片烟叶');
        $is_return = model('building_model')->query_upgrade($uid,2);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        $message = array();
        foreach ($bake_index as $key=>$index) {
            // 判断烘烤室状态
            $status = $this->column_sql('status',['uid'=>$uid,'bake_index'=>$index],'zy_bake',0);
            if ($status['status'] == 0){
                //判断提交的物品是否为烟叶
                $shop = $this->shop_model->detail($ids[$key]);
                if (!empty($shop) || $shop['type1']!='yanye'){
                    $store = $this->store_model->detail($uid, $ids[$key]);
                    if ($store['total'] > 0){
                        $result = $this->store_model->update_total(-1, $uid, $ids[$key]);
                        $time = config_item('bake_time');
                        $building_time = config_item('building_jiasu');
                        if ($result){
                            $start_time = $this->sortTime($uid);
                            $this->load->model('api/fire_model');
                            $row = $this->fire_model->jiasu($uid); //加速烘烤 放火
                            //$stop_time = $row?t_time($start_time + $time[$shop['type2']]*0.8):t_time($start_time + $time[$shop['type2']]);
                            $process_time = $time[$shop['type2']];
                            if($row){
                                $process_time = $process_time*0.8;
                            }
                            $stop_time = $is_return['status']?t_time($start_time + $process_time*$building_time['bake']):t_time($start_time + $process_time);

                            $this->bake_model->update([
                                'status' => 1,
                                'before_shopid' => $ids[$key],
                                'after_shopid' => $shop['mubiao'],
                                'start_time' => t_time($start_time),
                                'stop_time' => $stop_time,
                            ], ['uid' => $uid,'bake_index'=>$index]);

                            //保存烘烤记录
                            $this->table_insert('zy_bake_record', [
                                'uid' => $uid,
                                'bake_shopid' => $ids[$key],
                                'start_time' => t_time($start_time),
                                'stop_time' => $stop_time,
                                'add_time' => time()
                            ]);

                            //添加每日任务
//                            model('task_model')->update_today($uid, 4);

                            $message[$key]['bake_index'] = $index;
                            $message[$key]['before_shopid'] = $ids[$key];
                            $message[$key]['after_shopid'] = $shop['mubiao'];
                            $message[$key]['status'] = 1;
                            $message[$key]['start_time'] = t_time($start_time);
                            $message[$key]['stop_time'] = $stop_time;

                        }else{
                            t_error(2, '你的烟叶库存不够，请稍后再来');
                        }
                    }else{
                        t_error(2, '库存不够了，请稍后再试');
                    }
                }else{
                    t_error(1, '商品或烟叶不存在');
                }
            }else{
                t_error(1, '烘烤室忙碌中，请稍后再来');
            }
        }

        // 随机发生事件 3%
        /*$user = $this->user_model->detail($uid);
        $rand_number = rand(1,100);
        if ($rand_number <= 50 && $user['game_lv'] >= 6) {
            $this->event_model->insert([
                'type1' => 2,
                'uid' => $uid,
                'title' => '烘烤室发生事件，请尽快处理。',
                'add_time' => $now_time,
            ]);
        }*/

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '更新失败，系统繁忙请稍后再来');

        return $message;
    }

    /**
     * 计算烘烤排队时间
     */
    public function sortTime($uid){
        $result = $this->db->query("select stop_time from zy_bake WHERE uid='$uid' AND status=1 ORDER BY stop_time DESC limit 1")->row_array();
        if(empty($result) || strtotime($result['stop_time']) < time()){
            $time = time();
        }else{
            $time = strtotime($result['stop_time']) + 1;
        }
        return $time;
    }

    public function bake_gather($uid, $bake_index){
        $this->db->trans_start();
        $bake_index = explode(',', $bake_index);
        if (count($bake_index) > 4) t_error(1, '每次最多有4个烘烤槽');
        $is_return = model('building_model')->query_upgrade($uid,2);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
//        $arr = [];
        $return_arr['success'] = array();
        $return_arr['false'] = array();
        $return_arr['reduce'] = array();

        foreach ($bake_index as $key=>$index) {
            // 判断烘烤室状态
            $query = $this->column_sql('after_shopid,stop_time',['uid'=>$uid,'bake_index'=>$index],'zy_bake',0);
            if($query['after_shopid']&&$query['stop_time']){
                $time = strtotime($query['stop_time']);
                if($time <= time()){

//                    isset($arr[$query['after_shopid']]) ? $arr[$query['after_shopid']]++ : $arr[$query['after_shopid']] =1 ;
                    $after_shopid = $this->fire_gather($uid,$query);
                    if($after_shopid['status']==1){
                        unset($after_shopid['status']);
                        $return_arr['false'][] = $after_shopid;
                    }else if($after_shopid['status']==2){
                        unset($after_shopid['status']);
                        $return_arr['reduce'][] = $after_shopid;
                    }else{
                        $return_arr['success'][] = $after_shopid;
                    }
//                    $this->load->model('api/fragment_model');
//                    $suipian = $this->fragment_model->get_fragment($uid,'bake');


//                    $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                    $this->bake_model->update([
                        'status' => 0,
                        'before_shopid' => 0,
                        'after_shopid' => 0,
                        'start_time' => 0,
                        'stop_time' => 0,
                    ], ['uid' => $uid,'bake_index'=>$index]);

                    //$store = $this->store_model->detail($uid, $query['after_shopid']);
                    //$result[$key]['shopid'] = $query['after_shopid'];
                    //$result[$key]['num'] = $store['total'];
                    //根据烟叶shopid获取其等级
                    // 经验值增加
                    $xp = 0;
                    $type2 = $this->column_sql('type2',['shopid'=>$after_shopid['after_shopid']],'zy_shop',0);
                    if($type2){
                        $config_xp = config_item('bake_xp');
                        $xp = $config_xp[$type2['type2']];
                    }
                    $this->user_model->xp($uid, $xp);
                    //添加每日任务
                    model('task_model')->update_today($uid, 4);
//                    model('coolrun_model')->update_total($uid,1);
//                    model('nationalday_model')->update_num($uid); //国庆期间任务
                    model('leaf_model')->update_task_value($uid);//叠烟叶每日任务
                    model('energytrees_model')->updateTotal($uid,4);
                    $suipian = model('midautumn_model')->update_total($uid);
                    if(count($suipian)>0){
                        $return_arr['suipian'][] = $suipian;;
                    }

                }

            }

        }
        if(empty($return_arr['suipian'])){
            $return_arr['suipian'] = array();
          }

        $data = ['uid' => $uid];
        $this->load->model('api/turntable_model');
        $return_arr['draws_times'] = $this->turntable_model->is_activity($data,'bake');

        $this->db->trans_complete();
//        $result = array();
//        $i = 0;
//        foreach($arr as $k=>$v){
//            $result[$i]['shopid'] = $k;
//            $result[$i]['num'] = $v;
//            $i++;
//        }
//        return $result;

        return $return_arr;
    }

    // 被放火后入库，烟叶可能降级
    function fire_gather($uid, $query)
    {
        $sql = "select * from zy_fire WHERE friend_uid=?   AND  status=? ORDER BY id DESC";
        $row = $this->db->query($sql,[$uid,0])->row_array();

            if(empty($row)){
                $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                $result['after_shopid'] = $query['after_shopid'];
                return $result;
            }
        // 判断是损坏烟叶,等级
            if ($row && $query['after_shopid'] < 430 && $row['destroy_time']<$query['stop_time'] && $row['stop_time']>$query['stop_time']) {

                $rand_num = rand(1, 20); //降级、破坏烟叶 几率
                if ($query['after_shopid'] < 410 &&  $rand_num == 1) { // 1星
                    $sql = "select uid,id from zy_bake_record WHERE uid=?  AND status=? AND bake_shopid<? ORDER BY id DESC ";
                    $id = $this->db->query($sql,[$uid,0,310])->row_array();
                    $this->table_update('zy_fire',['status' => 1],['friend_uid' =>$uid, 'id' => $row['id']]);
                    $this->table_update('zy_bake_record', ['fire_id' => $row['id'],'status'=>1],['uid' =>$uid, 'id' => $id['id']]);
                    $this->store_model->update_total(0, $uid, $query['after_shopid'],1);
                    $result['after_shopid'] = $query['after_shopid'];
                    $result['status'] = 1;
                    return $result;
                }else if ($query['after_shopid'] > 410 && $query['after_shopid'] < 420 &&  $rand_num == 2) {// 2星

                    $sql = "select uid,id from zy_bake_record WHERE uid=?  AND status=? AND bake_shopid<? AND bake_shopid>? ORDER BY id DESC ";
                    $id = $this->db->query($sql,[$uid,0,320,310])->row_array();

                    $this->table_update('zy_fire',['status' => 1],['friend_uid' =>$uid, 'id' => $row['id']]);
                    $this->table_update('zy_bake_record', ['fire_id' => $row['id'],'status'=>2],['uid' =>$uid, 'id' => $id['id']]);
                    $this->store_model->update_total(1, $uid, $query['after_shopid']-10,1);
                    $result['after_shopid'] = $query['after_shopid']-10;
                    $result['status'] = 2;
                    return $result;
                }else if ($query['after_shopid'] > 420 && $query['after_shopid'] < 430  &&  $rand_num == 3) {// 3星
                    $sql = "select uid,id from zy_bake_record WHERE uid=?  AND status=? AND bake_shopid<? AND bake_shopid>? ORDER BY id DESC ";
                    $id = $this->db->query($sql,[$uid,0,330,320])->row_array();
                    $this->table_update('zy_fire',['status' => 1],['friend_uid' =>$uid, 'id' => $row['id']]);
                    $this->table_update('zy_bake_record', ['fire_id' => $row['id'],'status'=>2],['uid' =>$uid, 'id' => $id['id']]);
                    $this->store_model->update_total(1, $uid, $query['after_shopid']-10,1);
                    $result['after_shopid'] = $query['after_shopid']-10;
                    $result['status'] = 2;
                    return $result;
                }else{
                    $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                    $result['after_shopid'] = $query['after_shopid'];
                    return $result;

                }
        }else{
                $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                $result['after_shopid'] = $query['after_shopid'];
                return $result;
            }

    }


    public function bake_jiasu($uid, $bake_index){
        $this->db->trans_start();
        $bake_index = explode(',', $bake_index);
        if (count($bake_index) > 4) t_error(1, '每次最多有4个烘烤槽');
        $is_return = model('building_model')->query_upgrade($uid,2);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //获取指引步骤
        $guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();

        $time = t_time();
        foreach($bake_index as $key=>$value){
            $status = $this->table_row('zy_bake', ['uid'=>$uid,'bake_index'=>$value]);
            if(!$status['before_shopid'] || !$status['after_shopid']) t_error(9, '加速失败！');
            $user = $this->user_model->detail($uid);
            // 判断所需闪电
            if($guid_step_row['step1']!=5 || $guid_step_row['step2']!=1){
                $number = count_shandian(strtotime($status['stop_time'])-time());
                if ($number > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');
                // 扣除闪电
                $this->user_model->shandian($uid, -$number);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 15,
                    'shandian' => -$number,
                ]);
            }
            //更新状态表
            $this->table_update('zy_bake', ['stop_time' => $time],['uid'=>$uid,'bake_index'=>$value]);

            $res = $this->db->query("select bake_index,start_time,stop_time from zy_bake where uid='$uid' AND status!=0 AND stop_time>'$time' order by start_time ASC")->result_array();
            //print_r($res);
            if(!empty($res)){
                foreach($res as $k=>&$v){
                    if($k==0){
                        //判断第一个是否已经开始
                        if(strtotime($v['start_time']) > strtotime($time)){
                            //计算时间段
                            $temp_all_time = strtotime($v['stop_time']) - strtotime($v['start_time']);
                            $v['start_time'] = t_time(strtotime($time)+1);
                            $v['stop_time'] = t_time(strtotime($v['start_time'])+$temp_all_time);
                        }
                    }else{
                        //计算时间段
                        $temp_all_time = strtotime($v['stop_time']) - strtotime($v['start_time']);
                        $v['start_time'] = t_time(strtotime($res[$k-1]['stop_time'])+1);
                        $v['stop_time'] = t_time(strtotime($v['start_time'])+$temp_all_time);
                    }

                }
                foreach($res as $kk=>$vv){
                    $this->table_update('zy_bake', ['start_time'=>$vv['start_time'],'stop_time' =>$vv['stop_time']],['uid'=>$uid,'bake_index'=>$vv['bake_index']]);
                }
            }

        }
        $this->db->trans_complete();

        $result = $this->db->query("select bake_index,start_time,stop_time from zy_bake where uid='$uid' AND status!=0 AND stop_time>'$time' order by start_time ASC")->result_array();
        return $result;
    }


}
