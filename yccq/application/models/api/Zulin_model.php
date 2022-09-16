<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  租赁表
 */
include_once 'Base_model.php';

class Zulin_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_zulin';
        $this->load->model('api/user_model');
    }

    /**
     * 获取用户物品列表
     *
     * @return array
     */
    function list_all($uid)
    {
        $time = t_time();
        //$list = $this->lists_sql("SELECT * FROM {$this->table} WHERE  uid='{$uid}' AND stop_time>'$time'  LIMIT 1000");
		$list = $this->column_sql('*',array('uid'=>$uid,'stop_time>'=>$time),$this->table,1);
        if(!empty($list)){
            foreach($list as $key=>$value){
                if($value['stop_time'] < t_time()){
                    $list[$key]['remain_time'] = 0;
                }else{
                    $list[$key]['remain_time'] = diffBetweenTwoDays(t_time(),$value['stop_time']);
                }
            }
        }
        //获取制烟机等级
        //$query = $this->db->query("select zhiyan_lv from zy_user WHERE uid='$uid'")->row_array();
		$query = $this->column_sql('zhiyan_lv',array('uid'=>$uid),'zy_user',0);
        $result['list'] = $list;
        $result['zhiyan_lv'] = $query['zhiyan_lv'];
        return $result;
    }

    /**
     *  租赁制烟机
     * */
    function zu($uid,$number){
        $is_return = model('building_model')->query_upgrade($uid,8);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //判断用户制烟等级和银元是否满足要求
        $user = $this->user_model->detail($uid);
        if($number == 2){
            if($user['zhiyan_lv'] < 2) t_error(1, '你的制烟等级不够！');
        }else if($number == 3){
            if($user['zhiyan_lv'] < 4) t_error(1, '你的制烟等级不够！');
        }
        $zulin_type = config_item('zulin_type');
        if ($zulin_type[$number]['money'] > $user['money']) t_error(2, '你的银元不够了，请稍后再来');
        //if ($zulin_type[$number]['money'] > $user['ledou']) t_error(3, '你的乐豆不够了，请稍后再来');
        // 事务开始
        $this->db->trans_start();
        // 银元乐豆扣除
        $this->user_model->money($uid, -$zulin_type[$number]['money'],0);
        //插入制烟机表（zy_zulin）
        $time = time();
        $this->table_insert('zy_zulin', [
            'uid' => $uid,
            'number' => $number,
            'start_time' => t_time($time),
            'stop_time' => t_time($time+86400*7),
        ]);

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 6,
            'money' => -$zulin_type[$number]['money'],
        ]);

        $this->db->trans_complete();
        $result['start_time'] = t_time($time);
        $result['stop_time'] = t_time($time+86400*7);
        return  $result;
    }


}
