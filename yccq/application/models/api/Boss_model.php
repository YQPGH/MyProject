<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/4/18
 * Time: 9:51
 */
include_once 'Base_model.php';

class Boss_model extends Base_model{
    function __construct(){
        parent::__construct();
        $this->table = 'zy_boss';
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
        $result['id'] = $this->table_insert('zy_boss_invite',$data);

        $url = base_url().'api/Main/bossInvite?incode='.$result['id'];

        return ['url'=>$url];
    }

    //是否有好友邀请
    function is_friend_invite($uid,$code){

        $is_exit = $this->column_sql('code',['id'=>$code],'zy_boss_invite',0);

        $row['is_invite'] = $is_exit?1:0;
        $row['is_invite'] = $this->table_count('zy_boss_invite',['id'=>$code]);

        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a, zy_boss_invite b WHERE code='$is_exit[code]' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }

    //绑定界面
    function mentor_binding($uid,$code){
        $this->db->trans_start();
        $is_exit = $this->column_sql('*',['id'=>$code],'zy_boss_invite',0);
        if(!$is_exit) t_error(1, '暂无邀请');

        $row = $this->table_row('zy_boss_record',['uid'=>$is_exit['uid'],'invited_uid'=>$uid]);
        if ($row) t_error(2, '已邀请,不可重复操作');

        if ($uid == $is_exit['uid']) t_error(3, '不能邀请自己');

        //被邀请的人是否已经组队，一个人只能同时组一个队
        $res = $this->row(['uid'=>$uid]);
        //if(!empty($res)&&$res['group_id']) t_error(4, '您已组队，不可同时组两队');

        //发送分享链接的人是否已经组队（判断zy_boss表该用户是否有数据），如果组有队伍，则取出group_id
        $boss_row = $this->row(['uid'=>$is_exit['uid']]);

        //两个均组队了
        if((!empty($boss_row)&&$boss_row['group_id'])&&(!empty($res)&&$res['group_id'])){
            //判断是否该组已经满人
            $group_count = $this->count(['group_id'=>$boss_row['group_id']]);
            if($group_count<4){
                $data = [
                    'uid' => $is_exit['uid'],
                    'invited_uid' => $uid,
                    'code' => $is_exit['code'],
                    'add_time'=> time()
                ];
                $this->table_insert('zy_boss_record',$data);
                $update_boss_data = [
                    'group_id' => $boss_row['group_id'],
                    'update_time'=> t_time()
                ];
                $this->update($update_boss_data,['uid'=>$uid]);
            }else{
                t_error(4, '该组已经满员！');
            }
        }

        //邀请的人组队了，被邀请的人未组队
        if((!empty($boss_row)&&$boss_row['group_id'])&&(empty($res))){
            //判断是否该组已经满人
            $group_count = $this->count(['group_id'=>$boss_row['group_id']]);
            if($group_count<4){
                $data = [
                    'uid' => $is_exit['uid'],
                    'invited_uid' => $uid,
                    'code' => $is_exit['code'],
                    'add_time'=> time()
                ];
                $this->table_insert('zy_boss_record',$data);
                $boss_data = [
                    'group_id' => $boss_row['group_id'],
                    'uid' => $uid,
                    'add_time'=> t_time()
                ];
                $this->insert($boss_data);
            }else{
                t_error(4, '该组已经满员！');
            }
        }

        //邀请的人未组队，被邀请的人已经组队
        if((empty($boss_row))&&(!empty($res)&&$res['group_id'])){
            $data = [
                'uid' => $is_exit['uid'],
                'invited_uid' => $uid,
                'code' => $is_exit['code'],
                'add_time'=> time()
            ];
            $this->table_insert('zy_boss_record',$data);
            $group_data = [
                'name' => 'AAA',
            ];
            $group_id = $this->table_insert('zy_boss_group',$group_data);
            $boss_data_1 = [
                'group_id' => $group_id,
                'uid' => $is_exit['uid'],
                'add_time'=> t_time()
            ];
            $this->insert($boss_data_1);
            $update_boss_data = [
                'group_id' => $group_id,
                'update_time'=> t_time()
            ];
            $this->update($update_boss_data,['uid'=>$uid]);
        }

        //两个均为组队
        if(empty($boss_row)&&empty($res)){
            $data = [
                'uid' => $is_exit['uid'],
                'invited_uid' => $uid,
                'code' => $is_exit['code'],
                'add_time'=> time()
            ];
            $this->table_insert('zy_boss_record',$data);
            $group_data = [
                'name' => 'AAA',
            ];
            $group_id = $this->table_insert('zy_boss_group',$group_data);
            $boss_data_1 = [
                'group_id' => $group_id,
                'uid' => $is_exit['uid'],
                'add_time'=> t_time()
            ];
            $this->insert($boss_data_1);
            $boss_data_2 = [
                'group_id' => $group_id,
                'uid' => $uid,
                'add_time'=> t_time()
            ];
            $this->insert($boss_data_2);
        }

        $this->db->trans_complete();
    }

