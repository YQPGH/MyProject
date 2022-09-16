<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Date: 2020/12/28
 * Time: 11:04
 */

include_once 'Base.php';
class Coolrun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/coolrun_model');
        $this->load->model('api/user_model');
    }


    function activity_time()
    {
        $time = $this->coolrun_model->activity_time();
        t_json($time);
    }

    function query_playerdata()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->getUserdata($uid);
        t_json($result);
    }

    public function invite()
    {

        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $state_base64 = base64_encode(site_url('api/Coolrun/share?incode='.$code));

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

    function share()
    {

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

        }
        $this->coolrun_model->initdata($uid);
        $data['content'] = '<span style="color:#f2d79c">正在参与</span><br>
                                <span style="color:#ffcb2e">新年有牛气，好运陪伴你</span><br>
                                <span style="color:#f2d79c">欢迎您一起来玩<br>
                                                  完成种植、醇化等<br>有机会获得</span><br>
                                <span style="color:#f2d79c; ">口粮品吸机会<br>口粮代金券<br>等精美好礼</span>';


        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $result = $this->coolrun_model->is_friend_invite($uid,$code);

            $data['incode'] = $code;
            $data['uid'] = $uid;
            $data['nickname'] = "好友".$result['nickname'];

            $this->load->view("share/coolrun_share",$data);
        }

    }

    function send_invite(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->mentor_invite($uid);
        t_json($result);
    }


    function invite_accept()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $code = $this->input->post('incode');

        $result = $this->coolrun_model->invite_accept($uid,$code);
        t_json();
    }



    function lists()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->task_list($uid);
        t_json($result);
    }

    function get_task_prize()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $id = $this->input->post('id');
        if (!$id) t_error(2, '操作有误');
        $result = $this->coolrun_model->task_receive($uid,$id);
        t_json($result);
    }


    function start_game()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->startGame($uid);
        t_json($result);
    }

    function game_over()
    {

        $data = [
            'uid' => $this->input->post('uid'),
            'code' => $this->input->post('code'),
            'score' => $this->input->post('score')
        ];
        if(!$data['uid']) t_error(1, '用户ID不能为空');
        if(!$data['code']) t_error(2, '随机码不能为空');
        $result = $this->coolrun_model->gameOver($data);
        t_json($result);
    }


    function get_prize()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->get_prize($uid);
        t_json($result);
    }

    function prize_list()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->prize_list($uid);
        t_json($result);
    }

    function prize_record()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->coolrun_model->prize_record($uid);
        t_json($result);
    }

    function test()
    {

//        $uid = '123';
//        $code = 2;
        $result = $this->coolrun_model->test();
    }
}
