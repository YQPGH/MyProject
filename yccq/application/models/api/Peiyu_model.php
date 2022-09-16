<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  种子培育中心
 */
include_once 'Base_model.php';

class Peiyu_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_peiyu';
        $this->load->model('api/shop_model');
        $this->load->model('api/store_model');
        $this->load->model('api/user_model');
    }

    /**
     * 开始培育
     *
     * @return string
     */
    function start($uid, $number, $yanye1, $yanye2)
    {
        $is_return = model('building_model')->query_upgrade($uid,4);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 判断培育巢状态
        if ($this->status($uid, $number) != 0) t_error(1, '该培育巢繁忙中，请稍后');
        // 判断烟叶库存
        $yanye1_store = $this->store_model->detail($uid, $yanye1);
        $yanye2_store = $this->store_model->detail($uid, $yanye2);
        if ($yanye1_store['total'] < 1 || $yanye2_store['total'] < 1) t_error(1, '烟叶库存不够了，请稍后');
        // 随机选一片烟叶培育,获取高一级种子
        $seed_id = $this->get_seed($yanye1, $yanye2);

        $this->db->trans_start();
        // 更新库存
        $this->store_model->update_total(-1, $uid, $yanye1);
        $this->store_model->update_total(-1, $uid, $yanye2);
        $building_time = config_item('building_jiasu');
        $stop_time = ($is_return['is_upgrade']==2)?((strtotime(t_time()) + 60*2*$building_time['peiyu'])):(strtotime(t_time()) + 60*2);
        // 更新培育中心
        $data =  [
            'yanye1' => $yanye1,
            'yanye2' => $yanye2,
            'seed' => $seed_id,
            'status' => 1,
            'start_time' => t_time(),
            'stop_time' => t_time($stop_time)
        ];
        $this->update($data, ['uid' => $uid, 'number' => $number]);

        //添加每日任务
        model('task_model')->update_today($uid, 9);

        // 更新银元 乐豆
        //$this->user_model->money($uid, -10, 0);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '事务提交失败，系统繁忙请稍后再来');

        return $data;
    }

    /**
     *  完成收取
     *
     * @return string
     */
    function gather($uid, $number)
    {
        $is_return = model('building_model')->query_upgrade($uid,4);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 判断培育槽状态
        if ($this->status($uid, $number) == 0) t_error(1, '该培育巢空闲中，请稍后');
        if ($this->status($uid, $number) == 1) t_error(1, '培育时间未到，请稍后');

        // 判断烟叶库存
        $peiyu_row = $this->row(['uid' => $uid, 'number' => $number]);
        if (!$peiyu_row) t_error(2, '信息为空，请稍后再试');

        $this->db->trans_start();

        //判断是否被放置了间谍
        $now = t_time();
//        $has_jd = $this->db->query("select count(*) as num,uid from jd_guyong WHERE friend_uid='$uid' AND stop_time>'$now'")->row_array();
      $sql = "select count(*) as num,uid from jd_guyong WHERE friend_uid=? AND stop_time>?";
        $has_jd = $this->db->query($sql, array($uid,$now))->row_array();
        $number = rand(1,50) ; // 1窃取成功，2不成功
        if($has_jd['num'] != 0 && $number ==1){
            //只能窃取三星一下的种子
            $shop = $this->shop_model->detail($peiyu_row['seed']);
            if($shop['type2'] < 3){
                //获取当天时间
                $today = strtotime(date('Y-m-d'));
//                $count = $this->db->query("SELECT COUNT(*) AS num FROM jd_shouru WHERE friend_uid='$uid' AND UNIX_TIMESTAMP(add_time)>$today")->row_array();
                $sql = "SELECT COUNT(*) AS num FROM jd_shouru WHERE friend_uid=? AND UNIX_TIMESTAMP(add_time)>?";
                $count = $this->db->query($sql, array($uid,$today))->row_array();
                if($count['num']<3){
                    $this->load->model('api/jiandie_model');
                    $this->jiandie_model->update_total($has_jd['uid'],$uid,$peiyu_row['seed']);
                    $result['is_stolen'] = 1;
//                    $jd = $this->db->query("select nickname from zy_user WHERE uid='$has_jd[uid]'")->row_array();
                    $sql = "select nickname from zy_user WHERE uid=?";
                    $jd = $this->db->query($sql, array($has_jd[uid]))->row_array();
                    $result['jd_name'] = $jd['nickname'];
                }else{
                    // 更新库存
                    $this->store_model->update_total(1, $uid, $peiyu_row['seed'],1);
                    $result['is_stolen'] = 0;
                    $result['jd_name'] = '';
                }
            }else{
                // 更新库存
                $this->store_model->update_total(1, $uid, $peiyu_row['seed'],1);
                $result['is_stolen'] = 0;
                $result['jd_name'] = '';
            }
        }else{
            // 更新库存
            $this->store_model->update_total(1, $uid, $peiyu_row['seed'],1);
            $result['is_stolen'] = 0;
            $result['jd_name'] = '';
        }

        // 更新培育中心
        $this->update([
            'yanye1' => 0,
            'yanye2' => 0,
            'seed' => 0,
            'status' => 0,
            'start_time' => 0,
            'stop_time' => 0
        ], $peiyu_row['id']);        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '事务提交失败，系统繁忙请稍后再来');
        $result['seed'] = $peiyu_row['seed'];
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
            $list = $this->lists('*', ['uid' => $uid], 'id');
            // 初次使用
            if (!$list) {
                $this->init($uid);
                $list = $this->lists('number,status', ['uid' => $uid], 'id');
            }

            foreach ($list as &$value) {
                if ($value['status'] == 1 && $value['stop_time'] <= t_time()) {
                    $value['status'] = 2;
                }
            }
            return $list;
        }
    }

    // 初始化，为用户建三条记录
    function init($uid)
    {
        for ($i = 1; $i <= 3; $i++) {
            $this->insert([
                'uid' => $uid,
                'number' => $i,
            ]);
        }
    }

    // 根据两片烟叶，随机获取高一级的种子id
    function get_seed($yanye1, $yanye2)
    {
        //$array = [1 => $yanye1, 2 => $yanye2];
        //$key = rand(1, 2);
        //$id = $array[$key];
        $yanye1_shop = $this->shop_model->detail($yanye1);
        $yanye1_type2 = $yanye1_shop['type2'];  //原始星级
        $yanye1_type3 = $yanye1_shop['type3'];  //原始品种

        $yanye2_shop = $this->shop_model->detail($yanye2);
        $yanye2_type2 = $yanye2_shop['type2'];  //原始星级
        $yanye2_type3 = $yanye2_shop['type3'];  //原始品种

        if($yanye1_type2==5 || $yanye2_type2==5) t_error(5, '不能培育五星种子！');

        $array = [1 => $yanye1_type2, 2 => $yanye2_type2];
        $key = rand(1, 2);
        $mubiao_type2 = $array[$key];           //合成后的星级

        $array = [1 => $yanye1_type3, 2 => $yanye2_type3];
        $key = rand(1, 2);
        $mubiao_type3 = $array[$key];           //合成后的品种

        if ($mubiao_type2 < 5){
//            $mubiao = $this->db->query("SELECT shopid FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye2_type2}+1 AND type3=$mubiao_type3")->row_array();
           $sql = "SELECT shopid FROM zy_shop WHERE type1=? AND type2=? AND type3=?";
            $mubiao = $this->db->query($sql, array('zhongzi',$mubiao_type2+1, $mubiao_type3))->row_array();
        }else{
//            $mubiao = $this->db->query("SELECT shopid FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye2_type2} AND type3=$mubiao_type3")->row_array();
            $sql = "SELECT shopid FROM zy_shop WHERE type1=? AND type2=? AND type3=?";
            $mubiao = $this->db->query($sql, array('zhongzi',$mubiao_type2, $mubiao_type3))->row_array();
        }

        return $mubiao['shopid'];

        /*if($yanye1_type2 == $yanye2_type2){
            $array = [1 => $yanye1_shop, 2 => $yanye2_shop];
            $key = rand(1, 2);
            $shop = $array[$key];
            if ($shop['type2'] < 5){
                $mubiao = $this->db->query("SELECT shopid FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye2_type2}+1 AND type3=$shop[type3]")->row_array();
            }else{
                $mubiao = $this->db->query("SELECT shopid FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye2_type2} AND type3=$shop[type3]")->row_array();
            }
            return $mubiao['shopid'];
        }else{
            if($yanye1_type2 < $yanye2_type2){
                $list = $this->lists_sql("SELECT * FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye2_type2} ");
            }else{
                $list = $this->lists_sql("SELECT * FROM zy_shop WHERE type1='zhongzi' AND type2={$yanye1_type2} ");
            }
            $array = [];
            foreach ($list as $value) {
                $array[] = $value['shopid'];
            }
            $rand_key = rand(0, count($array)-1);
            return $array[$rand_key];
        }*/

    }

    function upgrade($uid){
        $is_return = model('building_model')->query_upgrade($uid,4);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //首先获取当前玩家有多少个培育槽
//        $count = $this->db->query("select count(*) as num from zy_peiyu WHERE uid='$uid'")->row_array();
        $sql = "select count(*) as num from zy_peiyu WHERE uid=?";
        $count = $this->db->query($sql, array($uid))->row_array();
        $number = $count['num'];
        if ($number >= 6) t_error(3, '已是最高级，不可再扩展');
        $user = $this->user_model->detail($uid);
        $peiyu_type = config_item('peiyu_type');

        if ($peiyu_type[$number+1]['game_lv'] > $user['game_lv']) t_error(3, '你的等级不足，暂无法升级');
        if ($peiyu_type[$number+1]['money'] > $user['money']) t_error(3, '你的银元不够了，请稍后再来');
        // 事务开始
        $this->db->trans_start();
        // 乐豆扣除
        $this->user_model->money($uid,-$peiyu_type[$number+1]['money'],0);//消耗银元
        //插入培育表（zy_peiyu）
        $this->table_insert('zy_peiyu', [
            'uid' => $uid,
            'number' => $number+1,
        ]);

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 7,
            'money' => -$peiyu_type[$number+1]['money']
        ]);

        $this->db->trans_complete();
        $result['number'] = $number+1;
        return  $result;

    }

    function unlock_peiyu($uid,$spend_type){
        $is_return = model('building_model')->query_upgrade($uid,4);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //查看是否已经解锁
//        $row = $this->db->query("select game_lv,peiyu_status,ledou,money from zy_user WHERE uid='$uid'")->row_array();
        $sql = "select game_lv,peiyu_status,ledou,money from zy_user WHERE uid=?";
        $row = $this->db->query($sql, array($uid))->row_array();
        if ($row['peiyu_status']) t_error(1, '已解锁，不可再次解锁');
        //查看、判断是否满足解锁条件
        $unlock_term = config_item('unlock_peiyu_term');
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
        $this->db->set('peiyu_status', 1);
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
