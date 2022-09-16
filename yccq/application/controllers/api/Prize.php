<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日抽奖
include_once 'Base.php';

class Prize extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/prize_model');
    }

    // 所有列表
    public function lists()
    {
        $list = $this->prize_model->lists_all($this->uid);

        t_json($list);
    }

    //获取每日抽奖奖品列表
    public function every_day_reward_list()
    {
        $list = $this->prize_model->every_day_reward_list($this->uid);

        t_json($list);
    }

    //每日开始摇奖
    public function reward_start()
    {

        $list = $this->prize_model->reward_start($this->uid);

        t_json($list);
    }

    //=====================

    //获取积分抽奖奖品列表
    public function jifen_list()
    {
        $list = $this->prize_model->jifen_list();
        
        t_json($list);
    }

    //积分开始摇奖
    public function jifen_result()
    {
        $result = $this->prize_model->jifen_result($this->uid);

        // 异常数据记录
        $this->load->model('api/unusual_model');
        $this->unusual_model->log($this->uid, 2);

        t_json($result);
    }

    //获取四星抽奖奖品列表
    public function yan4_list()
    {
        $list = $this->prize_model->yan4_list();

        t_json($list);
    }

    //四星开始摇奖
    public function yan4_result()
    {
        $shopid = $this->input->post('shopid');
        if (!$shopid) t_error(1, '香烟id不能为空');

        $result = $this->prize_model->yan4_result($this->uid, $shopid);

        // 异常数据记录
        $this->load->model('api/unusual_model');
        $this->unusual_model->log($this->uid, 2);

        t_json($result);
    }

    //获取五星烟抽奖奖品列表
    public function yan5_list()
    {
        $list = $this->prize_model->yan5_list();

        t_json($list);
    }

    //五星开始摇奖
    public function yan5_result()
    {
        $shopid = $this->input->post('shopid');
        if (!$shopid) t_error(1, '香烟id不能为空');

        $result = $this->prize_model->yan5_result($this->uid, $shopid);
        
        // 异常数据记录
        $this->load->model('api/unusual_model');
        $this->unusual_model->log($this->uid, 2);

        t_json($result);
    }

    //我的获奖记录
    public function logs()
    {
        $result = $this->prize_model->logs($this->uid);

        t_json($result);
    }

    //抽到抵扣券的记录
    public function logs_quan()
    {
        $result = $this->prize_model->logs_quan($this->uid);

        t_json($result);
    }


}