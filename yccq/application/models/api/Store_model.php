<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  仓库表
 */
include_once 'Base_model.php';

class Store_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_store';
        $this->load->model('api/shop_model');
    }

    /**
     * 获取用户物品列表
     *
     * @return array
     */
    function list_all($uid, $type1 = 0, $type2 = 0)
    {
        //  全部的 分组显示
        if ($type1 == "0") {
            $list = $this->lists_sql("SELECT DISTINCT * FROM {$this->table} WHERE  uid=? ORDER BY shopid ASC LIMIT 1000",[$uid]);
            $result = [];
            foreach ($list as $value) {
                if ($value['type1'] != $type1) {
                    $result[$value['type1']][] = $value;
                }
            }
            $building_list = $this->column_sql('*',['uid'=>$uid,'total>'=>0],'zy_building_store',1);
            $result['building'] = (empty($building_list))?[]:$building_list;
            $result['used'] = $this->used($this->uid);//统计当前仓库已使用情况
            return $result;
        }
        $where = " uid='{$uid}' AND type1='{$type1}' ";
        if ($type2) $where .= " AND type2='{$type2}' ";

        //$list = $this->lists_sql("SELECT * FROM {$this->table} WHERE  $where ORDER BY shopid ASC LIMIT 1000");
        $list = $this->db->query("SELECT DISTINCT * FROM {$this->table} WHERE {$where} ORDER BY shopid ASC LIMIT 1000")->result_array();
        if($type1=='yan_pin'&&!empty($list)){
            $st_yan_type = config_item('st_yan_type');
            foreach($list as $key=>&$value){
                $type3 = $this->db->query("select type3 from zy_shop WHERE shopid=?",[$value[shopid]])->row_array();
                //查询是否有兑换券
                $quan = $this->db->query("select a.total,b.shopid as quan_shopid ,b.name from zy_store a , zy_shop b WHERE  a.uid=? AND a.shopid=b.shopid AND b.type3=? AND b.type4='quan'",[$uid,$type3['type3']])->row_array();
                $value['quan_name'] = $quan['name']?$quan['name']:'';
                $value['quan_shopid'] = $quan['quan_shopid']?$quan['quan_shopid']:0;
                $value['quan_total'] = $quan['total']?$quan['total']:0;
                //获取升级实体烟所需乐豆
                $value['ledou'] = $st_yan_type[$type3['type3']]['money'];
            }
        }


        return $list;

    }

    /**
     * 按分类格式化列表
     *
     * @return array
     */
    function lists_group($uid)
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7];
        $result = [];
        $shop_type1 = config_item('shop_type1');
        $list = $this->lists_sql("SELECT * FROM {$this->table} WHERE  uid=? LIMIT 1000",[$uid]);
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
     * 详情
     *
     * @return array 一维数组
     */
    function detail($uid, $shopid)
    {
        $value = $this->row(['uid' => $uid, 'shopid' => $shopid]);
        if($shopid==1651 || $shopid==1652 || $shopid==1653)
        {
            $value = $this->table_row('zy_building_store',['uid' => $uid, 'shopid' => $shopid]);
        }
        if ($value) {
            $value = $this->shop_model->append_one($value);
        }

        return $value;
    }


    /**
     * 更新用户商品库存数
     *
     * @return int
     */
    function update_total($total, $uid, $shopid, $judge=0)
    {
        // 加
        if ($total > 0) {
            //物品入库前，先判断仓库剩余容量是否足够
            $store_lv = $this->db->query("SELECT store_lv FROM zy_user WHERE uid=?",[$uid])->row_array();
            $used = $this->used($uid);
            $store_type = config_item('store_type');
            $is_return = model('building_model')->query_upgrade($uid,6);

            if($judge){
//                if($used+$total>$store_type[$store_lv['store_lv']]['size']) t_error(1, '仓库容量不足，请升级仓库！');
                $store_total = $is_return['status']?$store_type[$store_lv['store_lv']]['upgrade_size']:$store_type[$store_lv['store_lv']]['size'];

                if($used+$total>$store_total) t_error(1, '仓库容量不足，请升级仓库！');
            }
            $row = $this->row(['uid' => $uid, 'shopid' => $shopid]);
            if ($row) {
                $this->db->set('total', 'total+' . $total, FALSE);
                $this->db->set('update_time', t_time());
                $this->db->where('id', $row['id']);
                $this->db->update($this->table);

                $result = $this->db->affected_rows();
            } else {
                $shop = $this->shop_model->detail($shopid);
                $result = $this->insert([
                    'uid' => $uid,
                    'shopid' => $shopid,
                    'type1' => $shop['type1'],
                    'type2' => $shop['type2'],
                    'total' => $total,
                    'add_time' => t_time(),
                    'update_time' => t_time(),
                ]);
            }
            return $result;

        } else {  // =======减==============

            $row = $this->row(['uid' => $uid, 'shopid' => $shopid]);
            if (!$row || $row['total'] == 0) return 0;
            $this->db->set('total', 'total' . $total, FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('id', $row['id']);
            $this->db->update($this->table);

            return $this->db->affected_rows();
        }
    }

    //旧的更新仓库方法
    /*function update_total($total, $uid, $shopid, $judge=0)
    {
        // 加
        if ($total > 0) {
            //物品入库前，先判断仓库剩余容量是否足够
            $store_lv = $this->db->query("SELECT store_lv FROM zy_user WHERE uid='$uid'")->row_array();
            $used = $this->used($uid);
            $store_type = config_item('store_type');
            if($judge){
                if($used+$total>$store_type[$store_lv['store_lv']]['size']) t_error(1, '仓库容量不足，请升级仓库！');
            }
            $row = $this->row(['uid' => $uid, 'shopid' => $shopid]);
            if ($row) {
                $this->db->set('total', 'total+' . $total, FALSE);
                $this->db->set('update_time', t_time());
                $this->db->where('uid', $uid);
                $this->db->where('shopid', $shopid);
                $this->db->update($this->table);
                $result = $this->db->affected_rows();
            } else {
                $shop = $this->shop_model->detail($shopid);
                $result = $this->insert([
                    'uid' => $uid,
                    'shopid' => $shopid,
                    'type1' => $shop['type1'],
                    'type2' => $shop['type2'],
                    'total' => $total,
                    'add_time' => t_time(),
                    'update_time' => t_time(),
                ]);
            }
            return $result;

        } else {  // =======减==============
            $row = $this->row(['uid' => $uid, 'shopid' => $shopid]);
            if (!$row || $row['total'] == 0) return 0;

            $this->db->set('total', 'total+' . $total, FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->where('shopid', $shopid);
            $this->db->update($this->table);
            return $this->db->affected_rows();
        }
    }*/

    /**
     * 判断烟叶库存是否足够
     *
     * @param  多个商品id用逗号
     * @return bool
     */
    function total_null($uid, $shopid)
    {
        $shopid_array = explode(',', $shopid);
        $list = $this->lists_sql("select total from {$this->table} 
                          where uid=? AND shopid in ?", [$uid, $shopid_array]);
        foreach ($list as $value) {
            if ($value['total'] == 0) return false;
        }
        return true;
    }

    // 获取用户物品总数
    function get_total($uid, $type1 = '')
    {
        $value = $this->row_sql("SELECT SUM(total) total FROM `zy_store` 
                                WHERE uid=? AND type1=?",[$uid,$type1]);
        if ($value) {
            return $value['total'];
        } else {
            return 0;
        }
    }

    // 获取物品库存
    function shop_total($uid, $shopid)
    {
        $value = $this->row(['uid' => $uid, 'shopid' => $shopid]);
        if ($value) {
            return $value['total'];
        } else {
            return 0;
        }
    }

    /**
     *  出售物品给商行
     *
     * @return array 一维数组
     */
    function sale($uid, $shopid, $total)
    {
        $is_return = model('building_model')->query_upgrade($uid,6);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $store = $this->row(['uid' => $uid, 'shopid' => $shopid]);
        $shop = $this->shop_model->detail($shopid);

        if ($shop['back_money'] == 0) t_error(1, '物品不可出售给商行');
        if ($store['total'] < $total) t_error(2, '你的库存数量不够了');

        $this->db->trans_start();
        // 更新仓库
        $this->update_total(-$total, $uid, $shopid);
        // 银元乐豆更新
        $this->user_model->money($uid, $shop['back_money'] * $total);

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 19,
            'shopid' => $shopid,
            'money' => $shop['back_money'] * $total,
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '出售失败，系统繁忙请稍后再来');

        //$result['used'] = $this->used($this->uid);

        return ['money' => $shop['back_money'] * $total,'used'=>$this->used($uid)];
    }

    /**
     * 升级仓库
     *
     * @return array 一维数组
     */
    function upgrade($uid)
    {
        $is_return = model('building_model')->query_upgrade($uid,6);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 获取建筑状态
        $user = $this->user_model->detail($uid);
        if ($user['store_lv'] >= 43) t_error(1, '你的仓库已是最高级，不能再升级了');

        $store_type = config_item('store_type');
        if ($user['ledou'] < $store_type[$user['store_lv']+1]['money']) t_error(3, '你的乐豆不够了，请稍后再来');

        $this->db->trans_start();
        $this->table_update('zy_user',['store_lv' => $user['store_lv']+1], ['uid' => $uid]);
        $this->user_model->money($uid,0, -$store_type[$user['store_lv']+1]['money']);
        $total =  $is_return['status']?$store_type[$user['store_lv']+1]['upgrade_size']:$store_type[$user['store_lv']+1]['size'];
        //保存升级记录
        $this->table_insert('zy_store_upgrade_record', [
            'uid' => $uid,
            'before_store' => $store_type[$user['store_lv']]['size'],
            'after_store' => $total,
            'add_time' => t_time()
        ]);
        // 写入交易日志表
        $shop['shopid'] = 0;
        $shop['spend_type'] = 4;
        $shop['type1'] = '升级仓库';
        $shop['ledou'] = -$store_type[$user['store_lv']+1]['money'];
        $shop['money'] = 0;
        $shop['shandian'] =0;
        model('log_model')->trade($uid, $shop);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '购买失败，系统繁忙请稍后再来');

        return ['store_lv' => $user['store_lv'] + 1];
    }

    /**
     *  仓库状态 状态容量
     *
     * @return array 一维数组
     */
    function used($uid)
    {
        $row = $this->row_sql("SELECT SUM(total) total FROM {$this->table} WHERE uid=? AND type1 !=?",[$uid,'tudi']);
        return $row['total'];
    }



}
