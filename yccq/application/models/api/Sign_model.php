<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 每日签到
 */
include_once 'Base_model.php';

class Sign_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_sign';
        model('user_model');
        model('store_model');
    }

    // 开始签到,返回签到次数
    function sign($uid)
    {
        model('prize_model');

        // 判断今天是否已经签到
        $last_row = $this->row(['uid' => $uid]);
        if ($last_row) {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) t_error(1, '今天你已经签到过了，请明天再来。');
        }
        // 写入签到表
        $this->insert([
            'uid' => $uid,
            'add_time' => t_time(),
        ]);

        // 判断昨天是否签到，是更新用户连续签到数+1
        if ($last_row && $last_sign_day == $this->time->yesterday()) {
            $this->user_model->update_sign($uid);
            $user = $this->user_model->detail($uid);
            if ($user['sign_total'] == 8) { // 如果到7天奖励更多
                $this->user_model->update_sign($uid, 1);
                // 奖励经验和银元
                $prize = $this->prize_model->row(array('id'=>1));
                $this->user_model->xp($uid, $prize['xp']);
                if($prize['money']){
                    $this->user_model->money($uid, $prize['money']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'money' => $prize['money'],
                    ]);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid, $prize['shandian']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'shandian' => $prize['shandian'],
                    ]);
                }
                $result['sign_total'] = 1;
                $result['money'] = $prize['money'];
                $result['xp'] = $prize['xp'];
                $result['shopid'] = $prize['shop1'];
                $result['shop_num'] = $prize['shop1_total'];
                if($prize['shop1']){
                    //如果获得的是物品，存入仓库
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'shopid' => $prize['shop1'],
                    ]);
                }

            } else { // 单日签到
                // 奖励经验和银元
                $prize = $this->prize_model->row(array('id'=>$user['sign_total']));
                $this->user_model->xp($uid, $prize['xp']);
                if($prize['money']){
                    $this->user_model->money($uid, $prize['money']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'money' => $prize['money'],
                    ]);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid, $prize['shandian']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'shandian' => $prize['shandian'],
                    ]);
                }
                $result['sign_total'] = $user['sign_total'];
                $result['money'] = $prize['money'];

                $result['xp'] = $prize['xp'];
                $result['shopid'] = $prize['shop1'];
                $result['shop_num'] = $prize['shop1_total'];
                if($prize['shop1']){
                    //如果获得的是物品，存入仓库
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 13,
                        'shopid' => $prize['shop1'],
                    ]);
                }
            }
            return $result;

        } else { // 不是连续签到，重新1开始
            $this->user_model->update_sign($uid, 1);
            // 奖励经验和银元
            $prize = $this->prize_model->row(array('id'=>1));
            $this->user_model->xp($uid, $prize['xp']);
            if($prize['money']){
                $this->user_model->money($uid, $prize['money']);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 13,
                    'money' => $prize['money'],
                ]);
            }
            if($prize['shandian']){
                $this->user_model->shandian($uid, $prize['shandian']);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 13,
                    'shandian' => $prize['shandian'],
                ]);
            }
            $result['sign_total'] = 1;
            $result['money'] = $prize['money'];
            $result['xp'] = $prize['xp'];
            $result['shopid'] = $prize['shop1'];
            $result['shop_num'] = $prize['shop1_total'];
            if($prize['shop1']){
                //如果获得的是物品，存入仓库
                $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1']);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 13,
                    'shopid' => $prize['shop1'],
                ]);
            }
            return $result;
        }
    }

    // 签到列表, 返回最近连续签到的列表
    function list_my($uid)
    {
        $this->update_sign_status($uid);

        $list = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];
        $user = $this->user_model->detail($uid);
        $sign_total = $user['sign_total'];
        foreach ($list as $key => &$value) {
            if ($key <= $sign_total) $value = 1;
        }
        $result = [
            'sign_today' => 0,
            'sign_total' => $user['sign_total'],
            'sign_list' => $list,
        ];
        $last_row = $this->row(['uid' => $uid]);
        if ($last_row) {
            $last_sign_day = $this->time->day($last_row['add_time']);
            if ($last_sign_day == $this->time->today()) $result['sign_today'] = 1;
        }
        //获取每日签到的奖励
        $this->load->model('api/setting_model');
        $type2 = $this->setting_model->get('type2');
        $result['reward'] = $this->db->query("select money,xp,shandian,shop1,shop1_total from zy_prize WHERE type1=1 AND type2=?",[$type2])->result_array();

        return $result;
    }

    // 更新连续签到,
    function update_sign_status($uid)
    {
        $user = $this->user_model->detail($uid);
        
        if ($user['sign_total'] > 0) {
            $last_row = $this->row(['uid' => $uid]);
            // 如果最后签到日不等于昨天或者今天，清零
            $last_sign_day = $this->time->day($last_row['add_time']);
            if (($last_sign_day != $this->time->yesterday() && $last_sign_day != $this->time->today())||($user['sign_total'] == 7 && $last_sign_day == $this->time->yesterday())) {
                $this->user_model->update_sign($uid, 0);
            }
        }
    }



}
