<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  商行
 */
include_once 'Base_model.php';

class Shop_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_shop';
        $this->load->model('api/user_model');
    }

    /**
     * 商品用列表，商行可见
     *
     * @return array
     */
    function list_shop($uid)
    {

        $sale_arr = $this->user_model->is_sale_shop();

        $all = ['putong' => [], 'shenmi' => []];

        $result = [];
        $shop_type1 = config_item('shop_type1');

        $list = $this->lists_sql("SELECT *
                         FROM zy_shop
                         WHERE status=0
                         LIMIT 1000");

        foreach ($list as $value) {
            if ($value['json_data']) {
                $temp = json_decode($value['json_data'], true);//json格式数据转换为数组格式
                $value = array_merge($value, $temp);    //与转化后的数组合并
            }

            if($sale_arr['is_sale'] && $value['type1']!='tudi'){
                $value['money'] = $sale_arr['sale_num']*$value['money'];
            }

            unset($value['id'], $value['json_data']);

            foreach ($shop_type1 as $type1 => $name) {

                if ($value['type1'] == $type1) {

//                    if(!$sale_arr['is_sale'] && $value['type1']=='chongzi' &&  $value['type2']==3){
//                       continue;
//                    }
                    $result[$type1][] = $value;
                }
            }
        }
        $all['putong'] = $result;

        // 神秘商行列表
        $my_refresh = $this->row_sql("select * from zy_shenmi_shop WHERE uid=? ORDER BY id DESC",[$uid]);
        $sys_refresh = $this->row_sql("select * from zy_setting WHERE mkey='shenmi_shop'");
        if (strtotime($my_refresh['add_time']) > strtotime($sys_refresh['add_time'])) { //个人后刷新
            if($my_refresh['shop_ids'])
                $shop_ids = explode(',', $my_refresh['shop_ids']);
            else
                $shop_ids = [];
        } else {
            $shop_ids = explode(',', $sys_refresh['mvalue']);
            $this->table_update('zy_shenmi_shop', ['shop_ids' => $sys_refresh['mvalue'], 'add_time' => t_time()], ['uid' => $uid]);
        }


        if(count($shop_ids)>0) {
            foreach ($shop_ids as $value) {
                $list_rand[] = $this->row_sql("select * from zy_shop WHERE shopid=?",[$value]);
            }

            $is_return = $this->count_num($uid);
            $money = ($is_return['is_upgrade'] ==2)?0.9:1;
            $result = [
                'zhongzi'=>[],
                'building'=>[]
            ];
            foreach ($list_rand as &$value) {
                $value['money'] = floor($money*$value['money']);
                $value['ledou'] = floor($money*$value['ledou']);
                if ($value['json_data']) {
                    $temp = json_decode($value['json_data'], true);
                    $value = array_merge($value, $temp);
                }
                unset($value['id'], $value['json_data']);

                foreach ($shop_type1 as $type1 => $name) {
                    if ($value['type1'] == $type1) {
                        $result[$type1][] = $value;
                    }
                }
            }
            $all['shenmi'] = $result;
        }
        else
        {
            $all['shenmi'] = [
                'zhongzi'=>[],
                'building'=>[]
            ];
        }
        $bean = $this->count_num($uid);

        $all['bean'] = $bean['bean'];
        $all['next_refresh_time'] = date('Y-m-d H:i:s', strtotime($sys_refresh['add_time']) + 3600);
        return $all;
    }

    function count_num($uid){
        $is_return = model('building_model')->query_upgrade($uid,3);

        $today = t_time(0,0);
        $sql = "select count(*) as num from log_shop_refresh WHERE uid=? AND addtime>=?";
        $num = $this->db->query($sql, array($uid, $today))->row_array();
        if($is_return['is_upgrade']==2){
            $bean = ($num['num']>=20)?2:0;
        }
        else
        {
            $bean = 2;
        }
        $result['is_upgrade'] = $is_return['is_upgrade'];
        $result['status'] = $is_return['status'];
        $result['num'] = $num['num'];
        $result['bean'] = $bean;
        return $result;
    }

    //个人刷新神秘商店
    function my_refresh($uid)
    {
        $is_return =  $this->count_num($uid);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $is_max = $this->user_model->is_ledou_max_total($uid, 1);
        if (!$is_max) t_error(1, '今日乐豆使用已经超过上限');

        // 事务开始
        $this->db->trans_start();
        $user = $this->user_model->detail($uid);

        if($is_return['status']){
            if (2 > $user['ledou'] && $is_return['num']>=20)  t_error(3, '你的乐豆不足，请稍后再来');
        }
        else
        {
            if (2 > $user['ledou'])   t_error(3, '你的乐豆不足，请稍后再来');
        }

        if($is_return['status']){

            if($is_return['num']>=20){
                $this->user_model->money($uid, 0, -2);//消耗乐豆
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 11,
                    'ledou' => -2
                ]);
            }else{
                $this->table_insert('log_shop_refresh',[
                    'uid'=>$uid,
                    'addtime'=>t_time()
                ]);
            }
        }else{
            $this->user_model->money($uid, 0, -2);//消耗乐豆
            // 写入交易日志表
            model('log_model')->trade($uid, [
                'spend_type' => 11,
                'ledou' => -2
            ]);
        }

        $this->db->trans_complete();

        $type2 = $this->getShenMiType2($user['game_lv']);
        $list = $this->lists_sql("SELECT * FROM zy_shop WHERE status=1 AND type2 IN ? LIMIT 1000",[$type2]);
        $list_building = $this->lists_sql("SELECT * FROM zy_shop WHERE status=1 AND type1=? LIMIT 3",[['building']]);//建筑升级材料
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

        $update['shop_ids'] = $str;
        $update['add_time'] = t_time();
        $this->db->update('zy_shenmi_shop', $update, array('uid' => $uid));

        $result = [];
        $shop_type1 = config_item('shop_type1');
        $money = ($is_return['is_upgrade'] ==2)?0.9:1;
        foreach ($list_rand as &$value) {
            $value['money'] = floor($money*$value['money']);
            $value['ledou'] = floor($money*$value['ledou']);
            if ($value['json_data']) {
                $temp = json_decode($value['json_data'], true);
                $value = array_merge($value, $temp);
            }
            unset($value['id'], $value['json_data']);
//            if($is_return['status']){
//                $value['money'] = floor($value['money']*0.9);
//            }
            foreach ($shop_type1 as $type1 => $name) {

                if ($value['type1'] == $type1) {
                    $result[$type1][] = $value;
                }
            }
        }
        $result['bean'] = $is_return['bean'];
        return $result;
    }

    //获取神秘商行商品解锁条件
    function getShenMiType2($game_lv){
        $shenmi_shop_type2 = config_item('shenmi_shop_type2');
        foreach($shenmi_shop_type2 as $key=>$value){
            if($game_lv >= $value['start_lv'] && $game_lv <= $value['end_lv']){
                $tpye2 = $value['type2'];
            }
        }
        return explode(',',$tpye2);
    }


    /**
     *  所有物品列表
     *
     * @return array
     */
    function list_all()
    {
        $result = [];
        $list = $this->lists_sql("SELECT *
                         FROM zy_shop
                         WHERE 1
                         LIMIT 1000");

        foreach ($list as $value) {
            if ($value['json_data']) {
                $temp = json_decode($value['json_data'], true);
                $value = array_merge($value, $temp);
            }
            unset($value['id'], $value['json_data']);
            $result[$value['shopid']] = $value;
        }

        return $result;
    }


    /**
     * 获取一条记录, 同时更新查看次数
     *
     * @return array 一维数组
     */
    function detail($shopid)
    {
        $value = $this->row(['shopid' => $shopid]);

        if (!$value) t_error(1, '商品信息为空，请稍后再试');

        unset($value['add_time']);
        // $value['thumb'] = base_url($value['thumb']);
        if ($value['json_data']) {
            $temp = json_decode($value['json_data'], true);
            $value = array_merge($value, $temp);
        }

        return $value;
    }

    /**
     * 购买物品
     *
     * @return int
     */
    function buy($uid, $shopid, $total)
    {

        $is_return =  $this->count_num($uid);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $sale_arr = $this->user_model->is_sale_shop();
        model('store_model');

        $shop = $this->detail($shopid);

        if (!$shop || $shop['total'] < $total) t_error(1, '该商品已售罄');
        if ($shop['status'] > 1) t_error(1, '该商品不可购买');


        // 用户银元判断
        $user = $this->user_model->detail($uid);
        if($sale_arr['is_sale'] && $shop['status'] == 0){
            $sale_money = $sale_arr['sale_num']*$shop['money'];
            $sale_ledou = $sale_arr['sale_num']*$shop['ledou'];
        }else{
            $sale_money = $shop['money'];
            $sale_ledou = $shop['ledou'];
        }

        if ($sale_money * $total > $user['money']) t_error(2, '你的银元不够了，请稍后再来');
        if ($sale_ledou * $total > $user['ledou']) t_error(3, '你的乐豆不够了，请稍后再来');

        // 事务开始
        $this->db->trans_start();

        // 写入土地表 需返回购买的土地表id
        if ($shop['type1'] == 'tudi')
        {

            //土地每三级才能买一块土地
            $game_lv = $this->db->query("SELECT game_lv FROM zy_user WHERE uid=?",[$uid])->row_array();//玩家等级
            $land_num = $this->db->query("SELECT COUNT(*) as num FROM zy_land WHERE uid=?",[$uid])->row_array();//玩家当前土地数量
            $land_buy_lv = config_item('land_buy_lv');
            if ($land_num['num']+$total > $land_buy_lv[$game_lv['game_lv']]['size']) t_error(1, '等级不够，无法购买新土地');
            // 银元扣除
            $single_price = $shop['money']+($land_num['num']-6)*10000;

            if($sale_arr['is_sale'])
            {

                $this->user_model->money($uid, -($single_price * $total+($total*($total-1)/2)*10000)*0.9, 0);
            }
            else
            {
                $this->user_model->money($uid, -$single_price * $total+($total*($total-1)/2)*10000, 0);
            }
            $this->load->model('api/land_model');
            $result = $this->land_model->add($uid, $shopid, $total);
        }
        else if($shop['type1'] == 'chongzi')
        {

            $this->load->model('api/chongzi_model');
            $result = $this->chongzi_model->buy_chongzi($uid,$shopid,$shop['type2']);
            // 银元乐豆扣除
            $this->user_model->money($uid, -$sale_money * $total, -$sale_ledou * $total);
        }
        else if($shop['type1'] == 'building_gift')
        {
            if($user['purchased']) t_error(9,'请勿重复购买');
            if ($total > 1) t_error(8, '只限购一份');
            $result  = $this->building_material($uid,$shop,$user['money']);
        }
        else
        {

            // 神秘商行商品 只能买一个，买后删除
            if ($shop['status'] == 1) {

                $shenmi = $this->table_row('zy_shenmi_shop', ['uid' => $uid]);
                if (empty($shenmi['shop_ids'])) t_error(4, '该商品已售罄，请稍后再来');
                $shenmi_ids = explode(',', $shenmi['shop_ids']);
                if (!in_array($shopid, $shenmi_ids)) t_error(5, '该商品已售罄，请稍后再来');

                foreach ($shenmi_ids as $key => $shenid) {
                    if ($shenid == $shopid) unset($shenmi_ids[$key]);
                }
                $this->table_update('zy_shenmi_shop', [
                    'shop_ids' => join(',', $shenmi_ids),
                    'add_time' => t_time()
                ], ['uid' => $uid]);

                if( $is_return['is_upgrade']==2)
                {

                    $sale_money = intval($sale_money*0.9);
                    $sale_ledou = intval($sale_ledou*0.9);
                }
            }

            // 银元乐豆扣除
            $this->user_model->money($uid, -$sale_money * $total, -$sale_ledou * $total);

            if($shop['type1']!='building'){
                $result = $this->store_model->update_total($total, $uid, $shopid,1);
            }else{
                $arrray[]['shop'] = $shopid;
                $arrray[0]['total'] = 1;
                $this->building_model->insert_record($uid,$arrray,1);
                $result = 1;
            }

        }

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 1,
            'shopid' => $shopid,
            'ledou' => -$sale_ledou * $total,
            'money' => -$sale_money * $total,
        ]);

        //添加每日任务
        model('task_model')->update_today($uid, 10);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '购买失败，系统繁忙请稍后再来');

        return $result;
    }


    //真龙商行内售卖建筑升级材料礼包
    function building_material($uid,$shop,$money)
    {

        $list = [];
        if($shop['money'] > $money) t_error(2, '你的银元不够了，请稍后再来');
        if($shop['total']<1) t_error(9,'该商品已售罄');
        $list = $this->db->query("select shopid,total from zy_shop WHERE type1=?",['building'])->result_array();
        foreach($list as &$value)
        {
            $value['total'] = $value['total']*10;
            model('building_model')->update_store($uid,$value['shopid'],'+'.$value['total']);
        }

        $this->table_update('zy_user',
            [
            'purchased' => 1
            ],
            [
                'uid'=>$uid
            ]);
         // 银元扣除
        $this->user_model->money($uid, -$shop['money']);



        $this->db->set('total', 'total - 1' , FALSE)
                ->set('update_time', t_time())
                ->where('shopid', $shop['shopid'])
                ->update('zy_shop');
         // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 1,
            'shopid' => $shop['shopid'],
            'ledou' => 0,
            'money' => -$shop['money'],
        ]);

        return $list;
    }

    function selling_time()
    {
//        $act_time = $this->user_model->query_holiday_time('selling_time');
//
//
//        if(time()>$act_time['start_time'] && time()<$act_time['end_time'])
//        {
//            $time =   date('Y-m-d H:i:s',$act_time['start_time'] +86400);
            $row = $this->db->query("select id,total,status from zy_shop WHERE type1=?",['building_gift'])->row_array();

            if(!$row['total'] && !$row['status'])
            {
                $this->table_update('zy_shop',['status' => 2],['id' => $row['id']]);
            }

//        }



    }


    //========================================
    /**
     * 格式化列表
     *
     * @return array 一维数组
     */
    function list_format($field = '*')
    {
        $result = [];
        $list = $this->lists_sql("SELECT $field FROM zy_shop LIMIT 1000");
        foreach ($list as $row) {
            $result[$row['shopid']] = $row;
        }
        return $result;
    }

    /**
     * 按分类格式化列表
     *
     * @return array
     */
    function lists_group()
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7];
        $result = [];
        $shop_type1 = config_item('shop_type1');
        $list = $this->lists_sql("SELECT * FROM zy_shop LIMIT 1000");
        foreach ($list as $row) {
            foreach ($shop_type1 as $type1 => $name) {
                if ($row['type1'] == $type1) {
                    foreach ($numbers as $type2) {
                        if ($row['type2'] == $type2) $result[$type1][$type2][] = $row;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 为列表附加上商品信息
     *
     * @return array 一维数组
     */
    function append_list($list)
    {
        $shop_list = $this->list_format();
        foreach ($list as &$value) {
            $value['name'] = $shop_list[$value['shopid']]['name'];
            $value['type2'] = $shop_list[$value['shopid']]['type2'];
            $value['work_time'] = $shop_list[$value['shopid']]['work_time'];
            $value['thumb'] = $shop_list[$value['shopid']]['thumb'];
            // $value['thumb'] = base_url($shop_list[$value['shopid']]['thumb']);
        }

        return $list;
    }

    /**
     * 获取一条记录, 同时更新查看次数
     *
     * @return array 一维数组
     */
    function append_one($value)
    {
        $shop_list = $this->list_format();

        $value['name'] = $shop_list[$value['shopid']]['name'];
        $value['thumb'] = $shop_list[$value['shopid']]['thumb'];

        return $value;
    }

    /**
     * 更新访问量
     *
     * @param int $id
     * @return array 二维数组
     */
    function update_trade($shopid)
    {
        $this->db->set('total', 'total+1', FALSE);
        $this->db->set('sales', 'sales+1', FALSE);
        $this->db->where('shopid', $shopid);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }




}
