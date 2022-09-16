<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/4/18
 * Time: 9:59
 */

include_once 'Base.php';

class Leaf extends CI_Controller
{
    function __construct(){
        parent::__construct();

        $this->load->model('api/leaf_model');
        $this->load->model('api/user_model');


    }

    function activity_time(){
        $time = $this->leaf_model->activity_time();
        t_json($time);
    }

    function user_invite(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
       $result = $this->leaf_model->mentor_invite($uid);
        t_json($result);
    }

    function invite(){

        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);
        $user = $this->user_model->queryUid($openid);

        if($user) {
            $uid = $user;
            $data['content'] = '<span style="color:#962f16">正在参与</span><br>
                                <span style="color:#b91f00;font-weight: bold">【年年有金叶，登录有惊喜】</span><br>
                                <span style="color:#962f16">欢迎您一起来玩<br>
                                                            升级香叶还有机会<br>获得</span>
                                <span style="color:#b91f00;font-weight: bold">口粮代金券</span>
                                <span style="color:#962f16">等奖励</span>';

        }else{

            //如果用户不存在，需为初始化用户信息
            $nickname = addslashes($_REQUEST['nickName']);
            $nickname = urldecode($nickname);
            $headPhoto = addslashes($_REQUEST['headPhoto']);
            $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);
            $this->leaf_model->initdata($uid,1);
            $data['content'] = '<span style="color:#962f16">正在参与</span><br>
                                <span style="color:#b91f00;font-weight: bold">【年年有金叶，登录有惊喜】</span><br>
                                <span style="color:#962f16">欢迎您一起来玩<br>
                                                            升级香叶还有机会<br>获得</span>
                                <span style="color:#b91f00;font-weight: bold">口粮代金券</span>
                                <span style="color:#962f16">等奖励</span>';

        }

        $code = $_REQUEST['incode'];
        $this->load->model('api/leaf_model');
        $result = $this->leaf_model->is_friend_invite($uid,$code);

        $data['incode'] = $code;
        $data['uid'] = $uid;
        $data['nickname'] = "好友".$result['nickname'];

        $this->load->view("share/springfest_share",$data);
    }

    function user_binding(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $code = $this->input->post('incode');

        $result = $this->leaf_model->mentor_binding($uid,$code);
        t_json();
    }

    //获取用户的福气值
    public function query_lucky_value(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $value = $this->leaf_model->queryLuckyValue($uid);
        t_json($value);
    }


    function composition(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $index = $this->input->post('index');
        if(empty($index)) t_error(2,"编号有误");
        $result = $this->leaf_model->composition($uid,$index);
        t_json($result);
    }

    function recovery(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $index = $this->input->post('index');
        $result = $this->leaf_model->recovery($uid,$index);
        t_json($result);
    }

    function leafmove()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $start_index = $this->input->post('start_index');
        $end_index = $this->input->post('end_index');
        if($start_index=='' || $end_index== '') t_error(2,'操作有误');
        $result = $this->leaf_model->leafmove($uid,$start_index, $end_index);
        t_json();
    }

    function cultivateLeaves(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $level = $this->input->post('level');
        $result = $this->leaf_model->levelUnlock($uid,$level);
        t_json($result);
    }


    public function leaf_prize(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->leaf_model->leaf_prize_list($uid);
        t_json($result);
    }

    //抽奖
    public function get_prize(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $prize = $this->leaf_model->leaf_get_prize($uid);
        t_json($prize);
    }

    public function prize_record(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $prize = $this->leaf_model->prize_record($uid);
        t_json($prize);
    }

    //任务列表
    function lists(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->leaf_model->task_list($uid);
        t_json($result);
    }

    function task_receive(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $id = intval($this->input->post('id'));

        if($id == '' ) t_error(2,'操作有误');
        $result = $this->leaf_model->task_receive($uid,$id);
        t_json();
    }


    function sign(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $result = $this->leaf_model->sign($uid);
        t_json($result);
    }

    function sign_list(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->leaf_model->list_my($uid);
        t_json($result);
    }


    function savemessage(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(11, '用户ID不能为空');
        $pid = trim($this->input->post('id'));
        $truename = $this->input->post('truename');
        $phone = $this->input->post('phone');
        $address = $this->input->post('address');
        $code = $this->input->post('code');
        $phone = is_mobile_phone($phone);
        if(!$phone) t_error(1,'请输入有效号码');
        if(empty($truename) || empty($address)) t_error(3,'信息不全，请重新填写！');

        $result = $this->leaf_model->savemessage($uid,$pid,$truename,$phone,$address,$code);
        t_json();
    }


    function getUsermessage(){

//        $uid = 'abcc';
        $uid = $_SESSION['uid'];

        if (!$uid) t_error(11, '用户ID不能为空');
        $id = trim($_REQUEST['id']);
//        $id = 15;
        $result = $this->leaf_model->getUsermessage($uid,$id);
        $result['uid'] = $uid;
        $result['id'] = $id;
        $this->load->model('api/address_model');
        $address = $this->address_model->get_province($result['province_code'],$result['city_code'],$result['area_code']);
        $result['province_name'] = $address['province'];
        $result['city_name'] = $address['city'];
        $result['area_name'] = $address['area'];
        if($result['province_code']){
            unset($result['province_code'],$result['city_code'],$result['area_code']);
        }

        $this->load->view("client/leaf_address",$result);

    }


    function test(){
        $uid = $this->input->post('uid');
        $this->leaf_model->task_update_today($uid, 2);
    }
}
