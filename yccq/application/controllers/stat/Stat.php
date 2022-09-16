<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stat extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('stat/stat_model');
    }

    // 每分钟执行一次
    public function minute1()
    {

    }

    // 每10分钟执行一次
    public function minute10()
    {
        $this->stat_model->day();

        echo "it's ok" . PHP_EOL;
    }

    // 每小时执行一次
    public function hour1()
    {
        $this->stat_model->month();     // 月统计表
        $this->stat_model->shop_set();  // 神秘商店刷新

        echo "it's ok" . PHP_EOL;
    }

    // 每天中午12：00点执行
    public function day12_00()
    {
        $this->stat_model->orders_set(); // 订单刷新
        echo "it's ok" . PHP_EOL;
    }

    // 每天凌晨00:01执行
    public function day00_01()
    {
        $this->stat_model->month();     // 月统计表
        $this->stat_model->day(1); // 每日综合统计
        $this->stat_model->question_set(); // 题目设置
        $this->stat_model->unusual(); // 异常数据记录
        $this->stat_model->task_set(); // 每日任务设置
//        $this->stat_model->resetNum(); //重置每日碎片数量
        $this->stat_model->taskprize_scan(); //每日扫码任务奖励设置
//        $this->stat_model->fragment_prize_day1(); //重置碎片每日奖品数量

//        $this->stat_model->turntable_prize();

        $this->stat_model->rand_shop(); //建筑
        $this->stat_model->init_today_task_times(); //叠金叶
        $this->stat_model->init_scan_today_task_times();
        $this->stat_model->init_task();
        $this->stat_model->mid_autumn_task();
//        $this->stat_model->runSign();
        $this->stat_model->updateTrees();
        echo "it's ok" . PHP_EOL;
    }



    public function week(){
        $this->stat_model->sign_rank(); //每周签到、抽奖奖励随机值
        $this->stat_model->show_type(); //每周签到、抽奖奖励随机值
        $this->stat_model->updateRankingPrizeConfig(); //定时周一更新排行榜的奖品
        $this->stat_model->updateRankingJfPrizeConfig(); //定时周一更新积分排行榜的奖品
    }

    public function test(){
        $this->stat_model->updateRankingJfPrizeConfig(); //定时周一更新积分排行榜的奖品
    }

}
