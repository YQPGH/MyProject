<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  配方研究所
 */
include_once 'Base_model.php';

class Peifang_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_peifang';
        $this->load->model('api/shop_model');
        $this->load->model('api/store_model');
    }

    /**
     * 开始合成，返回高级配方id， 三个不同的任意配方书，合成其中星级最高配方+1星级
     *
     * @return string
     */
    function start($uid, $peifang1, $peifang2, $peifang3)
    {
        $is_return = model('building_model')->query_upgrade($uid,5);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        /*if ($peifang1 == $peifang2 || $peifang1 == $peifang3 || $peifang2 == $peifang3)
            t_error(1, '请提交三个不同的配方书');*/

        // 判断烟叶库存
        if ($this->store_model->total_null($uid, "$peifang1,$peifang2,$peifang3") == false)
            t_error(2, '你的配方库存不够了，请稍后');
        // 三个不同的任意配方书，合成其中星级最高配方+1星级
        $peifang_high = $this->get_high_peifang($peifang1, $peifang2, $peifang3);

        $this->db->trans_start();
        // 更新库存
        $this->store_model->update_total(-1, $uid, $peifang1);
        $this->store_model->update_total(-1, $uid, $peifang2);
        $this->store_model->update_total(-1, $uid, $peifang3);
        //判断是否被放置了间谍
        $now = t_time();
        //$has_jd = $this->db->query("select count(*) as num,uid from jd_guyong WHERE friend_uid='$uid' AND stop_time>'$now'")->row_array();
       $sql = "select count(*) as num,uid from jd_guyong WHERE friend_uid=? AND stop_time>?";
        $has_jd = $this->db->query($sql, array($uid, $now))->row_array();
        $number = rand(1,50) ; // 1窃取成功，2不成功
        if($has_jd['num'] != 0 && $number == 1){
            $peifang_high_detail = $this->shop_model->detail($peifang_high);
            //获取当天时间
            $today = strtotime(date('Y-m-d'));
            $sql = "SELECT COUNT(*) AS num FROM jd_shouru WHERE friend_uid=? AND UNIX_TIMESTAMP(add_time)>?";
            $count = $this->db->query($sql, array($uid,$today))->row_array();
            if($peifang_high_detail['type2'] < 3 && $count['num'] < 3){
                $this->load->model('api/jiandie_model');
                $this->jiandie_model->update_total($has_jd['uid'],$uid,$peifang_high);
                $result['is_stolen'] = 1;
                $sql = "select nickname from zy_user WHERE uid=?";
                $jd = $this->db->query($sql, array($has_jd['uid']))->row_array();
                $result['jd_name'] = $jd['nickname'];
            }else{
                $this->store_model->update_total(+1, $uid, $peifang_high,1);
                $result['is_stolen'] = 0;
                $result['jd_name'] = '';
            }
        }else{
            $this->store_model->update_total(+1, $uid, $peifang_high,1);
            $result['is_stolen'] = 0;
            $result['jd_name'] = '';
        }

        // 更新配方表
        $data = [
            'uid' => $uid,
            'peifang1' => $peifang1,
            'peifang2' => $peifang2,
            'peifang3' => $peifang3,
            'peifang_high' => $peifang_high,
            'status' => 1,
            'add_time' => t_time(),
        ];
        $this->insert($data);

        //添加每日任务
        model('task_model')->update_today($uid, 8);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '事务提交失败，系统繁忙请稍后再来');
        $result['peifang'] = $peifang_high;
        return $result;
    }

    // 获取培育室状态, 多个或者单个 培育槽
    function status($uid, $number = 0)
    {
        if ($number > 0) {
            $value = $this->row(['uid' => $uid, 'number' => $number]);
            if (!$value) t_error(1, '没有找到该培育槽编号，请检查');
            if ($value['status'] == 1 && $value['stop_time'] <= t_time()) {
                $value['status'] = 2;
            }
            return $value['status'];

        } else {
            $row = $this->row(['uid' => $uid]);
            // 初次使用
            if (!$row) {
                $this->init($uid);
                $row = $this->row(['uid' => $uid]);
            }

            if ($row['status'] == 1 && $row['stop_time'] <= t_time()) {
                $row['status'] = 2;
            }

            return $row;
        }
    }

    // 初始化，为用户建三条记录
    function init($uid)
    {
        for ($i = 1; $i <= 1; $i++) {
            $this->insert([
                'uid' => $uid,
                'number' => $i,
            ]);
        }
    }

    // 三个不同的任意配方书，合成其中星级最高配方+1星级
    function get_high_peifang($peifang1, $peifang2, $peifang3)
    {
        $store1 = $this->shop_model->detail($peifang1);
        $store2 = $this->shop_model->detail($peifang2);
        $store3 = $this->shop_model->detail($peifang3);
        //判断是否三个商品都是配方
        if($store1['type1']!='peifang' || $store2['type1']!='peifang' || $store3['type1']!='peifang') t_error(6, '必须放入调香书');

        //判断三个配方是否等级相同
        if($store1['type2']==$store2['type2']&&$store2['type2']==$store3['type2']){
            if ($store1['type2'] == 5) {
                t_error(5, '不能提交五星级调香书');
            }
            //根据合成前的等级获取合成概率
//            $query = $this->db->query("select * from zy_peifang_rate where grade_before=$store1[type2] and rate!=0")->result_array();
             $sql = "select * from zy_peifang_rate where grade_before=? and rate!=?";
            $query = $this->db->query($sql, array($store1[type2], 0))->result_array();
            $temp = 0;
            $temp_arr = array();
            foreach($query as $key=>$value){
                $temp_arr[$key]['rate_start'] = $temp;
                $temp = $temp+$value['rate'];
                $temp_arr[$key]['rate_end'] = $temp-1;
                $temp_arr[$key]['grade_after'] = $value['grade_after'];
            }
            $number = rand(1, 100);
            foreach($temp_arr as $key=>$value){
                if ($number>=$value['rate_start']&&$number<= $value['rate_end']) {
                    $type2 = $value['grade_after'];
                    break;
                }
            }
            $type3 = $store1['type3'];
        }else{
            if ($store1['type2'] >= $store2['type2']) {
                $high = $store1;
            } else {
                $high = $store2;
            }

            if ($high['type2'] < $store3['type2']) {
                $high = $store3;
            }
            if ($high['type2'] == 5) {
                t_error(5, '不能提交五星级配方');
            }
            $type2 = $high['type2'];
            $type3_arr[0] = $store1['type3'];
            $type3_arr[1] = $store2['type3'];
            $type3_arr[2] = $store3['type3'];
            $rand_key = array_rand($type3_arr);
            $type3 = $type3_arr[$rand_key];
        }

//        $list = $this->db->query("SELECT shopid FROM zy_shop WHERE type1='peifang' AND type2={$type2} AND type3=$type3")->row_array();
        $sql = "SELECT shopid FROM zy_shop WHERE type1=? AND type2=? AND type3=?";
        $list = $this->db->query($sql, array('peifang', $type2,$type3))->row_array();
        return $list['shopid'];
    }

    function unlock_peifang($uid,$spend_type){

        //查看是否已经解锁
//        $row = $this->db->query("select game_lv,peifang_status,ledou,money from zy_user WHERE uid='$uid'")->row_array();
        $sql = "select game_lv,peifang_status,ledou,money from zy_user WHERE uid=?";
        $row = $this->db->query($sql, array($uid))->row_array();
        if ($row['peifang_status']) t_error(1, '已解锁，不可再次解锁');
        //查看、判断是否满足解锁条件
        $unlock_term = config_item('unlock_peifang_term');
        if($row['game_lv'] < $unlock_term['game_lv']) t_error(2, '未达到解锁等级');
        if($row[$spend_type] < $unlock_term[$spend_type]) t_error(2, '您的乐豆或银元不足');

        // 事务开始
        $this->db->trans_start();
        // 扣除乐豆或者银元
        if($spend_type == 'ledou'){
            $this->user_model->money($uid,0,-$unlock_term[$spend_type]);
        }else if($spend_type == 'money'){
            $this->user_model->money($uid,-$unlock_term[$spend_type],0);
        }

        //更新表（zy_user）
        $this->db->set('peifang_status', 1);
        $this->db->where('uid', $uid);
        $this->db->update('zy_user');

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 29, //解锁花费
            "$spend_type" => -$unlock_term[$spend_type]
        ]);

        $this->db->trans_complete();

    }


}
