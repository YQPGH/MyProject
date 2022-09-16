<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 栏目模型
 */
include_once 'Base_model.php';
class Category_model extends Base_model {
	
	function __construct() {
		
		parent::__construct ();
		
		$this->table = 'zy_category';
	}

    function lists_format()
    {
        $list = $this->lists_sql("SELECT * FROM $this->table ORDER BY sort,id limit 1000");
        return $this->format($list);
    }


    // 首页
    public function get_tree ()
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);
        $string = '<li><span class=\"float_right\">';
        $string .= '<a href=\"index.php?d=admin&c=category&m=edit&id=$id\">{$sort}</a>&nbsp;&nbsp;';
        $string .= '<a href=\"'.$this->baseurl.'edit?id=$id\">编辑</a>&nbsp;&nbsp;';
        $string .= '<a href=\"'.$this->baseurl.'delete?id=$id\" onclick=\"return confirm(\'确定要删除吗？\');\">删除</a>';
        $string .= '</span><span class=\"bianhao\">$id</span>&nbsp;&nbsp;$spacer $name </li>';

        return $this->tree->get_tree(0, $string);
    }

    // 编辑
    public function get_select ($selectid=0, $parentid=0)
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
    public function get_tree_admin ()
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);

        $string = '<li><a href=\"'.site_url('admin/news/index?catid=').'$id\" target=\"main\" >$spacer  ▪ $name </a></li>';

        return $this->tree->get_tree(0, $string);
    }

    // 调用子类
    public function get_child ($catid = 0)
    {
        $list = $this->lists_format();
        $this->load->library('tree');
        $this->tree->init($list);

        return $this->tree->get_child($catid);
    }

    // 调用子类
    public function get_name ($catid)
    {
        $row = $this->row($catid);

        return $row['name'];
    }
	
}
