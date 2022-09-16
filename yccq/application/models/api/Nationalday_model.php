<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/9/5
 * Time: 9:39
 */
include_once 'Base_model.php';
class Nationalday_model extends Base_model{

    function __construct(){
        parent::__construct();
        $this->table='zy_national_day';
    }

    function activity(){
        $time = config_item('activity_time');
        $starttime = strtotime($time['national_starttime']);
        $endtime = strtotime($time['national_endtime']);

        if(time()>$starttime && time()<$endtime){

            return true;
        }else{
            return false;
        }

    }

    function prize_list($uid){

        $is_holiday = $this->activity();

        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        $result = [];
        if($is_holiday){
            $prize = config_item('national_day');
            $result['list'] = $prize;
            $result['progress'] = $row['total']; //任务进度
            $result['is_finish'] =  $row['status'];
        }

        return $result;
    }

    function receive($uid){
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        if($row['total']<70) t_error(2,'任务未完成');
        $this->db->trans_start();
        if($row['status']==0){
            $prize = config_item('national_day');
            $this->load->model('api/user_model');
            $this->load->model('api/user_model');
            foreach($prize as $v){
                if($v['money']){
                    $this->user_model->money($uid,$v['money'],0);
                }
                if($v['shandian']){
                    $this->user_model->shandian($uid,$v['shandian']);
                }
                if($v['shopid']){
                    $this->store_model->update_total($v['shop_num'], $uid, $v['shopid']);
                }
            }
            $this->update(['status'=>1],['uid'=>$uid]);
        }else{
            t_error(1,'已领取');
        }
        $this->db->trans_complete();
    }

    function update_num($uid){
        $is_holiday = $this->activity();

        if($is_holiday){
            $sql = "select * from $this->table WHERE uid=?";
            $row = $this->db->query($sql,[$uid])->row_array();
            if($is_holiday && $row){
                $this->db->set('total','total+1',false)
                    ->set('update_time',t_time())
                    ->where('uid',$uid)
                    ->update($this->table);
            }else{
                $data = [
                    'uid'=>$uid,
                    'total'=>1,
                    'add_time'=>t_time()
                ];
                $this->insert($data);

            }

        }

    }
    function test($uid){
        if($uid=='10e4bf3ae3ee123351c5921f9f167bdd' || $uid=='544db551789f6a28b3b3d4a000e18c60' || $uid=='752393d5658218522c284ea3c58aea5d'
            || $uid=='11084d2608c3da4285974fb589f05937') {
            return true;
        }else{
            return false;
        }
    }

}