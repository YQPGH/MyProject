<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 文件
 */
include_once 'Base_model.php';

class Wldetail_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = '';
    }

    function menus()
    {
        $sql = "select a.name,a.url,a.order,a.type,b.priv_sign from zy_header_menus a,zy_privilege b WHERE a.root_pid=b.id AND type2=? ORDER BY `order` DESC ";
        $list = $this->db->query($sql,['wl'])->result_array();
        return $list;
    }

}
