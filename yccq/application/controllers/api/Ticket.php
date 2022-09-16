<?php defined('BASEPATH') OR exit('No direct script access allowed');

//种子培育中心
//include_once 'Base.php';

class Ticket extends CI_Controller
{
    private $key = 'YCCQTicket';
    private $query_key = 'YCCQQueryTicket';
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ticket_model');
        $this->load->model('api/user_model');
    }

    public function getTicket(){
        $openid = trim($this->input->post('openid'));
        $type = intval($this->input->post('type'));
        if($openid=='') t_error(1, '参数有误');
        $check = $this->user_model->checkUserByOpenid($openid);
        if(!$check) t_error(2, '用户不存在！');
        $result = $this->ticket_model->getTicket($openid, $type);
        t_json($result);
    }

    public function queryTicket(){
        $openid = trim($this->input->post('openid'));
        $ticket_id = trim($this->input->post('ticket_id'));
        $sign = trim($this->input->post('sign'));
        if($openid=='' || $ticket_id=='' || $sign==''){
            echo json_encode(array('code'=>'100000', 'msg' => '参数不全', 'data'=>array()));
            exit;
        }
        $check = $this->user_model->checkUserByOpenid($openid);
        if(!$check) {
            echo json_encode(array('code'=>'200000', 'msg' => '用户不存在', 'data'=>array()));
            exit;
        }
        if(!($this->chekQueryTicket($sign,$ticket_id,$openid))){
            echo json_encode(array('code'=>'300000', 'msg' => '校验有误', 'data'=>array()));
            exit;
        }
        //判断是否一个月抵扣了两次
        $checkNum = $this->ticket_model->queryNumMonth($openid);
        if($checkNum >= 5) {
            echo json_encode(array('code'=>'400000', 'msg' => '本月使用代金券次数已到上限，请下月再来！', 'data'=>array()));
            exit;
        }
        $result = $this->ticket_model->queryTicket($openid, $ticket_id);
        if($result){
            if($result['stat']==0){
                if(strtotime($result['vali']) >  time() ){
                    echo json_encode(array('code'=>'000000', 'msg' => '代金券有效！', 'data'=>$result));
                    exit;
                }else{
                    echo json_encode(array('code'=>'600000', 'msg' => '代金券已过期！', 'data'=>$result));
                    exit;
                }
            }else{
                echo json_encode(array('code'=>'700000', 'msg' => '代金券已使用！', 'data'=>$result));
                exit;
            }
        }else{
            echo json_encode(array('code'=>'500000', 'msg' => '代金券不存在', 'data'=>array()));
            exit;
        }
    }

    public function subTicket(){
        $openid = trim($this->input->post('openid'));
        $ticket_id = trim($this->input->post('ticket_id'));
        $sign = trim($this->input->post('sign'));
        if($openid=='' || $ticket_id=='' || $sign=='') t_error(1, '参数有误');
        $check = $this->user_model->checkUserByOpenid($openid);
        if(!$check) t_error(2, '用户不存在！');
        if(!($this->checkMD5($sign,$ticket_id,$openid))) t_error(3, '校验有误！');
        //判断是否一个月抵扣了五次
        $checkNum = $this->ticket_model->queryNumMonth($openid);
        if($checkNum >= 2) t_error(4, '本月使用代金券次数已到上限，请下月再来！');
        $result = $this->ticket_model->subTicket($openid, $ticket_id);

        t_json($result);
    }

    private function checkMD5($sign,$ticket_id,$openid){
        if($sign == md5($this->key.$ticket_id.$openid)){
            return true;
        }else{
            return false;
        }
    }

    private function chekQueryTicket($sign,$ticket_id,$openid){
        if($sign == md5($this->query_key.$ticket_id.$openid)){
            return true;
        }else{
            return false;
        }
    }

    public function sign(){
        echo md5($this->key.'8e86daca29025c5d7ba00e363b938256'.'oREekjnJ9TXGXdo7Dq6XHb9zgFqA');
    }

    public function queryNumMonth(){
        $openid = 'oREekjiRuGdjHb02mS66ATi7MFKo';

        $checkNum = $this->ticket_model->queryNumMonth($openid);
        print_r($checkNum);
    }

}