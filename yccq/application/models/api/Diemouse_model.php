<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/4/18
 * Time: 9:51
 */
include_once 'Base_model.php';

class Diemouse_model extends Base_model{
    function __construct(){
        parent::__construct();
        $this->table = 'zy_diemouse';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
    }

    //活动时间
    function activity_time(){
        $time = config_item('laxin_time');
        $starttime = strtotime($time['start_time']);
        $endtime = strtotime($time['end_time']);
        if(time()>$starttime && time()<$endtime){
            return true;
        }else{
            return false;
        }
    }

    //邀请
    function mentor_invite($uid){
        $code = t_rand_str();
        $data = [
           'uid' => $uid,
           'code' => $code,
           'add_time' => t_time()
        ];
        $result['id'] = $this->table_insert('zy_diemouse_invite',$data);

        $url = base_url().'api/Main/diemouseInvite?incode='.$result['id'];

        return ['url'=>$url];
    }

    //是否有好友邀请
    function is_friend_invite($uid,$code){

        $is_exit = $this->column_sql('code',['id'=>$code],'zy_diemouse_invite',0);

        $row['is_invite'] = $is_exit?1:0;
        $row['is_invite'] = $this->table_count('zy_diemouse_invite',['id'=>$code]);

        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a, zy_diemouse_invite b WHERE code='$is_exit[code]' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }

    //绑定界面
    function mentor_binding($uid,$code){

        $is_exit = $this->column_sql('*',['id'=>$code],'zy_diemouse_invite',0);
        if(!$is_exit) t_error(1, '暂无邀请');

        $row = $this->table_row('zy_diemouse_record',['uid'=>$is_exit['uid'],'invited_uid'=>$uid]);
        if ($row) t_error(2, '已助力,不可重复操作');

        if ($uid == $is_exit['uid']) t_error(3, '不能邀请自己');

        //每个人每天最多只能助力5次
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',strtotime('+1 day')))-1;
        $num_1 = $this->table_count('zy_diemouse_record', $where = array('uid'=>$is_exit['uid'],'add_time>'=>$today_start,'add_time<'=>$today_end));
        if($num_1>10)t_error(4, '该分享助力已到达次数上限');

        $num_2 = $this->table_count('zy_diemouse_record', $where = array('invited_uid'=>$uid,'add_time>'=>$today_start,'add_time<'=>$today_end));
        if($num_2>10)t_error(5, '您当天助力次数已到达上限');

        $this->db->trans_start();
        $data = [
            'uid' => $is_exit['uid'],
            'invited_uid' => $uid,
            'code' => $is_exit['code'],
            'add_time'=> time()
        ];
        $this->table_insert('zy_diemouse_record',$data);

        //更新用户福气值
        $this->user_model->update_lucky_value($is_exit['uid'],500);
        $this->user_model->update_lucky_value($uid,500);

        $this->db->trans_complete();
    }

    //
    function diemouse_prize(){
        $value = $this->db->query("select id,money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=84 limit 1")->row_array();
        $value['shop'] = [];
        if($value['shopid']&&$value['shop_num']){
            $temp['shopid'] = $value['shopid'];
            $temp['num'] = $value['shop_num'];
            $value['shop'][] = $temp;
        }
        if($value['shopid2']&&$value['shop2_num']){
            $temp2['shopid'] = $value['shopid2'];
            $temp2['num'] = $value['shop2_num'];
            $value['shop'][] = $temp2;
        }
        unset($value['shopid']);
        unset($value['shop_num']);
        unset($value['shopid2']);
        unset($value['shop2_num']);
        return $value;
    }

    //领取叠老鼠奖励
    function diemouse_get_prize($uid){

        $prize = $this->diemouse_prize();
        // 事务开始
        $this->db->trans_start();

        // 奖品入库
        if ($prize['money']) {
            $this->user_model->money($uid, $prize['money']);
        }
        // 奖品入库
        if ($prize['shandian']) {
            $this->user_model->shandian($uid, $prize['shandian']);
        }

        if(!empty($prize['shop'])){
            foreach($prize['shop'] as $key=>$value){
                if ($value['shopid']) {
                    $shop = $this->shop_model->detail($value['shopid']);
                    $this->store_model->update_total($value['num'], $uid, $value['shopid']);
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
                    if($shop['type4'] == 'quan'){
                        //根据uid获取openid
                        $data = array(
                            'shopid' => $prize['shopid'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_ticket_record', $data);
                    }
                }
                //$this->log_save($uid, $prize['id'], 0, $yan_id);
            }
        }
        $insert_data['uid'] = $uid;
        $insert_data['prizeid'] = $prize['id'];
        $insert_data['add_time'] = t_time();
        $this->db->insert('zy_diemouse_prize_record', $insert_data);
        $this->db->trans_complete();
    }



    //是否被邀请用户
    function queryUser($uid){

        $res = $this->row(['uid'=>$uid,'is_invited'=>1]);
        if($res){
            $result['is_invited'] = 1;
        }else{
            $result['is_invited'] = 0;
        }
        return $result;
    }



    function test($uid){


//        $test = $this->table_row('zy_laxin_invite',['uid'=>$uid,'code'=>$a,]);
//        $b = '';
//        $test = 'http://yccq.zlongwang.com/yccq/api/Main/' .$b .$a;
//        print_r($test);
//        $test = $this->queryHeaderframe($uid);
//        if($test) echo 123;exit;
//        print_r($test);exit;
    }


}