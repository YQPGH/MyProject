<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  统计
 */
include_once 'Base_model.php';

class Stat_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_stat_month';
    }

    // 获取月统计数据，按字段组合，方便输出给图标显示
    function month($year = 0)
    {
        $where = "";
        $result = [];
        if ($year) {
            $where = "WHERE dates like '{$year}%' ";
        }
        $list = $this->lists_sql("select * from zy_stat_month {$where} 
                          order by id DESC limit 12");
        if ($list) {
            $list = array_reverse($list);
            foreach ($list as $value) {
                $result['dates'][] = date('Y-m', strtotime($value['dates']));
                $result['users'][] = $value['users'];
                $result['active'][] = $value['active'];
                $result['logins'][] = $value['logins'];
                $result['user_gamelv'][] = $value['user_gamelv'];
            }
        }
        
        return $result;
    }

    // 获取月统计数据，按字段组合，方便输出给图标显示
    function xxx()
    {
        $years = config_item('years');
        foreach ($years as $key => $year) {
            if ($key == 0) continue;
            for ($i = 1; $i <= 12; $i++) {
                if (strlen($i) == 1) $i = '0' . $i;
                $this->insert([
                    'dates' => $key . '-' . $i
                ]);
            }
        }

    }


}
