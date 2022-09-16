<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 好友
include_once 'Base.php';

class Friend extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/friend_model');
    }

    // 我的好友列表
    public function lists()
    {
        $result = $this->friend_model->list_my($this->uid);

        t_json($result);
    }
    
    // 最近访问列表
    public function lists_visit()
    {
        $result = $this->friend_model->lists_visit($this->uid);

        t_json($result);
    }

    // 生成添加好友URL
    public function mark_url()
    {
        $result = $this->friend_model->mark_url($this->uid);
        
        t_json($result);
    }

    // 添加好友
    public function add()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, '随机码不能为空');

        $result = $this->friend_model->add_friend($this->uid, $code);

        t_json($result);
    }


    // 删除一个我的好友
    public function delete()
    {
        $id = $this->input->post('id');
        if (!$id) t_error(1, 'id不能为空');

        $result = $this->friend_model->delete_friend($this->uid, $id);

        t_json($result);
    }

    // 添加最近访问记录
    public function add_visit()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, '随机码不能为空');

        $result = $this->friend_model->add_visit($this->uid, $code);

        t_json($result);
    }

    public function applyList(){
        $result = $this->friend_model->applyList($this->uid);

        t_json($result);
    }

    //随机获取10个好友申请列表
    public function randFriendList(){
        $result = $this->friend_model->randFriendList($this->uid);

        t_json($result);
    }

    //添加申请
    public function addApply(){
        $fid = trim($this->input->post('fid'));
        if ($fid == '') t_error(1, 'fid不能为空');
        $result = $this->friend_model->addApply($this->uid,$fid);

        t_json($result);
    }

    //好友同意申请
    public function agreeApply(){
        $fid = trim($this->input->post('fid'));
        if ($fid == '') t_error(1, 'fid不能为空');
        $result = $this->friend_model->agreeApply($this->uid,$fid);

        t_json($result);
    }

    //好友拒绝
    public function refuseApply(){
        $fid = trim($this->input->post('fid'));
        if ($fid == '') t_error(1, 'fid不能为空');
        $result = $this->friend_model->refuseApply($this->uid,$fid);

        t_json($result);
    }




    //=============访问好友场景用到的接口===========

    // 好友土地列表
    public function land()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');

        $result = $this->friend_model->land($this->uid, $code);

        t_json($result);
    }

    // 好友路边摊
    public function market()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');

        $result = $this->friend_model->market($this->uid, $code);

        t_json($result);
    }

    // 好友基本信息
    public function user()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');

        $result = $this->friend_model->user($this->uid, $code);
        
        t_json($result);
    }

    //判断是否为好友
    public function is_my_friend()
    {
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');

        $result = $this->friend_model->is_my_friend($this->uid, $code);

        t_json($result);
    }


}