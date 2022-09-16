<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 权限模型
 */
include_once 'base_model.php';

class permission_model extends base_model
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'fly_permission';
    }

    // 获取模块
    function all_lists()
    {
        $list = array(
            'video' => '视频管理',
            'news' => '新闻通知',
            'user' => '会员管理',
            'ad' => '幻灯片广告',
            'friendlink' => '友情链接',
            'stat' => '播放统计',
            'setting' => '网站设置',
            'admin' => '管理员管理',
            'permission' => '权限管理',
            'version' => '版本管理',
        );

        return $list;
    }

    // 获取该角色拥有的模块
    function my_lists($catid)
    {
        $list = $this->lists('*', array('catid' => $catid));
        $result = array();
        foreach ($list as $value) {
            $result[] = $value['c'];
        }

        return $result;
    }

    // 获取模块
    function all_modules()
    {
        $list = array(
            'video' => array(
                'name' => '视频管理',
                'method' => array(
                    'index' => '查看',
                    'add' => '添加',
                    'edit' => '编辑',
                    'delete' => '删除',
                    'check' => '审核',
                ),
            ),
            'news' => array(
                'name' => '资讯通知',
                'method' => array(
                    'index' => '查看',
                    'add' => '添加',
                    'edit' => '编辑',
                    'delete' => '删除',
                    'check' => '审核',
                ),
            ),
        );

        return $list;
    }

    // 获取该角色拥有的模块
    function my_modules($catid)
    {
        $list = $this->lists('*', array('catid' => $catid));
        $result = array();
        foreach ($list as $value) {
            $result[$value['c']] = explode(',', $value['m']);
        }

        return $result;
    }

    // 获取该角色拥有的模块
    function check($catid, $c, $m)
    {
        $this->db->where('catid', $catid);
        $this->db->where('c', $c);
        // $this->db->like('m', $m);
        $query = $this->db->get($this->table, 1);
        return boolval(($query->row_array()));

        return true;
    }


}
