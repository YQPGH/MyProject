<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 管理员 模型
 */
include_once 'Base_model.php';

class Admin_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_admin';
    }

    // 更新登录次数和时间
    function update_logins($uid)
    {
        if (empty($uid)) return 0;
        $this->db->set('online', '1', FALSE);
        $this->db->set('logins', 'logins+1', FALSE);
        $this->db->set('lastlog_time', t_time());
        $this->db->where('id', $uid);
        $this->db->update($this->table);

        return $this->db->affected_rows();
    }

    /**
     *  格式化列表
     *
     * @return array 一维数组
     */
    function list_format($field = '*')
    {
        $result = [];
        $list = $this->lists_sql("SELECT {$field} FROM {$this->table} LIMIT 1000");
        foreach ($list as $row) {
            $result[$row['id']] = $row;
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
        $admin_list = $this->list_format();

        foreach ($list as &$value) {
            $value['username'] = $admin_list[$value['uid']]['username'];
            $value['truename'] = $admin_list[$value['uid']]['truename'];
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
        $admin_list = $this->list_format();

        $value['username'] = $admin_list[$value['uid']]['username'];
        $value['truename'] = $admin_list[$value['uid']]['truename'];

        return $value;
    }

}
