<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  新闻模型
 */
include_once 'Base_model.php';

class Log_shop_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_shop';
    }

}
