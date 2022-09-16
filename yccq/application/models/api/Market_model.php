<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  路边摊
 */
include_once 'Base_model.php';

class Market_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_market';
        $this->load->model('api/shop_model');
        $this->load->model('api/store_model');
    }

    /**
     * 出售
     * @return array
     */
    function start($uid, $shopid, $total, $money)
    {
        $is_return = model('building_model')->query_upgrade($uid,12);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 判断8个货架
        if ($this->count(['uid' => $uid]) >= 8) t_error(1, '你的货架格子满了，请稍后再试');
        if ($total > 10) t_error(1, '一次最多可以上架10个商品');
        // 判断库存
        $store = $this->store_model->detail($uid, $shopid);
        if ($store['type1'] == 'tudi') t_error(1, '土地不能出售');
        if ($store['total'] < $total) t_error(2, '库存不够了，请稍后再试');

        $this->db->trans_start();

        $shop = $this->shop_model->detail($shopid);
        if($shop['type1'] == 'building')
        {
            model('building_model')->update_store($uid,$shopid,'-'.$total);
            $type = $shop['type1'];
        }
        else
        {
            // 库存减少
            $this->store_model->update_total(-$total, $uid, $shopid);
            $type = $store['type1'];
        }

        // 写入路边摊表
        $now = $this->time->now();
        $stop_time = $this->time->time_add_hour(12);
        $data = [
            'number' => t_rand_str(),
            'uid' => $uid,
            'shopid' => $shopid,
            'type1' => $type,
            'total' => $total,
            'money' => $money,
            'start_time' => $now,
            'stop_time' => $stop_time,
            'add_time' => $now,
        ];
        $this->insert($data);

        //添加每日任务
        model('task_model')->update_today($uid, 7);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '发布失败，系统繁忙请稍后再来');

        return $data;
    }

    /**
     * 重新发布广告
     * @return array
     */
    function restart($uid, $number)
    {
        $this->load->model('api/user_model');
        // 事务开始
        $this->db->trans_start();
        $is_return = model('building_model')->query_upgrade($uid,12);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $this->user_model->money($uid,0,-1);    //扣除1个乐豆

        $now = $this->time->now();
        $stop_time = $this->time->time_add_hour(12);

        $market = $this->row(['uid' => $uid, 'number' => $number]);
        if (!$market) t_error(1, '信息为空');
        if ($market['status'] == 1) t_error(3, '商品已售，不能更改');
        if ($market['stop_time'] > $now) t_error(2, '广告生效中，不可重新发布');

        $data = [
            'start_time' => $now,
            'stop_time' => $stop_time,
        ];
        $where = ['uid' => $uid, 'number' => $number];
        $this->update($data, $where);

        $this->db->trans_complete();
        return $data;
    }

    /**
     * 到期手动下架
     * @return array
     */
    function stop($uid, $number)
    {
        $market = $this->row(['number' => $number]);
        if (!$market) t_error(1, '信息为空，请稍后再试');

        $this->db->trans_start();
        $shop = $this->shop_model->detail($market['shopid']);
        if($shop['type1'] == 'building') {
            model('building_model')->update_store($uid, $market['shopid'], '+'.$market['total']);
        }
        else
        {
            // 更新库存
            $this->store_model->update_total($market['total'], $uid, $market['shopid']);
        }

        // 路边摊表删除
        $this->delete(['number' => $number]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '下架失败，系统繁忙请稍后再来');

        return $market;
    }

    /**
     * 已售格子清除
     * @return array
     */
    function sold($uid, $number)
    {
        $market = $this->row(['number' => $number]);
        if (!$market) t_error(1, '信息为空');

        // 路边摊表删除
        $this->delete(['uid' => $uid, 'number' => $number]);

        return 0;
    }

    /**
     * 购买物品
     * @return array
     */
    function buy($uid, $number)
    {
        $is_return = model('building_model')->query_upgrade($uid,12);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        model('log_market_model');
        $market = $this->row(['number' => $number]);
        if (!$market) t_error(1, '信息为空');
        if ($market['status'] == 1) t_error(2, '你来晚了，物品已售');
        if ($market['stop_time'] < t_time()) t_error(3, '你来晚了，物品下架了');
        if ($uid == $market['uid']) t_error(2, '不能购买自己的物品');

        // 判断用户银元和库存
        $user = $this->user_model->detail($uid);
        if ($user['money'] < $market['money']) t_error(2, '你的银元不够了');

        $this->db->trans_start();
        $shop = $this->shop_model->detail($market['shopid']);
        if($shop['type1'] == 'building') {
            model('building_model')->update_store($uid, $market['shopid'], '+'.$market['total']);
        }
        else
        {
            // 更新购买者库存
            $this->store_model->update_total($market['total'], $uid, $market['shopid'],1);
        }
        // 更新购买者银元
        $this->user_model->money($uid, -$market['money']);
        // 更新出售者银元
        $this->user_model->money($market['uid'], $market['money']);
        // 更新边摊表记录
        $this->update(['status' => 1], ['number' => $number]);
        // 日志
        $this->log_market_model->log($uid, $market);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '发布失败，系统繁忙请稍后再来');

        return 1;
    }

    /**
     * 所有的
     * @return array
     */
    function list_all($uid, $page)
    {
        $this->load->model('api/user_model');

        $now = t_time();
        $offset = ($page - 1) * 4;
        $list = $this->lists_sql("
              SELECT uid,`number`,shopid, total,money,start_time,stop_time
              FROM {$this->table}
              WHERE stop_time>'{$now}' AND uid!='{$uid}' AND status=0
              ORDER BY start_time DESC
              LIMIT {$offset},4;");
        //$list = $this->table_lists($this->table,'uid,`number`,shopid, total,money,start_time,stop_time',
        //"stop_time>'{$now}' AND uid!='{$uid}' AND status=0",'start_time DESC',$offset,4);
        $list = $this->user_model->append_list($list);
        foreach ($list as &$row) {
            unset($row['uid']);
        }

        return $list;
    }

    /**
     * 我的列表
     * @return array
     */
    function list_my($uid)
    {
        $list = $this->lists('*', ['uid' => $uid], 'id');
        foreach ($list as &$value) {
            // 到期未卖状态设为2
            if ($value['status']==0 && $value['stop_time'] <= t_time()) $value['status'] = 2;
        }

        return $list;
    }

    function unlock_market($uid,$spend_type){

        //查看是否已经解锁
//        $row = $this->db->query("select game_lv,market_status,ledou,money from zy_user WHERE uid='$uid'")->row_array();
        $row = $this->column_sql('game_lv,market_status,ledou,money',['uid'=>$uid],'zy_user',0);
        if ($row['market_status']) t_error(1, '已解锁，不可再次解锁');
        //查看、判断是否满足解锁条件
        $unlock_term = config_item('unlock_market_term');
        if($row['game_lv'] < $unlock_term['game_lv']) t_error(2, '未达到解锁等级');
        if($row[$spend_type] < $unlock_term[$spend_type]) t_error(2, '您的乐豆或银元不足');

        // 事务开始
        $this->db->trans_start();
        // 扣除乐豆或者银元
        if($spend_type == 'ledou'){
            $this->user_model->money($uid,0,-$unlock_term[$spend_type]);
        }else if($spend_type == 'money'){
            $this->user_model->money($uid,-$unlock_term[$spend_type],0);
        }

        //更新表（zy_user）
        $this->db->set('market_status', 1);
        $this->db->where('uid', $uid);
        $this->db->update('zy_user');

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 29, //解锁花费
            "$spend_type" => -$unlock_term[$spend_type]
        ]);

        $this->db->trans_complete();

    }


}
