<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  用户的奖品
 */
include_once 'Base_model.php';

class Gift_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_gift_record';
        $this->load->model('admin/store_model');
        $this->load->model('admin/user_model');
    }
    
    public function exchange($openid,$type,$activity_type){
        //查看兑换次数是否超过上限
        $sql = "select COUNT(*) as num from $this->table WHERE openid=? AND type=? AND activity_type=$activity_type";
        $row =  $this->db->query($sql, array($openid,$type))->row_array();
        if($type == 1){
            if($row['num'] >= 1 ) {
                $result = ['code'=>1,'msg'=>"兑换次数已达到上限"];
                return $result;
            }
            $gift_str = '1,2,3,4,5';
            $description = '乐豆兑换游戏道具礼包';
        }else if($type == 2){
            if($row['num'] >= 1 ){
                $result = ['code'=>1,'msg'=>"兑换次数已达到上限"];
                return $result;
            }
            $gift_str = '6,7,8';
            $description = '乐豆兑换游戏抽奖礼包';
        }
        //保存数据
        $data = array(
            'openid' => $openid,
            'gid' => $gift_str,
            'type' => $type,
            'activity_type' => $activity_type,
            'status' => 0,
            'description' => $description,
            'add_time' => t_time()
        );
        $insertid = $this->db->insert($this->table, $data);
        if($insertid){
            $result = ['code'=>0,'msg'=>"ok"];
        }else{
            $result = ['code'=>1,'msg'=>"保存数据失败"];
        }

        return $result;
    }

    public function queryLdChangeGift($uid,$activity_type){
        //根据uid查询openid
        $openid = $this->user_model->queryOpenidByUid($uid);
        if(!$openid || $openid=='') t_error(1, '无此人！');
        $sql = "select * from $this->table WHERE openid=? AND status=? AND activity_type=$activity_type";
        $res =  $this->db->query($sql, array($openid,0))->result_array();
        //return $res;
        if(!empty($res)){
            foreach($res as $key=>$value){
                $temp = explode(",",$value['gid']);
                foreach($temp as $val){
                    $sql = "select type,shopid,num as shop_num from zy_gift_config WHERE id=?";
                    if($value['type']==1){
                        $result['daoju'][] =  $this->db->query($sql, [$val])->row_array();
                    }else{
                        $result['choujiang'][] =  $this->db->query($sql, [$val])->row_array();
                    }
                }
                $result['type'] = $value['type'];
                $result['activity_type'] = $value['activity_type'];
            }
            $result['is_pop'] = 1;

        }else{
            $result['is_pop'] = 0;
            $result['daoju'] = [];
            $result['choujiang'] = [];
            $result['activity_type'] = 0;
        }
        return $result;
    }

    public function getLdChangeGift($uid,$type,$activity_type){
        //根据uid查询openid
        $openid = $this->user_model->queryOpenidByUid($uid);
        if(!$openid || $openid=='') t_error(1, '无此人！');
        $sql = "select * from $this->table WHERE openid=? AND status=? AND type=? AND activity_type=?";
        $res =  $this->db->query($sql, array($openid,0,$type,$activity_type))->result_array();
        if(!empty($res)){
            foreach($res as $key=>$value){
                $this->db->trans_start();
                $temp = explode(",",$value['gid']);
                foreach($temp as $val){
                    $sql = "select type,shopid,num as shop_num from zy_gift_config WHERE id=?";
                    $result['list'][] = $row =  $this->db->query($sql, [$val])->row_array();
                    if($row['type']=='money'){
                        $this->user_model->money($uid, $row['shop_num']);
                        // 写入交易日志表
                        model('log_model')->trade($uid, [
                            'spend_type' => 39,
                            'money' => $row['shop_num'],
                        ]);
                    }
                    if($row['type']=='shandian'){
                        $this->user_model->shandian($uid, $row['shop_num']);
                        // 写入交易日志表
                        model('log_model')->trade($uid, [
                            'spend_type' => 39,
                            'shandian' => $row['shop_num'],
                        ]);
                    }
                    if($row['type']!='money'&&$row['type']!='shandian'&&$row['shopid']>0){
                        //如果获得的是物品，存入仓库
                        $shop = $this->shop_model->detail($row['shopid']);
                        $this->store_model->update_total($row['shop_num'],$uid,$row['shopid']);
                        // 写入交易日志表
                        model('log_model')->trade($uid, [
                            'spend_type' => 39,
                            'shopid' => $row['shopid'],
                        ]);
                        //如果是抵扣券
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
                }
                //更新领取状态
                $this->db->set(['status' => 1,'update_time'=>t_time()])->where(['openid' => $openid,'id'=>$value['id']])->update($this->table);
                $this->db->trans_complete();
            }
            return $result;
        }else{
            t_error(1, '奖品已领取！');
        }


    }






}
