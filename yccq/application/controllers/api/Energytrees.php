<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Energytrees extends CI_Controller
{


    function __construct()
    {
        parent::__construct();

        $this->load->model('api/energytrees_model');
    }

    function activityTime()
    {

        $time = $this->energytrees_model->activity_time();
        t_json($time);
    }



    function trees_share()
    {

        $this->load->model('api/energytrees_model');
        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);

        $user = $this->user_model->queryUid($openid);
        $id = $_REQUEST['incode'];
        $user_code = $this->user_model->row(['id'=>$id]);
        $code = $user_code['fid'];
        if($user)
        {
            $uid = $user;
        }
        else
        {
            //如果用户不存在，需为初始化用户信息
            $nickname = addslashes($_REQUEST['nickName']);
            $nickname = urldecode($nickname);
            $headPhoto = addslashes($_REQUEST['headPhoto']);
            $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);

        }

        $this->energytrees_model->init($uid);
        $result = $this->energytrees_model->friendInvite($uid,$code);
        $data['incode'] = $result['insert_id'];
        $data['uid'] = $uid;
        $data['nickname'] = "好友".$result['nickname'];

        $data['content'] = '<span style="color:#f2d79c;-webkit-text-stroke: 0.6px #480b0a; font-weight: bold;">'."$data[nickname]".'</span><br>
                                <span style="color:#f2d79c;-webkit-text-stroke: 0.6px #480b0a; font-weight: bold;">正在参与</span><br>
                                <span style="color:#ffcb2e;-webkit-text-stroke: 0.6px #480b0a; font-weight: bold">【能量“粽”动员】</span><br>
                                <span style="color:#f2d79c;-webkit-text-stroke: 0.6px #480b0a; font-weight: bold">欢迎您一起来玩<br>
                                                  获取能量值参与抽奖<br>有机会获得</span><br>
                                <span style="color:#f2d79c;-webkit-text-stroke: 0.6px #480b0a; font-weight: bold ">口粮代金券<br>等精美好礼</span>';

        $this->load->view("share/trees_share",$data);

    }
    function accessInvite()
    {
        $uid = $this->input->post('uid');

        if (!$uid) t_error(1, '用户ID不能为空');
        $code = $this->input->post('incode');

        $result = $this->energytrees_model->inviteAccept($uid,$code);
        t_json();
    }

    function getPrize(){

        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->getPrize($uid);

        t_json($result);
    }

    function queryInfo()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->queryInfo($uid);
        t_json($result);
    }

    function friendInfo()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $code = $this->input->post('code');
        if(!$code) t_error(2,'操作失败');
        $result  = $this->energytrees_model->friendInfo($uid,$code);
        t_json($result);
    }

    /**
     *  任务领奖
     *
     */
    function getTaskprize()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $id = intval($this->input->post('id'));
        if(!$id) t_error(2,'操作失败');
        $result  = $this->energytrees_model->getTaskprize($uid,$id);
        t_json($result);
    }

    /**
     *  任务列表
     *
     */
    function taskList()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->taskList($uid);
        t_json($result);
    }

    function prizeList()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->prizeList();

        t_json($result);
    }



    function myEnergylist()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->myEnergylist($uid);
        t_json($result);
    }

    function myLostlist()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->myLostlist($uid);
        t_json($result);
    }

    function ranking()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result  = $this->energytrees_model->ranking($uid);
        t_json($result);
    }

    function myReceive()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $index = $this->input->post('index');
        if(!$index) t_error(2,'操作有误');
        $result  = $this->energytrees_model->myReceive($uid,$index);
        t_json($result);
    }

    function friendReceive()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $index = $this->input->post('index');
        if(!$index) t_error(2,'操作有误');
        $code = $this->input->post('code');
        $result  = $this->energytrees_model->friendReceive($uid,$index,$code);
        t_json($result);
    }

    function  prize_record()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $type = 1;
        $result  = $this->energytrees_model->prize_record($uid,$type);
        t_json($result);
    }


}
