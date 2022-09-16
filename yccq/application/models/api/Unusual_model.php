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
        4 => '今日银元收入超过1000个',
    ];

    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_unusual';
    }

    function log($uid, $typeid)
    {
        $log = false;
        $title = $this->titles[$typeid];

        // 今日制烟记录
        if($typeid == 1) {
            $total = $this->table_count('zy_process_record',
                ['uid'=>$uid, 'add_time >='=>$this->time->today()]);
            if($total >=3) {
                $log = true ;
                $title = "今日制烟数为{$total}包";
            }
        }
        
        // 今日抽奖记录
        if($typeid == 2) {
            $total = $this->table_count('log_prize',
                ['uid'=>$uid, 'add_time >='=>$this->time->today()]);
            if($total >=3) {
                $log = true ;
                $title = "今日抽奖数为{$total}次";
            }
        }

        if($log) {
            $this->insert([
                'uid' => $uid,
                'typeid' => $typeid,
                'title' => $title,
                'add_time' => t_time(),
            ]);
        }
    }

}
