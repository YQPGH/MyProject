<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 转盘抽奖
 */

include_once 'Base_model.php';
class Turntable_model extends Base_model{
    function __construct(){
        parent::__construct();
        $this->table='zy_turntable_prize_config';
        $this->load->model('api/user_model');
    }

    //活动时间
    function is_activity($uid,$type){

        $time = config_item('activity_time');
        $start_time = strtotime($time['turntable_starttime']);
        $end_time = strtotime($time['turntable_endtime']);
//        $today = date("Y-m-d 00:00:00");
//        $today = strtotime($today);
        $today = $this->time->today();
        $sql = "select draw_times,game_lv from zy_user where uid=?";
        $res = $this->db->query($sql,[$uid['uid']])->row_array();

        if(time() >$start_time && time()< $end_time){

            if($res['draw_times'] < 5){
                if($type == 'xxl'){

                    $sql = "select * from xxl_record where uid=? AND status=? AND add_time>'$today'";
                    $query = $this->db->query($sql,[$uid['uid'],1])->row_array();
                    if($query){
                        return 0;
                    }else{
                        $this->update_total($uid['uid']);
                        $this->table_update('xxl_record', ['status' => 1], ['uid' =>$uid['uid'],'code'=>$uid['code']]);
                        return 1;
                    }
                }else if($type == 'wb'){

                    $sql = "select * from zy_hunt_record where uid=?  AND  add_time>'$today' AND status=?";
                    $row = $this->db->query($sql,[$uid['uid'],1])->row_array();
                    if($row){
                        return 0;
                    }else{
                        $this->update_total($uid['uid']);
                        $this->table_update('zy_hunt_record', ['status' => 1], ['uid' =>$uid['uid'],'id'=>$uid['id']]);
                        return 1;
                    }
                }else if($type == 'bake'){

                    $get_sql = "select count(*) as num from zy_bake_record where uid=? AND times_status=? AND  update_time>'$today'";
                    $get_row = $this->db->query($get_sql,[$uid['uid'],1])->row_array();

//                    $get_row = $this->column_sql('*',['uid' => $uid['uid'],'start_time >'=>$today,'times_status' => 0],'zy_bake_record',0);
                    if($get_row['num']>0) return 0;

                    $sql = "select * from zy_bake_record where uid=?  AND  start_time>'$today' ORDER BY id DESC ";
                    $row = $this->db->query($sql,[$uid['uid']])->row_array();
                    $rand_num = rand(1,100);

                    if($res['game_lv']>3  && !empty($row)  &&  $row['times_status'] == 0 && $rand_num>70) {
                        $this->update_total($uid['uid']);
                        $this->table_update('zy_bake_record', ['times_status' => 1,'update_time' =>t_time()], ['uid' =>$row['uid'],'id'=>$row['id']]);
                        return 1;
                    }else{
                        return 0;
                    }


                }else if($type == 'aging'){
                    $get_sql = "select count(*) as num from zy_aging_record where uid=?  AND status=? AND  update_time>'$today'";
                    $get_row = $this->db->query($get_sql,[$uid['uid'],1])->row_array();

//                    $get_row = $this->column_sql('*',['uid' => $uid['uid'], 'start_time >'=>$today,'status' => 0],'zy_aging_record',0);
                    if($get_row['num']>0) return 0;

                    $sql = "select * from zy_aging_record where uid=?  AND  start_time>'$today' ORDER BY id DESC ";
                    $row = $this->db->query($sql,[$uid['uid']])->row_array();

                    $rand_num = rand(1,100);
                    if($res['game_lv']>3  && !empty($row) && $row['status'] == 0  && $rand_num>60) {

                        $this->update_total($uid['uid']);
                        $this->table_update('zy_aging_record', ['status' => 1,'update_time' =>t_time()], ['uid' =>$uid['uid'],'id'=>$row['id']]);
                        return 1;
                    }else{

                        return 0;
                    }

                }else if($type == 'process'){

                    $get_sql = "select count(*) as num from zy_process_record where uid=? AND status=? AND  update_time>'$today'";
                    $get_row = $this->db->query($get_sql,[$uid['uid'],1])->row_array();
//                    $get_row = $this->column_sql('*',['uid' => $uid['uid'],'start_time >'=>$today,'status' => 0],'zy_process_record',0);
                    if($get_row['num']>0) return 0;
                    $sql = "select * from zy_process_record where uid=?  AND  start_time>'$today' ORDER BY id DESC ";
                    $row = $this->db->query($sql,[$uid['uid']])->row_array();
                    $rand_num = rand(1,100);
                    if($res['game_lv']>3  && !empty($row) &&  $row['status'] == 0  && $rand_num>10) {
                        $this->update_total($uid['uid']);
                        $this->table_update('zy_process_record', ['status' => 1,'update_time' =>t_time()], ['uid' =>$uid['uid'],'id'=>$row['id']]);
                        return 1;
                    }else{
                        return 0;
                        }
                }
            }else{
                return 0;
            }

        }else{

            return 0;
        }
    }

