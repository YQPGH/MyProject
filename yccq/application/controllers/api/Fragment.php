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

    //活动时间
    function is_activity(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->is_activity($uid);
        t_json($result);
    }

    //保存扫码获得碎片
    function randGetsuipian(){
        $stime=microtime(true);
        $openid = trim(addslashes($_REQUEST['openid']));
        $sign 	= $_REQUEST['sign'];
        $smokeType = $_REQUEST['smokeType'];
        $callback = isset($_REQUEST['f_callback']) ? trim($_REQUEST['f_callback']) : ''; //jsonp回调参数，必需
//        $openid = "11";
//        $sign 	= "aebacd2d40420b5bd23cf69d6de5c3e5";
//        $smokeType = 2;
//        $callback = 'f_callback';


//        $time = config_item('suipian_time');
//        $end_time = strtotime($time['end_time']);
//        if(time()>$end_time){
//            $jsoncallback = json_encode(array('code'=>'400000', 'msg' => '活动已结束', 'data'=>array()));
//            echo  $callback . '(' . $jsoncallback .')';
//            exit;
//        }
        if($openid==''  || $sign=='' ){
            $msg = '参数不全';
            $jsoncallback = json_encode(array('code'=>'100000', 'msg' => $msg, 'data'=>array()));
            echo  $callback . '(' . $jsoncallback .')';
            $this->postErrorlog($_REQUEST,$msg);
            exit;
        }

        if(!($this->checkSaveSuipian($sign,$openid))){
            $msg = '校验有误';
            $jsoncallback =  json_encode(array('code'=>'200000', 'msg' => $msg, 'data'=>array()));
            echo  $callback . '(' . $jsoncallback .')';
            $this->postErrorlog($_REQUEST,$msg);
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

        $result = $this->fragment_model->randGetsuipian($uid,$status,$smokeType);
        if($result['insert_id']){
            unset($result['insert_id']);
            $msg = '保存成功！';
            $jsoncallback = json_encode(array('code'=>'000000', 'msg' => $msg, 'data'=>$result));
            echo   $callback . '(' . $jsoncallback .')';
            $this->postErrorlog($_REQUEST,$msg);

            $eqtime=microtime(true);//获取程序执行结束的时间
            $total=$eqtime-$stime;   //计算差值
            $this->db->insert('zy_stime',['openid' => $openid,'times'=>$total,'addtime'=>t_time()]);
            exit;
        }else{
            $msg = '保存失败！';
            $jsoncallback = json_encode(array('code'=>'300000', 'msg' => $msg, 'data'=>array()));
            echo  $callback . '(' . $jsoncallback .')';

            $this->postErrorlog($_REQUEST,$msg);
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




    //新用户扫码礼包
    function newer_scan(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->newer_scan($uid);
        t_json($result);
    }

    //碎片数量
    function fragment_num(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->fragment_num($uid);

        t_json($result);
    }

    //今日获得碎片数量
    function today_num(){
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'用户ID不能为空');
        $result = $this->fragment_model->today_num($uid);
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

    //抽奖回推地址
    function updatePrizeStaus(){

        $key = 'zyfragmentjp';                  //正式环境
        $code 	= $_REQUEST['ID'];    //订单id
        $qr_code 	= $_REQUEST['QRCode'];    //物料系统二维码
        $name 	= $_REQUEST['Name'];    //领取人姓名
        $phone 	= $_REQUEST['Phone'];    //领取人电话
        $type 	= $_REQUEST['Type'];    //物料类型
        $order_time = $_REQUEST['OrderTime'];    //订单时间
        $sign 	= $_REQUEST['Sign']; //签名
        if(!($code && $sign)){

            echo json_encode( array('success' => false) );
        }
        if($sign == strtoupper(md5($code.$qr_code.$name.$phone.$type.$order_time.$key))){
            $result = $this->fragment_model->updatePrizeStaus($code,$qr_code,$name,$phone,$type,$order_time,$sign);
            if($result){
                unset($result);
                echo json_encode( array('success' => true) );
            }else{
                echo json_encode( array('success' => false) );
            }
        }else{
            echo json_encode( array('success' => false) );
        }

    }

    //好友列表
    function friend_list(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->friend_list($uid);
        t_json($result);
    }


    //处理索要的碎片
    function Ask(){

        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $type = $this->input->post('type');
        if($type== '') t_error(2,'参数不全');
        $result = $this->fragment_model->Ask($uid,$type);
        t_json($result);

    }


    //赠送碎片
    function toSendSuipian(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $share_id = $this->input->post('share_id');
        if($share_id == '' )  t_error(2,'该分享不存在');
        $result = $this->fragment_model->toSendSuipian($uid,$share_id);
        t_json($result);
    }

    //合成
    function composeFragment(){

        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->composeFragment($uid);
        t_json($result);
    }

    //奖品列表
    function prize_lists(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->fragment_model->prize_lists($uid);
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

    function postErrorlog($data,$msg)
    {

        $post_data = array(
            'openid' =>$data['openid'],
            'postdata'=>json_encode($data),
            'intro' => $msg,
            'addtime' => t_time()
        );
        $this->db->insert('zy_fragment_errorlog',$post_data);
    }

    function test()
    {

//        echo md5(uniqid('abcc'));
        $uid = "abcc";
//        $da = date("w");
//        echo $da;exit;
//        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
//        $result = $this->fragment_model->test($uid);
//print_r(rand(1,5));
//        $result = $this->fragment_model->prizereturn($uid);
//        t_json($result);

    }

}
