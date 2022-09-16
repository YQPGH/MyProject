<?php defined('BASEPATH') OR exit('No direct script access allowed');

//酷码活动（龙币兑换乐豆）
//include_once 'Base.php';

class Kuma extends CI_Controller
{
    private $key = 'YCCQPROPQSC';
    private $query_key = 'YCCQQueryPROPQSC';
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/kuma_model');
        $this->load->model('api/user_model');
    }

    public function saveProp(){
        $openid = trim($this->input->post('openid'));
        $type = trim($this->input->post('type'));
        $sign = trim($this->input->post('sign'));
        if($openid=='' || $type=='' || $sign==''){
            echo json_encode(array('code'=>'100000', 'msg' => '参数不全', 'data'=>array()));
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
        if(!($this->checkSaveProp($sign,$type,$openid))){
            echo json_encode(array('code'=>'200000', 'msg' => '校验有误', 'data'=>array()));
            exit;
        }

        $result = $this->kuma_model->saveProp($uid,$type);
        if($result['insert_id']){
            unset($result['insert_id']);
            echo json_encode(array('code'=>'000000', 'msg' => '保存成功！', 'data'=>$result));
            exit;
        }else{
            echo json_encode(array('code'=>'300000', 'msg' => '保存失败！', 'data'=>array()));
            exit;
        }

    }

    public function queryProp(){
        $openid = trim($this->input->post('openid'));
        //$type = trim($this->input->post('type'));
        $sign = trim($this->input->post('sign'));
        if($openid=='' || $sign==''){
            echo json_encode(array('code'=>'100000', 'msg' => '参数不全', 'data'=>array()));
            exit;
        }
        $check = $this->user_model->queryUid($openid);
        if(!$check) {
            echo json_encode(array('code'=>'000000', 'msg' => '道具为空', 'data'=>array()));
            exit;
        }
        if(!($this->checkQueryProp($sign,$openid))){
            echo json_encode(array('code'=>'200000', 'msg' => '校验有误', 'data'=>array()));
            exit;
        }
        $result = $this->kuma_model->queryProp($check);
        if(!empty($result)){
            echo json_encode(array('code'=>'000000', 'msg' => '成功', 'data'=>$result));
            exit;
        }else{
            echo json_encode(array('code'=>'000000', 'msg' => '道具为空', 'data'=>array()));
            exit;
        }
    }

    private function checkSaveProp($sign,$type,$openid){
        if($sign == md5($this->key.$type.$openid)){
            return true;
        }else{
            return false;
        }
    }

    private function checkQueryProp($sign,$openid){
        if($sign == md5($this->query_key.$openid)){
            return true;
        }else{
            return false;
        }
    }

    public function sign(){
        echo md5($this->key.'8e86daca29025c5d7ba00e363b938256'.'oREekjnJ9TXGXdo7Dq6XHb9zgFqA');
    }

    // 获取道具列表
    public function lists()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $list = $this->kuma_model->propLists($uid);

        t_json($list);
    }

    public function getProp(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        //$id = intval($this->input->post('id'));
        //if($id<0) t_error(1, '参数有误');
        $list = $this->kuma_model->getProp($uid);
        t_json($list);
    }



}