    //查询当前队伍组队信息
    function boss_group_list($uid){
        $boss_row = $this->row(['uid'=>$uid]);
        $result = [];
        if(!empty($boss_row)&&$boss_row['group_id']){
            $list = $this->lists('uid',['group_id'=>$boss_row['group_id'],'uid !='=>$uid]);
            if(!empty($list)){
               foreach($list as &$value){
                   $row = $this->column_sql('uid,nickname,head_img,game_lv',['uid'=>$value['uid']],'zy_user');
                   $result[] = $row;
               }
            }
            return $result;
        }else{
            $group_data = [
                'name' => 'AAA',
            ];
            $group_id = $this->table_insert('zy_boss_group',$group_data);
            $boss_data = [
                'group_id' => $group_id,
                'uid' => $uid,
                'add_time'=> t_time()
            ];
            $this->insert($boss_data);
        }

    }

    //保存贡献值记录
    function add_boss_value_record($uid,$boss_value,$shopid,$type1,$type2){
        $data = [
            'uid' => $uid,
            'value' => $boss_value,
            'shopid' => $shopid,
            'type1' => $type1,
            'type2' => $type2,
            'add_time'=> time()
        ];
        $this->table_insert('zy_boss_value_record',$data);
    }

    //更新个人总贡献值（可加可减）
    function update_boss_value($uid,$value){
        if($uid ){
            $this->db->set('boss_value', 'boss_value+' . $value, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
        }
        return $this->queryBossValue($uid);
    }

    //获取个人总贡献值
    function queryBossValue($uid){
        $res = $this->column_sql('boss_value',['uid'=>$uid],'zy_boss',0);
        return $res['boss_value'];
    }

    //获取BOSS排行榜
    function bossRanking($uid){
        //根据uid获取group_id
        //$res = $this->column_sql('group_id',['uid'=>$uid],'zy_boss',0);
        //$list =$this->db->query("select a.*,c.nickname from zy_boss a,zy_user c WHERE a.group_id=$res[group_id] AND c.uid=a.uid ORDER BY a.boss_value DESC")->result_array();
        $list =$this->db->query("select a.*,c.nickname from zy_boss a,zy_user c WHERE c.uid=a.uid ORDER BY a.boss_value DESC limit 0,10")->result_array();
        $my_ranking = 0;
        $result = [];
        if(!empty($list)){
           foreach($list as $key=>$value){
                if($value['uid']==$uid){
                    $my_ranking = $key+1;
                }
               $temp['ranking'] = $key+1;
               $temp['nickname'] = $value['nickname'];
               $temp['boss_value'] = $value['boss_value'];
               $result['ranking_list'][] = $temp;
           }
        }
        $result['my_ranking'] = $my_ranking;
        return $result;
    }

    function boss_prize(){
        $value = $this->db->query("select id,money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=79 limit 1")->row_array();
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

    //领取奖励
    function boss_get_prize($uid){
        /*$prize = [
            'money'=>20000,
            'shandian'=>100,
            'shop'=>[
                ['shopid'=>641,'shop_num'=>1]
            ]
        ];*/
        $prize = $this->boss_prize();
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
            }
        }
        $insert_data['uid'] = $uid;
        $insert_data['prizeid'] = $prize['id'];
        $insert_data['add_time'] = t_time();
        $this->db->insert('zy_boss_prize_record', $insert_data);
        $this->db->trans_complete();
    }

