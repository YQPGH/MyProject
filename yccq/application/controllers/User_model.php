<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  用户
 */
include_once 'Base_model.php';

class User_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_user';
        $this->load->model('api/store_model');
        $this->load->model('api/Ld_model');
        $this->load->model('api/setting_model');

    }

    /**
     *  用户详情
     *
     * @return int
     */
    function detail($uid)
    {
        $user = $this->row(['uid' => $uid]);
        if (!$user) t_error(1, '获取用户信息失败，请稍后再试');
        //$user['local_img'] = base_url() . $user['local_img'];
        //$user['thumb'] = $user['local_img'];
        $user['local_img'] = $user['head_img'];
        $user['thumb'] = $user['head_img'];
        //if($user['guide_finish']==0){
        //   $user['guide'] = $this->db->query("select guide_step,text from zy_guide WHERE guide_step>$user[guide_step] ORDER BY guide_step ASC")->result_array();
        //}
        return $user;
    }

    /**
     *  经验等级增加
     *
     * @return int 返回影响行数
     */
    function xp($uid, $xp)
    {
        if (empty($uid) || empty($xp)) return 0;
        $user = $this->detail($uid);
        if (!$user) t_error(1, '没有找到该用户，请稍后再试');

        //判断是否达到满级（50级）
        if($user['game_lv'] == 50){
            return 0;
        }

        $all_xp = $user['game_xp'] + $xp;
        $affected_row = $this->updateXP($uid,$all_xp);
        $this->table_insert('log_xp', [
            'uid' => $uid,
            'xp' => $xp,
            'add_time' => t_time(),
        ]);
        return $affected_row;
    }

    //添加经验
    function updateXP($uid,$all_xp){
        $user = $this->detail($uid);
        if (!$user) t_error(1, '没有找到该用户，请稍后再试');
        // 先判断是否可升级
        if ($all_xp >= $user['game_xp_all']) {
            // 更新经验值和游戏等级
            $data = [
                'game_lv' => $user['game_lv'] + 1,
                'game_xp' => $all_xp - $user['game_xp_all'],
                'game_xp_all' => $this->get_xp($user['game_lv'] + 2),
            ];
            $this->update($data, ['uid' => $uid]);
            //游戏升级，获得相应的奖
            $this->gameLvPrize($uid,$user['game_lv'] + 1);

            //添加到游戏升级奖励记录表
            $insert['uid'] = $uid;
            $insert['game_lv'] = $user['game_lv'] + 1;
            $insert['add_time'] = t_time();
            $this->table_insert('zy_gamelv_prize_record',$insert);
            $affected_row = $this->updateXP($uid,$data['game_xp']);
            if (!$affected_row) t_error(1, '用户交易失败，请稍后再试');


            return $affected_row;
        } else {
            // 更新经验值
            $this->db->set('game_xp', $all_xp, FALSE);
            $this->db->where('uid', $uid);
            $affected_row = $this->db->update($this->table);
            if (!$affected_row) t_error(1, '用户交易失败，请稍后再试');
            return $affected_row;
        }

    }


    //  根据等级获取升级所需经验值
    function get_xp($lv)
    {
        if($lv == 0 ) $lv++;
        $xp = ($lv*$lv+$lv*2)*10+70;
        return $xp;
    }



    /**
     *  游戏升级，获得相应的奖励
     */
    public function gameLvPrize($uid,$game_lv){
        $game_lv_prize = config_item('game_lv_prize');
        if($game_lv_prize[$game_lv]['type'] == 'money' ){
            $this->money($uid,$game_lv_prize[$game_lv]['num'],0);
        }else if($game_lv_prize[$game_lv]['type'] == 'ledou'){
            $this->money($uid,0,$game_lv_prize[$game_lv]['num']);
        }else if($game_lv_prize[$game_lv]['type'] == 'shandian'){
            $this->shandian($uid,$game_lv_prize[$game_lv]['num']);
        }else{
            if($game_lv_prize[$game_lv]['type'] == 'quan'){
                $openid = $this->user_model->queryOpenidByUid($uid);
                for($i = 0; $i < $game_lv_prize[$game_lv]['num']; $i++){
					$data = array(
						'shopid' => $game_lv_prize[$game_lv]['shopid'],
						'ticket_id' => t_rand_str($uid),
						'uid' => $uid,
						'openid' => $openid,
						'stat' => 0,
						'addtime' => time()
					);
					$this->db->insert('zy_ticket_record', $data);
					
					//添加奖品记录
					$prize_log = array(
						'uid'=>$uid,
						'ticket_id'=>$data['ticket_id'],
						'shopid'=>$game_lv_prize[$game_lv]['shopid'],
						'add_time'=>t_time()
					);
					$this->db->insert('log_prize_quan', $prize_log);
				}
            }
            $this->store_model->update_total($game_lv_prize[$game_lv]['num'],$uid,$game_lv_prize[$game_lv]['shopid']);

        }

       
    }
    /**
     *  查询游戏升级后，是否领取相应的奖励
     */
    public function queryGameLvPrize($uid,$game_lv){
        $count = $this->table_count('zy_gamelv_prize_record',['uid'=>$uid,'game_lv'=>$game_lv]);
        if($count){
            $game_lv_prize = config_item('game_lv_prize');
            $data[] = $game_lv_prize[$game_lv];
            return $data;
        }else{
            t_error(1, '无升级奖励记录');
        }
    }



    /**
     *  更新用户银元和乐豆
     *  $money：银元
     *  $ledou：乐豆
     *
     * @return int 返回影响行数
     */
    function money($uid, $money = 0, $ledou = 0)
    {
        if (empty($uid)) return 0;
        if (empty($money) && empty($ledou)) return 0;

        $user = $this->detail($uid);

        if (!$user) return t_error(91, '没有该用户');

        if ($money < 0) {
            if ($user['money'] < abs($money)) t_error(92, '你的银元不够了');
        }
        if ($ledou < 0) {
            if ($user['ledou'] < abs($ledou)) t_error(92, '你的乐豆不够了');
        }
        $this->db->trans_start();
        // 更新银元
        $this->db->set('money', 'money+' . $money, FALSE);
        $this->db->set('ledou', 'ledou+' . $ledou, FALSE);
        $this->db->where('uid', $uid);
        $affected_row = $this->db->update($this->table);
        if (!$affected_row) t_error(99, '用户交易失败，请稍后再试');

        //更新云软系统乐豆
        if ($ledou > 0) {
            $this->Ld_model->rechargeDY($ledou, $user['openid'], '烟草传奇奖励乐豆');
        }
        if ($ledou < 0) {
            $this->Ld_model->consumeYD(abs($ledou), $user['openid'], '烟草传奇公测消耗');
        }


        $this->db->trans_complete();
        return $affected_row;
    }

    /**
     *  更新用户 shandain 数量
     *
     * @return int 返回影响行数
     */
    function shandian($uid, $shandian = 0)
    {
        if (empty($uid)) return 0;
        if (empty($shandian)) return 0;

        $user = $this->detail($uid);
        if (!$user) return t_error(91, '没有该用户');

        if ($shandian < 0) {
            if ($user['shandian'] < abs($shandian)) t_error(92, '你的闪电不够了');
        }

        //更新闪电
        $this->db->set('shandian', 'shandian+' . $shandian, FALSE);
        $this->db->where('uid', $uid);
        $affected_row = $this->db->update($this->table);
        if (!$affected_row) t_error(99, '用户交易失败，请稍后再试');

        return $affected_row;
    }

    /**
     *  更新用户 积分数量
     *
     * @return int 返回影响行数
     */
    function jifen($uid, $jifen = 0)
    {
        if (empty($uid) || empty($jifen)) return 0;

        $user = $this->detail($uid);
        if (!$user) return t_error(91, '没有该用户');

        if ($jifen < 0) {
            if ($user['jifen'] < abs($jifen)) t_error(92, '你的积分不够了');
        }

        //更新
        $this->db->set('jifen', 'jifen+' . $jifen, FALSE);
        $this->db->where('uid', $uid);
        $affected_row = $this->db->update($this->table);
        if (!$affected_row) t_error(99, '用户交易失败，请稍后再试');

        return $affected_row;
    }

    /**
     * 更新签到数
     *
     * @param int $id
     * @return array 二维数组
     */
    function update_sign($uid, $number = -1)
    {
        $str = $number == -1 ? 'sign_total+1' : $number;
        $this->db->set('sign_total', $str, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * 更新每日登录次数
     *
     * @param int $id
     * @return array 二维数组
     */
    function update_login($uid)
    {

        $this->db->set('login_total', 'login_total+1', FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * 为列表附加上信息
     *
     * @return array 一维数组
     */
    function append_list($list)
    {
        if (empty($list)) return $list;

        $uid_arr = [];
        foreach ($list as $row) {
            $uid_arr[] = $row['uid'];
        }
        $query = $this->db->select('uid,nickname,head_img,game_lv')
            ->where_in('uid', $uid_arr)
            ->limit(count($uid_arr))
            ->get($this->table);
        $user_list = $query->result_array();
        $user_list2 = [];
        foreach ($user_list as $user) {
            //$user['thumb'] = base_url($user['local_img']);
            $user['thumb'] = $user['head_img'];
            $user_list2[$user['uid']] = $user;
        }

        foreach ($list as &$value) {
            $value['nickname'] = $user_list2[$value['uid']]['nickname'];
            $value['user_thumb'] = $user_list2[$value['uid']]['thumb'];
            $value['game_lv'] = $user_list2[$value['uid']]['game_lv'];
            // $value['thumb'] = base_url($shop_list[$value['shopid']]['thumb']);
        }

        return $list;
    }

    /**
     * 为列表附加上信息
     *
     * @return array 一维数组
     */
    function append_list_friend($list)
    {
        if (empty($list)) return $list;

        $uid_arr = [];
        foreach ($list as $row) {
            $uid_arr[] = $row['uid'];
        }

        $query = $this->db->select('uid,nickname,head_img,game_lv,yannong_lv,yannong_total,zhiyan_lv,zhiyan_total,jiaoyi_lv,jiaoyi_total,pinjian_lv,pinjian_total')
            ->where_in('uid', $uid_arr)
            ->limit(count($uid_arr))
            ->get($this->table);
        $user_list = $query->result_array();
        $user_list2 = [];
        foreach ($user_list as $user) {
            //$user['thumb'] = base_url($user['local_img']);
            $user['thumb'] = $user['head_img'];
            $user_list2[$user['uid']] = $user;
        }
        foreach ($list as &$value) {
            $value['nickname'] = $user_list2[$value['uid']]['nickname'];
            $value['user_thumb'] = $user_list2[$value['uid']]['thumb'];
            $value['game_lv'] = $user_list2[$value['uid']]['game_lv'];
            $value['yannong_lv'] = $user_list2[$value['uid']]['yannong_lv'];
            $value['yannong_total'] = $user_list2[$value['uid']]['yannong_total'];
            $value['zhiyan_lv'] = $user_list2[$value['uid']]['zhiyan_lv'];
            $value['zhiyan_total'] = $user_list2[$value['uid']]['zhiyan_total'];
            $value['jiaoyi_lv'] = $user_list2[$value['uid']]['jiaoyi_lv'];
            $value['jiaoyi_total'] = $user_list2[$value['uid']]['jiaoyi_total'];
            $value['pinjian_lv'] = $user_list2[$value['uid']]['pinjian_lv'];
            $value['pinjian_total'] = $user_list2[$value['uid']]['pinjian_total'];
            // $value['thumb'] = base_url($shop_list[$value['shopid']]['thumb']);
        }

        return $list;
    }

    // 新用户注册，同时写入多张表
    function register()
    {

        $this->insert(['uid' => 'xxxxxx', 'nickname' => xxxx]);

        $this->table_insert('zy_status', ['uid' => 'xxxxxx']);
    }


    function ledou_list($uid)
    {
        //玩家总乐豆数量
        $ledou_total = $this->db->query("select ledou from zy_user WHERE uid=?",[$uid])->row_array();
        //统计今天乐豆使用情况
        $today = t_time(0, 0);
        //$today_total = $this->db->query("select sum(ledou) as today_total from log_shop WHERE uid='$uid' AND add_time>'$today'")->row_array();
		$today_total = $this->column_sql('sum(ledou) as today_total',array('uid'=>$uid,'add_time>'=>$today),'log_shop',0);
        //从配置表中获取每日使用乐豆上限值
        $ledou_day_total = $this->db->query("select mvalue from zy_setting WHERE mkey='ledou_day_total'")->row_array();
        //玩家总乐豆数量
        $list['ledou_total'] = $ledou_total['ledou'];
        $list['today_use_total'] = $today_total['today_total'];
        $list['ledou_day_total'] = $ledou_day_total['mvalue'];
        return $list;
    }

    //购买闪电
    function buy_shandian($uid, $number)
    {
        //判断是不是8月9号，这一天购买闪电打9折
        $sale_arr = $this->is_sale_money_shandian();
        if($sale_arr['is_sale']){
            $user = $this->detail($uid);
            if ($number > $user['ledou']) t_error(1, '您的乐豆不够了');

            //统计今天乐豆使用情况
            $is_max = $this->is_ledou_max_total($uid, $number);
            if ($is_max) {
                //事务开始
                $this->db->trans_start();
                $present_shandain = config_item('present_shandain');
                $real_number = $number/$sale_arr['sale_num'];
                $shandian = $real_number + $present_shandain[$real_number];
                // 更新银元
                $this->db->set('shandian', 'shandian+' . $shandian, FALSE);
                $this->db->set('ledou', 'ledou-' . $number, FALSE);
                $this->db->where('uid', $uid);
                $this->db->update($this->table);

                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 3,
                    'ledou' => -$number,
                    'shandian' => $shandian
                ]);

                $this->Ld_model->consumeYD($number, $user['openid'], '烟草传奇消耗');

                $this->db->trans_complete();
                //获取最新的乐豆、银元
                $mess = $this->db->query("select shandian,ledou from zy_user WHERE uid=?",[$uid])->row_array();
                //获取今日乐豆使用情况
                //$new_today_total = $this->db->query("select sum(ledou) as today_total from log_shop WHERE uid='$uid' AND add_time>'$today'")->row_array();
                //$mess['today_use_total'] = $new_today_total['today_total'];
                return $mess;

            } else {
                t_error(1, '今日乐豆使用已经超过上限');
            }
        }else{
            $user = $this->detail($uid);
            if ($number > $user['ledou']) t_error(1, '您的乐豆不够了');

            //统计今天乐豆使用情况
            $is_max = $this->is_ledou_max_total($uid, $number);
            if ($is_max) {
                //事务开始
                $this->db->trans_start();
                $present_shandain = config_item('present_shandain');
                $shandian = $number + $present_shandain[$number];
                // 更新银元
                $this->db->set('shandian', 'shandian+' . $shandian, FALSE);
                $this->db->set('ledou', 'ledou-' . $number, FALSE);
                $this->db->where('uid', $uid);
                $this->db->update($this->table);

                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 3,
                    'ledou' => -$number,
                    'shandian' => $shandian
                ]);



                $this->Ld_model->consumeYD($number, $user['openid'], '烟草传奇公测消耗');

                $this->db->trans_complete();
                //获取最新的乐豆、银元
                $mess = $this->db->query("select shandian,ledou from zy_user WHERE uid=?",[$uid])->row_array();
                //获取今日乐豆使用情况
                //$new_today_total = $this->db->query("select sum(ledou) as today_total from log_shop WHERE uid='$uid' AND add_time>'$today'")->row_array();
                //$mess['today_use_total'] = $new_today_total['today_total'];
                return $mess;

            } else {
                t_error(1, '今日乐豆使用已经超过上限');
            }
        }

    }

    function ledou_to_money($uid, $number)
    {
        //判断是不是8月9号，这一天购买闪电打9折
        $sale_arr = $this->is_sale_money_shandian();
        if($sale_arr['is_sale']){
            $user = $this->detail($uid);
            if ($number > $user['ledou']) t_error(1, '您的乐豆不够了');

            //统计今天乐豆使用情况
            $is_max = $this->is_ledou_max_total($uid, $number);
            if ($is_max) {
                //事务开始
                $this->db->trans_start();
                $present_money = config_item('present_money');
                $real_number = $number/$sale_arr['sale_num'];
                $money = 100 * $real_number + $present_money[$real_number];
                // 更新银元
                $this->db->set('money', 'money+' . $money, FALSE);
                $this->db->set('ledou', 'ledou-' . $number, FALSE);
                $this->db->where('uid', $uid);
                $this->db->update($this->table);

                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 2,
                    'ledou' => -$number,
                    'money' => $money,
                ]);

                $user = $this->detail($uid);

                $this->Ld_model->consumeYD($number, $user['openid'], '烟草传奇消耗');

                $this->db->trans_complete();
                //获取最新的乐豆、银元
                $mess = $this->db->query("select money,ledou from zy_user WHERE uid=?",[$uid])->row_array();
                //获取今日乐豆使用情况
                //$new_today_total = $this->db->query("select sum(ledou) as today_total from log_shop WHERE uid='$uid' AND add_time>'$today'")->row_array();
                //$mess['today_use_total'] = $new_today_total['today_total'];
                return $mess;

            } else {
                t_error(1, '今日乐豆使用已经超过上限');
            }
        }else{
            $user = $this->detail($uid);
            if ($number > $user['ledou']) t_error(1, '您的乐豆不够了');

            //统计今天乐豆使用情况
            $is_max = $this->is_ledou_max_total($uid, $number);
            if ($is_max) {
                //事务开始
                $this->db->trans_start();
                $present_money = config_item('present_money');
                $money = 100 * $number + $present_money[$number];
                // 更新银元
                $this->db->set('money', 'money+' . $money, FALSE);
                $this->db->set('ledou', 'ledou-' . $number, FALSE);
                $this->db->where('uid', $uid);
                $this->db->update($this->table);

                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 2,
                    'ledou' => -$number,
                    'money' => $money,
                ]);

                $user = $this->detail($uid);

                $this->Ld_model->consumeYD($number, $user['openid'], '烟草传奇公测消耗');

                $this->db->trans_complete();
                //获取最新的乐豆、银元
                $mess = $this->db->query("select money,ledou from zy_user WHERE uid=?",[$uid])->row_array();
                //获取今日乐豆使用情况
                //$new_today_total = $this->db->query("select sum(ledou) as today_total from log_shop WHERE uid='$uid' AND add_time>'$today'")->row_array();
                //$mess['today_use_total'] = $new_today_total['today_total'];
                return $mess;

            } else {
                t_error(1, '今日乐豆使用已经超过上限');
            }
        }

    }

    function is_ledou_max_total($uid, $ledou)
    {
        //统计今天乐豆使用情况
        $today = t_time(0, 0);
        $today_total = $this->row_sql("select sum(ledou) as today_total from log_shop 
                    WHERE uid=? AND ledou<? AND add_time>?", [$uid,0,$today]);
        //从配置表中获取每日使用乐豆上限值
        $ledou_day_total = $this->setting_model->get('ledou_day_total');
        if ($today_total['today_total'] + $ledou <= $ledou_day_total) {
            return true;
        } else {
            return false;
        }
    }

    //烟农成就系统
    function yannong_achieve($uid, $index)
    {
        //更新熟练度
        if ($index == 1) {
            $number = 1;
        } else if ($index == 2) {
            $number = 2;
        } else {
            $number = 0;
        }
        $this->db->trans_start();
        $this->db->set('yannong_total', 'yannong_total+' . $number, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $yannong_total = $this->db->query("select yannong_total from zy_user WHERE uid=?",[$uid])->row_array();
        $yannong_type = config_item('yannong_type');

        $lv = 0;
        foreach ($yannong_type as $key => $value) {
            if ($key == count($yannong_type)) {
                if ($yannong_total['yannong_total'] >= $value['size']) {
                    $lv = $key;
                }
            } else {
                if ($yannong_total['yannong_total'] >= $value['size'] && $yannong_total['yannong_total'] < $yannong_type[$key + 1]['size']) {
                    $lv = $key;
                    break;
                }
            }
        }

        $this->db->set('yannong_lv', $lv, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);

        $this->db->trans_complete();
    }

    //制烟成就系统
    function zhiyan_achieve($uid)
    {
        $this->db->trans_start();
        $number = 1;
        $this->db->set('zhiyan_total', 'zhiyan_total+' . $number, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $total = $this->db->query("select zhiyan_total from zy_user WHERE uid=?",[$uid])->row_array();
        $type = config_item('zhiyan_type');

        $lv = 0;
        foreach ($type as $key => $value) {
            if ($key == count($type)) {
                if ($total['zhiyan_total'] >= $value['size']) {
                    $lv = $key;
                }
            } else {
                if ($total['zhiyan_total'] >= $value['size'] && $total['zhiyan_total'] < $type[$key + 1]['size']) {
                    $lv = $key;
                    break;
                }
            }
        }
        $this->db->set('zhiyan_lv', $lv, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);

        $this->db->trans_complete();
    }

    //交易成就系统
    function jiaoyi_achieve($uid)
    {
        $this->db->trans_start();
        $number = 1;
        $this->db->set('jiaoyi_total', 'jiaoyi_total+' . $number, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $total = $this->db->query("select jiaoyi_total from zy_user WHERE uid=?",[$uid])->row_array();
        $type = config_item('jiaoyi_type');

        $lv = 0;
        foreach ($type as $key => $value) {
            if ($key == count($type)) {
                if ($total['jiaoyi_total'] >= $value['size']) {
                    $lv = $key;
                }
            } else {
                if ($total['jiaoyi_total'] >= $value['size'] && $total['jiaoyi_total'] < $type[$key + 1]['size']) {
                    $lv = $key;
                    break;
                }
            }
        }
        $this->db->set('jiaoyi_lv', $lv, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);

        $this->db->trans_complete();
    }

    //品鉴成就系统
    function pinjian_achieve($uid)
    {
        $this->db->trans_start();
        $number = 1;
        $this->db->set('pinjian_total', 'pinjian_total+' . $number, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $total = $this->db->query("select pinjian_total from zy_user WHERE uid=?",[$uid])->row_array();
        $type = config_item('pinjian_type');

        $lv = 0;
        foreach ($type as $key => $value) {
            if ($key == count($type)) {
                if ($total['pinjian_total'] >= $value['size']) {
                    $lv = $key;
                }
            } else {
                if ($total['pinjian_total'] >= $value['size'] && $total['pinjian_total'] < $type[$key + 1]['size']) {
                    $lv = $key;
                    break;
                }
            }
        }
        $this->db->set('pinjian_lv', $lv, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);

        $this->db->trans_complete();
    }

    function guide_step($uid, $number)
    {
        $this->db->set('guide_step', $number, FALSE);
        $this->db->where('uid', $uid);
        $this->db->update($this->table);
        $total = $this->db->query("select count(*) as total from zy_guide")->row_array();
        if ($number >= $total['total']) {
            $this->db->set('guide_finish', 1, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
            $result['guide_finish'] = 1;
        } else {
            $result['next_step'] = $number + 1;
            $result['guide_finish'] = 0;
        }
        return $result;
    }

    // 用户首次注册初始化，多张表建立记录
    function init($openid, $nickname, $headPhoto)
    {

        $this->load->model('api/Ld_model');
        $ledou = $this->Ld_model->getYD($openid);
        if($ledou['status'] == 0){
            $user_data['ledou'] = $ledou['smokeBeansCount'];
        }else{
            $user_data['ledou'] = 0;
        }
        $this->db->trans_start();//事务开启
        // 主表
        $user_data['openid'] = $openid;
        $user_data['uid'] = t_rand_str($openid);
        $user_data['fid'] = t_rand_str($openid);
        $user_data['nickname'] = $nickname;
        $user_data['head_img'] = $headPhoto;
        $user_data['local_img'] = '';
        $user_data['add_time'] = t_time();
        $user_data['last_time'] = t_time();
        $user_data['game_lv'] = 0;
        $user_data['game_xp_all'] = $this->get_xp(1);
        $user_data['store_lv'] = 1;
        $user_data['logins'] = 1;
        $this->insert($user_data);
	    $this->db->insert('zy_user_login', [
				'uid' => $user_data['uid'],
				'add_time' => t_time(),
				'ip' => ip(),
				'agent' => get_agent()
			]);
        //初始化用户土地
        for ($i = 0; $i < 6; $i++) {
            $this->db->insert('zy_land', [
                'uid' => $user_data['uid'] ,
                'land_shopid' => 101,
                'add_time' => t_time()]);
        }

        //初始化烘烤室
        for($i = 0; $i < 4; $i++){
            $this->db->insert('zy_bake', [
                'uid' => $user_data['uid'],
                'bake_index' => $i,
                'add_time' => t_time()]);
        }

        //初始化醇化室
        for($i = 0; $i < 4; $i++){
            $this->db->insert('zy_aging', [
                'uid' => $user_data['uid'],
                'aging_index' => $i,
                'add_time' => t_time()]);
        }

        //初始化加工厂
        for($i = 0; $i < 3; $i++){
            $this->db->insert('zy_process', [
                'uid' => $user_data['uid'],
                'process_index' => $i,
                'add_time' => t_time()]);
        }

        // 每日任务表
        $this->db->insert('zy_task_today', [
            'uid' => $user_data['uid'],
            'update_time' => t_time(),
        ]);

        // 初始化订单
        //$orders = $this->setting_model->get('order_today');//从订单配置表获取初始化的6个订单
        //$orders_arr = explode(',',$orders);
        $orders_arr = [1,2,3,4,5,6];
        for ($i = 0; $i < 6; $i++) {
            $this->db->insert('zy_orders', [
                'uid' => $user_data['uid'] ,
                'order_id' => $orders_arr[$i],
                'order_index' => $i,
                'add_time' => t_time()]);
        }

        // 个人神秘商行
        $this->db->insert('zy_shenmi_shop', ['uid' => $user_data['uid']]);
        // 新手引导步骤表
        $this->db->insert('zy_guide', ['uid' => $user_data['uid']]);

        // 新手礼包
        //$this->newer_gift($user_data['uid']);

        $this->db->trans_complete();//事务结束
        return $user_data['uid'];
    }

    // 用户登陆初始化
    function login($openid, $nickname, $headPhoto)
    {
        $this->load->model('api/Ld_model');
        $ledou = $this->Ld_model->getYD($openid);
        if($ledou['status'] == 0){
            $this->db->set([
                'nickname' => $nickname,
                'ledou' => $ledou['smokeBeansCount'],
                'head_img' => $headPhoto,
                'last_time' => t_time(),])
                ->set('logins', 'logins+1')
                ->where(['openid' => $openid])
                ->update($this->table);
        }else{
            $this->db->set([
                'nickname' => $nickname,
                'head_img' => $headPhoto,
                'last_time' => t_time(),])
                ->set('logins', 'logins+1')
                ->where(['openid' => $openid])
                ->update($this->table);
        }

        // 登陆日志
        $user = $this->row(['openid' => $openid]);
        $this->db->insert('zy_user_login', [
            'uid' => $user['uid'],
            'add_time' => t_time(),
            'ip' => ip(),
            'agent' => get_agent()
        ]);

        return $user['uid'];
    }

    // 新手礼包
    function newer_gift($uid){
        //判断是否已经领取过新手礼包
        // 2星巴西烟叶·醇10片   1星云贵烟叶·醇4片  1星吕宋烟叶·醇5片
        //银元10000  闪电100  土地6
        $query = $this->db->query("select is_newer_gift from zy_user WHERE uid=?",[$uid])->row_array();
        if($query['is_newer_gift']==0){
            $this->db->trans_start();
            $this->db->set('money', 'money+' . 100000, FALSE);
            //$this->db->set('ledou', 'ledou+' . 50, FALSE);
            $this->db->set('shandian', 'shandian+' . 100, FALSE);
            $this->db->set('is_newer_gift', 1, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update($this->table);

            $this->store_model->update_total(6, $uid, 101);   //土地6
            $this->store_model->update_total(4, $uid, 503);   //一星云贵烟叶-醇 4
            $this->store_model->update_total(5, $uid, 504);   //一星吕宋烟叶-醇 5
            $this->store_model->update_total(14, $uid, 502);   //一星巴西烟叶-醇 14
//            $this->load->model('api/fragment_model');
//            $this->fragment_model->fragment_total($uid, 1,'新手礼包'); //碎片A*1

            $this->db->trans_complete();

            $res = $this->detail($uid);
            return $res;
        }else{
            t_error(1, '已经领取过新手礼包');
        }

    }

    //根据openid查询是否有此人
    public function checkUserByOpenid($openid){
        $result = $this->count(['openid'=>$openid]);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    //根据uid查询openid
    public function queryOpenidByUid($uid){
        $res = $this->column_sql('openid',['uid'=>$uid],'zy_user',0);
        return $res['openid'];
    }

    //根据openid查询uid
    public function queryUid($openid){
        $res = $this->column_sql('uid',['openid'=>$openid],'zy_user',0);
        return $res['uid'];
    }

    //判断银元、闪电是否打折，打几折
    public function is_sale_money_shandian(){
        $time = $this->query_holiday_time('fan_fest');
        if(time()>$time['start_time'] && time()<$time['end_time']){
            $res['is_sale'] = 1;
            $res['sale_num'] = 0.9;
        }else{
            $res['is_sale'] = 0;
            $res['sale_num'] = 1;
        }

        return $res;
    }

    //判断商行是否打折，打几折
    public function is_sale_shop(){
        $time = $this->query_holiday_time('fan_fest');
        if(time()>$time['start_time'] && time()<$time['end_time']){
            $res['is_sale'] = 1;
            $res['sale_num'] = 0.9;
        }else{
            $res['is_sale'] = 0;
            $res['sale_num'] = 1;
        }

        return $res;
    }

    //获取用户已解锁头像框列表
//    public function getHeaderFrameList($uid)
//    {
//        $user = $this->detail($uid);
//
//        if($user){
//            $result = array(0);
//
//            switch ($user['yannong_lv']) {
//                case '4':
//                    $result[] = 4;
//                case '3':
//                    $result[] = 3;
//                case '2':
//                    $result[] = 2;
//                case '1':
//                    $result[] = 1;
//            }
//            switch ($user['jiaoyi_lv']) {
//                case '4':
//                    $result[] = 8;
//                case '3':
//                    $result[] = 7;
//                case '2':
//                    $result[] = 6;
//                case '1':
//                    $result[] = 5;
//            }
//            switch ($user['zhiyan_lv']) {
//                case '4':
//                    $result[] = 12;
//                case '3':
//                    $result[] = 11;
//                case '2':
//                    $result[] = 10;
//                case '1':
//                    $result[] = 9;
//            }
//
//            switch ($user['pinjian_lv']) {
//                case '4':
//                    $result[] = 16;
//                case '3':
//                    $result[] = 15;
//                case '2':
//                    $result[] = 14;
//                case '1':
//                    $result[] = 13;
//            }
//            $this->load->model('api/laxin_model');
//            $is_exit = $this->laxin_model->queryHeaderframe($uid);
//
//            for($i=0;$i<count($is_exit);$i++){
//                $result[] = intval($is_exit[$i]);
//            }
//            sort($result);
//            return $result;
//        }
//    }

    public function getHeaderFrameList($uid)
    {
        $user = $this->detail($uid);

        if($user){
            $result = array(0);
            $sql = "select type1,type2,type3 from zy_headerframe";
            $list = $this->db->query($sql)->result_array();

            foreach($list as $value){
                if($value['type1'] == 'yannong_lv' && $value['type2']<=$user['yannong_lv']){
                    $result[] = intval($value['type3']);
                }
                if($value['type1'] == 'jiaoyi_lv' && $value['type2']<=$user['jiaoyi_lv']){
                    $result[] = intval($value['type3']);
                }
                if($value['type1'] == 'zhiyan_lv' && $value['type2']<=$user['zhiyan_lv']){
                    $result[] = intval($value['type3']);
                }
                if($value['type1'] == 'pinjian_lv' && $value['type2']<=$user['pinjian_lv']){
                    $result[] = intval($value['type3']);
                }
                if($value['type1'] == 'game_lv' && $value['type2'] <= $user['game_lv']){
                    $result[] = intval($value['type3']);
                }
            }
            $this->load->model('api/laxin_model');
            $is_exit = $this->laxin_model->queryHeaderframe($uid);
            for($i=0;$i<count($is_exit);$i++){
                $result[] = intval($is_exit[$i]);
            }
            sort($result);

            return $result;
        }
    }

    //设置头像框
    public function setHeaderFrame($uid,$frameid)
    {
        return $this->update(array('header_frame' => $frameid),array('uid' => $uid));
    }

    //活动列表
    public function rank_index_list(){
        //$result = $this->column_sql('name,img,type,action,intro',['status'=>0],'zy_activity_list',$type=1);
        $result = $this->db->query("select name,img,type,action,intro from zy_activity_list WHERE status=0 ORDER BY arcrank DESC")->result_array();
        return $result;
    }

    //查询是否领取节日奖励
    function queryHolidayGift($uid){

        $time = $this->query_holiday_time('newyears_day');
        //先判断奖励时段是否正确
        $row = $this->column_sql('has_holiday_gift',['uid'=>$uid],'zy_user',0);
        if(time()>$time['start_time'] && time()<$time['end_time'] && $row['has_holiday_gift'] == 0){
//            $value = $this->db->query("select money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=82 limit 1")->row_array();
            $result['list'] = config_item('holiday_gift');
//            $result['list'] = $value;
            $result['is_pop'] = 1;   //是否弹框
            $result['has_holiday_gift'] = $row['has_holiday_gift'];
        }else{
            $result['is_pop'] = 0;   //是否弹框
            $result['has_holiday_gift'] = 0;
            $result['list'] = [];
        }

        return $result;
    }

    //领取节日奖励
    function getHolidayGift($uid){
        $time = $this->query_holiday_time('newyears_day');
        //先判断奖励时段是否正确
        if(time()>$time['start_time'] && time()<$time['end_time']){
            //先查询是否领取过
            $row = $this->column_sql('has_holiday_gift',['uid'=>$uid],'zy_user',0);
            if($row['has_holiday_gift']) t_error(1, '奖励已领取！');
            if($row['has_holiday_gift'] == 0){
                $this->db->trans_start();
                $list = config_item('holiday_gift');
//                $list = $this->db->query("select id,money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=82 limit 1")->row_array();
                if($list['money']){
                    $this->user_model->money($uid, $list['money']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'money' => $list['money'],
                    ]);
                }
                if($list['shandian']){
                    $this->user_model->shandian($uid, $list['shandian']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'shandian' => $list['shandian'],
                    ]);
                }
                if($list['shopid']){
                    //如果获得的是物品，存入仓库
                    $shop = $this->shop_model->detail($list['shopid']);
                    $this->store_model->update_total($list['shop_num'],$uid,$list['shopid']);
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'shopid' => $list['shopid'],
                    ]);
                    //如果是抵扣券
                    if($shop['type4'] == 'quan'){
                        //根据uid获取openid
                        $data = array(
                            'shopid' => $list['shopid'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_ticket_record', $data);
                    }
                }
                //更新领取状态
                $this->db->set(['has_holiday_gift' => 1])->where(['uid' => $uid])->update($this->table);
//                $insert_data['uid'] = $uid;
//                $insert_data['prizeid'] = $list['id'];
//                $insert_data['add_time'] = t_time();
//                $this->db->insert('zy_christmas_prize_record', $insert_data);
                $this->db->trans_complete();
                $result['money'] = $list['money'];
                $result['shandian'] = $list['shandian'];
                $result['shopid'] = $list['shopid'];
                $result['shop_num'] = $list['shop_num'];
                return $result;
            }
        }else{
            t_error(2, '领取时间未到或已过！');
        }
    }

    //查询是否领取节日奖励
    function queryHolidayGift_2($uid){
        $time = $this->query_holiday_time('holiday_time');
        //先判断奖励时段是否正确
        $row = $this->column_sql('has_holiday_gift_2',['uid'=>$uid],'zy_user',0);
        if(time()>$time['start_time'] && time()<$time['end_time'] && $row['has_holiday_gift_2'] == 0){
            $value = $this->db->query("select money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=83 limit 1")->row_array();
            //$result['list'] = config_item('holiday_gift_2');
            $result['list'] = $value;
            $result['is_pop'] = 1;   //是否弹框
            $result['has_holiday_gift'] = $row['has_holiday_gift_2'];
        }else{
            $result['is_pop'] = 0;   //是否弹框
            $result['has_holiday_gift'] = 0;
            $result['list'] = [];
        }

        return $result;
    }

    //领取节日奖励
    function getHolidayGift_2($uid){
        $time = $this->query_holiday_time('holiday_time');
        //先判断奖励时段是否正确
        if(time()>$time['start_time'] && time()<$time['end_time']){
            //先查询是否领取过
            $row = $this->column_sql('has_holiday_gift_2',['uid'=>$uid],'zy_user',0);
            if($row['has_holiday_gift_2']) t_error(1, '奖励已领取！');
            if($row['has_holiday_gift_2'] == 0){
                $this->db->trans_start();
                //$list = config_item('holiday_gift2');
                $list = $this->db->query("select id,money,shandian,shop1 shopid,shop1_total shop_num,shop2 shopid2,shop2_total shop2_num from zy_prize where type1=83 limit 1")->row_array();
                if($list['money']){
                    $this->user_model->money($uid, $list['money']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'money' => $list['money'],
                    ]);
                }
                if($list['shandian']){
                    $this->user_model->shandian($uid, $list['shandian']);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'shandian' => $list['shandian'],
                    ]);
                }
                if($list['shopid']){
                    //如果获得的是物品，存入仓库
                    $shop = $this->shop_model->detail($list['shopid']);
                    $this->store_model->update_total($list['shop_num'],$uid,$list['shopid']);
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    // 写入交易日志表
                    model('log_model')->trade($uid, [
                        'spend_type' => 27,
                        'shopid' => $list['shopid'],
                    ]);
                    //如果是抵扣券
                    if($shop['type4'] == 'quan'){
                        //根据uid获取openid
                        $data = array(
                            'shopid' => $list['shopid'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_ticket_record', $data);
                    }
                }
                //更新领取状态
                $this->db->set(['has_holiday_gift_2' => 1])->where(['uid' => $uid])->update($this->table);
                $insert_data['uid'] = $uid;
                $insert_data['prizeid'] = $list['id'];
                $insert_data['add_time'] = t_time();
                $this->db->insert('zy_newyear_prize_record', $insert_data);
                $this->db->trans_complete();
                $result['money'] = $list['money'];
                $result['shandian'] = $list['shandian'];
                $result['shopid'] = $list['shopid'];
                $result['shop_num'] = $list['shop_num'];
                return $result;
            }
        }else{
            t_error(2, '领取时间未到或已过！');
        }
    }

    //更新福气值（可加可减）
    function update_lucky_value($uid,$value){
        if($uid ){
           $res = $this->queryLuckyValue($uid);
            if($value<0 && $res['lucky_value']<50){
                t_error(2,'您的福气值不够了');
            }else{
            $this->db->set('lucky_value', 'lucky_value+' . $value, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
            }
        }
//        return $this->queryLuckyValue($uid);
    }

    //获取用户福气值
    function queryLuckyValue($uid){
        $res = $this->column_sql('lucky_value,head_img',['uid'=>$uid],'zy_user',0);

        return $res;
    }

   //获取当前活动日期
    function query_holiday_time($type)
    {
        $sql = "select UNIX_TIMESTAMP(start_time) start_time,UNIX_TIMESTAMP(end_time) end_time from zy_activity_config WHERE `name`=?";
        $time = $this->db->query($sql,[$type])->row_array();
        return $time;
    }
}
