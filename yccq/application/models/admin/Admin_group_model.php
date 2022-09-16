<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 角色
 */
include_once 'Base_model.php';

class Admin_group_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_admin_group';
    }

    /**
     *  格式化列表
     *
     * @return array 一维数组
     */
    function list_format()
    {
        $result = [];
        $list = $this->lists_sql("SELECT * FROM {$this->table} LIMIT 1000");
        foreach ($list as $row) {
            $result[$row['id']] = $row['title'];
        }
        return $result;
    }

    /**
     * 查询角色-栏目 是否有权限
     *
     * @return bool
     */
    function has_permission($groupid, $menu_tag)
    {
        if($groupid == 1) return true;

        $this->load->model('admin/menu_model');
        $menu = $this->menu_model->row(['tag'=>$menu_tag]);
        $row = $this->row($groupid);
        $ids = explode(',', $row['menu_ids']);

        return in_array($menu['id'], $ids);
    }


}
