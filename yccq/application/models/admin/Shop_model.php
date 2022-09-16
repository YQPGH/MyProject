<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 *  商品
 */
include_once 'Base_model.php';

class Shop_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_shop';
    }


    /**
     * 获取一条记录, 同时更新查看次数
     *
     * @return array 一维数组
     */
    function detail($shopid)
    {
        $value = $this->row(['shopid' => $shopid]);
        if (!$value) return $value;

        unset($value['add_time']);
        // $value['thumb'] = base_url($value['thumb']);
        if ($value['json_data']) {
            $temp = json_decode($value['json_data'], true);
            $value = array_merge($value, $temp);
        }

        return $value;
    }

    /**
     * 格式化列表
     *
     * @return array 一维数组
     */
    function list_format($field = '*')
    {
        $result = [];
        $list = $this->lists_sql("SELECT {$field} FROM zy_shop LIMIT 1000");
        foreach ($list as $row) {
            $result[$row['shopid']] = $row;
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
//            $value['name'] = $shop_list[$value['shopid']]['name'];
//            $value['type2'] = $shop_list[$value['shopid']]['type2'];
//            $value['work_time'] = $shop_list[$value['shopid']]['work_time'];
//            $value['thumb'] = $shop_list[$value['shopid']]['thumb'];
            // $value['thumb'] = base_url($shop_list[$value['shopid']]['thumb']);
            $value['shop'] = $shop_list[$value['shopid']];
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

}
