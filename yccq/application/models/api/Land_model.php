<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  土地 种植采集
 */
include_once 'Base_model.php';

class Land_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_land';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/user_model');
    }

    /**
     *  获取我的土地状态
     *
     * @return int
     */
    function lists_status($uid)
    {
        //$lists = $this->lists_sql("SELECT * FROM zy_land WHERE uid='{$uid}' LIMIT 100;");
        $sql = "SELECT * FROM zy_land WHERE uid=? LIMIT 100";
        $lists = $this->db->query($sql,[$uid])->result_array();
        //$lists = $this->shop_model->append_list($lists);

        // 增加虫灾事件
        $this->load->model('api/event_model');
        $lists = $this->event_model->append_list($lists);

        return $lists;
    }

    /**
     *  开始种植, 返回当前土地块种植信息
     *
     * @return int
     */
    function seed($uid, $land_id, $seed_shopid)
    {


        // 先判断用户和空闲code信息错误
        $land = $this->row($land_id);

        if ($land['uid'] != $uid) t_error(1, '用户信息错误，请稍后再试');
        if ($land['status'] != 0) t_error(2, '土地已种植，请稍后再试');

        $seed_store = $this->store_model->detail($uid, $seed_shopid);

        if (!$seed_store || $seed_store['type1'] != 'zhongzi' || $seed_store['total'] < 1)
            t_error(3, '您的种子库存不够了，请稍后再试');

        // 根据烟农等级计算 结束时间
        //$yannong_lv = $this->db->query("select yannong_lv from zy_user WHERE uid='$uid'")->row_array();
        $sql = "select yannong_lv from zy_user WHERE uid=?";
        $yannong_lv = $this->db->query($sql,[$uid])->row_array();
        $yannong_type = config_item('yannong_type');
        $jian_time = 1;

        if ($yannong_lv['yannong_lv']) {
            $jian_time = $yannong_type[$yannong_lv['yannong_lv']]['jian_time'];
        }
        //根据土地等级，计算缩减种植时间
        $land_shop = $this->shop_model->detail($land['land_shopid']);
        $seed_shop = $this->shop_model->detail($seed_shopid);
        $now = time();
        $start_time = t_time($now);
        $stop_time = t_time($now + ($seed_shop['work_time'] * ($land_shop['work_time'] / 100)) * $jian_time);

        //更新土地表
        $data = [
            'seed_shopid' => $seed_shopid, // 烟叶商品id
            'yanye_shopid' => $seed_shop['mubiao'], // 烟叶商品id
            'status' => 1,
            'start_time' => $start_time,
            'stop_time' => $stop_time,
        ];
        $this->db->trans_start();
        $affected_rows = $this->update($data, $land_id);
        $this->store_model->update_total(-1, $uid, $seed_shopid); // 更新仓库
        $this->user_model->yannong_achieve($uid, 1); //增加种植熟练度

        //保存种植记录
        $this->table_insert('zy_seed_record', [
            'seed_shopid' => $seed_shopid, // 烟叶商品id
            'yanye_shopid' => $seed_shop['mubiao'], // 烟叶商品id
            'land_shopid' => $land_id,
            'uid' => $uid,
            'start_time' => $start_time,
            'stop_time' => $stop_time,
            'add_time' => t_time()
        ]);
        // 增加虫灾事件
        $this->load->model('api/event_land_model');
        $result['event_land'] = $this->event_land_model->start($this->uid, $land_id);

        // 经验值增加

        $this->user_model->xp($uid, 3);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '种植失败，系统繁忙请稍后再来');
        $land_row = $this->row($land_id);
        $land_row['event_land'] = $result['event_land'];
        $land_row['s_time'] = $stop_time-$start_time;
        return $land_row;
    }


    /**
     *  一键采集
     *
     * @return int
     */
    function yi_jian_gather($uid)
    {
        //获取用户所有成熟的种子
        $now = t_time();
        $where = array('uid =' => $uid, 'status =' => 1, 'stop_time <=' => $now);
        $lands = $this->lists('id,yanye_shopid', $where, 'id', 100);

        $return_arr['success'] = array();
        $return_arr['false'] = array();
        $return_arr['suipian'] = array();
        $return_arr['trees_number'] = array();
        foreach ($lands as &$value) {
            $temp = $this->gather($uid, $value['id']);

            if(count($temp['trees_number'])>0)
            {
                foreach($temp['trees_number'] as $val)
                {
                    $return_arr['trees_number'][] =  $val;
                }
            }
            if(count($temp['suipian'])>0){
                $return_arr['suipian'][] = $temp['suipian'];
            }
            unset($temp['suipian'],$temp['trees_number']);

            if($temp['is_use']){
                unset($temp['is_use']);
                $return_arr['false'][] = $temp;
            }else{
                unset($temp['is_use']);
                $return_arr['success'][] = $temp;
            }
        }
        if(empty($return_arr['suipian'])){
            $return_arr['suipian'] = array();
        }

        return $return_arr;
    }

    /**
     *  采集
     *
     * @return 返回烟叶商品id
     */
    function gather($uid, $land_id)
    {
        // 判断用户
        $land = $this->row($land_id);

        if ($land['uid'] != $uid) t_error(1, '用户信息错误，请稍后再试');
        // 判断烟叶是否成熟
        if ($land['status'] == 0) t_error(2, '土地空闲没有烟叶可收');
        if ($land['stop_time'] > t_time()) t_error(3, '烟叶尚未成熟，请稍后再试');


        $this->db->trans_start();
        // 土地清空
        $data = [
            'seed_shopid' => 0,
            'yanye_shopid' => 0,
            'status' => 0,
            'start_time' => 0,
            'stop_time' => 0,
        ];
        $affected_rows = $this->update($data, $land_id);

        if (!$affected_rows) t_error(4, '采集烟叶出问题了，请稍后再试');

        // 计算虫灾旱灾后入库，烟叶可能损失
        $yanye_shopid = $this->event_gather($uid, $land);

        $is_use = $this->has_chongzi($uid,$land);

        //保存采摘记录
        $xp = 0;
        //根据烟叶shopid获取其等级
        $type2 = $this->column_sql('type2',['shopid'=>$yanye_shopid],'zy_shop',0);
      if($yanye_shopid && $is_use==0) {

            $insert_id = $this->table_insert('zy_gather_record', [
                'yanye_shopid' => $yanye_shopid, // 烟叶商品id
                'uid' => $uid,
                'add_time' => t_time()
            ]);

            if($type2){
                //增加经验
                $config_xp = config_item('gather_xp');
                $xp = $config_xp[$type2['type2']];
                //保存积分记录
                $this->addJiFenYanYe($uid,$insert_id,$type2['type2']);
                //增加积分
//                $this->addZzJiFen($uid,$type2['type2']);
            }
        }
        // 经验值增加
        $this->user_model->xp($uid, $xp);

        //$this->user_model->yannong_achieve($uid,2); //增加种植熟练度

        //添加每日任务
        model('task_model')->update_today($uid, 1);
//        model('nationalday_model')->update_num($uid); //国庆期间任务
        model('leaf_model')->update_task_value($uid);//叠烟叶每日任务

        $trees  = model('energytrees_model')->updateEnergy($uid,$type2);

//        $this->load->model('api/fragment_model');
//        $status = $this->fragment_model->get_fragment($uid,'plant');
        $status = model('midautumn_model')->update_total($uid);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(99, '更新失败，系统繁忙请稍后再来');
        $return_arr = array(
            'id'=>$land_id,
            'yanye_shopid'=>$yanye_shopid,
            'is_use'=>$is_use,
            'suipian'=>$status
        );

        $return_arr['trees_number'] = $trees['number'];

        return $return_arr;

    }

    //判断是否有害虫
    function has_chongzi($uid,$land){

        $number = config_item('use_number');
        $sql = "select count(*) as num,id,use_number from chongzi_send WHERE friend_uid=? AND UNIX_TIMESTAMP(stop_time)>? AND UNIX_TIMESTAMP(destroy_time)<?";
        $row = $this->db->query($sql,[$uid,strtotime($land['stop_time']),strtotime($land['stop_time'])])->row_array();
        //四星烟叶以下
        if($row['num'] && $land['yanye_shopid']<331 && $row['use_number']<$number){

            $sql = "select yanye_shopid,land_shopid,id,is_use from zy_seed_record WHERE uid=? AND land_shopid=? and seed_shopid=? and yanye_shopid=? ORDER by id DESC ";
            $res = $this->db->query($sql,[$uid,$land['id'],$land['seed_shopid'],$land['yanye_shopid']])->row_array();

            if($res){
                $this->table_update('zy_seed_record',
                    ['is_use' => 1,'send_id'=>$row['id']],
                    [
                        'uid'=>$uid,
                        'land_shopid'=>$res['land_shopid'],
                        'id'=> $res['id']
                    ]);

                $this->db->set('use_number','use_number+1', FALSE);
                $this->db->where('id',  $row['id']);
                $this->db->update('chongzi_send');
                $this->db->affected_rows();
                return 1;
            }
        }else{
            return 0;
        }
    }

    // 计算虫灾旱灾后入库，烟叶可能降级
    function event_gather($uid, $land)
    {
        $this->load->model('api/event_model');
        $event = $this->event_model->row(['uid'=>$uid, 'land_id' => $land['id']]);
        // 判断是否处理,  烟叶入库
        if(empty($event)) {
            $affected_rows = $this->store_model->update_total(+1, $uid, $land['yanye_shopid'],1);
            if (!$affected_rows) t_error(5, '采集烟叶出问题了，请稍后再试');
            return $land['yanye_shopid'];
        }

        // 删除事件记录
        $this->event_model->delete(['uid'=>$uid, 'land_id' => $land['id']]);

        // 判断等级
        if ($land['yanye_shopid'] < 331) {

            if ($land['yanye_shopid'] < 310 && rand(1, 10) > 7) { // 1星
                return 0;
            }
            // 2星
            if ($land['yanye_shopid'] > 310 && $land['yanye_shopid'] < 320 && rand(1, 10) > 8) {
                // 烟叶入库
                $affected_rows = $this->store_model->update_total(+1, $uid, $land['yanye_shopid'] - 10,1);
                if (!$affected_rows) t_error(5, '采集烟叶出问题了，请稍后再试');
                return $land['yanye_shopid'] - 10;
            }
            // 3星
            if ($land['yanye_shopid'] > 320 && $land['yanye_shopid'] < 330 && rand(1, 10) > 9) {
                // 烟叶入库
                $affected_rows = $this->store_model->update_total(+1, $uid, $land['yanye_shopid'] - 10,1);
                if (!$affected_rows) t_error(5, '采集烟叶出问题了，请稍后再试');
                return $land['yanye_shopid'] - 10;
            }
        }

        return $land['yanye_shopid'];
    }

    /**
     *  增加积分
     *  type 烟叶等级
     *  @return array
     */
    function addZzJiFen($uid,$type){
        $yanye_jifen = config_item('yanye_jifen');
        $jifen = $yanye_jifen[$type] ? $yanye_jifen[$type] : 0;
        //查询种植积分表是否存在对应的记录
        //$row = $this->db->query("select id from zy_zhhongzhi_jifen WHERE uid='$uid'")->row_array();
        $sql = "select id from zy_zhhongzhi_jifen WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        $mrow = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        $colum_1 = "jifen_".$mrow['mvalue'];
        $colum_2 = "update_time_".$mrow['mvalue'];
        if($row['id']){
            $this->db->set("$colum_1", "$colum_1+" . $jifen, FALSE);
            $this->db->set("$colum_2", t_time());
            $this->db->where('id', $row['id']);
            $this->db->update('zy_zhhongzhi_jifen');
        }else{
            $this->table_insert('zy_zhhongzhi_jifen', [
                "uid" => $uid,
                "$colum_1" => $jifen,    //获得的积分
                "$colum_2" => t_time()
            ]);
        }

    }


    /**
     *  保存积分记录
     *  type 烟叶等级
     *  @return array
     */
    function addJiFenYanYe($uid,$gid,$type){
        $yanye_jifen = config_item('yanye_jifen');
        $this->table_insert('zy_yanye_jifen', [
            'uid' => $uid,
            'gid' => $gid,                  //收获烟叶记录id，zy_gather_record对应的记录id
            'type' => $type,                //烟叶等级
            'jf' => $yanye_jifen[$type],    //获得的积分
            'add_time' => t_time()
        ]);
    }



    /**
     *  升级土地
     *
     * @return array
     */
    function upgrade($uid, $land_id)
    {
        $land = $this->row($land_id);
        if ($land['uid'] != $uid) t_error(1, '用户信息错误，请稍后再试');
        if ($land['status'] != 0) t_error(1, '请土地空闲时再来升级');
        $user = $this->user_model->detail($uid);
        $shop = $this->shop_model->detail($land['land_shopid']);
        if ($shop['type2'] == '3') t_error(2, '高级土地不能再升级了');

        if ($shop['type2'] == '1') {
            // 判断用户银元和乐豆是否足够
            if ($user['money'] < $shop['upgrade_money']) t_error(2, '你的银元不足，请稍后再试');

            // 更新仓库
            $this->store_model->update_total(-1, $uid, $shop['shopid']);
            $this->store_model->update_total(+1, $uid, $shop['upgrade_id'],1);
            // 更新土地表
            $this->update([
                'land_shopid' => $shop['upgrade_id'],
            ], $land_id);

            //保存升级记录
            $this->table_insert('zy_land_upgrade_record', [
                'uid' => $uid,
                'before_land' => '初级土地',
                'after_land' => '中级土地',
                'add_time' => t_time()
            ]);

            // 扣除用户银元
            $this->user_model->money($uid, -$shop['upgrade_money'], -$shop['upgrade_ledou']);

            // 写入交易日志表
            model('log_model')->trade($uid, [
                'spend_type' => 18,
                'money' => -$shop['upgrade_money'],
                'ledou' => -$shop['upgrade_ledou']
            ]);

            return $shop['upgrade_id'];
        }

        if ($shop['type2'] == '2') {
            // 判断用户银元和乐豆是否足够
            if ($user['money'] < $shop['upgrade_money']) t_error(2, '你的银元不足，请稍后再试');
            // 更新仓库
            $this->store_model->update_total(-1, $uid, $shop['shopid']);
            $this->store_model->update_total(+1, $uid, $shop['upgrade_id'],1);
            // 更新用户土地商品id
            $this->update(['land_shopid' => $shop['upgrade_id']], $land_id);

            //保存升级记录
            $this->table_insert('zy_land_upgrade_record', [
                'uid' => $uid,
                'before_land' => '中级土地',
                'after_land' => '高级土地',
                'add_time' => t_time()
            ]);

            // 扣除用户银元
            $this->user_model->money($uid, -$shop['upgrade_money'], -$shop['upgrade_ledou']);

            // 写入交易日志表
            model('log_model')->trade($uid, [
                'spend_type' => 18,
                'money' => -$shop['upgrade_money'],
                'ledou' => -$shop['upgrade_ledou']
            ]);

            return $shop['upgrade_id'];
        }

    }

    // 添加土地  返回本次购买的土地id
    function add($uid, $shopid, $total)
    {
        // 判断土地上限 20块
        $user_total = $this->count(['uid' => $uid]);
        if (($user_total) >= 20) t_error(4, '土地上限为20块，不能再购买');
        
        $new_ids = [];
        for ($i = 1; $i <= $total; $i++) {
            $new_ids[] = $this->insert([
                'uid' => $uid,
                'land_shopid' => $shopid,
                'add_time' => t_time(),
            ]);
        }
        return ['land_id' => join(',', $new_ids)];
    }

    function my_delete($uid, $land_id)
    {
        $data = array('seed_shopid' => 0, 'yanye_shopid' => 0, 'status' => 0, 'start_time' => '0000-00-00 00:00:00', 'stop_time' => '0000-00-00 00:00:00');
        $res = $this->update($data, array('id' => $land_id, 'uid' => $uid));
        return $res;
    }

    function seed_jiasu($uid, $land_id)
    {
        $land_row = $this->row(['uid'=>$uid, 'id'=>$land_id]);
        if(empty($land_row) || $land_row['status']==0) t_error(1, '土地为空');
        if($land_row['status'] > 0 && t_time() > $land_row['stop_time']) t_error(2, '烟叶已经成熟了');
        //获取指引步骤
        //$guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();
        $sql = "select step1,step2 from zy_guide where uid=?";
        $guid_step_row = $this->db->query($sql,[$uid])->row_array();
        //根据种子id获取对应的等级
        $number = count_shandian(strtotime($land_row['stop_time'])-time());
        $user = $this->user_model->detail($uid);
        if ($number > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');

        //事务开始
        $this->db->trans_start();

        //扣除闪电
        if($guid_step_row['step1']!=4 || $guid_step_row['step2']!=1){
            $this->user_model->shandian($uid, -$number);//消耗闪电
        }
        //更新状态表（zy_land）
        $data = array(
            'stop_time' => t_time(),
        );
        $this->db->where('id', $land_id);
        $this->db->update('zy_land', $data);

        // 写入交易日志表
        if($guid_step_row['step1']!=4 || $guid_step_row['step2']!=1){
            model('log_model')->trade($uid, [
                'spend_type' => 14,
                'shandian' => -$number
            ]);
        }

        $this->db->trans_complete();

    }


}
