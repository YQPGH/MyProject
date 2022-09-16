<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 三月三乐豆中心对接奖品
 * User: Administrator
 * Date: 2021/3/19
 * Time: 15:45
 */

include_once 'Base_model.php';

class Ldzxmarch_model extends Base_model{


    private $key = 'ACTIVITYCALLBACK';
//    private $url = "https://testactcenter.gxtianhai.cn/api/updatePrizeStatus";//测试环境
    private $url = "https://actcenter.th008.cn//api/updatePrizeStatus";//生产环境
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_ldzxmarch_prize_record';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
    }

    //获取领奖资格
    function getPrize($uid,$activityId,$orderId)
    {
        $prize = $this->db->query("select * from zy_prize WHERE type1=?",['march'])->row_array();

        if($prize)
        {
            $result['insert_id'] = $this->table_insert('zy_ldzxmarch_prize_record',[
                'uid' => $uid,
                'pid' => $prize['id'],
                'activityId'=>$activityId,
                'orderId' => $orderId,
                'add_time' => t_time()
            ]);
            return $result;
        }

    }




//更新奖品状态表
    function receivePrize($uid)
    {
        $time = date('YmdHis');
        $days = time()-86400*3;
        $row = $this->db->query("select * from {$this->table}
where uid=? AND status=?",[$uid,0])->row_array();

        if($row && time()-strtotime($row['add_time'])<$days)
        {

            $openid = $this->user_model->queryOpenidByUid($uid);
            $data['activityId'] = $row['activityId'];
            $data['orderId'] = $row['orderId'];  //奖品订单
            $data['openid'] = $openid;
            //数据有效性签名 MD5(activityId+orderId+openid+key) 编码方式为utf-8,输出大写MD5-32位加密值
            $data['sign'] = strtoupper(MD5($data['activityId'].$data['orderId'].$data['openid'].$this->key));
            $return = $this->https_request($this->url, $data);

            //存入数据库
            $data_log['postdata'] = json_encode($data);
            $data_log['returndata'] = json_encode($return);
            $data_log['ip'] = get_real_ip();
            $data_log['openid'] =  $openid;
            $data_log['postUrl'] =  $this->url;
            $data_log['add_time'] = date('Y-m-d H:i:s',strtotime($time));
            $this->db->insert('zy_ldzxmarch_tradelog',$data_log);

            if($return['code']==0)
            {
                $this->db->trans_start();
                $list = $this->db->query("select id,money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num
from zy_prize where type1=?",['march'])->row_array();
                if($list['money']){
                    $this->user_model->money($uid, $list['money']);
                }
                if($list['shandian']){
                    $this->user_model->shandian($uid, $list['shandian']);
                }
                if($list['shopid'] || $list['shopid2']){
                    //如果获得的是物品，存入仓库
//                    $shop = $this->shop_model->detail($list['shopid']);
                    $this->store_model->update_total($list['shop_num'],$uid,$list['shopid']);

//                    $shop2 = $this->shop_model->detail($list['shopid2']);
                    $this->store_model->update_total($list['shop2_num'],$uid,$list['shopid2']);
                    //如果是抵扣券
//                    if($shop['type4'] == 'quan'){
//                        //根据uid获取openid
//                        $data = array(
//                            'shopid' => $list['shopid'],
//                            'ticket_id' => t_rand_str($uid),
//                            'uid' => $uid,
//                            'openid' => $openid,
//                            'stat' => 0,
//                            'addtime' => time()
//                        );
//                        $this->db->insert('zy_ticket_record', $data);
//                    }
                }
                //更新领取状态
                $this->db->set(['status' => 1,'update_time'=>t_time()])->where(['uid' => $uid])->update($this->table);
                // 写入奖品日志表
                model('prize_model')->log_save($uid, $list['id']);
                $this->db->trans_complete();
            }
            else
            {
                t_error(1,$return['msg']);
            }
        }

    }


//是否有奖励
function queryPrize($uid)
{
    $days = 86400*3;

    $row = $this->db->query("select * from {$this->table}
where uid=? and `status`=?",[$uid,1])->row_array();

    $record = $this->db->query("select * from {$this->table}
where uid=? and `status`=?",[$uid,0])->row_array();

    if(time()<strtotime($record['add_time'])+$days)
    {

       if(time()<strtotime($row['add_time'])+$days && $row)
       {
           $result['is_pop'] = 0;
           $result['list'] = [];
       }
        else
        {
            $prize = $this->db->query("select money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num
from zy_prize where type1=?",['march'])->result_array();

            $list = [];
            foreach($prize as &$value)
            {
                if($value['money'])
                {
                    $res['type'] = 'money';
                    $res['num'] = $value['money'];
                    array_push($list,$res);
                }
                if($value['shandian'])
                {
                    $res['type'] = 'shandian';
                    $res['num'] = $value['shandian'];
                    array_push($list,$res);
                }
                if($value['shopid'])
                {
                    $res['type'] = 'shop';
                    $res['shopid'] = $value['shopid'];
                    $res['num'] = $value['shop_num'];
                    array_push($list,$res);
                }
                if($value['shopid2'])
                {
                    $res['type'] = 'shop';
                    $res['shopid'] = $value['shopid2'];
                    $res['num'] = $value['shop2_num'];
                    array_push($list,$res);
                }
            }
            $result['is_pop'] = 1;

            $result['list'] = $list;
        }

    }
    else
    {
        $result['is_pop'] = 0;
        $result['list'] = [];
    }
    return $result;
}
    /**
     * 模拟POST提交数据
     * @param string $url 链接地址
     * @param array $data 数组
     */
    public function https_request($url,$data = null){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output,true);
    }
}