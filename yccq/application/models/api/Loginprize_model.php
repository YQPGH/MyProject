<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 每日登录
 */
include_once 'Base_model.php';

class Loginprize_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_loginprize_record';
        model('user_model');
        model('store_model');
    }

    function login_activity($uid){
        $time = model('user_model')->query_holiday_time('login');

        if(time() > $time['start_time'] && time() < $time['end_time'] )
        {
            $user = $this->user_model->detail($uid);
            $result = [
                'login_today' => 0,
                'login_total' => $user['login_total'],
            ];
            $last_row = $this->row(['uid' => $uid]);
            if ($last_row) {
                $last_login_day = $this->time->day($last_row['add_time']);
                if ($last_login_day == $this->time->today()) $result['login_today'] = 1;
            }
            $result['is_pop'] = 1;   //是否弹框
        }
        else
        {
            $result['is_pop'] = 0;   //是否弹框
        }

        return $result;
    }

    // 每日登录,返回登录次数
    function login($uid)
    {
        model('prize_model');
        // 判断今天是否已经登录
        $last_row = $this->row(['uid' => $uid]);
        $last_login_day = $this->time->day($last_row['add_time']);
        if ($last_login_day == $this->time->today()) {
            t_error(1, '今天你已经登录过了，请明天再来!');
        }else{
            // 写入登录表
            $this->insert([
                'uid' => $uid,
                'add_time' => t_time(),
            ]);
               //更新用户连续登录数+1
                $this->user_model->update_login($uid);
                $user = $this->user_model->detail($uid);
                // 奖励
                $prize = $this->prize_model->row(array('type1'=>13,'type2'=>$user['login_total']));

                if($prize['money']){
                    $this->user_model->money($uid, $prize['money']);
                    $result['money'] = $prize['money'];
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid, $prize['shandian']);
                    $result['shandian'] = $prize['shandian'];
                }
                if($prize['shop1']){
                    //如果获得的是物品，存入仓库
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1']);
                    $result['shopid'] = $prize['shop1'];
                    $result['shop_num'] = $prize['shop1_total'];
                }

            $result['login_total'] = $user['login_total'];
            // 奖品日志保存
            $insert_id = $this->table_insert('log_prize', [
                'uid' => $uid,
                'prize_id' => $prize['id'],
                'xh_jifen' => 0,
                'xh_shopid' => 0,
                'add_time' => t_time(),
            ]);
            return $result;
        }

    }

}
