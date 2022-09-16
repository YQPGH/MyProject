<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  消消乐
 */
include_once 'Base_model.php';

class Xxl_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'xxl_record';
        $this->load->model('api/user_model');
        $this->load->model('api/shop_model');
        $this->load->library('user_agent');
    }

    /**
     * 开始游戏
     * @return array
     */
    function startGame($uid)
    {
        // 先查询今天是否超过5次
        $today = $this->time->today();
        //$count = $this->count_sql("SELECT COUNT(*) total FROM {$this->table}
                  //WHERE uid=? AND add_time>? LIMIT 100;", [$uid, $today]);
        //if ($count >= 5) t_error(1, '超过今天限玩次数了，请明天再来');

        //$query = $this->db->query("select xxl_update_time from zy_user  where uid = '$uid'")->row_array();
		$query = $this->column_sql('xxl_update_time',array('uid'=>$uid),'zy_user',0);
        //判断是否今天第一次进入游戏
        $today = date('Y-m-d');
        if(strtotime($today) > strtotime($query['xxl_update_time'])){
            $update = array(
                'xxl_play_times' => 3,
                'xxl_update_time' => t_time()
            );
            $this->db->where('uid', $uid);
            $this->db->update('zy_user', $update);
        }
        //$res = $this->db->query("select xxl_play_times play_times from zy_user where uid='$uid'")->row_array();
		$res = $this->column_sql('xxl_play_times play_times',array('uid'=>$uid),'zy_user',0);
        if ($res['play_times']>0) {
            //更新剩余次数
            $this->db->set('xxl_play_times', 'xxl_play_times-1', FALSE);
            $this->db->set('xxl_update_time', "'".date('Y-m-d H:i:s')."'", FALSE);
            $this->db->where('uid', $uid);
            $this->db->update('zy_user');
        }else{
            $res['play_times'] = 0;
        }

        $code = t_rand_str();
        $this->insert([
            'code' => $code,
            'uid' => $uid,
            'add_time' => t_time(),
            'ip' => $this->input->ip_address(),
            'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() .
                $this->agent->version()
        ]);

        $result['code'] = $code;
        $result['my_play'] = $res;
        return $result;
//        return ['code' => $code];
    }

    //是否消耗乐豆
   function  updateBeans($uid){
       // 先查询今天是否超过50次
       $today = $this->time->today();
       $count = $this->count_sql("SELECT COUNT(*) total FROM {$this->table}
                  WHERE uid=? AND add_time>? LIMIT 100;", [$uid, $today]);
       if ($count >= 50) t_error(1, '超过今天限玩次数了，请明天再来');
       $this->user_model->money($uid,0,-2);
       // 写入交易日志表
       model('log_model')->trade($uid, [
           'spend_type' => 31,
           'ledou' => -2,
           'money' => 0,
       ]);
       //$res = $this->db->query("select ledou from  zy_user WHERE uid='$uid'")->row_array();
	   $res = $this->column_sql('ledou',array('uid'=>$uid),'zy_user',0);
       /*if($res['ledou']>=2){
           $this->db->set('ledou','ledou-2', FALSE);
           $this->db->where('uid', $uid);
           $this->db->update('zy_user');
           
       }else{
           t_error(3, '你的乐豆不足，请稍后再来');
       }*/
       // 随机码
       $code = t_rand_str();
       $this->insert([
           'code' => $code,
           'uid' => $uid,
           'add_time' => t_time(),
           'ip' => $this->input->ip_address(),
           'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() .
               $this->agent->version()
       ]);

       $result['bean'] = $res;
       $result['code'] = $code;
       return $result;
   }

    /**
     * 游戏结束
     * @return array
     */
    function stopGame($data)
    {
        $this->load->model('api/store_model');

        $record = $this->row(['code' => $data['code']]);
        if (!$this->row(['code' => $data['code']])) {
            t_error(1, '随机码错误');
        }
        if ($record['money']) {
            t_error(2, '不能重复提交');
        }
        // 检查步数和时间，防止作弊
        if ((time() - strtotime($record['add_time'])) < 20) {
            t_error(3, '游戏时间过短，请稍后再试');
        }
        if ($data['step']>1 && $data['live_time']>1) {
            t_error(4, '数据有误，请稍后再试');
        }

        $data['update_time'] = t_time();
        $data['user_agent'] = $this->agent->platform() . '/' . $this->agent->browser() .
            $this->agent->version();

        // 奖品发放
        $prize = $this->givePrize($data['score']);


        if ($prize) {
            $this->load->model('api/turntable_model');
            $times['draws_times'] = $this->turntable_model->is_activity($data,'xxl');

            $this->load->model('api/fragment_model');
            $suipian = $this->fragment_model->get_fragment($data['uid'],'xxl');

            if(count($suipian)>0){
                $data['money'] = $prize['money'];
                // 更新用户仓库表
                $this->user_model->money($data['uid'], $prize['money']);
                $sql = "select thumb from zy_shop WHERE shopid=? ";
                $query = $this->db->query($sql,[$suipian['shop']])->row_array();
                $prize['shopid'] = $suipian['shop'];
                $prize['shop_name'] = $suipian['name'].' x1';
                $prize['shop_thumb'] = $query['thumb'];
            }elseif($times['draws_times'] == 1){
                $data['money'] = $prize['money'];
                // 更新用户仓库表
                $this->user_model->money($data['uid'], $prize['money']);
                $sql = "select shopid,name,thumb,total from zy_shop WHERE type1=? ";
                $query = $this->db->query($sql,['zhuanpan'])->row_array();
                $prize['shopid'] = $query['shopid'];
                $prize['shop_name'] = $query['name'].' x1';
                $prize['shop_thumb'] = $query['thumb'];
            }else{
                $data['money'] = $prize['money'];
                $data['shopid'] = $prize['shopid'];
                // 更新用户仓库表
                $this->user_model->money($data['uid'], $prize['money']);
                $this->store_model->update_total(1, $data['uid'], $prize['shopid']);//游戏上线第一个月，奖励变两倍，之后改成一倍
            }

        }

        // 更新游戏记录行
        $this->update($data, ['code' => $data['code']]);

        return $prize;
    }

    /**
     *  根据用户分数 随机发放奖励
     * @return array
     */
    function givePrize($score)
    {
        $row = $this->row_sql("SELECT * FROM xxl_prize_config 
                ORDER BY id LIMIT 1");
        if ($score < $row['score_min']) return [];

        //$row = $this->row_sql("SELECT * FROM xxl_prize_config 
                //WHERE score_min<={$score} AND score_max>={$score} LIMIT 1;");
		$row = $this->row_sql("SELECT * FROM xxl_prize_config WHERE score_min<=? AND score_max>=? LIMIT 1;",[$score,$score]);
        if (!$row) return [];

        // 银元
        $data['money'] = $row['money'];

        // 物品
        $randKey = $this->getRandKey(
            $row['shop1_rate'],
            $row['shop2_rate'],
            $row['shop3_rate'],
            $row['shop4_rate'],
            $row['shop5_rate']);

        $data['shopid'] = $row['shopid' . $randKey];

        if ($data['shopid']) {
            $shop = $this->shop_model->detail($data['shopid']);
            $data['shop_name'] = $shop['name'] . 'x1';  //先显示奖励翻倍，往后可能要改回一倍
            $data['shop_thumb'] = $shop['thumb'];
        }


        return $data;
    }

    // 根据不同的权重 返回键
    function getRandKey($p1, $p2 = 0, $p3 = 0, $p4 = 0 ,$p5 = 0)
    {
        $rand = rand(1, 100);
        $a = $p1;
        $b = $a + $p2;
        $c = $b + $p3;
        $d = $c + $p4;
        $e = $d + $p5;

        if ($rand <= $a) return 1;
        if ($rand > $a and $rand <= $b) return 2;
        if ($rand > $b and $rand <= $c) return 3;
        if ($rand > $c and $rand <= $d) return 4;
        if ($rand > $d and $rand <= $e) return 5;

        return 0;
    }

}