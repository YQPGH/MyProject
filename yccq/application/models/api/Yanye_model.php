<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  烟叶相关
 */
include_once 'Base_model.php';

class Yanye_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_yanye';
        $this->load->model('api/shop_model');
        $this->load->model('api/store_model');
        $this->load->model('api/status_model');
        $this->load->model('api/event_model');
        $this->load->model('api/user_model');
    }

    /**
     * 开始烘烤
     *
     * @return int
     */
    function bake_start($uid, $shopid)
    {
        // 判断烘烤室状态
        $status = $this->status_model->detail($uid);
        if ($status['bake_status'] != 0) t_error(1, '烘烤室忙碌中，请稍后再来');

        // 更新仓库表
        $this->db->trans_start();
        $ids = explode(',', $shopid);
        //判断烘烤烟叶数量是否大于5片
        if (count($ids) > 5) t_error(1, '每次最多可烘烤5片烟叶');
        foreach ($ids as $id) {
            //判断提交的物品是否为烟叶
            $query = $this->db->query("select count(*) as num from zy_shop WHERE shopid=$id AND type1='yanye'")->row_array();
            if ($query['num'] == 0) t_error(1, '烟叶不存在');
            $result = $this->store_model->update_total(-1, $uid, $id);
            if ($result == 0) t_error(2, '你的烟叶库存不够，请稍后再来');
        }

        $now_time = t_time();
        $time = 8;

        $this->status_model->update([
            'bake_status' => 1,
            'bake_shopid' => $shopid,
            'bake_start' => $now_time,
            'bake_stop' => t_time(time() + 60 * $time),
            'bake_time' => $time,
        ], ['uid' => $uid]);

        //保存烘烤记录
        $this->table_insert('zy_bake_record', [
            'uid' => $uid,
            'bake_shopid' => $shopid,
            'start_time' => $now_time,
            'stop_time' => t_time(time() + t_time(time() + 60 * $time)),
            'add_time' => $now_time
        ]);
        
        // 随机发生事件 3%
        $user = $this->user_model->detail($uid);
        $rand_number = rand(1,100);
        if ($rand_number <= 50 && $user['game_lv'] >= 6) {
            $this->event_model->insert([
                'type1' => 2,
                'uid' => $uid,
                'title' => '烘烤室发生事件，请尽快处理。',
                'add_time' => $now_time,
            ]);
        }

        //添加每日任务
        model('task_model')->update_today($uid, 4);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '更新失败，系统繁忙请稍后再来');

        return $this->status_model->detail($uid);
    }

    // 烘烤加速
    function bake_jiasu($uid)
    {
        $status = $this->table_row('zy_status', ['uid'=>$uid]);
        $user = $this->user_model->detail($uid);
        //获取指引步骤
        $guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();
        // 根据烟叶数目判断所需闪电
        $number = count_shandian(strtotime($status['bake_stop'])-time());
        $ids = explode(',', $status['bake_shopid']);
        $shandian = count($ids)*$number;
        if ($shandian > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');

        // 事务开始
        $this->db->trans_start();

        // 扣除闪电
        if($guid_step_row['step1']!=5 || $guid_step_row['step2']!=1){
            $this->user_model->shandian($uid, -$shandian);
        }
        //更新状态表
        $this->table_update('zy_status', ['bake_stop' => t_time()],['uid'=>$uid]);
        // 删除事件 若有
        $this->table_delete('zy_event',['uid'=>$uid, 'type1'=>2]);

        // 写入交易日志表
        if($guid_step_row['step1']!=5 || $guid_step_row['step2']!=1){
            model('log_model')->trade($uid, [
                'spend_type' => 15,
                'shandian' => -$shandian,
            ]);
        }

        $this->db->trans_complete();
    }

    /**
     *  收取烘烤烟叶
     *
     * @return array
     */
    function bake_gather($uid)
    {
        $bake = $this->status_model->detail($uid);
        if (empty($bake) || $bake['bake_status'] == 0) t_error(1, '暂无烘烤烟叶可收取，请稍后再来');
        if ($bake['bake_stop'] > t_time()) t_error(2, '烘烤完成时间未到，请稍后再来');

        $ids = explode(',', $bake['bake_shopid']);
        $this->db->trans_start();

        // 有事件，产生未烤烟叶
        $event = $this->table_row('zy_event', ['uid'=>$uid, 'type1'=>2]);
        if ($event) {//出现未烘烤的烟叶
            $rand_key = array_rand($ids);//随机获取一片叶子，返回给玩家
            $this->store_model->update_total(1, $uid, $ids[$rand_key]);
            $result['weikao'][]['shopid'] = $ids[$rand_key];
            unset($ids[$rand_key]);
            foreach ($ids as $shopid) {
                $shop = $this->shop_model->detail($shopid);
                $this->store_model->update_total(1, $uid, $shop['mubiao']);
                $result['success'][]['shopid'] = $shop['mubiao'];
            }
            $result['jianchan'] = array();
            $result['jiangji'] = array();
        } else {
            // 更新仓库表
            foreach ($ids as $shopid) {
                $shop = $this->shop_model->detail($shopid);
                $this->store_model->update_total(1, $uid, $shop['mubiao']);
                $result['success'][]['shopid'] = $shop['mubiao'];
            }
            $result['jiangji'] = array();
            $result['jianchan'] = array();
            $result['weikao'] = array();
        }

        // 更新状态表
        $this->status_model->update([
            'bake_status' => 0,           
            'bake_shopid' => '',
            'bake_time' => 0,
            'bake_start' => 0,
            'bake_stop' => 0,
        ], ['uid' => $uid]);

        // 经验值增加
        $xp = model('xp_config_model')->get('hongkao');
        $this->user_model->xp($uid, $xp);
        $this->event_model->delete(['uid' => $uid, 'type1' => 2]);// 删除事件
        $this->db->trans_complete();

        return $result;
    }

    /**
     *  开始醇化
     *
     * @return int
     */
    function aging_start($uid, $shopid)
    {
        // 先看状态
        $status = $this->status_model->detail($uid);
        if ($status['aging_status'] != 0) t_error(1, '醇化室忙碌中，请稍后再来');
        //首先获取当前玩家醇化室等级
        $query = $this->db->query("select aging_lv from zy_status WHERE uid='$uid'")->row_array();
        $chun_type = config_item('chun_type');
        // 更新仓库表
        $this->db->trans_start();
        $ids = explode(',', $shopid);
        foreach ($ids as $id) {
            //判断提交的物品是否为烘烤过的烟叶
            $shop_yan = $this->db->query("select count(*) as num from zy_shop WHERE shopid=$id AND type1='yanye_kao'")->row_array();
            if ($shop_yan['num'] == 0) t_error(1, '该烘烤的烟叶不存在');
            $result = $this->store_model->update_total(-1, $uid, $id);
            if ($result == 0) t_error(2, '你的烟叶库存不够，请稍后再来');
        }
        // 更新状态表
        $this->status_model->update([
            'aging_status' => 1,
            'aging_shopid' => $shopid,
            'aging_start' => t_time(),
            'aging_stop' => t_time(time() + $chun_type[$query['aging_lv']]['work_time'] * 60),
        ], ['uid' => $uid]);

        //保存醇化记录
        $this->table_insert('zy_aging_record', [
            'uid' => $uid,
            'aging_shopid' => $shopid,
            'start_time' => t_time(),
            'stop_time' => t_time(time() + $chun_type[$query['aging_lv']]['work_time'] * 60),
            'add_time' => t_time()
        ]);

        //添加每日任务
        model('task_model')->update_today($uid, 5);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '更新失败，系统繁忙请稍后再来');

        return $this->status_model->detail($uid);
    }


    /**
     *  收取醇化烟叶
     *
     * @return array
     */
    function aging_gather($uid)
    {
        $value = $this->status_model->detail($uid);
        if (empty($value) || $value['aging_status'] == 0) t_error(1, '暂无醇化烟叶可收取，请稍后再来');
        if ($value['aging_stop'] > t_time()) t_error(2, '醇化完成时间未到，请稍后再来');
        // 更新仓库表
        $ids = explode(',', $value['aging_shopid']);
        foreach ($ids as $shopid) {
            $shop = $this->shop_model->detail($shopid);
            $this->store_model->update_total(1, $uid, $shop['mubiao']);
        }
        // 更新状态表
        $this->status_model->update([
            'aging_status' => 0,
            'aging_shopid' => '',
            'aging_start' => 0,
            'aging_stop' => 0,
        ], ['uid' => $uid]);

        // 经验值增加
        $xp = model('xp_config_model')->get('chunhua');
        $this->user_model->xp($uid, $xp);

        return $shop['yanye_id'];
    }

    /**
     * 获取用户某种物品
     *
     * @return array 一维数组
     */
    function list_by_shopid($uid, $shopid)
    {
        $list = $this->lists_sql("SELECT id,shopid
                            FROM zy_yanye 
                            WHERE  uid='{$uid}' AND shopid='{$shopid}'
                            LIMIT 1000");
        $list = $this->shop_model->append_list($list);
        return $list;
    }

    /**
     * 获取用户某种物品 总数
     *
     * @return array 一维数组
     */
    function count_by_shopid($uid, $shopid)
    {
        $value = $this->row_sql("SELECT count(*) as total
                            FROM zy_yanye 
                            WHERE  uid='{$uid}' AND shopid='{$shopid}'
                            LIMIT 10000");

        return $value['total'];
    }

    /**
     * 醇化室升级
     *
     * @return array 一维数组
     */
    function upgrade_aging($uid)
    {
        //首先获取当前玩家醇化室等级
        $query = $this->db->query("select id,aging_lv from zy_status WHERE uid='$uid'")->row_array();
        $number = $query['aging_lv'];
        if ($number >= 3) t_error(3, '已经是最高级');
        $user = $this->user_model->detail($uid);
        $chun_type = config_item('chun_type');
        if ($chun_type[$number + 1]['money'] > $user['money']) t_error(3, '你的银元不够了，请稍后再来');
        //统计今天乐豆使用情况
        //$is_max = $this->user_model->is_ledou_max_total($uid, $chun_type[$number + 1]['money']);
        //if (!$is_max) t_error(3, '你的乐豆今日使用已经到达上限，请稍后再来');
        // 事务开始
        $this->db->trans_start();
        // 银元乐豆扣除
        $this->user_model->money($uid, -$chun_type[$number + 1]['money'], 0);//消耗乐豆
        //更新状态表（zy_status）
        $data = array(
            'aging_lv' => $number + 1,
        );

        $this->db->where('id', $query['id']);
        $this->db->update('zy_status', $data);

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 5,
            'ledou' => -$chun_type[$number + 1]['money'],
        ]);

        $this->db->trans_complete();
        $result['number'] = $number + 1;
        return $result;
    }
    
    function chun_jiasu($uid)
    {
        //首先获取当前玩家醇化室等级
        $query = $this->db->query("select id,aging_stop,aging_shopid from zy_status WHERE uid='$uid'")->row_array();

        //获取指引步骤
        $guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();
        $user = $this->user_model->detail($uid);
        $number = count_shandian(strtotime($query['aging_stop'])-time());
        $ids = explode(',', $query['aging_shopid']);
        $shandian = count($ids)*$number;

        if ($shandian > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');
        // 事务开始
        $this->db->trans_start();

        if($guid_step_row['step1']!=6 || $guid_step_row['step2']!=1){
            $this->user_model->shandian($uid, -$shandian);//消耗闪电
        }
        //更新状态表（zy_status）
        $data = array(
            'aging_stop' => t_time(),
        );

        $this->db->where('id', $query['id']);
        $this->db->update('zy_status', $data);

        // 写入交易日志表
        if($guid_step_row['step1']!=6 || $guid_step_row['step2']!=1){
            model('log_model')->trade($uid, [
                'spend_type' => 16,
                'shandian' => -$shandian
            ]);
        }

        $this->db->trans_complete();
    }


}
