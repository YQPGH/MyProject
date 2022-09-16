<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 碎片管理
 */

//include_once 'Base.php';

class Fragment extends CI_Controller{

    private $key = 'YCCQFRAGMENT';

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/fragment_model');
        $this->load->model('api/user_model');

    }

    //保存扫码获得碎片
    function randGetsuipian(){
        $openid = trim($this->input->post('openid'));
        $sign = trim($this->input->post('sign'));
        if($openid==''  || $sign==''){
            echo json_encode(array('code'=>'100000', 'msg' => '参数不全', 'data'=>array()));
            exit;
        }
        $check = $this->user_model->queryUid($openid);

        if($check) {
            $uid = $check;
            $status = 1;
        }else{
            //如果用户不存在，需为初始化用户信息
            $nickname = '';
            $headPhoto = '';
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);
            $status = 0;
        }
        if(!($this->checkSaveSuipian($sign,$openid))){
            echo json_encode(array('code'=>'200000', 'msg' => '校验有误', 'data'=>array()));
            exit;
        }

        $result = $this->fragment_model->randGetsuipian($uid,$status);
        if($result['insert_id']){
            unset($result['insert_id']);
            echo json_encode(array('code'=>'000000', 'msg' => '保存成功！', 'data'=>$result));

            exit;
        }else{
            echo json_encode(array('code'=>'300000', 'msg' => '保存失败！', 'data'=>array()));
            exit;
        }
    }

    //校验
    private function checkSaveSuipian($sign,$openid){
        if($sign == md5($this->key.$openid)){
            return true;
        }else{
            return false;
        }
    }

    //获取扫码碎片
    function queryFragment(){
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'用户ID不能为空');
        $result  = $this->fragment_model->queryFragment($uid);
        t_json($result);

    }
    //碎片数量
    function fragment_num(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->fragment_num($uid);

        t_json($result);
    }

    //查询合成剩余次数
    function queryKeynum(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->queryKeynum($uid);

        t_json($result);
    }
    //抽奖
    function prize_exchange(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->prize_exchange($uid);
        t_json($result);
    }

    function saveShare(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $share_type = trim($this->input->post('share_type')) == 'ask' ? 2 :1;
        $suipian_index = trim($this->input->post('suipian_index'));
        if($share_type == '' || $suipian_index == '') t_error(2,'参数不全');

        $result = $this->fragment_model->saveShare($uid,$share_type,$suipian_index);
        t_json($result);
    }

    /*
    * 索要、赠送的链接，填写此地址
    */
    function  askGiveShareUrl(){
        $func = $this->input->get('func') == 'ask' ? 'Ask' : 'Give';
        $rand = $this->input->get('rand');
        $type = $this->input->get('suipian_type');
        $result = $this->fragment_model->askGiveShareUrl($func,$rand,$type);

        if($result['id']){

            // 测试环境《真龙服务号》
            $apiUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxccb43a09acc5a5c8&';
            $apiUrl .= 'redirect_uri=http://zl.haiyunzy.com/thirdInterface/thirdInterface!autoLogin3.action&';
            $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';

            //正式环境《真龙》
//            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
//            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/thirdInterface/thirdInterface!autoLogin3.action&';
//            $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';

            $state_base64 = base64_encode(site_url('api/Fragment/'.$func.'&r='.$rand.'-'.$result['id']));
            $temp = sprintf($apiUrl, $state_base64);

            header("Location: " . $temp);
            exit;
        }else{
            $data['msg'] = '该分享不存在'.$rand;
            $this->load->view('client/tip', $data);
        }
    }

    //处理赠送的碎片
    function Give(){
        $phone_os 	= addslashes($_SERVER['HTTP_USER_AGENT']);
        $openid 	= trim(addslashes($_REQUEST['openid']));
        $uid = trim(addslashes($_REQUEST['unionid']));
        $nickname 	= addslashes($_REQUEST['nickName']);
        $headPhoto 	= addslashes($_REQUEST['headPhoto']);
        $headPhoto = str_replace("/0","/132" , $headPhoto);
        $r = explode('-',$_REQUEST['r']);

//        if (strpos($phone_os, 'MicroMessenger') === false) {
//            //非微信浏览器禁止浏览
//            $this->load->view('client/tip', []);
//            return;
//        }
        if ($openid == '0000') {
            $data['msg'] = '获取用户信息失败，请重新进入游戏。';
            $this->load->view('client/tip', $data);
            return;
        }

        $result = $this->fragment_model->Give($openid,$uid,$nickname,$headPhoto,$r);

        $this->load->view("client/index", $result);

    }

    //处理索要的碎片
    function Ask(){
        $phone_os 	= addslashes($_SERVER['HTTP_USER_AGENT']);
        $openid 	= trim(addslashes($_REQUEST['openid']));
        $uid = trim(addslashes($_REQUEST['unionid']));
        $nickname 	= addslashes($_REQUEST['nickName']);
        $headPhoto 	= addslashes($_REQUEST['headPhoto']);
        $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可
        $r = explode('-',$this->input->get('r'));
//        if (strpos($phone_os, 'MicroMessenger') === false) {
//            //非微信浏览器禁止浏览
//            $this->load->view('tip', []);
//            return;
//        }

        if ($openid == '0000' || $openid == '') t_error(1,'获取用户信息失败');

        $result = $this->fragment_model->Ask($openid,$uid,$nickname,$headPhoto,$r);
        $this->load->view("client/index", $result);
    }


    //领取碎片
    function toReceiveSuipian(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $share_id = $this->input->post('share_id');
        $rand = $this->input->post('rand');
        if($share_id == '' || $rand== '')  t_error(4,'信息不全');


        $result = $this->fragment_model->toReceiveSuipian($uid,$share_id,$rand);
        t_json($result);
    }

    //赠送碎片
    function toSendSuipian(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $share_id = $this->input->post('share_id');
        $rand = $this->input->post('rand');
        if($share_id == '' || $rand== '')  t_error(5,'信息不全');
        $result = $this->fragment_model->toSendSuipian($uid,$share_id,$rand);
        t_json($result);
    }

    function test(){
//        print_r(trim(addslashes($_REQUEST['uid'])));exit;
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');

        $rand = $this->input->post('rand');
        $share_id = $this->input->post('share_id');

        $result = $this->fragment_model->toSendSuipian($uid,$share_id,$rand);
//        $result = $this->fragment_model->dayPutnum();
        t_json($result);
    }

    //赠送记录
//    function giveRecord(){
//        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
//        $result = $this->fragment_model->giveRecord($uid);
//        t_json($result);
//    }
//
//    //索要记录
//    function askRecord(){
//        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
//        $result = $this->fragment_model->askRecord($uid);
//        t_json($result);
//    }

    //合成
    function composeFragment(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->composeFragment($uid);
        t_json($result);
    }

    //获得记录
    function getRecord(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->getRecord($uid);
        t_json($result);
    }
    //分享记录
    function shareRecord(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->shareRecord($uid);
        t_json($result);
    }
}