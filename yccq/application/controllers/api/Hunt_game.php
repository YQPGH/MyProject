<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 挖宝游戏

include_once 'Base.php';

class Hunt_game extends Base {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/hunt_model');
    }

    //关数
    public function getPass(){
        $list = $this->hunt_model->getPass($this->uid);
        t_json($list);
    }

    //游戏结束
    public function gameover()
    {
        $score = $this->input->post('score');
        $pass = $this->input->post('pass');
        $result = $this->hunt_model->score($this->uid, $score, $pass);
        t_json($result);
    }

    //获取今日游戏通关记录
    public function getRecord(){
        $result = $this->hunt_model->getRecord($this->uid);
        t_json($result);
    }

    //更新数据库，挑战券的数量
    public function updatePlayTimes(){
        $result = $this->hunt_model->updatePlayTimes($this->uid);
        t_json($result);
    }

    //消耗乐豆
    public function beans(){
        $reeult = $this->hunt_model->beans($this->uid);
        t_json($reeult);
    }
}
