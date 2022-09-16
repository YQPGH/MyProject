<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  间谍
 */
include_once 'Base_model.php';

class Jiandie_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'jd_guyong';
        $this->load->model('api/jiandie_model');
        $this->load->model('api/friend_model');
    }

    /**
     * 开始雇佣一个间谍放入好友
     * code 好友随机码
     * @return array
     */
    function start($uid, $code)
    {
        // 开始雇佣
        $friend_uid = $this->friend_model->get_uid($uid, $code);
        //判断是否已经租赁间谍，并且租赁期未过
        $now = t_time();
        //$zu = $this->db->query("select count(*) as num from jd_jiandie WHERE uid='$uid' AND status=0 AND stop_time>'$now' ORDER BY id DESC")->row_array();
        $sql = "select count(*) as num from jd_jiandie WHERE uid=? AND status=0 AND stop_time>'$now' ORDER BY id DESC";
        $zu = $this->db->query($sql,[$uid])->row_array();
        if($zu['num'] == 0) t_error(1, '你尚未雇佣间谍，请先雇佣一个');
        //判断两次派遣间谍是否超过12小时
        //$query = $this->db->query("select count(*) as num from jd_guyong WHERE uid='$uid' AND status=0 AND stop_time>'$now' ORDER BY id DESC ")->row_array();
        $sql = "select count(*) as num from jd_guyong WHERE uid=? AND status=0 AND stop_time>'$now' ORDER BY id DESC ";
        $query = $this->db->query($sql,[$uid])->row_array();
        if($query['num'] != 0) t_error(1, '两次派遣需间隔12小时');
        //判断该好友是否已经被放置过间谍
        //$has_jd = $this->db->query("select count(*) as num from jd_guyong WHERE friend_uid='$friend_uid' AND stop_time>'$now'")->row_array();
        $sql = "select count(*) as num from jd_guyong WHERE friend_uid=? AND stop_time>'$now'";
        $has_jd = $this->db->query($sql,[$friend_uid])->row_array();
        if($has_jd['num'] != 0) t_error(1, '该好友已经被放置间谍！');

        // 事务开始
        $this->db->trans_start();
        $result['start_time'] = $this->time->now();
        $result['stop_time'] = $this->time->time_add_hour(12);
        $insert_id = $this->insert([
            'uid' => $uid,
            'friend_uid' => $friend_uid,
            'number' => t_rand_str(),
            'start_time' => $result['start_time'],
            'stop_time' => $result['stop_time'],
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '系统繁忙请稍后再来');

        return $result;
    }

    /**
     * 清除间谍
     * code 好友随机码
     * @return array
     */
    function clear($uid, $number)
    {
        // 开始雇佣
        $rows = $this->update(['status' => 1], [
            'friend_uid' => $uid,
            'number' => $number
        ]);

        if (!$rows) t_error(1, '清除间谍失败');

        return;
    }

    /**
     * 好友有收获， 转成我的收入
     *
     * @return array
     */
    function shouru($friend_uid, $shopid)
    {
        $random = rand(1, 100);
        // 好友农场是否有间谍
        $row = $this->row(['friend_uid' => $friend_uid]);
        if ($row && $random <= 50) {
            // 入间谍库
            $result = $this->update_total($row['uid'],$friend_uid, $shopid);
        } else {
            // 入仓库
            $result = model('store_model')->update_total(+1, $friend_uid, $shopid);
            if (!$result) t_error(5, '采集烟叶出问题了，请稍后再试');
        }
        
        return $result;
    }

    /**
     * 我的收入列表
     *
     * @return array
     */
    function list_shouru($uid)
    {
        $list = $this->lists_sql("SELECT shopid,total,status,add_time FROM jd_shouru WHERE uid='{$uid}' AND status=0 LIMIT 100;");
        $list = $this->shop_model->append_list($list);

        return $list;
    }

    /**
     * 一键入库
     *
     * @return array
     */
    function to_store($uid)
    {
        model('store_model');
        //$list = $this->lists_sql("SELECT shopid,total,status,add_time FROM jd_shouru WHERE uid='{$uid}' AND status=0 LIMIT 100;");
        $sql = "SELECT shopid,total,status,add_time FROM jd_shouru WHERE uid=? AND status=0 LIMIT 100";
        $list = $this->db->query($sql,[$uid,])->result_array();
        if(!$list) return 0;

        // 事务开始
        $this->db->trans_start();

        // 更改间谍库
        $this->table_update('jd_shouru',['status'=>1,'update_time'=>t_time()],['uid'=>$uid] );
        // 入库
        foreach ($list as $shouru) {
            $this->store_model->update_total($shouru['total'], $uid, $shouru['shopid']);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(99, '系统繁忙请稍后再来');
        
        return 0;
    }
    
    /**
     * 更新用户商品库存数
     *
     * @return int
     */
    function update_total($uid,$friend_uid, $shopid)
    {
        /*$row = $this->table_row('jd_shouru', ['uid' => $uid, 'shopid' => $shopid]);
        if ($row) {
            $this->db->set('total', 'total+1', FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->where('shopid', $shopid);
            $this->db->update('jd_shouru');
            $result = $this->db->affected_rows();
        } else {
            $result = $this->table_insert('jd_shouru', [
                'uid' => $uid,
                'shopid' => $shopid,
                'total' => 1,
                'add_time' => t_time(),
                'update_time' => t_time(),
            ]);
        }*/

        $result = $this->table_insert('jd_shouru', [
            'uid' => $uid,
            'friend_uid' => $friend_uid,
            'shopid' => $shopid,
            'total' => 1,
            'add_time' => t_time(),
            'update_time' => t_time(),
        ]);
        return $result;

    }

    /**
     *  租赁间谍
     * */
    function zu_jiandie($uid){
        //判断是否已经租赁间谍，租赁期是否已过
        //$zu = $this->db->query("select count(*) as num,id,status from jd_jiandie WHERE uid='$uid'")->row_array();
        $sql = "select count(*) as num,id,status from jd_jiandie WHERE uid=?";
        $zu = $this->db->query($sql,[$uid])->row_array();
        if($zu['num'] != 0 && $zu['status'] ==0 ) t_error(1, '你已经租赁了一个间谍，不可重复租赁');
        // 用户银元判断
        $user = $this->user_model->detail($uid);
        if (2000 > $user['money']) t_error(2, '你的银元不够了，请稍后再来');
        //if ($zulin_type[$number]['money'] > $user['ledou']) t_error(3, '你的乐豆不够了，请稍后再来');
        // 事务开始
        $this->db->trans_start();
        // 银元乐豆扣除
        $this->user_model->money($uid, -20000);
        //插入租赁间谍表（zu_jiandie）
        $time = time();
        if($zu['num']!=0){
            $this->db->set('status', 0);
            $this->db->set('start_time', t_time());
            $this->db->set('stop_time', t_time($time+86400*7));
            $this->db->where('id', $zu['id']);
            $this->db->where('uid', $uid);
            $this->db->update('jd_jiandie');
        }else{
            $this->table_insert('jd_jiandie', [
                'uid' => $uid,
                'start_time' => t_time($time),
                'stop_time' => t_time($time+86400*7),
            ]);
        }

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 21,
            'money' => -2000
        ]);
        
        $this->db->trans_complete();
        $result['start_time'] = t_time($time);
        $result['stop_time'] = t_time($time+86400*7);
        return  $result;
    }

    /**
     *     获取间谍所有状态信息：租赁信息、派遣信息、被放置间谍信息
     * */
    function jd_status($uid){
        //租赁
        $now = t_time();
        //$zu = $this->db->query("select * from jd_jiandie WHERE uid='$uid'")->row_array();
        $sql = "select * from jd_jiandie WHERE uid=?";
        $zu = $this->db->query($sql,[$uid])->row_array();
        if(!empty($zu) && $zu['stop_time']<$now && $zu['status'] == 0){
            //租赁期已过
            $this->db->set('status', 1);
            $this->db->where('id', $zu['id']);
            $this->db->where('uid', $uid);
            $this->db->update('jd_jiandie');
            //$zu = $this->db->query("select * from jd_jiandie WHERE uid='$uid'")->row_array();
            $sql = "select * from jd_jiandie WHERE uid=?";
            $zu = $this->db->query($sql,[$uid])->row_array();
        }
        //放置间谍信息
        //$jd_put = $this->db->query("select number,status,start_time,stop_time from jd_guyong WHERE uid='$uid' ORDER BY id DESC")->row_array();
        $sql = "select number,status,start_time,stop_time from jd_guyong WHERE uid=? ORDER BY id DESC";
        $jd_put = $this->db->query($sql,[$uid])->row_array();
        //被放置间谍信息
        //$jd_placed = $this->db->query("select number,status,start_time,stop_time from jd_guyong WHERE friend_uid='$uid' ORDER BY id DESC")->row_array();
        $sql = "select number,status,start_time,stop_time from jd_guyong WHERE friend_uid=? ORDER BY id DESC";
        $jd_placed = $this->db->query($sql,[$uid])->row_array();
        unset($zu['id']);
        unset($zu['uid']);
        $result['zu'] = $zu;
        $result['jd_put'] = $jd_put;
        $result['jd_placed'] = $jd_placed;
        return $result;
    }

    /**
     *    定时查询有无被放置间谍
     * */
    function jd_query($uid){
        //被放置间谍信息
        //$jd_placed = $this->db->query("select number,status,start_time,stop_time from jd_guyong WHERE friend_uid='$uid' ORDER BY id DESC")->row_array();
        $sql = "select number,status,start_time,stop_time from jd_guyong WHERE friend_uid=? ORDER BY id DESC";
        $jd_placed = $this->db->query($sql,[$uid])->row_array();
        return $jd_placed;
    }

    //解锁间谍系统
    function unlock_jiandie($uid,$spend_type){

        //查看是否已经解锁
        //$row = $this->db->query("select game_lv,jiandie_status,ledou,money from zy_user WHERE uid='$uid'")->row_array();
        $sql = "select game_lv,jiandie_status,ledou,money from zy_user WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        if ($row['jiandie_status']) t_error(1, '已解锁，不可再次解锁');
        //查看、判断是否满足解锁条件
        $unlock_term = config_item('unlock_jiandie_term');
        if($row['game_lv'] < $unlock_term['game_lv']) t_error(2, '未达到解锁等级');
        if($row[$spend_type] < $unlock_term[$spend_type]) t_error(2, '您的乐豆或银元不足');
        // 事务开始
        $this->db->trans_start();
        // 扣除乐豆或者银元
        if($spend_type == 'ledou'){
            $this->user_model->money($uid,$unlock_term[$spend_type],0);
        }else if($spend_type == 'money'){
            $this->user_model->money($uid,0,$unlock_term[$spend_type]);
        }

        //更新表（zy_user）
        $this->db->set('jiandie_status', 1);
        $this->db->where('uid', $uid);
        $this->db->update('zy_user');

        // 消费大于0时，写入交易日志表
        if($unlock_term[$spend_type]){
            model('log_model')->trade($uid, [
                'spend_type' => 29, //解锁花费
                "$spend_type" => -$unlock_term[$spend_type]
            ]);
        }


        $this->db->trans_complete();

    }


}
