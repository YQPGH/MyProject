<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  醇化
 */
include_once 'Base_model.php';

class Compensate_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_compensate';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/user_model');
    }

    /**
     *  获取赔偿记录
     *
     * @return int
     */
    function compensateLists($uid)
    {
        //$lists = $this->lists_sql("SELECT id,money,shandian,shopid,shop_num,description FROM $this->table WHERE uid='{$uid}' AND status=0 LIMIT 100;");
        $lists = $this->column_order_sql("id,money,shandian,shopid,shop_num,description",['uid'=>$uid,'status'=>0],"$this->table",'','',100,0,1);
        return $lists;
    }

    /**
     * 领取赔偿
     *
     * @return int
     */
    function getCompensate($uid,$id){
        //查询是否已经领奖
        //$row = $this->db->query("select * from $this->table WHERE id=$id AND uid='$uid'")->row_array();
        $row = $this->column_sql("*",['id'=>$id,'uid'=>$uid],$this->table,$type=0);
        if($row && $row['status']==0){
            $this->db->trans_start();
            if($row['money']){
                $this->user_model->money($uid, $row['money']);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 28,
                    'money' => $row['money'],
                ]);
            }
            if($row['shandian']){
                $this->user_model->shandian($uid, $row['shandian']);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 28,
                    'shandian' => $row['shandian'],
                ]);
            }
            if($row['shopid']){
                //如果获得的是物品，存入仓库
                $shop = $this->shop_model->detail($row['shopid']);
                $this->store_model->update_total($row['shop_num'],$uid,$row['shopid']);
                $openid = $this->user_model->queryOpenidByUid($uid);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 28,
                    'shopid' => $row['shopid'],
                ]);
                //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
                if($shop['type4'] == 'quan'){
                    //根据uid获取openid
                    $data = array(
                        'shopid' => $row['shopid'],
                        'ticket_id' => t_rand_str($uid),
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_ticket_record', $data);
                }
            }
            //更新领取状态
            $this->db->set(['status' => 1, 'update_time' => t_time(),])->where(['id' => $row['id']])->update($this->table);
            $this->db->trans_complete();
            $result['money'] = $row['money'];
            $result['shandian'] = $row['shandian'];
            $result['shopid'] = $row['shopid'];
            $result['shop_num'] = $row['shop_num'];
            return $result;
        }else{
            t_error(2, '赔偿已领取');
        }
    }

}
