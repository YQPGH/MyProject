<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 虫子
include_once 'Base.php';

class Chongzi extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/chongzi_model');
    }

    //好友列表
    function friend_list(){
        $result = $this->chongzi_model->friend_list($this->uid);
        t_json($result);
    }

    // 开始派遣虫子
    function start()
    {
        $code = $this->input->post('code');
        $type = $this->input->post('type');
        $result = $this->chongzi_model->start($this->uid, $code,$type);
        
        t_json($result);
    }

    // 驱赶虫子
    function clear()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '虫子编号不能为空');
        $result = $this->chongzi_model->clear($this->uid, $number);
        
        t_json($result);
    }

    
    // 转换记录
    function change_record()
    {

        $page = intval($this->input->post('page'));

        $result = $this->chongzi_model->change_record($this->uid, $page);

        t_json($result);
    }


    //虫子租赁、放置、被放置状态信息
    function chongzi_status(){
        $result = $this->chongzi_model->chongzi_status($this->uid);
        t_json($result);
    }

    //定时查询有无被放置虫子
    function chongzi_query(){
        $result = $this->chongzi_model->chongzi_query($this->uid);
        t_json($result);
    }



  //入侵记录
    function Ruqin(){

        $page = intval($this->input->post('page'));
        $result = $this->chongzi_model->Ruqin($this->uid, $page);

        t_json($result);
    }

    //当前能量
    function current_energy(){
        $page = intval($this->input->post('page'));
        $result = $this->chongzi_model->current_energy($this->uid, $page);
        t_json($result);

    }



    //领取能量
    function lingqu(){
        $id = intval($this->input->post('id'));
        if (!$id) t_error(1, 'id不能为空');
        $type = $this->input->post('type');
        $result = $this->chongzi_model->lingqu($this->uid,$type,$id);
        t_json($result);
    }

    //总能量
    function energy_total(){
        $result = $this->chongzi_model->energy_total($this->uid);
        t_json($result);
    }
    //领取闪电收益
    function receive_shouyi(){
        $result = $this->chongzi_model->receive_shouyi($this->uid);
        t_json($result);
    }

    //好友被放置虫子信息
    function friend_chongzi_placed(){
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');
        $result = $this->chongzi_model->friend_chongzi_placed($this->uid, $code);
        t_json($result);
    }
function test(){

    $result = $this->chongzi_model->test($this->uid);
    t_json($result);

}
}