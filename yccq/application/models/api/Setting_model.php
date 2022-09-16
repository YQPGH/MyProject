<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 设置模型
 */
include_once 'Base_model.php';

class Setting_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_setting';
    }


    // 获取单个值
    function get($key = '')
    {
        $value = $this->row_sql("select mvalue from $this->table where mkey=? ORDER BY id DESC limit 1",[$key]);
        if (empty($value)) {
            return false;
        }
        
        return $value['mvalue'];
    }


    // 设置单个值 $value mix
    function set($key, $data)
    {
        $last_row = $this->row_sql("select * from $this->table where mkey=? ORDER BY id DESC limit 1",[$key]);
        $last_sign_day = $this->time->day($last_row['add_time']);
        if ($last_sign_day == $this->time->today()){

        }else{
            $insert['title'] = '今日题目';
            $insert['mkey'] = $key;
            $insert['mvalue'] = $data;
            $insert['add_time'] = t_time();
            $this->db->insert($this->table,$insert);
        }
    }

    // 设置单个值 $value mix
    function set_order($key, $data)
    {
        $last_row = $this->row_sql("select * from $this->table where mkey=? ORDER BY id DESC limit 1",[$key]);
        $last_sign_day = $this->time->day($last_row['add_time']);
        if ($last_sign_day == $this->time->today()){

        }else{
            $insert['title'] = '今日订单';
            $insert['mkey'] = $key;
            $insert['mvalue'] = $data;
            $insert['add_time'] = t_time();
            $this->db->insert($this->table,$insert);
        }
    }


}
