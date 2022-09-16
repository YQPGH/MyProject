<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */


class Ldzxmarch extends CI_Controller
{

    private $key = 'YCCQMARCHPRIZE';
//    private $url = 'https://gametest.gxziyun.com/yccq/client/index ';
    private $url ='https://xccq.th00.com.cn/yccq/api/Main/index';
        function __construct()
    {
        parent::__construct();
        $this->load->model('api/ldzxmarch_model');
        $this->load->model('api/user_model');
    }



    function getPrize()
    {

        $openid = trim(addslashes($_REQUEST['openid']));

        $sign 	= $_REQUEST['sign'];
        $activityId=$_REQUEST['activityId'];
        $orderId = $_REQUEST['orderId'];

        if($openid==''){
            $msg = '用户不存在';
            $jsoncallback = json_encode(array('status'=>'2005', 'msg' => $msg));
            echo  $jsoncallback;
            $this->postErrorlog($_REQUEST,$msg);
            exit;
        }
        if($sign=='' ){
            $msg = '参数不全';
            $jsoncallback = json_encode(array('status'=>'2000', 'msg' => $msg));
            echo  $jsoncallback;
            $this->postErrorlog($_REQUEST,$msg);
            exit;
        }
        if(!($this->checkSave($sign,$activityId,$orderId,$openid))){
            $msg = '签名无效';
            $jsoncallback =  json_encode(array('status'=>'2002', 'msg' => $msg));
            echo  $jsoncallback;
            $this->postErrorlog($_REQUEST,$msg);
            exit;
        }

        $check = $this->user_model->queryUid($openid);

        if($check) {
            $uid = $check;
        }else{
            //如果用户不存在，需为初始化用户信息
            $nickname = '';
            $headPhoto = '';
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);
        }

        $result = $this->ldzxmarch_model->getPrize($uid,$activityId,$orderId);

        if($result['insert_id']){
            unset($result['insert_id']);
            $jsoncallback = json_encode(array('status'=>'0', 'msg' => '操作成功！','url'=>$this->url));
            echo  $jsoncallback;
            exit;
        }else{
            $msg = '未定义错误！';
            $jsoncallback = json_encode(array('status'=>'9999', 'msg' => $msg));
            echo  $jsoncallback;
            $this->postErrorlog($_REQUEST,$msg);
            exit;
        }
    }


    //校验
    private function checkSave($sign,$activityId,$orderId,$openId){
        if($sign == strtoupper(MD5($activityId.$orderId.$openId.$this->key))){
            return true;
        }else{
            return false;
        }
    }

    function receivePrize()
    {
        $uid = $this->input->post('uid');
        if(!$uid) t_error(1,'该用户不存在');
        $result = $this->ldzxmarch_model->receivePrize($uid);
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
        $this->db->insert('zy_ldzxmarch_errorlog',$post_data);
    }

    function queryPrize()
    {
        $uid = $this->input->post('uid');
        $result = $this->ldzxmarch_model->queryPrize($uid);
        t_json($result);
    }
}