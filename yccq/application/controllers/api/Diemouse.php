<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/4/18
 * Time: 9:59
 */

include_once 'Base.php';

class Diemouse extends CI_Controller{
    function __construct(){
        parent::__construct();

        $this->load->model('api/diemouse_model');
        $this->load->model('api/user_model');
    }

    function index(){

        $this->load->view("client/mouse");
    }

    function user_invite(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
       $result = $this->diemouse_model->mentor_invite($uid);
        t_json($result);
    }

    function invite(){

        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);
        $user = $this->user_model->queryUid($openid);

        if($user) {
            $uid = $user;
        }else{
            //如果用户不存在，需为初始化用户信息
            $nickname = addslashes($_REQUEST['nickName']);
            $nickname = urldecode($nickname);
            $headPhoto = addslashes($_REQUEST['headPhoto']);
            $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);
        }


        $code = $_REQUEST['incode'];
        $this->load->model('api/diemouse_model');
        $result = $this->diemouse_model->is_friend_invite($uid,$code);

        $data['incode'] = $code;
        $data['uid'] = $uid;
        $data['nickname'] = $result['nickname'];

        $this->load->view("client/diemouse_share",$data);
    }

    function user_binding(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $code = $this->input->post('incode');
        $result = $this->diemouse_model->mentor_binding($uid,$code);
        t_json($result);
    }

    //返回叠老鼠奖励
    public function diemouse_prize(){

        $result = $this->diemouse_model->diemouse_prize();
        t_json($result);
    }

    //领取奖励
    public function diemouse_get_prize(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $this->diemouse_model->diemouse_get_prize($uid);
        t_json();
    }




    function getTicketnum(){

        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->laxin_model->getTicketnum($uid);
        t_json($result);
    }

    function newUser(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->laxin_model->queryUser($uid);
        t_json($result);
    }

    //召集列表
    function lists(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->laxin_model->user_lists($uid);
        t_json($result);
    }

    function receive(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $id = intval($this->input->post('id'));
        $task_id = intval($this->input->post('task_id'));
        if($id == '' || $task_id == '') t_error(1,'操作有误');
        $result = $this->laxin_model->receive($uid,$id,$task_id);
        t_json($result);
    }


    function newer_prize_list(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->laxin_model->lv_prize_list($uid);
        t_json($result);
    }

    function newer_get_prize(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $id = intval($this->input->post('id'));
        if($id>0 && $id<=20){
            $result = $this->laxin_model->lv_get_prize($uid,$id);
            t_json($result);
        }else{
            t_error(1,'条件不符');
        }

    }


    function test(){
//        $code = $this->input->post('incode');
//        print_r($this->uid);exit;
//        $result = $this->user_model->test($this->uid);
//        $game_lv  = $this->input->post('game_lv');
//        $result = $this->laxin_model->queryHeaderframe($this->uid);
//        $uid = $this->input->post('uid');
//        $result = $this->laxin_model->test($uid);
//        t_json($result);
//        $data['nickname'] = 'test';
//        $this->load->view("client/invite_share",$data);
//        $this->load->view("client/test");
    }

   
}