    //领取奖励
    function boss_ranking_get_prize($uid){
        $prize = [
            'money'=>8000,
            'shandian'=>50,
            'shop'=>[
                ['shopid'=>632,'shop_num'=>1]
            ]
        ];
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
                    $this->store_model->update_total($value['shop_num'], $uid, $value['shopid']);
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

        $this->db->trans_complete();
    }

    //老用户 等级奖券列表
    function user_lists($uid){

        $list =$this->db->query("select a.id,a.task_1,a.task_2,a.task_3,a.task_4,a.task_5,a.task_6,c.game_lv,c.nickname,c.head_img from zy_laxin_record a,$this->table b,zy_user c WHERE a.uid='$uid' AND b.uid=a.invited_uid AND c.uid=b.uid")->result_array();
        $ticket_num = config_item('prize_task');
        if(count($list)>0){
            foreach($list as &$value){
                for($i=1;$i<=6;$i++){
                    $value['task'][$i]['task_id']  = $i;
                    $value['task'][$i]['ticket_num'] = $ticket_num['task_'.$i]['prize_quan'];
                    if($value['game_lv'] >= $ticket_num['task_'.$i]['game_lv']){
                        if($value['task_'.$i]==0){

                            $value['task'][$i]['is_finish']  = 1;
                            $value['task'][$i]['is_receive'] = 0;
                        }else{
                            $value['task'][$i]['is_finish']  = 1;
                            $value['task'][$i]['is_receive'] = 1;
                        }
                    }else{
                        $value['task'][$i]['is_finish']  = 0;
                        $value['task'][$i]['is_receive'] = 0;
                    }

                }

                unset($value['task_1']);
                unset($value['task_2']);
                unset($value['task_3']);
                unset($value['task_4']);
                unset($value['task_5']);
                unset($value['task_6']);
            }
        }

        return $list;
    }

    //老用户 领取等级奖券
    function receive($uid,$id,$task_id){
        $this->db->trans_start();
        $res = $this->table_row('zy_laxin_record',['uid'=>$uid,'id'=>$id,'task_'.$task_id=>1]);
        if($res) t_error(2,'已领取');
        $ticket_num = config_item('prize_task');
        $time = t_time();
        $this->table_update('zy_laxin_record',['task_'.$task_id=>1,'update_time'=>$time],['id'=>$id]);
          $quan = $ticket_num['task_'.$task_id]['prize_quan'];

          $this->db->set('current_ticket', 'current_ticket +'. $quan, FALSE)
                    ->set('total_ticket', 'total_ticket +'. $quan, FALSE)
                    ->set('update_time', $time)
                    ->where('uid', $uid)
                    ->update($this->table);
        $result['ticket'] = $quan;
        $this->db->trans_complete();

        return $result;

    }

    //新用户1-20级奖品列表
    function lv_prize_list($uid){
        $list = config_item('newer_game_lv_prize');
        $sql = "select game_lv from zy_user WHERE uid=?";
        $lv = $this->db->query($sql,[$uid])->row_array();

        $type_lv = config_item('newer_game_lv_prize');

        foreach($list as $key=>&$value){
            $res = $this->table_row('zy_laxin_invitee_prize',['uid'=>$uid,'ticket_num'=>$type_lv[$key]['ticket_num']]);
            $value['is_receive'] = $res?1:0;
            $value['is_finish'] = 0;
            if($lv['game_lv'] >= $key) $value['is_finish'] = 1;
            $value['id'] = $key;
        }
        $lists['list'] = $list;

        return $lists;
    }

