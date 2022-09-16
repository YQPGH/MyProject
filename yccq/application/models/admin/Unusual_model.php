<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  异常数据
 */
include_once 'Base_model.php';

class Unusual_model extends Base_model
{
    public $titles = [
        1 => '今日制烟超过3包',
        2 => '今日抽奖超过3次',
        3 => '今日乐豆收入超过1000个',
        4 => '今日乐币收入超过1000个',
    ];

    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_unusual';
    }

    

}
