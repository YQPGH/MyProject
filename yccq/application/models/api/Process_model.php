<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  加工包装
 */
include_once 'Base_model.php';

class Process_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_process';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/yanye_model');
        $this->load->model('api/status_model');
    }

    /**
     *  获取烘烤室各个槽的状态
     *
     * @return int
     */
    function lists_status($uid)
    {
//        $lists = $this->lists_sql("SELECT process_index,before_shopid,after_shopid,status,start_time,stop_time
//                                   FROM zy_process
//                                   WHERE uid='{$uid}'
//                                   LIMIT 100;");
        $sql = "SELECT process_index,before_shopid,after_shopid,status,start_time,stop_time
                                   FROM zy_process
                                   WHERE uid=?
                                   LIMIT 100;";
        $lists = $this->db->query($sql, array($uid))->result_array();
        return $lists;
    }

    /**
     * 开始加工
     *
     * @return int
     */
    function process_start($uid, $process_index,$before_shopid)
    {
        // 更新仓库表
        $this->db->trans_start();
        $process_index = explode(',', $process_index);
        $ids = explode(',', $before_shopid);
        //判断烘烤烟叶数量是否大于5片
        if (count($process_index) > 3) t_error(1, '每次最多有3个加工槽');
        if (count($ids) > 3) t_error(1, '每次最多可加工3个配方');
        $is_return = model('building_model')->query_upgrade($uid,8);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');

        //校验用户租赁的机器是否和加工槽匹配
        $time = t_time();
//        $zulin = $this->db->query("select number from zy_zulin WHERE uid='$uid' AND stop_time > '$time' ORDER BY number DESC limit 1")->row_array();
          $sql = "select number from zy_zulin WHERE uid=? AND stop_time > ? ORDER BY number DESC limit 1";
        $zulin = $this->db->query($sql, array($uid,$time))->row_array();
        if($zulin['number'] < count($process_index)) t_error(1, '加工机器等级不够！');
        $message = array();
        $start_time = time();
        foreach ($process_index as $key=>$index) {
            // 判断加工厂状态
            $status = $this->column_sql('status',['uid'=>$uid,'process_index'=>$index],'zy_process',0);
            if(!$status) t_error(9,'加工厂不存在');
            if ($status['status'] == 0){
                //判断提交的物品是否为配方书
                $shop = $this->shop_model->detail($ids[$key]);
                if (!empty($shop) || $shop['type1']!='peifang'){
                    $store = $this->store_model->detail($uid, $ids[$key]);
                    if ($store['total'] > 0){
                        $result = $this->store_model->update_total(-1, $uid, $ids[$key]);

                        if ($shop['zhuliao_count']) {
                            $zhuliao = $this->store_model->detail($uid, $shop['zhuliao_id']);
                            if ($shop['zhuliao_count'] > $zhuliao['total']) t_error(3, '主料不够，请稍后再试');
                            $this->store_model->update_total(-$shop['zhuliao_count'], $uid, $shop['zhuliao_id']);
                        }
                        if ($shop['fuliao1_count']) {
                            $fuliao1 = $this->store_model->detail($uid, $shop['fuliao1_id']);
                            if ($shop['fuliao1_count'] > $fuliao1['total']) t_error(3, '辅料1不够，请稍后再试');
                            $this->store_model->update_total(-$shop['fuliao1_count'], $uid, $shop['fuliao1_id']);
                        }
                        if ($shop['fuliao2_count']) {
                            $fuliao2 = $this->store_model->detail($uid, $shop['fuliao2_id']);
                            if ($shop['fuliao2_count'] > $fuliao2['total']) t_error(3, '辅料2不够，请稍后再试');
                            $this->store_model->update_total(-$shop['fuliao2_count'], $uid, $shop['fuliao2_id']);
                        }
                        if ($shop['lvzui_count']) {
                            $lvzui = $this->store_model->detail($uid, $shop['lvzui_id']);
                            if ($shop['lvzui_count'] > $lvzui['total']) t_error(3, '滤嘴不够，请稍后再试');
                            $this->store_model->update_total(-$shop['lvzui_count'], $uid, $shop['lvzui_id']);
                        }
                        //根据成就计算缩减时间
//                        $zhiyan_lv = $this->db->query("select zhiyan_lv from zy_user WHERE uid='$uid'")->row_array();
                        $sql = "select zhiyan_lv from zy_user WHERE uid=?";
                        $zhiyan_lv  = $this->db->query($sql, array($uid))->row_array();
                        $zhiyan_type = config_item('zhiyan_type');
                        $jian_time = 1;
                        if($zhiyan_lv['zhiyan_lv']){
                            $jian_time = $zhiyan_type[$zhiyan_lv['zhiyan_lv']]['jian_time'];
                        }
                        $time = config_item('process_time');
                        $building_time = config_item('building_jiasu');
                        $process_time = $is_return['status']?($time[$shop['type2']]*$jian_time*$building_time['zhiyan']):($time[$shop['type2']]*$jian_time);
                        if ($result){
                            $stop_time = t_time($start_time + $process_time);
                            $this->process_model->update([
                                'status' => 1,
                                'before_shopid' => $ids[$key],
                                'after_shopid' => $shop['mubiao'],
                                'start_time' => t_time($start_time),
                                'stop_time' => $stop_time,
                            ], ['uid' => $uid,'process_index'=>$index]);

                            //保存加工记录
                            $this->table_insert('zy_process_record', [
                                'uid' => $uid,
                                'process_shopid' => $ids[$key],
                                'start_time' => t_time($start_time),
                                'stop_time' => $stop_time,
                                'add_time' => time()
                            ]);

                            //添加每日任务
                            //model('task_model')->update_today($uid, 5);
                            $message[$key]['process_index'] = $index;
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
                t_error(1, '加工槽忙碌中，请稍后再来');
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '更新失败，系统繁忙请稍后再来');

        return $message;
    }

    public function process_gather($uid, $process_index){
        $this->db->trans_start();
        $process_index = explode(',', $process_index);
        if (count($process_index) > 3) t_error(1, '每次最多有3个加工槽');
        $is_return = model('building_model')->query_upgrade($uid,8);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $result = array();
        foreach ($process_index as $key=>$index) {
            // 判断加工状态
            $query = $this->column_sql('after_shopid,stop_time',['uid'=>$uid,'process_index'=>$index],'zy_process',0);
            if($query['after_shopid']&&$query['stop_time']){
                $time = strtotime($query['stop_time']);
                if($time <= time()){
                    $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                    $this->process_model->update([
                        'status' => 0,
                        'before_shopid' => 0,
                        'after_shopid' => 0,
                        'start_time' => 0,
                        'stop_time' => 0,
                    ], ['uid' => $uid,'process_index'=>$index]);
                    $store = $this->store_model->detail($uid, $query['after_shopid']);
                    $val['after_shopid'] = $query['after_shopid'];
                    $val['num'] = $store['total'];;
                    $result['list'][] = $val;
//                    $result[$key]['shopid'] = $query['after_shopid'];
//                    $result[$key]['num'] = $store['total'];
//                    $this->load->model('api/fragment_model');
//                    $suipian = $this->fragment_model->get_fragment($uid,'zhiyan');


                    // 经验值增加
                    $xp = 0;
                    $type2 = $this->column_sql('type2',['shopid'=>$query['after_shopid']],'zy_shop',0);
                    if($type2){
                        $config_xp = config_item('process_xp');
                        $xp = $config_xp[$type2['type2']];
                    }
                    $this->user_model->xp($uid, $xp);
                    //添加每日任务
                    model('task_model')->update_today($uid, 2);
                    model('energytrees_model')->updateTotal($uid,4);
//                    model('coolrun_model')->update_total($uid,1);
//                    添加叠烟叶每日任务
                    model('leaf_model')->update_task_value($uid);
                    //增加制烟熟练度
                    $this->user_model->zhiyan_achieve($uid);
                    //增加积分
                    //$this->addJiFenYan($uid,$type2['type2']);
//                    model('nationalday_model')->update_num($uid); //国庆期间任务
                    $suipian = model('midautumn_model')->update_total($uid);
                    if(count($suipian)>0){
                        $result['suipian'][] = $suipian;
                    }
                }
            }

        }
        if(empty($result['suipian'])){
            $result['suipian'] = array();
        }
        $data = ['uid' => $uid];
        $this->load->model('api/turntable_model');
        $result['draws_times'] = $this->turntable_model->is_activity($data,'process');

        $this->db->trans_complete();

        return $result;
    }

    /**
     *  增加积分
     *  type 烟叶等级
     *  @return array
     */
    function addJiFenYan($uid,$type)
    {
        $yan_jifen = config_item('yan_jifen');
        $jifen = $yan_jifen[$type] ? $yan_jifen[$type] : 0;
        //查询制烟积分表是否存在对应的记录
//        $row = $this->db->query("select id from zy_zhiyan_jifen WHERE uid='$uid'")->row_array();
        $sql = "select id from zy_zhiyan_jifen WHERE uid=?";
        $row  = $this->db->query($sql, array($uid))->row_array();
//        $mrow = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        $get_sql = "select mvalue from zy_setting WHERE mkey=?";
        $mrow  = $this->db->query($get_sql, array('week_num'))->row_array();
        $colum_1 = "jifen_".$mrow['mvalue'];
        $colum_2 = "update_time_".$mrow['mvalue'];
        if($row['id']){
            $this->db->set("$colum_1", "$colum_1+" . $jifen, FALSE);
            $this->db->set("$colum_2", t_time());
            $this->db->where('id', $row['id']);
            $this->db->update('zy_zhiyan_jifen');
        }else{
            $this->table_insert('zy_zhiyan_jifen', [
                "uid" => $uid,
                "$colum_1" => $jifen,    //获得的积分
                "$colum_2" => t_time()
            ]);
        }
    }



    /*function addJiFenYan($uid,$type){
        $yan_jifen = config_item('yan_jifen');
        $this->table_insert('zy_yan_jifen', [
            'uid' => $uid,
            'type' => $type,               //烟等级
            'jf' => $yan_jifen[$type],    //获得的积分
            'add_time' => t_time()
        ]);
    }*/

    public function process_jiasu($uid, $process_index){
        $this->db->trans_start();
        $process_index = explode(',', $process_index);
        if (count($process_index) > 3) t_error(1, '每次最多有6个加工槽');
        $is_return = model('building_model')->query_upgrade($uid,8);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //获取指引步骤
//        $guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();
            $sql = "select step1,step2 from zy_guide where uid=?";
         $guid_step_row  = $this->db->query($sql, array($uid))->row_array();
        // 扣除闪电

        foreach($process_index as $key=>$value){
            $status = $this->table_row('zy_process', ['uid'=>$uid,'process_index'=>$value]);
            if(!$status['before_shopid'] || !$status['after_shopid']) t_error(9, '加速失败！');
            $user = $this->user_model->detail($uid);
            // 根据烟叶数目判断所需闪电
            if($guid_step_row['step1']!=7 || $guid_step_row['step2']!=2){
                $number = count_shandian(strtotime($status['stop_time'])-time());
                if ($number > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');
                $this->user_model->shandian($uid, -$number);
            }
            //更新状态表
            $this->table_update('zy_process', ['stop_time' => t_time()],['uid'=>$uid,'process_index'=>$value]);
            // 写入交易日志表
            model('log_model')->trade($uid, [
                'spend_type' => 17,
                'shandian' => -$number,
            ]);


        }

        $this->db->trans_complete();
    }



}
