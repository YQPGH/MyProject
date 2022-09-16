<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * 碎片管理
 */

include_once 'Base_model.php';

class Fragment_model extends Base_model
{

    //正式使用
    private $aiapi_appid = 'YCCQYXKomjmtiDD29LBPS';
    private $aiapi_source  = 'YCCQYX';
    private $aiapi_sign_method    = 'md5';
    private $wl_url = 'http://lywlif.zl88.cn/Interface/IFAddV4.ashx';
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_fragment';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');
        $this->load->model('api/friend_model');

    }

    function is_activity($uid){
        $time = config_item('suipian_time');
        $start_time = strtotime($time['start_time']);
        $end_time = strtotime($time['end_time']);
        if(time()>$start_time && time()<$end_time){
            return 0;
        }else{
            return 1;
        }
    }
    //查询是否有足够的碎片发放
    function dayPutnum($type){

//        $arr = [1,2,3,4,5,6];//对应碎片A B C D E F
//        $rand_key = array_rand($arr,1);
        $row = $this->db->query("select number_{$type} from zy_fragment_set WHERE id=1")->row_array();

        if($row["number_{$type}"] > 0){
            $index = $type;
        }else{
//            $index = $arr[$rand_key];
            $index = 0;
        }
        return $index;
    }

    //返回碎片类型
    function return_suipian(){

        $list  = config_item('suipian_type');
        foreach($list as $key=>$value){
            $proArr[$value['id']] = $value['rate'];
        }
        $typeid = $this->get_rand_num($proArr);
        $index = $this->dayPutnum($typeid);

        return $index;
    }

    //碎片 获取
    function get_fragment($uid,$type)
    {

        $rand = rand(0, 99);
        $this->db->trans_start();
        $time = config_item('suipian_time');
        $start_time = strtotime($time['start_time']);
        $end_time = strtotime($time['end_time']);
        $name = config_item('fragment_rate');
        $max_num = config_item('suipian_maxnum');
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        if(!$row){
            $this->insert([
                'uid' => $uid,
                'is_newer_scan' =>1,
                'add_time' => t_time(),
                'update_time' => t_time()
            ]);
        }
        $type_num = $this->return_suipian();
        $row_shop = $this->db->query("select shopid shop,name,total from zy_shop WHERE type1='suipian' AND type2=$type_num")->row_array();
        $user_lv = $this->column_sql('game_lv',['uid'=>$uid],'zy_user',0);

        if($row_shop && time()>$start_time && time()<$end_time){
            if ($rand < $name[$type]['rate'] && $row['max_num']<$max_num && $user_lv['game_lv']>3) {
                    $this->fragment_total($uid,$type_num,$name[$type]['name']);
                    $result['shop'] = $row_shop['shop'];
                    $result['name'] = $row_shop['name'];
                    $result['total'] = $row_shop['total'];
            }else{
                $result = array();
            }
        }else{
            $result = array();

        }


        $this->db->trans_complete();
        return $result;
    }


    //添加 更新
    function fragment_total($uid, $rand_num,$type)
    {
        $this->db->set('number_'.$rand_num, 'number_'.$rand_num.'+1', FALSE);
       if($type == '新手礼包'){
           $this->db->set('max_num', 'max_num+0', FALSE);
       }else{
           $this->db->set('max_num', 'max_num+1', FALSE);
       }
        $this->db->set('today_num','today_num+1',FALSE);
        $this->db->set('update_time', t_time());
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $this->insert_record($uid,$rand_num,$type);
        //更新碎片库存
        $suipian_field = "number_{$rand_num}";
        $this->db->query("update zy_fragment_set set  $suipian_field=$suipian_field-1 where id=1");
    }

    //指定表名添加一条记录
    function insert_record($uid,$rand_num,$type){
        $data = [
            'uid' => $uid,
            'type' => $rand_num,
            'resource' => $type,
            'add_time' => t_time()
        ];
        $this->table_insert('zy_fragment_record',$data);
    }


    //查询拥有碎片
    function fragment_num($uid){
        $row = $this->column_sql('number_1,number_2,number_3,number_4,number_5,number_6',['uid'=>$uid],$this->table,0);

        $result['list'] = array();
        $i=1;
        if($row){
            foreach($row as $k=>$v){
                $result['list'][$i] = intval($v);
                $i++;
            }
        }else{
            for($k=1;$k<=6;$k++){
                $result['list'][$k] = intval(0);
            }
        }


        return $result;
    }

    //查询今日获取碎片数量
    function today_num($uid){

        $row = $this->column_sql('today_num num',['uid'=>$uid],$this->table,0);
        if(!$row) $row['num'] = 0;
        return $row;


    }

    //查询合成剩余次数
    function queryKeynum($uid){
        $row = $this->column_sql('key_num num',['uid'=>$uid],$this->table,0);
        if(!$row) $row['num'] = 0;
        return $row;

    }


    // 新手扫码礼包
    function newer_scan($uid){

        $time = $this->is_activity($uid);
        if($time) t_error(3,'活动已结束');
        //  闪电*300  银元*80000 三星云贵烟叶-醇*8
        //  随机碎片*1   三星改良调香书*3 三星津巴布韦种子*8
        $item = config_item('newer_scan_gift');
        $row = $this->column_sql('*',['is_newer_scan'=>0,'uid'=>$uid],'zy_fragment',0);
        //判断是否已经领取过礼包
        if($row && $row['is_newer_scan']==0){

            $sql = "select * from zy_fragment_scan WHERE uid=? AND type>? AND status=? AND is_newer=?";
            $query = $this->db->query($sql,[$uid,0,0,1])->row_array();
            $shop = $this->db->query("select shopid shop,name,total from zy_shop WHERE type1='suipian' AND type2=$query[type]")->row_array();
            if($query){
                $this->db->trans_start();
                $this->db->set('money', 'money+' . $item['money'], FALSE);
                $this->db->set('shandian', 'shandian+' . $item['shandian'], FALSE);
                $this->db->where('uid', $uid);
                $this->db->update('zy_user');

                $this->store_model->update_total($item['shop1_total'], $uid, $item['shop1']);   // 三星改良调香书 3
                $this->store_model->update_total($item['shop2_total'], $uid, $item['shop2']);   //三星津巴布韦种子 8
                $this->store_model->update_total($item['shop3_total'], $uid, $item['shop3']);   //三星云贵烟叶-醇 8

                $this->db->set('number_'.$query['type'], 'number_'.$query['type'].' +1', FALSE)
                    ->set('today_num','today_num+1',false)
                    ->set('is_newer_scan',1)
                    ->set('update_time',t_time())
                    ->where('uid' , $uid)
                    ->update($this->table);

                $this->table_update('zy_fragment_scan',['status' =>1],['id'=>$query['id']]);

                $suipian_field = 'number_'.$query['type'];
                $this->db->query("update zy_fragment_set set  $suipian_field=$suipian_field-1 where id=1");
                $this->db->trans_complete();

                $result['money'] = $item['money'];
                $result['shandian'] = $item['shandian'];
                $result['shop1'] = $item['shop1'];
                $result['shop1_total'] = $item['shop1_total'];
                $result['shop2'] = $item['shop2'];
                $result['shop2_total'] = $item['shop2_total'];
                $result['shop3'] = $item['shop3'];
                $result['shop3_total'] = $item['shop3_total'];
                $result['suipian_name'] = $shop['name'];
                $result['suipian_shop'] = $shop['shop'];
                $result['suipian_total'] = $shop['total'];
                return $result;
            }else{
                t_error(1, '已经领取过新手礼包');
            }
        }else{
            t_error(2, '已经领取过新手礼包');
        }

    }

    //用户扫码返回随机碎片/种子
    function randGetsuipian($uid,$status,$smokeType){

        $this->db->trans_start();
        $sqtime=microtime(true);
//        $type_suipian = $this->return_suipian();
        $type_suipian = rand(1,5);
        $today = strtotime(t_time(0,0));
        //查询统计扫码3次得碎片
        $count = $this->count_sql("SELECT COUNT(*) total FROM zy_fragment_scan
                  WHERE uid=? AND UNIX_TIMESTAMP(add_time)>? ORDER BY id DESC LIMIT 10;", [$uid, $today]);

        $eqtime=microtime(true);//获取程序执行结束的时间
        $total=$eqtime-$sqtime;   //计算差值


        $is_prize = 0;
        $is_newer = 0;
        if($status == 0){

            $type_num = ($smokeType==2)?$type_suipian:0;
            $is_newer = 1;
        }
        if($status == 1){
            if($count == 0){
                $type_num = 0;
            }elseif($count == 2) {
                $this->load->model('api/setting_model');
                $suipian_type = $this->setting_model->get('suipian_type');
                $type_num = $suipian_type;
                $is_prize = 1;
            }else{
                $type_num = 0;
            }
        }
        model('task_model')->update_today($uid, 11);//添加每日任务
        model('task_model')->update_today($uid, 12); //添加每日任务
        model('leaf_model')->task_update_today($uid, 2);//叠烟叶每日任务
        model('energytrees_model')->updateTotal($uid,3);


        $etime=microtime(true);//获取程序执行结束的时间
        $totaltime=$etime-$sqtime;   //计算差值
        $code = md5(uniqid($uid));

            $data = [
                'uid'=>$uid,
                'code'=> $code,
                'type'=>$type_num,
                'status'=>0,
                'smokeType' => $smokeType,
                'is_newer' => $is_newer,
                'add_time'=>t_time(),
                'update_time'=>t_time(),
                'time1' =>$total,
                'time2' =>$totaltime,
            ];
            $result['insert_id'] = $this->table_insert('zy_fragment_scan',$data);


        $result['status'] = $status;
        $result['is_prize'] = $is_prize;
        $result['add_time'] = t_time();
        $this->db->trans_complete();

        return $result;

    }



    //索要好友列表
    function friend_list($uid){

        $friend_list = $this->table_lists('zy_friend','friend_uid uid', ['uid' => $uid], 'id', 100);

        $list = [];
        $array = [];

        if(count($friend_list)>0){
            foreach($friend_list as &$value){

                $sql = "select id,suipian_type,status,from_uid from zy_fragment_share WHERE to_uid=? AND from_uid=? ORDER BY id DESC ";
                $type = $this->db->query($sql,[$uid,$value['uid']])->row_array();

                if($type){
                    $user = $this->column_sql('nickname,head_img',['uid'=>$value['uid']],'zy_user',0);
                    $value['nickname'] = $user['nickname'];
                    $value['head_img'] = $user['head_img'];
                    $shop = $this->column_sql('shopid shop',['type1'=>'suipian','type2'=>$type['suipian_type']],'zy_shop',0);
                    $value['shop'] = $shop['shop'];
                    $value['suipian_type'] = $type['suipian_type'];
                    $value['share_id'] = $type['id'];
                    if($type['status'] == 0){
                        $value['receive_num'] = 0;
                    }else{
                        $value['receive_num'] = 1;
                    }
                    $value['is_myask'] = 0;
                    unset($value['uid']);
                    $list[] = $value;
                }
            }
        }
        $get_sql = "select id,suipian_type,status from zy_fragment_share WHERE  from_uid=? ORDER BY id DESC ";
        $my_type = $this->db->query($get_sql,[$uid])->row_array();
        if($my_type){
            $user = $this->column_sql('nickname,head_img',['uid'=>$uid],'zy_user',0);

            $array['nickname'] = $user['nickname'];
            $array['head_img'] = $user['head_img'];
            $shop = $this->column_sql('shopid shop',['type1'=>'suipian','type2'=>$my_type['suipian_type']],'zy_shop',0);

            $array['shop'] = $shop['shop'];
            $array['suipian_type'] = $my_type['suipian_type'];
            $array['share_id'] = $my_type['id'];
            $array['receive_num'] = 0;
            $array['is_myask'] = 1;
            $my_lists[] = $array;

        }
        if(count($friend_list)>0 && count($my_lists)>0){
            $lists = array_merge($my_lists,$list);
            $receive_num = array_column($lists,'receive_num');
            $is_myask = array_column($lists,'is_myask');
            array_multisort($is_myask,SORT_DESC,$receive_num,SORT_DESC,$lists);
            return $lists;
        }else if($my_type && empty($friend_list)){
            return $my_lists;
        }else if(count($friend_list)>0 && empty($my_type)){
            return $list;
        }



    }

    /**
     * 处理索要的碎片
     */
    function Ask($uid,$type){

        $this->db->trans_start();
        $today = strtotime(t_time(0,0));
        $sql = "select * from zy_fragment_share WHERE from_uid=? AND UNIX_TIMESTAMP(add_time)>$today";
        $res =$this->db->query($sql,[$uid])->row_array();
        if($res) t_error(3,'今日碎片索要已发布，请明日再来');
        //获取我的好友列表
        $list = $this->table_lists('zy_friend','id,friend_uid uid, code',['uid' => $uid],'id DESC',100,0);

        $rand = t_rand_str();
        if(count($list)>0){
            foreach($list as $value){
                $data = [
                    'from_uid' =>$uid,
                    'to_uid' => $value['uid'],
                    'share_type'=> 2,
                    'suipian_type'=> $type,
                    'rand' => $rand,
                    'add_time' => t_time(),
                ];
                $result['share_id'] = $this->table_insert('zy_fragment_share', $data);
            }
        }else{
            $data = [
                'from_uid' =>$uid,
                'to_uid' => '',
                'share_type'=> 2,
                'suipian_type'=> $type,
                'rand' => $rand,
                'add_time' => t_time(),
            ];
            $result['share_id'] = $this->table_insert('zy_fragment_share', $data);
        }

        $this->db->trans_complete();
    }


    /**
     * 赠送碎片
     * 玩家发出索要碎片链接后，另外的玩家点击链接，点击“确定”赠送，前端调用此方法
     */
    function toSendSuipian($uid,$share_id){

        $sql = "select * from zy_fragment_share WHERE  to_uid=? AND id=? ";
        $row =$this->db->query($sql,[$uid,$share_id])->row_array();

        $today = strtotime(date('Y-m-d'));
        $sql = "select count(*) as num from zy_fragment_share WHERE  from_uid=?  AND UNIX_TIMESTAMP(add_time)>? AND status=?";
        $res =$this->db->query($sql,[$row['from_uid'],$today,1])->row_array();

        if($res['num'] >=1) t_error(3,'该好友今日获赠次数已上限');
        //判断是否存在该分享记录
        if ($row) {
            //如果没人赠送
            if ($row['status'] == 0) {
                    //判断是否有足够的碎片赠送
                    $suipian_id = $this->getSuipian($uid, $row['suipian_type']);
                  if ($suipian_id > 0) {
                        $this->db->trans_start();
                        //更新用户表的碎片获得情况
                        $this->db->set('number_'.$row['suipian_type'], "number_$row[suipian_type]-1", FALSE);
                        $this->db->set('update_time', t_time());
                        $this->db->where('uid', $uid);
                        $this->db->update($this->table);
                        $is_exit = $this->column_sql('*',['uid'=>$uid],$this->table,0);

                        if($is_exit){
                            $this->db->set('number_'.$row['suipian_type'], "number_$row[suipian_type]+1", FALSE);
                            $this->db->set('is_receive','is_receive+1',false);
                            $this->db->set('today_num','today_num+1',false);
                            $this->db->set('update_time', t_time());
                            $this->db->where('uid', $row['from_uid']);
                            $this->db->update($this->table);
                        }else{
                            $this->insert([
                                'uid' => $row['from_uid'],
                                'number_'.$row['suipian_type'] => 1,
                                'is_newer_scan' =>1,
                                'today_num'=>1,
                                'add_time' => t_time(),
                                'update_time' => t_time()
                            ]);
                        }
                        //更新碎片的拥有者
                        $this->table_update('zy_fragment_share',['status'=> 1,'receive_time'=>t_time()],['id' => $share_id]);
                        $msg = '赠送成功';
                        $this->db->trans_complete();
                        return $msg;
                  } else {
                        t_error(4,'碎片不足');
                     }
            }else{
                t_error(5,'赠送过了');
            }
        }
    }


    /**
    * 检查是否有足够的碎片
    */
    function getSuipian($uid,$type){

        $sql = "select id from $this->table WHERE  uid=? AND number_{$type} > ?";
        $res = $this->db->query($sql,[$uid,0])->row_array();

        if($res){
            return $res['id'];
        }else{
            return 0;
        }
    }


    //别人向自己索要与自己主动赠送记录
    function shareRecord($uid){
        $type = config_item('suipian_type');

        $sql = "select  from_uid, to_uid, share_type, suipian_type, receive_time from zy_fragment_share WHERE to_uid=?  AND share_type=? AND status=? AND   DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= receive_time ORDER BY receive_time DESC ";
        $list = $this->db->query($sql,[$uid,2,1])->result_array();

        foreach($list as &$value){

            $nickname = $this->column_sql('nickname',['uid'=>$value['from_uid']],'zy_user',0);
            $value['type'] = $type[$value['suipian_type']]['name'];
            $value['nickname'] = $nickname['nickname'];
            unset($value['from_uid']);
            unset($value['to_uid']);
            unset($value['share_type']);
            unset($value['suipian_type']);
        }
        $result['list'] = $list;
        return $result;
    }

    //获得记录
    function getRecord($uid){
        $type = config_item('suipian_type');
        $list = $this->db->query("select type,resource,add_time from zy_fragment_record  where uid='$uid' AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= add_time ORDER BY id DESC  limit 1000")->result_array();
        $arr = $this->db->query("select code,type,update_time add_time from zy_fragment_scan  where uid='$uid' AND status=1 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= update_time  ORDER BY id DESC  limit 1000")->result_array();
        $sql = "select  to_uid, share_type, suipian_type type, receive_time add_time from zy_fragment_share WHERE from_uid=?  AND share_type=? AND status=? AND   DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= receive_time ORDER BY receive_time DESC ";
        $get_arr = $this->db->query($sql,[$uid,2,1])->result_array();
        $array = array_merge($list,$arr, $get_arr);

        foreach($array as &$value){

            if($value['to_uid']){
                $nickname = $this->column_sql('nickname',['uid'=>$value['to_uid']],'zy_user',0);
                if($value['type'] == 2) $type1 = '赠送';
                $value['resource'] = $nickname['nickname'].$type1;
                unset($value['to_uid']);
                unset($value['share_type']);
            }
            $value['type'] = $type[$value['type']]['name'];
            if($value['code']){
                $value['resource'] = '扫码';
                unset($value['code']);
            }

        }
        $add_time = array_column($array,'add_time');
        array_multisort($add_time,SORT_DESC,$array);
        $result['list'] = $array;
        return $result;
    }

    //更新用户碎片领取信息
    function updatefragment($uid,$type,$id){
        $this->db->trans_start();
//        $this->db->set("number_{$type}", "number_{$type}+1", FALSE)
//            ->set('today_num','today_num+1',false)
//            ->set('update_time',t_time())
//            ->where('uid' , $uid)
//            ->update($this->table);
        $this->table_update('zy_fragment_scan',['status'=>1],['uid'=>$uid,'id'=>$id]);
        //更新碎片库存
//        $suipian_field = "number_{$type}";
//        $this->db->query("update zy_fragment_set set  $suipian_field=$suipian_field-1 where id=1");
        $this->db->trans_complete();
    }

    function test($uid){

//        $is_exit = $this->prize_blacklist($uid);
//        $is_exit = $this->getBlackprize();

//        return $is_exit;
        //更新奖品表
//        $time = t_time();
//        $code = 'fdgvdr333';
//        $affected_rows = $this->table_update('zy_fragment_prize_record',['status'=>1,' update_time'=>$time],['code'=>$code]);

//        return $affected_rows;
    }

    //合成
    function composeFragment($uid){
        $status = $this->is_activity($uid);
        if($status == 1) t_error(3,'活动已结束');
        $this->db->trans_start();
        $row = $this->column_sql('number_1,number_2,number_3,number_4,number_5,number_6',['uid'=>$uid],$this->table,0);
        if($row['number_1']>0 && $row['number_2']>0 && $row['number_3']>0 && $row['number_4']>0 && $row['number_5']>0 && $row['number_6']>0){
            $insert_id = $this->table_insert('zy_fragment_compose',[
                'uid'=>$uid,
                'add_time'=>t_time()

            ]);
            //更新用户碎片 钥匙数量
            $this->db->set('number_1', 'number_1 -1', FALSE)
                ->set('number_2', 'number_2 -1', FALSE)
                ->set('number_3', 'number_3 -1', FALSE)
                ->set('number_4', 'number_4 -1', FALSE)
                ->set('number_5', 'number_5 -1', FALSE)
                ->set('number_6', 'number_6 -1', FALSE)
                ->set('key_num', 'key_num +1', FALSE)
                ->set('update_time',t_time())
                ->where('uid' , $uid)
                ->update($this->table);

        }else{
            t_error(2,'您的碎片不足');
        }
        $this->db->trans_complete();
    }

    //拼图碎片抽奖
    function prize_exchange($uid){

        $this->db->trans_start();
        $prize_time = $this->column_sql('update_time',['uid'=>$uid],$this->table,0);
        if(time()-strtotime($prize_time['update_time'])<3) t_error(3,'时间过短，请稍后再试');
        //是否有足够的碎片 建立行锁
        $row = $this->db->query("SELECT * FROM zy_fragment_compose WHERE uid=? AND status=? FOR UPDATE;",[$uid,0])->row_array();
        if($row){
            $total = $this->table_count('zy_fragment_prize_record',['uid'=>$uid]);

            $is_exit = $this->prize_blacklist($uid);
            if($is_exit || $total!=0){
                $result = $this->getBlackprize();
            }else{

                    $result = $this->getPrize();


            }

            $this->table_update('zy_fragment_compose',['status'=>1,'update_time'=>t_time()],['id'=>$row['id']]);
            //更新用户合成次数
            $this->db->set('key_num', 'key_num -1', FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
            if($result['prize']['shopid']){
                $id = md5(uniqid($uid)); //奖品订单id
                $sql = "select * from zy_fragment_prize_record WHERE uid=? and id=?";
                $my_prize_record = $this->db->query($sql,[$uid,$id])->row_array();
                if($result['prize']['type2'] <4 && !$my_prize_record){
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    //调用获取物料领取地址接口，获取领奖地址
                    $my_prize_type = 'A'.$result['prize']['type2'];//正式领取需要传值
                    //$my_prize_type = 'A4';//测试值
                    //$res = $this->testqueryWlUrl($openid,$my_prize_type , $id);
                    $res = $this->queryWlUrl($openid, $my_prize_type ,$id);

                    if($res['AIAPI_Res_Code'] == 0 &&  $res['URL']){
                        $prize_data['uid'] = $uid;
                        $prize_data['code'] = $id;
                        $prize_data['shopid'] = $result['prize']['shopid'];
                        $prize_data['url'] = $res['URL'];
                        $prize_data['add_time'] = t_time();
                        $this->db->insert('zy_fragment_prize_record' , $prize_data);
                        //数据库数量减1
                        $this->db->set('total', 'total-1', FALSE)
                            ->set('update_time',t_time())
                            ->where('id' , $result['prize']['id'])
                            ->where('type1' , 14)
                            ->update('zy_prize');
                        $this->db->set("num_{$result['prize']['type2']}", "num_{$result['prize']['type2']}-1", FALSE)
                            ->where('id' , 1)
                            ->update('zy_fragment_prize_config');
                    }else{
                        $error_data['uid'] = $uid;
                        $error_data['aiapi_res_code'] = $res['AIAPI_Res_Code'];
                        $error_data['aiapi_res_error'] = $res['AIAPI_Res_Error'];
                        $error_data['code'] = $id;
                        $error_data['prize_type'] = $my_prize_type;
                        $error_data['url'] = $res['URL'];
                        $error_data['status'] = 0;
                        $error_data['add_time'] = t_time();

                        $this->db->insert('zy_fragment_prize_error' , $error_data);
                        $result = $this->prize_shop();
                    }
                }else{
                    $this->store_model->update_total($result['prize']['shop1_total'],$uid,$result['prize']['shopid']);
                }
            }

            if($result['prize']['money']){
                $this->user_model->money($uid, $result['prize']['money'], 0);  //银元
            }
            if($result['prize']['shandian']){
                $this->user_model->shandian($uid, $result['prize']['shandian']); //闪电
            }
            if($result['prize']['type2'] >3){
                $this->log_save($uid, $result['prize']['id'], 0, 0);
            }

            $result['list'] = $this->other_prize($result['prize']['id']);
            unset($result['prize']['id']);
            unset($result['prize']['type2']);

        }else{

            t_error(2,'请先合成拼图！');
        }
        $this->db->trans_complete();
        return $result;

    }

    //显示剩下的奖品
    function other_prize($prizeid){

        $list = $this->db->query("select money,shandian,shop1 shopid,shop1_total from zy_prize WHERE type1=14 AND id NOT IN ($prizeid)")->result_array();
        return $list;

    }
    //显示所有奖品
    function prize_lists($uid){

        $list['list'] = $this->db->query("select money,shandian,shop1 shopid,shop1_total from zy_prize WHERE type1=14 ")->result_array();
        return $list;

    }
    //如果返回的奖品信息有误，则随机获取其他奖品
    function prize_shop(){
        $arr = [5,6];
        $rand_key = array_rand($arr,1);
        $result['prize'] = $this->column_sql('id,money,shandian,shop1 shopid,shop1_total,type2',['type1'=>14,'type2'=>$arr[$rand_key]],'zy_prize',0);

        return $result;
    }

    function testgetPrize(){


        $sql = "select id,get_rate,type2 from zy_prize WHERE type1=? AND total>?";
        $list = $this->db->query($sql,[14,0])->result_array();
        $row = $this->db->query("select * from zy_fragment_prize_config WHERE id=1;")->row_array();
        foreach($list as $key=>$value){
            if($value['type2'] <4 && $row['num_'.$value['type2']] == 0){
                unset($value);
            }else{
                $proArr[$value['id']] = $value['get_rate'];
            }
        }

        $prize_id = 381;
        $sql = "select id,money,shandian,shop1 shopid,shop1_total,type2 from zy_prize WHERE id=? AND type1=?";
        $result['prize'] = $this->db->query($sql,[$prize_id,14])->row_array();
        return $result;
    }


    //随机获取奖品
    function getPrize(){


        $sql = "select id,get_rate,type2 from zy_prize WHERE type1=? AND total>?";
        $list = $this->db->query($sql,[14,0])->result_array();
        $row = $this->db->query("select * from zy_fragment_prize_config WHERE id=1;")->row_array();
        foreach($list as $key=>&$value){
            if($value['get_rate'] == 0 && $value['type2'] == 3)  $value['get_rate'] = 0.33;
//            if($value['type2'] == 2) $value['get_rate'] = -1;
            if($value['type2'] <4 && $row['num_'.$value['type2']] == 0){
                unset($value);
            }else{
                $proArr[$value['id']] = $value['get_rate'];
            }
        }

        $prize_id = $this->get_rand_num($proArr);
        $sql = "select id,money,shandian,shop1 shopid,shop1_total,type2 from zy_prize WHERE id=? AND type1=?";
        $result['prize'] = $this->db->query($sql,[$prize_id,14])->row_array();
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

    function log_save($uid, $prize_id, $jifen = 0, $shopid = 0){

        // 奖品日志保存
        $insert_id = $this->table_insert('log_prize', [
            'uid' => $uid,
            'prize_id' => $prize_id,
            'xh_jifen' => $jifen,
            'xh_shopid' => $shopid,
            'add_time' => t_time(),
        ]);
    }

    function testqueryWlUrl($openid,$prize_type , $id){
        $result['URL'] = 'https://www.baidu.com';
        $result['status'] = 0;
        $result['AIAPI_Res_Code'] = 0;
        return $result;
    }

    //查询物料地址
    function queryWlUrl($openid,$prize_type , $id )
    {
        if(!$openid || !$id ){
            return false;
        }
        $time = date('YmdHis');
        $data['aiapi_appid'] = $this->aiapi_appid;
        $data['aiapi_source'] = $this->aiapi_source;
        $data['aiapi_timestamp'] = $time;
        $data['Id'] = $id;  //奖品订单
        $data['aiapi_sign_method'] = $this->aiapi_sign_method;
        $data['Type'] = $prize_type;
        $data['qrcode'] = $id;  //与奖品订单一样
        $data['time'] = $time.$this->get_millisecond();
        $data['OpenID'] = $openid;
        //数据有效性签名 MD5(aiapi_appid + aiapi_source + aiapi_timestamp+id+qrcode+type+time) 编码方式为utf-8,输出大写MD5-32位加密值
        $data['aiapi_sign'] = strtoupper(MD5($data['aiapi_appid'].$data['aiapi_source'].$data['aiapi_timestamp'].$data['Id'].$data['qrcode'].$data['Type'].$data['time']));

        $return = $this->https_request($this->wl_url, $data);
//        var_dump( $return );exit;
        //存入数据库
        $data_log['postdata'] = json_encode($data);
        $data_log['returndata'] = json_encode($return);
        $data_log['ip'] = ip();
        $data_log['openid'] =  $openid;
        $data_log['postUrl'] =  $this->wl_url;
        $data_log['add_time'] = date('Y-m-d H:i:s',strtotime($time));
        $this->db->insert('zy_fragment_trade_log',$data_log);

        return $return;
    }


    function get_millisecond(){
        list($usec, $sec) = explode(" ", microtime());
        return substr((string)$usec,2,4);
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

    //更新奖品状态表
    public function updatePrizeStaus($code,$qr_code,$name,$phone,$type,$order_time,$sign){

        //更新奖品表
        $time = t_time();
        $affected_rows = $this->table_update('zy_fragment_prize_record',['status'=>1,'update_time'=>$time],['code'=>$code]);

        //保存物料系统回推的用户信息
        $insert_data['code'] = $code;
        $insert_data['qr_code'] = $qr_code;
        $insert_data['name'] = $name;
        $insert_data['phone'] = $phone;
        $insert_data['type'] = $type;
        $insert_data['order_time'] = $order_time;
        $insert_data['sign'] = $sign;
        $insert_data['add_time'] = $time;
        $this->db->insert('zy_fragment_message' , $insert_data);

        return $affected_rows;

    }

    function add_error($data, $return, $openid, $url, $ip){
        //存入数据库
        $data_log['postdata'] = json_encode($data);
        $data_log['returndata'] = json_encode($return);
        $data_log['ip'] = $ip;
        $data_log['openid'] =  $openid;
        $data_log['postUrl'] =  $url;
        $data_log['add_time'] = t_time();
        $this->db->insert('zy_fragment_error_log',$data_log);

    }

    function getBlackprize(){
        $sql = "select id,get_rate,type2 from zy_prize WHERE type1=? AND type2>?";
        $list = $this->db->query($sql,[14,3])->result_array();
        foreach($list as $key=>$value){
            $proArr[$value['id']] = $value['get_rate'];
        }

        $prize_id = $this->get_rand_num($proArr);
        $sql = "select id,money,shandian,shop1 shopid,shop1_total,type2 from zy_prize WHERE id=? AND type1=?";
        $result['prize'] = $this->db->query($sql,[$prize_id,14])->row_array();
        return $result;
    }
    function prize_blacklist($uid){
        $user = $this->column_sql('*',['uid'=>$uid,'status'=>1],'zy_prize_black',0);
        $openid = $this->user_model->queryOpenidByUid($uid);
        if($openid && $user) {
            return true; //黑名单中的人，直接返回true
        }else{
            return false;
        }
    }

}
