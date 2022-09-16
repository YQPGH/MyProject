<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  酷码活动（龙币兑换乐豆）
 */
include_once 'Base_model.php';

class Kuma_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_kuma';
    }

    //保存道具
    public function saveProp($uid,$type){
        //配置文件查询奖励
        $prop_config = config_item('prop_config');
        $prop = $prop_config[$type];
        $prop['uid'] = $uid;
        $prop['status'] = 0;
        $prop['description'] = '龙币兑换乐豆，赠送游戏道具！';
        $prop['add_time'] = t_time();
        $result['insert_id'] = $this->insert($prop);

        $arr = [1=>'一星调香书',2=>'二星调香书',3=>'三星调香书',4=>'四星调香书',5=>'五星调香书',6=>'六星调香书',7=>'七星调香书'];
        $row = $this->column_sql('name,type2',array('shopid'=>$prop['shopid']),'zy_shop',$type=0);
        $result['name'] = $arr[$row['type2']];
        $result['money'] = $prop['money'];
        $result['num'] = $prop['shop_num'];
        $result['add_time'] = $prop['add_time'];

        return $result;
    }

    //查询道具
    public function queryProp($uid){
        $result = $this->lists("money,shopid,shop_num as num, status,add_time",array('uid'=>$uid));
        $arr = [1=>'一星调香书',2=>'二星调香书',3=>'三星调香书',4=>'四星调香书',5=>'五星调香书',6=>'六星调香书',7=>'七星调香书'];
        foreach($result as $key=>&$value){
            $row = $this->column_sql('name,type2',array('shopid'=>$value['shopid']),'zy_shop',$type=0);
            $value['name'] = $arr[$row['type2']];
            $value['status_msg'] = $value['status'] ? '已领取' : '未领取';
            unset($value['shopid']);
        }

        return $result;
    }

    /**
     *  获取道具记录（为了进入游戏弹窗）
     *
     * @return int
     */
    function propLists($uid)
    {
        //$lists = $this->lists_sql("SELECT id,money,shandian,shopid,shop_num,description FROM $this->table WHERE uid='{$uid}' AND status=0 LIMIT 100;");
        $lists = $this->column_order_sql("id,money,shandian,shopid,shop_num,description",['uid'=>$uid,'status'=>0],"$this->table",'','',100,0,1);
        return $lists;
    }

    /**
     * 领取道具
     *
     * @return int
     */
    function getProp($uid){
        //查询是否已经领奖
        //$row = $this->db->query("select * from $this->table WHERE id=$id AND uid='$uid'")->row_array();
        $list = $this->column_sql("*",['status'=>0,'uid'=>$uid],$this->table,$type=1);
        if(!empty($list)){
            $result = array();
            $temp = array();
            foreach($list as $key=>$row){
                if($row && $row['status']==0){
                    $this->db->trans_start();
                    if($row['money']){
                        $this->user_model->money($uid, $row['money']);
                        // 写入交易日志表
                        model('log_model')->trade($uid, [
                            'spend_type' => 31,
                            'money' => $row['money'],
                        ]);
                    }
                    if($row['shandian']){
                        $this->user_model->shandian($uid, $row['shandian']);
                        // 写入交易日志表
                        model('log_model')->trade($uid, [
                            'spend_type' => 31,
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
                            'spend_type' => 31,
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
                    $result['money'] += $row['money'];
                    $result['shandian'] += $row['shandian'];
                    if(!in_array($row['shopid'],$temp)){
                        $temp[] = $row['shopid'];
                        $shop_tmp['shopid'] = $row['shopid'];
                        $shop_tmp['shop_num'] = $row['shop_num'];
                        $result['list'][$row['shopid']] =  $shop_tmp;
                    }else{
                        $result['list'][$row['shopid']]['shop_num'] +=  $row['shop_num'];
                    }
                }else{
                    t_error(2, '道具已领取');
                }
            }

            return $result;
        }

    }

}
