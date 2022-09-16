<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  用户
 */

include_once 'Base_model.php';

class Hunt_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_hunt_record';
        $this->load->model('api/store_model');
        $this->load->model('api/user_model');
    }

    function getPass($uid)
    {
        //$query = $this->db->query("select pass,play_times,hunt_update_time from zy_user where uid='$uid'")->row_array();
        $sql = "select pass,play_times,hunt_update_time from zy_user where uid=?";
        $query =  $this->db->query($sql, array($uid))->row_array();
        //判断是否今天第一次进入游戏
        $today = date("Y-m-d");
        if ($query['hunt_update_time'] < $today) {
            $update = array(
                'play_times' => 3,
                'hunt_update_time' => date("Y-m-d H:i:s")
            );
            $this->db->where('uid', $uid);
            $this->db->update('zy_user', $update);
        }

        //$res = $this->db->query("select pass,play_times from zy_user where uid='$uid'")->row_array();
        $sql = "select pass,play_times from zy_user where uid=?";
        $res =  $this->db->query($sql, array($uid))->row_array();
        $result['my_pass'] = $res;
        $result['my_record'] = $this->getRecord($uid);
        return $result;
    }

    function  score($uid, $score, $pass)
    {

        //$res = "select pass from zy_user where uid='$uid'";
        //$this->db->trans_start();
        //$my_result = $this->db->query($res)->row_array();
        $sql = "select pass from zy_user where uid=?";
        $my_result =  $this->db->query($sql, array($uid))->row_array();
        if (!empty($my_result)) {
            if ($pass > $my_result['pass'] && $pass <= 6) {
                $update = array(
                    'uid' => $uid,
                    'pass' => $pass,
                    'score' => $score,
                    'hunt_update_time' => date("Y-m-d H:i:s"),
                );
                $this->db->where('uid', $uid);
                $this->db->update('zy_user', $update);
            }
        }
        //存入game_record表，每关每天限制通过一次
        $data = array(
            'uid' => $uid,
            'pass' => $pass - 1,
            'score' => $score,
            'add_time' => date("Y-m-d H:i:s"),
        );
        $record_id = $this->insert($data);

        //获取相应的奖励
        $pass = $pass - 1;
        //$list = $this->db->query("select id,name,type1,type2,money,shop1,shop1_total,shop2,shop2_total,rate from zy_hunt_prize WHERE pass=$pass")->result_array();
        $sql = "select id,name,type1,type2,money,shop1,shop1_total,shop2,shop2_total,rate from zy_hunt_prize WHERE pass=?";
        $list = $this->db->query($sql, array($pass))->result_array();
        $temp_arr = array();
        $temp_int = 0;
        $money = 0;
        if (!empty($list)) {
            foreach ($list as $key => &$value) {
                if ($value['rate'] == 100) {
                    if ($value['money'] != 0) {
                        // 更新银元
                        $this->db->set('money', 'money+' . $value['money'], FALSE);
                        $this->db->where('uid', $uid);
                        $this->db->update('zy_user');
                        $money = $value['money'];
                        //保存奖励记录
                        $this->db->set('money', $value['money'], FALSE);
                        $this->db->where('id', $record_id);
                        $this->db->update('zy_hunt_record');
                    }
                }else{
                    $value['start_rate'] = $temp_int;
                    $temp_int += $value['rate'];
                    //$value['end_rate'] = $temp_int - 1;
                    $value['end_rate'] = $temp_int; //此处不 减1 ，是因为数据库概率为99时，也能百分百中奖
                    $temp_arr[] = $value;
                }
            }

        }
        //return $temp_arr;exit();
        //按概率获取奖励
        $rand_key = rand(0,99);
        if(!empty($temp_arr)){
            foreach($temp_arr as $key=>$value){
                if($rand_key >= $value['start_rate'] && $rand_key <= $value['end_rate']){
                    $prize_arr = $value;
                    break;
                }
            }
        }
        $val = [
            'uid' => $uid,
            'id' => $record_id
        ];
        $this->load->model('api/turntable_model');
        $times['draws_times'] = $this->turntable_model->is_activity($val,'wb');

        $this->load->model('api/fragment_model');
        $suipian  = $this->fragment_model->get_fragment($uid,'wb');

        if(count($suipian)>0){
            $sql = "select thumb from zy_shop WHERE shopid=? ";
            $query = $this->db->query($sql,[$suipian['shop']])->row_array();

            $arr['money'] = $money;
            $arr['shop1_name'] = $suipian['name'];
            $arr['shop1_thumb']= $query['thumb'];
            $arr['shop1_total'] = $suipian['total'];
            $arr['shop2_thumb'] = '';
            $arr['shop2_name'] = '';
            $arr['shop2_total'] = 0;
            $result['prize'] = $arr;
        }elseif($times['draws_times'] == 1){
            $sql = "select shopid,name,thumb,total from zy_shop WHERE type1=? ";
            $query = $this->db->query($sql,['zhuanpan'])->row_array();
            $arr['money'] = $money;
            $arr['shop1_name'] = $query['name'];
            $arr['shop1_thumb']= $query['thumb'];
            $arr['shop1_total'] = $query['total'];
            $arr['shop2_thumb'] = '';
            $arr['shop2_name'] = '';
            $arr['shop2_total'] = 0;
            $result['prize'] = $arr;
        }else{
            //获取具体奖励名称
            if(!empty($prize_arr)){
                if($prize_arr['shop1']!=0 || $prize_arr['shop2']!=0){
                    if($prize_arr['shop1']!=0){
                        //$query = $this->db->query("select shopid,name,thumb from zy_shop WHERE type1='$prize_arr[type1]' AND type2=$prize_arr[shop1]")->result_array();
                        $sql = "select shopid,name,thumb from zy_shop WHERE type1=? AND type2=?";
                        $query = $this->db->query($sql,[$prize_arr['type1'],$prize_arr['shop1']])->result_array();
                        $shop_key = array_rand($query);
                        $arr['shop1_thumb'] = $query[$shop_key]['thumb'];
                        $arr['shop1_name'] = $query[$shop_key]['name'];
                        $arr['shop1_total'] = $prize_arr['shop1_total'];
                        // 更新仓库表
                        //$this->update_total($prize_arr['shop1_total'],$uid,$query[$shop_key]['shopid']);
                        $this->store_model->update_total($prize_arr['shop1_total'],$uid,$query[$shop_key]['shopid']);
                        //保存奖励记录
                        $this->db->set('shop1_name', "'".$query[$shop_key]['name']."'", FALSE);
                        $this->db->set('shop1_total', $prize_arr['shop1_total'], FALSE);
                        $this->db->where('id', $record_id);
                        $this->db->update('zy_hunt_record');
                    }else{
                        $arr['shop1_name'] = '';
                        $arr['shop1_thumb']= '';
                        $arr['shop1_total'] = 0;
                    }
                    if($prize_arr['shop2']!=0){
                        //$query = $this->db->query("select shopid,name,thumb from zy_shop WHERE type1='$prize_arr[type1]' AND type2=$prize_arr[shop2]")->result_array();
                        $sql = "select shopid,name,thumb from zy_shop WHERE type1=? AND type2=?";
                        $query = $this->db->query($sql,[$prize_arr['type1'],$prize_arr['shop1']])->result_array();
                        $shop_key = array_rand($query);
                        $arr['shop2_thumb'] = $query[$shop_key]['thumb'];
                        $arr['shop2_name'] = $query[$shop_key]['name'];
                        $arr['shop2_total'] = $prize_arr['shop2_total'];
                        // 更新仓库表
                        //$this->update_total($prize_arr['shop2_total'],$uid,$query[$shop_key]['shopid']);
                        $this->store_model->update_total($prize_arr['shop2_total'],$uid,$query[$shop_key]['shopid']);
                        //保存奖励记录
                        $this->db->set('shop2_name', "'".$query[$shop_key]['name']."'", FALSE);
                        $this->db->set('shop2_total', $prize_arr['shop2_total'], FALSE);
                        $this->db->where('id', $record_id);
                        $this->db->update('zy_hunt_record');
                    }else{
                        $arr['shop2_thumb'] = '';
                        $arr['shop2_name'] = '';
                        $arr['shop2_total'] = 0;
                    }
                    $arr['money'] = $money;
                    $result['prize'] = $arr;

                }
            }else{
                $arr['money'] = $money;
                $arr['shop1_name'] = '';
                $arr['shop1_thumb']= '';
                $arr['shop1_total'] = 0;
                $arr['shop2_thumb'] = '';
                $arr['shop2_name'] = '';
                $arr['shop2_total'] = 0;
                $result['prize'] = $arr;
            }
        }



        //$this->db->trans_complete();
        $result['my_record'] = $this->getRecord($uid);
        //$res = $this->db->query("select pass from zy_user where uid='$uid'")->row_array();
        $sql = "select pass from zy_user where uid=?";
        $res = $this->db->query($sql,[$uid])->row_array();
        $result['my_pass'] = $res;

        $result['draws_times'] = $times['draws_times'];
        return $result;
        //$result['my_record'] = $this->getRecord($uid);

    }

    //获取今日游戏通关记录
    function getRecord($uid){
        $today = date("Y-m-d");
        //$res =  "select pass from zy_hunt_record where uid='$uid' AND add_time > '$today' ORDER BY pass ASC";
        //$result = $this->db->query($res)->result_array();
        $sql =  "select pass from zy_hunt_record where uid=? AND add_time > '$today' ORDER BY pass ASC";

        $result = $this->db->query($sql,[$uid])->result_array();

        return $result;
    }

    //更新数据库，挑战券的数量
    function updatePlayTimes($uid){
        $this->db->set('play_times', 'play_times-1', FALSE);
        $this->db->where('uid', $uid);
        $this->db->update('zy_user');
        //$res = $this->db->query("select play_times from zy_user where uid='$uid'")->row_array();
        $sql = "select play_times from zy_user where uid=?";
        $res = $this->db->query($sql,[$uid])->row_array();
        $result['my_pass'] = $res;
        return $result;
    }

    //消耗乐豆
    function beans($uid){
        $this->user_model->money($uid,0,-2);
        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 30,
            'ledou' => -2,
            'money' => 0,
        ]);
        //$res = $this->db->query("select ledou from  zy_user WHERE uid='$uid'")->row_array();
        $sql = "select ledou from  zy_user WHERE uid=?";
        $res = $this->db->query($sql,[$uid])->row_array();
            /*if($res['ledou']>2){
                $this->db->set('ledou','ledou-2', FALSE);
                $this->db->where('uid', $uid);
                $this->db->update('zy_user');
            }else{
                t_error(3, '你的乐豆不足，请稍后再来');
            }*/
        $result['bean'] = $res;

        return $result;
    }





}
