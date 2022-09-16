<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  日志总类
 */
include_once 'Base_model.php';

class Log_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_shop';
    }

    /**
     * 交易日志记录
     * @return 0
     */
    function trade($uid, $shop)
    {
        $this->insert([
            'uid' => $uid,
            'type' => (int)$shop['spend_type'],
            'shopid' => (int)$shop['shopid'],
            'money' => (int)$shop['money'],
            'ledou' => (int)$shop['ledou'],
            'shandian' => (int)$shop['shandian'],
            'add_time' => t_time(),
            'ip' => $this->input->ip_address()
        ]);

        return 0;
    }


}
