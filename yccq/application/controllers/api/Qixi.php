<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * User: Administrator
 * Date: 2020/6/23
 * Time: 10:54
 */
include_once 'Base.php';
class Qixi extends CI_Controller
{
    function  __construct()
    {
        parent::__construct();
        $this->load->model('api/qixi_model');
        $this->load->model('api/user_model');
    }

    function activity_time(){
        $time = $this->qixi_model->activity_time();
        t_json($time);
    }

    function get_user()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->qixi_model->get_user($uid);
        t_json($result);
    }

    function move()
    {

        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->qixi_model->move($uid);
        t_json($result);
    }

    function user_invite(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->qixi_model->mentor_invite($uid);
        t_json($result);
    }



    public function invite(){

        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $state_base64 = base64_encode(site_url('api/Qixi/share?incode='.$code));

            //正式环境《真龙》
            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
            $apiUrl .=  'response_type=code&state='.$state_base64;
            $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';

            $temp = sprintf($apiUrl, $state_base64);

            header("Location: " . $temp);
            exit;
        }


    }

    function share(){

        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);
        $user = $this->user_model->queryUid($openid);

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
            $this->qixi_model->initdata($uid,1);
        }

        $data['content'] = '<span style="color:#a0c0f0">正在参与</span><br>
                                <span style="color:#fcecbb">乘风破浪来见你<br>七夕相约</span><br>
                                <span style="color:#a0c0f0">欢迎您一起来玩！<br>
                                                  帮助牛郎织女相会，<br>有机会获得，</span><br>
                                <span style="color:#fcecbb">口粮代金券，<br>游戏道具，<br>等精美好礼！</span>';


        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $result = $this->qixi_model->is_friend_invite($uid,$code);

            $data['incode'] = $code;
            $data['uid'] = $uid;
            $data['nickname'] = "好友".$result['nickname'];

            $this->load->view("client/qixi_share",$data);
        }

    }

    function invite_accept(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $code = $this->input->post('incode');

        $result = $this->qixi_model->invite_accept($uid,$code);
        t_json();
    }


    function sign(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->qixi_model->sign($uid);
        t_json($result);
    }

    function sign_list(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->qixi_model->list_my($uid);
        t_json($result);
    }

    //任务列表
    function lists(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->qixi_model->task_list($uid);
        t_json($result);
    }

    function task_receive(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $id = intval($this->input->post('id'));

        if($id == '' ) t_error(2,'操作有误');
        $result = $this->qixi_model->task_receive($uid,$id);
        t_json($result);
    }

    // 更新角色
    public function update_role()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $role = intval($this->input->post('role'));
        if(!$role) t_error(2, '不能为空');
        $data['role'] = $role;

        $result = $this->qixi_model->update($data, ['uid' => $uid]);
        if (!$result) t_error(3, '更新失败，请稍后再试');

        t_json();
    }


    function prize_list()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->qixi_model->prize_list($uid);
        t_json($result);
    }

    /**
     * 抽奖
     */
    function get_prize()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->qixi_model->get_prize($uid);
        t_json($result);
    }

    /**
     * 抽奖记录
     */
    function prize_record()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->qixi_model->prize_record($uid);
        t_json($result);
    }


    function test()
    {


//        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
//        $id = intval($this->input->post('id'));
//        $type = intval($this->input->post('status'));
//
//        $this->qixi_model->test();

    }

    function message_update()
    {
        $this->qixi_model->message_update();
    }




}
