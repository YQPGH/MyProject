<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  日志总类
 */
include_once 'Base_model.php';

class Log_market_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_market';
    }

    /**
     * 交易日志记录
     * @return 0
     */
    function log($uid, $row)
    {
        $time = t_time();
        // 买
        $this->insert([
            'uid' => $uid,
            'shopid' => $row['shopid'],
            'total' => $row['total'],
            'type1' => $row['type1'],
            'money' => -$row['money'],
            'ledou' => $row['ledou'],
            'add_time' => $time,
        ]);

        // 卖
        return $this->insert([
            'uid' => $row['uid'],
            'shopid' => $row['shopid'],
            'total' => $row['total'],
            'type1' => $row['type1'],
            'money' => $row['money'],
            'ledou' => $row['ledou'],
            'add_time' => $time,
        ]);
    }


}