    function lv_get_prize($uid,$id){

        $this->db->trans_start();
        $row = $this->row(['uid'=>$uid,'is_invited'=>1]);

        $type_lv = config_item('newer_game_lv_prize');
        $res = $this->table_row('zy_laxin_invitee_prize',['uid'=>$uid,'ticket_num'=>$type_lv[$id]['ticket_num']]);

        if($res) t_error(2,'已领取');
        if($row){
            $this->user_model->money($uid,$type_lv[$id]['money'],0);
            $this->user_model->shandian($uid,$type_lv[$id]['shandian']);
            $this->db->set('current_ticket','current_ticket + '.$type_lv[$id]['ticket_num'],false)
                ->set('total_ticket','total_ticket + '.$type_lv[$id]['ticket_num'],false)
                ->set('update_time',t_time())
                ->where('uid',$uid)
                ->update($this->table);

            $this->table_insert('zy_laxin_invitee_prize',[
                'uid'=>$uid,
                'ticket_num' =>$type_lv[$id]['ticket_num'],
                'add_time'=>t_time()
            ]);
            $result['money'] = $type_lv[$id]['money'];
            $result['shandian'] = $type_lv[$id]['shandian'];
            $result['ticket_num'] = $type_lv[$id]['ticket_num'];
            $this->db->trans_complete();
            return $result;
        }

    }


    //兑换奖品列表
    function prize_list(){

        $list = $this->column_sql('id,type1,type3,money,shandian,shop1 shopid,shop1_total shop_total,shop2_total,total',['type2'=>'lx'],'zy_prize',1);

        foreach($list as &$value){
            if($value['type1']<16){
                $value['prize_num'] = $value['total'];
            }else{
                $value['prize_num'] = '不限';
            }
            $value['frame'] = 0;
            if($value['type3']== 'frame' ){
                $value['frame'] = $value['shopid'];
                $value['shopid'] = 0;
            }

            $value['ticket_num'] = $value['shop2_total'];
            unset($value['shop2_total']);
            unset($value['type1']);
            unset($value['type3']);
            unset($value['total']);
        }


        return $list;
    }

