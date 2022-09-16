<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 设置模型
 */
include_once 'Base_model.php';

class Xp_config_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_xp_config';
    }

    // 获取单个值
    function all()
    {
        $result = [];
        $list = $this->lists_sql("select * from $this->table  order by id limit 100");
        foreach ($list as $value) {
            $result[$value['mkey']] = $value['mvalue'];
        }

        return $result;
    }

    // 获取单个值
    function get_list()
    {
        $list = $this->lists_sql("select * from $this->table  limit 100");

        return $list;
    }


    // 获取单个值
    function get($key = '')
    {
        $value = $this->row_sql("select mvalue from $this->table where mkey='$key' limit 1");

        if (empty($value)) {
            return false;
        }

        return $value['mvalue'];
    }


    // 设置单个值 $value mix
    function set($key, $data)
    {
        $value = $this->row_sql("select mkey from $this->table where mkey='$key' limit 1");
        if (empty($value)) { // 添加
            $this->insert($this->table, array('mkey' => $key, 'mvalue' => $data));
        } else { // 修改
            //$this->db->where ( 'mkey', $key );
            // $this->db->update ( $this->table, array('mvalue'=>$data) );
            $where = array('mkey' => $key);
            $data = array('mvalue' => $data);
            $this->update($data, $where);
        }
    }
}
