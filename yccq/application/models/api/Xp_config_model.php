<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  经验值配置
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
    function get($key = '')
    {
        $value = $this->row_sql("select mvalue from $this->table where mkey='$key' limit 1");

        if (empty($value)) {
            return false;
        }

        return $value['mvalue'];
    }

}
