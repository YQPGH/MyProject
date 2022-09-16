<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 栏目模型
 */
include_once 'Base_model.php';

class Menu_model extends Base_model
{

    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_menu';
    }

    function lists_format()
    {
        $list = $this->lists_sql("SELECT * FROM $this->table ORDER BY sort,id limit 1000");
        return $this->format($list);
    }


    // 首页
    public function get_tree()
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);
        $string = '<li><span class=\"float_right\">';
        $string .= '<a href=\"/admin/menu/edit?id=$id\">{$sort}</a>&nbsp;&nbsp;';
        $string .= '<a href=\"' . $this->baseurl . 'edit?id=$id\">编辑</a>&nbsp;&nbsp;';
        $string .= '<a href=\"' . $this->baseurl . 'delete?id=$id\" onclick=\"return confirm(\'确定要删除吗？\');\">删除</a>';
        $string .= '</span><span class=\"bianhao\">$id</span>&nbsp;&nbsp;$spacer $name - $tag </li>';

        return $this->tree->get_tree(0, $string);
    }

    // 编辑
    public function get_select($selectid = 0, $parentid = 0)
    {
        // 分类
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);
        return $this->tree->get_tree($parentid,
            "<option value=\$id \$selected>\$spacer\$name</option>",
            $selectid);
    }

    // 格式化数组, 方便用tree类来处理
    function format($list)
    {
        $result = array();

        foreach ($list as $key => $value) {
            $result[$value['id']] = $value;
        }

        return $result;
    }

    // 后台调用
    public function get_tree_admin()
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);

        $string = '<li><a href=\"' . site_url('admin/news/index?catid=') . '$id\" target=\"main\" >$spacer  ▪ $name </a></li>';

        return $this->tree->get_tree(0, $string);
    }

    // 调用子类
    public function get_child($catid = 0)
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);

        return $this->tree->get_child($catid);
    }

    // 调用子类
    public function get_name($catid)
    {
        $row = $this->row($catid);

        return $row['name'];
    }

    // 调用子类
    public function get_names($ids)
    {
        $result = '';
        $list = $this->lists_format();
        $id_array = explode(',', $ids);
        foreach ($id_array as $id) {
            $result .= $list[$id]['name'] .', ';
        }

        return $result;
    }

    // 首页
    public function get_tree_checkbox()
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);
        $string = '<li><input type=\"checkbox\" name=\"ids[]\" value=\"{$id}\">&nbsp;&nbsp;$spacer $name </li>';

        return $this->tree->get_tree(0, $string);
    }

    function topMenu()
    {

        $sql = "select * from zy_menu  WHERE parentid=? and  nav > ? and display=? ORDER BY `sort` ASC ";
        $list = $this->db->query($sql,[0,0,0])->result_array();

        return $list;
    }

    function leftMenu()
    {
        //获取列表
        $sql = "select a.title,a.name,a.id,a.nav,a.type,a.url,a.tag
from zy_menu  a
WHERE a.parentid>? AND a.title=? and a.display=?
ORDER BY  a.`sort` ASC";
        $list = $this->db->query($sql,[0,1,0])->result_array();

        foreach($list as &$value)
        {
            $sql = "select a.title,a.name,a.nav,a.type,a.url,a.tag from zy_menu  a,zy_privilege b
WHERE a.parentid=? AND a.title=? and a.display=? and a.root_pid=b.id
ORDER BY  a.`sort` ASC ";
            $data = $this->db->query($sql,[$value['id'],0,0])->result_array();
            $value['child'] = $data;
        }

        return $list;
    }

}
