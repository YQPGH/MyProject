<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日抽奖
include_once 'Base.php';

class Gift extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/gift_model');
    }

    //乐豆兑换抵扣券
    public function exchange()
    {
        $openid = $this->input->post('openid');
        $type = $this->input->post('type');
        $sign = $this->input->post('sign');
        $activity_type = 3; //1 双十一活动，2双十二活动,3元旦活动
        if(!$openid && !$type && !$sign){
            echo json_encode(['code'=>0,'msg'=>"参数不全"], JSON_UNESCAPED_UNICODE);
            return;
        }
        $key = 'yccqtbag1111cos';
        if($sign != md5($openid.$key.$type)){
            echo json_encode(['code'=>0,'msg'=>"校验错误"], JSON_UNESCAPED_UNICODE);
            return;
        }

        $result = $this->gift_model->exchange($openid,$type,$activity_type);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //查询是否领取双十一乐豆兑换礼物
    public function queryLdChangeGift(){
        $uid = $this->input->post('uid');
        $activity_type = $this->input->post('activity_type');
        //1 双十一活动，2双十二活动,3元旦活动
        $result = $this->gift_model->queryLdChangeGift($uid,$activity_type);
        t_json($result);
    }


    //领取乐豆兑换礼物
    public function getLdChangeGift(){
        $uid = $this->input->post('uid');
        $type = $this->input->post('type');
        $activity_type = $this->input->post('activity_type');
        $result = $this->gift_model->getLdChangeGift($uid,$type,$activity_type);
        t_json($result);
    }




}