    //奖品兑换
    function exchange_ticket($uid,$id){

        $this->db->trans_start();
        $row = $this->column_sql('current_ticket',['uid'=>$uid],$this->table,0);
        $sql = "select money,shandian,type1,type3,total,shop1,shop1_total,shop2_total ticket_num from zy_prize where id=?  AND type2=?";
        $prize = $this->db->query($sql,[$id,'lx'])->row_array();
        if($row['current_ticket']<$prize['ticket_num']) t_error(2,'您的奖券不足');

        $total = $this->count_sql("select COUNT(*) total FROM zy_laxin_prize_record WHERE uid=? and pid=?  ",[$uid,$id]);
        $item = config_item('limit_times');

        if($prize['type3'] == 'quan'){
            $ticket_total =  $this->count_sql("select COUNT(*) total FROM zy_laxin_prize_record a,zy_prize p WHERE a.uid=? AND a.pid=p.id AND p.type3=? ",[$uid,'quan']);
            if($ticket_total>=2) t_error(5,'该奖品兑换已上限');
        }

        if($item[$id] && $total >= $item[$id]) t_error(4,'该奖品兑换已上限');

        if($prize['type1']<16){
            if($prize['total']==0) t_error(3,'奖品已兑完');
            $this->db->set('total','total - '.$prize['shop1_total'],false)
                ->set('update_time',t_time())
                ->where('id',$id)
                ->update('zy_prize');
        }

        $this->db->set('current_ticket','current_ticket - '.$prize['ticket_num'],false)
            ->set('update_time',t_time())
            ->where('uid',$uid)
            ->update($this->table);


        // 奖品入库
        if ($prize['money']) {
            $this->user_model->money($uid, $prize['money'],0);
        }

        if ($prize['shandian']) {
            $this->user_model->shandian($uid, $prize['shandian']);
        }
        if($prize['shop1'] && $prize['type3']!='frame'){
            $shop = $this->shop_model->detail($prize['shop1']);
            if ($shop['type4']=='quan' || $shop['type1']=='peifang') {
                $this->store_model->update_total($prize['shop1_total'], $uid, $prize['shop1']);
                if($shop['type4']=='quan'){
                    $openid = $this->user_model->queryOpenidByUid($uid);  //根据uid获取openid
                    $data = array(
                        'shopid' => $prize['shop1'],
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
        if($prize['type3'] == 'shop'){
            $this->table_insert('log_prize', [
                'uid' => $uid,
                'prize_id' => $id,
                'xh_jifen' => 0,
                'xh_shopid' => 0,
                'add_time' => t_time(),
            ]);
        }
        $status = $this->column_sql('shopid',['type1'=>'prize','type2'=>$prize['type1']],'zy_shop',0)?1:0;

        $data = [
            'uid'=>$uid,
            'pid'=>$id,
            'is_real'=> $status,
            'add_time'=>t_time()
        ];
        $this->table_insert('zy_laxin_prize_record',$data);
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

    //查询邀请人数，券数
    function getTicketnum($uid){

        $is_activity = $this->activity_time();

        if(empty($is_activity)) t_error(1,'活动已结束');
        $row = $this->column_sql('current_ticket,total_ticket,invite_num',['uid'=>$uid],$this->table,0);

        if(!$row){
            $this->insert([
                'uid'=>$uid,
                'add_time'=>t_time()
            ]);
            $result['invite_num'] = 0;
            $result['current_num'] = 0;
            $result['total_ticket'] = 0;
        }else{
            $result['invite_num'] = $row['invite_num'];
            $result['current_num'] = $row['current_ticket'];
            $result['total_ticket'] = $row['total_ticket'];

        }

        return $result;

    }


    //保存用户信息
    function savemessage($uid,$id,$truename,$phone,$province,$city,$area,$street){
        $get_sql = "select id,pid,status,add_time from zy_laxin_message where uid=? AND pid=?";
        $row = $this->db->query($get_sql,[$uid,$id])->row_array();
        $a_time = time()-strtotime($row['add_time']);
//        $u_time = time()-strtotime($row['update_time']);
        if($a_time <60 ) t_error(6,'提交时间过短，请稍后再试');
        if($row && $row['status'] == 1) t_error(5,'不可修改');
        $sql = "select uid from zy_laxin_message where uid=? ORDER BY id DESC ";
        $is_exit = $this->db->query($sql,[$uid])->row_array();
        $address = implode(",",array($province,$city,$area,$street));

        $check_sql = "select * from zy_laxin_prize_record where uid=? AND id=?";
        $check = $this->db->query($check_sql,[$uid,$id])->row_array();
        if(empty($check))  t_error(4,'保存失败！');
        $data = [
            'uid'=>$uid,
            'truename'=>$truename,
            'phone'=>$phone,
            'address'=>$address,
            'status'=>0,
            'pid'=>$id,

        ];
        if($is_exit){
//            $sql = "select id,pid from zy_laxin_message where uid=? AND pid=?";
//            $row = $this->db->query($sql,[$uid,$id])->row_array();
            if($row){
                $data['update_time']  = t_time();
                $this->table_update('zy_laxin_message',$data,['uid'=>$uid,'id'=>$row['id']]);
            }else{
                $data['add_time']  = t_time();
                $this->table_insert('zy_laxin_message',$data);
            }
        }else{
            $data['add_time']  = t_time();
            $this->table_insert('zy_laxin_message',$data);
        }


    }

    //查询用户信息
    function getUsermessage($uid,$id){
        $sql = "select uid,truename,phone,address,status,add_time from zy_laxin_message where uid=? AND pid=?";
        $row = $this->db->query($sql,[$uid,$id])->row_array();

        $max_time = 86400;
        if($row){
            if(time()-strtotime($row['add_time'])>$max_time){
                $this->table_update('zy_laxin_message',['status'=>1],['uid'=>$uid,'pid'=>$id]);
                $result['status'] = 1;
            }else{
                $result['status'] = 0;
            }
            $str = explode(',',$row['address']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
        }else{

            $sql = "select truename,phone,address,status,add_time from zy_laxin_message where uid=? ORDER BY id desc";
            $row = $this->db->query($sql,[$uid])->row_array();
            $result['status'] = 0;
            $str = explode(',',$row['address']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            if(empty($row)) $result = [];

        }

        return $result;
    }


    //查询是否已获得限定头像框
    function queryHeaderframe($uid){

        $get_sql = "SELECT *  FROM zy_laxin_prize_record WHERE uid=? AND pid in ?";
        $list = $this->db->query($get_sql, array($uid,array(410,411,412)))->result_array();

        if(count($list)){
            foreach($list as $v){
                $sql = "SELECT type1  FROM zy_prize WHERE id=?";
                $row = $this->db->query($sql, array($v['pid']))->row_array();
                $result[] = $row['type1'];
            }
             return $result;
        }

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