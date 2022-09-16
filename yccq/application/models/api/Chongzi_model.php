<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  虫子
 */
include_once 'Base_model.php';

class Chongzi_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'chongzi_send';
        $this->load->model('api/chongzi_model');
        $this->load->model('api/friend_model');
        $this->load->model('api/user_model');

    }

    //好友列表
    function friend_list($uid){

        $friend_list = $this->friend_model->list_my($uid);

        foreach($friend_list['list'] as &$val){

            // 获取好友id
           $friend_uid = $this->friend_model->get_uid($uid, $val['code']);

            //判断该好友是否已经被放置过虫子
            $sql = "select * from chongzi_send WHERE friend_uid=? ";
            $has_chongzi = $this->db->query($sql,[$friend_uid])->row_array();

            $val['status'] = $has_chongzi['status'];
            $val['start_time'] = $has_chongzi['start_time'];
            $val['stop_time'] = $has_chongzi['stop_time'];
        }


        return $friend_list['list'];

    }


    /**
     * 开始派遣一条虫子放入好友
     * code 好友随机码
     * @return array
     */
    function start($uid, $code,$type)
    {
        $is_return = model('building_model')->query_upgrade($uid,13);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        $friend_uid = $this->friend_model->get_uid($uid, $code);

        $sql = "select nickname,game_lv from zy_user WHERE uid=?";
        $result = $this->db->query($sql,[$friend_uid])->row_array();

        //好友等级5级以上可放害虫
        if($result['game_lv']<=5) t_error(1,'该好友不可放置虫子');

        //判断是否已经购买虫子，并且购买期未过
        $now = t_time();
        $sql = "select count(*) as num,shopid,stop_time,send_status,type from zy_chongzi WHERE uid=? and type=? AND status=0 AND stop_time>'$now' ORDER BY id DESC";
        $zu = $this->db->query($sql,[$uid,$type])->row_array();

        if($zu['num'] == 0) t_error(2, '你尚未购买虫子，请先购买');
        if($zu['type']== $type && $zu['send_status']==1) t_error(3, '该虫子已派遣');
        //判断该好友是否已经被放置过虫子
        $sql = "select count(*) as num from chongzi_send WHERE friend_uid=? AND stop_time>'$now'";
        $has_cz = $this->db->query($sql,[$friend_uid])->row_array();

        if($has_cz['num'] != 0) t_error(4, '该好友已经被放置虫子！');
        // 事务开始
        $this->db->trans_start();
        $result['start_time'] = $this->time->now();

//        $stop_time = strtotime($this->time->time_add_hour(12));//待至12小时

        $time = config_item('time');
        $stop_time = $time['stop_time'];
        $destroy_time = $time['destroy_time'];
        $result['stop_time'] = (strtotime($zu['stop_time']) <=$stop_time)?strtotime($zu['stop_time']):$stop_time;

        $insert_id = $this->insert([
            'uid' => $uid,
            'friend_uid' => $friend_uid,
            'type' => $type,
            'number' => t_rand_str(),
            'start_time' => $result['start_time'],
            'destroy_time' => t_time($destroy_time),
            'stop_time' => t_time($result['stop_time']),
        ]);

        //更新派遣状态
        $this->table_update('zy_chongzi',['send_status' => 1], [
            'uid' => $uid,
            'type' => $type,
        ]);
        $this->db->trans_complete();

    }




    /**
     * 清除虫子
     *
     * @return array
     */
    function clear($uid, $number)
    {
        $is_return = model('building_model')->query_upgrade($uid,13);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        // 事务开始
        $this->db->trans_start();

        // 开始清除
        $rows = $this->update(['status' => 1,'is_count' => 1,'stop_time' => t_time()], [
            'friend_uid' => $uid,
            'number' => $number,
        ]);

        if ($rows){
            $friend_uid = $this->row(['friend_uid'=>$uid]);
            $this->table_update('zy_chongzi',['send_status' => 0], [
                'uid' => $uid,
                'type'=>$friend_uid['type']
            ]);
            $this->get_energy($friend_uid['uid'],$number);
        } else{
            t_error(1, '清除虫子失败');
        }
        $this->db->trans_complete();
        return;
    }


    /**
     * 更新用户商品库存数
     *
     * @return int
     */
    function update_total($uid,$energy)
    {

        $sql = "select energy from zy_user WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();

        if ($row) {
            $this->db->set('energy', 'energy+'.$energy, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update('zy_user');
            $result = $this->db->affected_rows();
        }

        return $result;

    }


    /**
     *  购买虫子
     * */
    function buy_chongzi($uid, $shopid,$type){
        //判断是否已经购买虫子
        $sql = "select count(*) as num,id,status,shopid from zy_chongzi WHERE uid=? and shopid=$shopid";
        $zu = $this->db->query($sql,[$uid])->row_array();
        $time = time();
        if(strtotime($zu['stop_time'])<$time)
        {
            $this->table_update('zy_chongzi',[
                'status' =>1
            ],[
                'uid'=>$uid,
                'shopid' => $shopid
            ]);
        }
//        if($zu['num'] != 0 && strtotime($zu['stop_time'])>$time && $zu['shopid'] == $shopid) t_error(1, '不可重复购买同一条虫子');
        if($zu['num'] != 0 && $zu['status'] ==0 && $zu['shopid'] == $shopid) t_error(1, '不可重复购买同一条虫子');
        //插入购买虫子表
        if($zu['num']!=0 && $zu['shopid'] == $shopid){
            $this->db->set('status', 0);
            $this->db->set('send_status', 0);
            $this->db->set('shopid', $shopid);
            $this->db->set('type', $type);
            $this->db->set('start_time', t_time());
            $this->db->set('stop_time', t_time($time+86400*3));
            $this->db->where('id', $zu['id']);
            $this->db->where('uid', $uid);
            $this->db->update('zy_chongzi');
        }else{
            $this->table_insert('zy_chongzi', [
                'uid' => $uid,
                'shopid' => $shopid,
                'type' => $type,
                'send_status' =>0,
                'status' => 0,
                'start_time' => t_time($time),
                'stop_time' => t_time($time+86400*3),
            ]);
        }


//        $result['start_time'] = t_time($time);
//        $result['stop_time'] = t_time($time+86400*3);
//        return  $result;
    }

    /**
     *     获取虫子所有状态信息：购买信息、派遣信息、被放置虫子信息
     * */
    function chongzi_status($uid){


        $time = time();
        $sql = "select type from chongzi_send WHERE uid=? AND UNIX_TIMESTAMP(stop_time)<?  ORDER BY id DESC limit 10";
        $lists = $this->db->query($sql,[$uid,$time])->result_array();

        // 事务开始
        $this->db->trans_start();
        if(count($lists)){
            foreach($lists as $val){
                $this->table_update('zy_chongzi',['send_status' => 0], [
                    'uid' => $uid,
                    'type'=>$val['type']
                ]);
            }
        }

        $now = t_time();
        $sql = "select * from zy_chongzi WHERE uid=?";
        $buy = $this->db->query($sql,[$uid])->result_array();

        if(!empty($buy)){
            foreach($buy as $value){
               if($value['stop_time']<$now && $value['status'] == 0){
                   //购买期已过
                   $this->db->set('status', 1);
                   $this->db->where('id', $value['id']);
                   $this->db->where('uid', $uid);
                   $this->db->update('zy_chongzi');

                   $sql = "select * from zy_chongzi WHERE uid=?";
                   $buy = $this->db->query($sql,[$uid])->result_array();
               };
            }
        }
        //放置虫子信息
        $sql = "select number,status,start_time,stop_time,type,friend_uid from chongzi_send WHERE uid=? AND  UNIX_TIMESTAMP(stop_time)>?";
        $chongzi_put = $this->db->query($sql,[$uid,time()])->result_array();

        foreach($chongzi_put as &$value){
            $sql = "select nickname from zy_user WHERE uid=?";
            $user= $this->db->query($sql,[$value['friend_uid']])->row_array();

            $value['nickname'] = $user['nickname'];
        }

        unset($buy['id']);
        unset($buy['uid']);
        unset($chongzi_put['friend_uid']);
        $result['buy'] = $buy;
        $result['chongzi_put'] = $chongzi_put;
        $this->db->trans_complete();
        return $result;
    }

    /**
     *    定时查询有无被放置虫子
     * */
    function chongzi_query($uid){
        model('building_model')->upgrade_status($uid);
        $this->get_myenergy($uid);
        $this->update(['status'=>1],['friend_uid'=>$uid,'status'=>0,'stop_time<='=>t_time()]);
        $this->update(['status'=>1],['uid'=>$uid,'status'=>0,'stop_time<='=>t_time()]);
        //被放置虫子信息
        $sql = "select a.number,a.type,a.status,a.start_time,a.stop_time,b.nickname from chongzi_send a,zy_user b WHERE a.friend_uid=? AND UNIX_TIMESTAMP(a.stop_time)>? and a.uid=b.uid ORDER BY a.id DESC";
        $chongzi_placed = $this->db->query($sql,[$uid,time()])->row_array();

        return $chongzi_placed;
    }

    /**
     * 好友家
     * 查询好友有无被放置虫子
     * */
    function friend_chongzi_placed($uid, $code){

        // 根据code 获取好友uid
        $friend_uid = $this->friend_model->get_uid($uid, $code);

        //被放置虫子信息
        $sql = "select a.number,a.type,a.status,a.start_time,a.stop_time,b.nickname from chongzi_send a,zy_user b WHERE a.friend_uid=? AND a.status=? and a.uid=b.uid ORDER BY a.id DESC";
        $chongzi_placed = $this->db->query($sql,[$friend_uid,0])->row_array();
        return $chongzi_placed;
    }

    /**
     *   虫子被驱赶 能量获取
     * */
    function get_energy($uid,$number){

        //判断是否被放置害虫
        $sql = "select id,uid,type,status,stop_time,start_time,destroy_time,friend_uid from chongzi_send WHERE uid=? AND number=? ";
        $has_cz = $this->db->query($sql, array($uid, $number))->row_array();

        $stop_time = strtotime($has_cz['stop_time']);
        $time = strtotime($has_cz['start_time']);

        if($has_cz){

                $minute=floor(($stop_time-$time)%86400/60);
                $energy = intval($has_cz['type']) * ($minute/2);   //根据虫子等级计算能量
                $this->table_insert('chongzi_shouru', [
                        'uid' => $uid,
                        'friend_uid' => $has_cz['friend_uid'],
                        'total' => ceil($energy/2),
                        'index' =>$has_cz['id'],
                        'type' => $has_cz['type'],
                        'add_time' => t_time(),
                 ]);
            $this->table_insert('chongzi_shouru', [
                'uid' => $has_cz['friend_uid'],
                'friend_uid' => $uid,
                'total' => ceil($energy/2),
                'index' =>$has_cz['id'],
                'status' =>1,
                'add_time' => t_time(),
            ]);
            $this->update_total($has_cz['friend_uid'],ceil($energy/2));
            }

        return ;
    }

    /**
     *
     * 虫子自动返回的获取能量
     * */
    function get_myenergy($uid){

        //判断是否放置害虫
        $sql = "select id,uid,type,status,stop_time,start_time,destroy_time,friend_uid from chongzi_send WHERE uid=? AND is_count=? and  UNIX_TIMESTAMP(stop_time)<=? ORDER BY id DESC  limit 100";
        $has_cz = $this->db->query($sql, array($uid,0,time()))->result_array();

        // 事务开始
        $this->db->trans_start();
        if(count($has_cz)){
            foreach($has_cz as $val){
                $stop_time = strtotime($val['stop_time']);
                $time = strtotime($val['start_time']);
                    $minute=floor(($stop_time-$time)%86400/60);
                    $energy = intval($val['type']) * ($minute/2);   //根据虫子等级计算能量

                    $this->table_insert('chongzi_shouru', [
                        'uid' => $uid,
                        'friend_uid' => $val['friend_uid'],
                        'total' => $energy,
                        'type' => $val['type'],
                        'index' =>$val['id'],
                        'add_time' => t_time(),
                    ]);
                $this->update(['is_count'=>1],
                    [
                    'uid'=>$val['uid'],
                    'id'=>$val['id'],
                    'is_count'=>0,
                    ]);
            }
        }
        $this->db->trans_complete();

    }

    //当前能量 列表
    function current_energy($uid,$page=0){
        $per_page = 20;
        $offset = $page*$per_page;

        $list['list'] = $this->db->query("select id,friend_uid,type,total,add_time,status from chongzi_shouru WHERE uid=? AND type>0
              ORDER BY status ASC ,id DESC limit ?,?",[$uid,$offset,$per_page])->result_array();

        $query = $this->db->query("select count(*) as num from chongzi_shouru WHERE uid='$uid' AND type>0")->row_array();
        $list['page']['curr_page'] = $page+1;
        $list['page']['total_page'] = ceil($query['num']/$per_page)==0 ? 1 : ceil($query['num']/$per_page);

        foreach($list['list'] as &$value){

            $sql = "select uid,nickname from zy_user WHERE uid=?";
            $user = $this->db->query($sql,[$value['friend_uid']])->row_array();
            $value['energy'] = $value['total'];
            $value['nickname'] = $user['nickname'];
            unset($value['friend_uid']);

        }
        return $list;
    }


    //领取能量
    function lingqu($uid,$type,$id){
        $is_return = model('building_model')->query_upgrade($uid,13);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        $sql = "select id,uid,type,total,status from chongzi_shouru WHERE uid=?  AND id=? AND type=? ";
        $res = $this->db->query($sql,[$uid,$id,$type])->row_array();

        if($res['status'] == 0){
            $rows = $this->table_update('chongzi_shouru', [
                'status' => 1,
                'update_time' => t_time(),
            ],['type' => $type,'uid'=>$uid,'status' =>0,'id'=>$id]);
            if($rows){
                $this->update_total($uid,$res['total']);
            }else{
                t_error(1, '领取失败！');
            }
        }else{
            t_error(2, '已领取！');
        }

    }



    //能量
    function energy_total($uid){
        $sql = "select energy from zy_user WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        return $row;

    }

    //领取闪电
    function receive_shouyi($uid){

        $sql = "select uid,energy from zy_user WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        $change_energy = config_item('energy');
        if($row['energy']>= $change_energy){
            $profit = intval(floor($row['energy']/$change_energy));
            $energy = intval($row['energy']%$change_energy);
            $total = $row['energy']-$energy;

            $this->db->set('shandian', 'shandian+' .$profit, FALSE);
            $this->db->set('energy', $energy);
            $this->db->where('uid',  $uid);
            $this->db->update('zy_user');
            $result = $this->db->affected_rows();
            if($result){
                $this->table_insert('zy_change_record', [
                    'uid' => $uid,
                    'total' => $total,
                    'shandian' => $profit,
                    'add_time' => t_time(),
                ]);
            }
            $res['shandian'] = $profit;

        }
        if(!$result) t_error(1, '领取失败！');

        return $res;
    }


    //转换记录
    function change_record($uid,$page=0){

//        $offset = ($page - 1) * 20;
//        $list = $this->table_lists('zy_change_record','total,shandian,add_time',['uid'=>$uid],'id',20,$offset);

        $per_page = 20;
        $offset = $page*$per_page;

        $list['list'] = $this->db->query("select total,shandian,add_time from zy_change_record WHERE uid=?
              ORDER BY id DESC limit ?,?",[$uid,$offset,$per_page])->result_array();

        $query = $this->db->query("select count(*) as num from zy_change_record WHERE uid='$uid'")->row_array();
        $list['page']['curr_page'] = $page+1;
        $list['page']['total_page'] = ceil($query['num']/$per_page)==0 ? 1 : ceil($query['num']/$per_page);

        return $list;

    }

    //虫子入侵记录
    function Ruqin($uid,$page=0){

        $per_page = 20;
        $offset = $page*$per_page;

        $list['list'] = $this->db->query("select id,uid,friend_uid,status,number,start_time,stop_time from chongzi_send WHERE friend_uid=?
              ORDER BY status ASC ,start_time DESC limit ?,?",[$uid,$offset,$per_page])->result_array();

        $query = $this->db->query("select count(*) as num from chongzi_send WHERE friend_uid='$uid'")->row_array();
        $list['page']['curr_page'] = $page+1;
        $list['page']['total_page'] = ceil($query['num']/$per_page)==0 ? 1 : ceil($query['num']/$per_page);

        foreach($list['list'] as &$val){

            $sql = "select nickname,head_img,game_lv from zy_user WHERE uid=? ";
            $user = $this->db->query($sql,[$val['uid']])->row_array();

            $get_sql = "select id,total from chongzi_shouru WHERE uid=? AND `index`=? AND type=?";
            $energy = $this->db->query($get_sql,[$uid,$val['id'],0])->row_array();

            $val['energy'] =  $energy?$energy['total']:0;
            $val['nickname'] = $user['nickname'];
            $val['game_lv'] = $user['game_lv'];
            $val['head_img'] =  $user['head_img'];


            unset($val['uid']);
            unset($val['friend_uid']);
        }
        return $list;
    }

function test($uid){



}

}