    //更新用户抽奖机会
    function update_total($uid){

       $this->db->set('draw_times','draw_times + 1',false);
       $this->db->where('uid',$uid);
       $this->db->update('zy_user');
    }


    //抽奖机会
    function draw_times($uid){
        $sql = "select * from zy_turntable_record where uid=? ORDER BY id DESC ";
        $row = $this->db->query($sql,[$uid])->row_array();
        $time = config_item('activity_time');
        $end_time = strtotime($time['turntable_endtime']);
        //判断当前是否是当天
        $today_time =  strtotime(date('Y-m-d'));
        $last_time = strtotime($row['add_time']);
        if(time() < $end_time && $last_time < $today_time){
            $this->table_update('zy_user',['ledou_draw_times'=>20],['uid'=>$uid]);

        }
        $result = $this->column_sql('draw_times,ledou_draw_times',array('uid'=>$uid),'zy_user',0);
        return $result;
    }

    /**
     * 开始抽奖
     *
     */
    function start($uid){
        $time = config_item('activity_time');
        $end_time = strtotime($time['turntable_endtime']);
        if(time() > $end_time) t_error(1,'活动已结束!');
        $this->db->trans_start();

        $sql = "select * from zy_turntable_record where uid=? ORDER BY id DESC ";
        $row = $this->db->query($sql,[$uid])->row_array();
        //判断当前是否是当天
        $today_time =  strtotime(date('Y-m-d'));

        $res = $this->column_sql('uid,draw_times,ledou_draw_times,last_time',array('uid'=>$uid),'zy_user',0);
        // 乐豆抽奖,每日上限20次
        if(strtotime($row['add_time'])>$today_time && $res['draw_times'] == 0 && $res['ledou_draw_times'] == 0) t_error(2,'今日抽奖次数已上限，请明日再来！');

        if($res['draw_times'] == 0){
            if($res['ledou_draw_times'] != 0){
                $this->user_model->money($uid,0,-10);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 32,
                    'ledou' => -10,
                    'money' => 0,
                ]);
                $this->db->set('ledou_draw_times', 'ledou_draw_times-1', FALSE);
                $this->db->where('uid', $uid);
                $this->db->update('zy_user');
            }
        }else{
            $this->db->set('draw_times', 'draw_times-1', FALSE);
            $this->db->where('uid', $uid);
            $this->db->update('zy_user');
        }


            $result = $this->prize_return();
        


        if ($result['type'] == 'shop') {
            if($result['id'] == 2 || $result['id'] == 6 || $result['id'] == 8){
                //查询商品表的详情
                $row_shop = $this->db->query("select type4,json_data from zy_shop WHERE shopid=$result[shopid]")->row_array();
                $temp = json_decode($row_shop['json_data'], true);
                //京东卡的记录
                $openid = $this->user_model->queryOpenidByUid($uid);
          
                $goods_id = $temp['goodsId'];
                $card_id = t_rand_str($uid);
                $cardValue = $temp['mubiao'];
                $retrun_data = $this->ziyunExchangeGoods($openid,$goods_id,$card_id,$cardValue);
                if($retrun_data['status'] == 0){
                    $data = array(
                        'shopid' => $result['shopid'],
                        'goods_id' => $goods_id,
                        'card_id' => $card_id,
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_jdk_record', $data);
                    $this->db->set("card_{$result['id']}_num", "card_{$result['id']}_num-1", FALSE);
                    $this->db->where('id', 1);
                    $this->db->update('zy_turntable_set');

                    $this->db->set("give_num", "give_num-1", FALSE);
                    $this->db->where('id', $result['id']);
                    $this->db->update($this->table);


                    //京东卡直接存入zy_ticket_record表
                    $data_card = array(
                        'shopid' => $result['shopid'],
                        'ticket_id' => t_rand_str($uid),
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_ticket_record', $data_card);

                }else{
                    //保存接口返回错误信息，方便查错
                    $data = array(
                        'status' => $retrun_data['status'],
                        'message' => $retrun_data['message'],
                        'shopid' => $result['shopid'],
                        'goods_id' => $goods_id,
                        'card_id' => $card_id,
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_jdk_error', $data);

                    $result = $this->prize_shop();

                }


            }else{
                $this->store_model->update_total($result['num'], $uid, $result['shopid']);
                $this->log_save($uid,$result);
            }
        }
        $this->table_insert('zy_turntable_record',
            [
                'uid' => $uid,
                'pid' => $result['id'],
                'add_time' => t_time()
            ]);

        // 奖品入库
        if ($result['type'] == 'money') {
            $this->user_model->money($uid, $result['num'],0);
            $this->log_save($uid,$result);
        }

        if ($result['type'] == 'shandian') {
            $this->user_model->shandian($uid, $result['num']);
            $this->log_save($uid,$result);
        }
        $this->db->trans_complete();

        unset($result['rate']);
        unset($result['give_num']);
        unset($result['id']);
        return $result;
    }

    function log_save($uid,$result){
        $res = $this->column_sql('id',array('type1'=>12,'type2'=>$result['id']),'zy_prize',0);

        // 奖品日志保存
        $insert_id = $this->table_insert('log_prize', [
            'uid' => $uid,
            'prize_id' => $res['id'],
            'xh_jifen' => 0,
            'xh_shopid' => 0,
            'add_time' => t_time(),
        ]);
    }

    //如果返回京东卡失败，则随机返回其他奖品
    function prize_shop(){
        $arr = [1,4,5];
        $rand_key = array_rand($arr,1);
        $result = $this->column_sql('*',array('id'=>$arr[$rand_key]),$this->table,0);
        $result['index'] = $result['id'];

        unset($result['update_time']);
        return $result;
    }



    function prize_return(){
        $lists = $this->column_sql('*',array('give_num'=>0),$this->table,1);
        if(!empty($lists)){
            foreach($lists as $value){
                $this->update(['rate' => 0],['id' => $value['id']]);
            }
            $sql = "select * from $this->table WHERE give_num>? AND rate>?   ORDER BY id ASC";
            $prize_array = $this->db->query($sql,[0,0])->result_array();
//            $prize_array = $this->db->query("select * from $this->table  ORDER BY id ASC")->result_array();
        }else{
//            $prize_array = $this->db->query("select * from $this->table ORDER BY id ASC")->result_array();
            $sql = "select * from $this->table WHERE give_num>? AND rate>?   ORDER BY id ASC";
            $prize_array = $this->db->query($sql,[0,0])->result_array();
        }

        foreach($prize_array  as $key=>$value){
            $proArr[$value['id']] = $value['rate'];
        }


        $prize_id = $this->get_rand_num($proArr);

        $result = $this->column_sql('*',array('id'=>$prize_id),$this->table,0);
        $result['index'] = $prize_id;

        unset($result['update_time']);
        return $result;
    }

    private function get_rand_num($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {

            $randNum = mt_rand(0, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }

        unset ($proArr);
        return $result;
    }

//京东卡，调用中烟商城接口，生成订单
    public function ziyunExchangeGoods($openid,$goodsId,$cardId,$cardValue){
        $key = '0ekr1cb3e77a338f92f43f220i9d8978';
        $data['goodsId'] = $goodsId;
        $data['cardId'] = $cardId;
        $data['cardValue'] = $cardValue;
        $data['openId'] = $openid;
        $data['sign'] = md5($openid.$key.$cardId);

//        $url = 'http://ld.haiyunzy.com/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //测试地址
        $url = 'http://ld.thewm.cn/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //正式生产地址

        $return = $this->http($url,$data,true);
        return $return;
    }

    //模拟POST提交
    function http($url, $data = NULL, $json = false){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if($json && is_array($data)){
                $data = json_encode( $data ,JSON_UNESCAPED_UNICODE);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($json){
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array(
                        'Content-type: application/json',
                        'Content-Length:' . strlen($data))
                );
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            var_dump("错误：".$errorno);
            return array('errorno' => false, 'errmsg' => $errorno);
        }
        curl_close($curl);
        //var_dump('数据：'.$res);
        return json_decode($res, true);

    }
}

