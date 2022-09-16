<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日签到
include_once 'Base.php';

class XXL extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/xxl_model');
    }

    // 用户信息
    public function user()
    {
        $this->load->model('api/user_model');
        $user = $this->user_model->detail($this->uid);
        t_json($user);
    }
    
    // 开始游戏
    public function startGame()
    {
        $result = $this->xxl_model->startGame($this->uid);
        t_json($result);
    }

    // 结束游戏
    public function stopGame()
    {
        $data = [
            'code' => $this->input->post('code'),
            'uid' => $this->uid,
            'step' => $this->input->post('step'),
            'score' => $this->input->post('score'),
            'live_time' => $this->input->post('time'),
        ];
        if(!$data['code']) t_error(1, '随机码不能为空');

        $result = $this->xxl_model->stopGame($data);
        
        t_json($result);
    }

   //消耗乐豆
   public function updateBeans(){
       $result = $this->xxl_model->updateBeans($this->uid);
       t_json($result);
   }
}