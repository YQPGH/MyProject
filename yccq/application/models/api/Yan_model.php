<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  烟
 */
include_once 'Base_model.php';

class Yan_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_yan';
        $this->load->model('api/shop_model');
        $this->load->model('api/store_model');
        $this->load->model('api/prize_model');
        $this->load->model('api/St_message_model');
    }

    /**
     * 品鉴
     *
     * @return string
     */
    function pinjian($uid, $shopid)
    {
        $is_return = model('building_model')->query_upgrade($uid,9);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 判断用户
        $store = $this->store_model->detail($uid, $shopid);
        if (!$store) t_error(1, '信息为空，请稍后再试');
        if ($store['total'] < 1) t_error(2, '库存不够了，请稍后再试');
        $user = $this->user_model->detail($uid);

        // 根据品鉴等级计算花费的银元
        //$pinjian_lv = $this->db->query("select pinjian_lv from zy_user WHERE uid='$uid'")->row_array();
		$pinjian_lv = $this->column_sql('pinjian_lv',array('uid'=>$uid),'zy_user',0);
        $pinjian_type = config_item('pinjian_type');
        $jian_money = 1;
        if ($pinjian_lv['pinjian_lv']) {
            $jian_money = $pinjian_type[$pinjian_lv['pinjian_lv']]['jian_money'];
        }
        $money = ($is_return['is_upgrade'] ==2)?floor(500 * $jian_money*0.8):500 * $jian_money;
//        $money = 500 * $jian_money;
        if ($money > $user['money']) t_error(3, '你的银元不足，请稍后再来');

        $this->db->trans_start();
        $this->user_model->money($uid, -$money, 0);//消耗银元
        // 品鉴香烟增加
        $shop = $this->shop_model->detail($shopid);
        $this->store_model->update_total(1, $uid, $shop['mubiao'],1);
        // 香烟减少
        $this->store_model->update_total(-1, $uid, $shopid);

        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 8,
            'money' => -$money,
        ]);

        // 经验值增加
        $xp = model('xp_config_model')->get('pinjian');
        $this->user_model->xp($uid, $xp);

        $this->user_model->pinjian_achieve($uid); //增加品鉴历练值

        //保存品鉴记录
        $this->table_insert('zy_pinjian_record', [
            'uid' => $uid,
            'shopid' => $shopid,
            'add_time' => t_time()
        ]);

        //添加每日任务
        model('task_model')->update_today($uid, 6);


        $this->db->trans_complete();
        $result['shopid'] = $shop['mubiao'];
        //随机获取品鉴成功提示语
        //$text = $this->db->query("select text,text2 from zy_shop WHERE shopid=$shop[mubiao]")->row_array();
		$text = $this->column_sql('text,text2',array('shopid'=>$shop[mubiao]),'zy_shop',0);
        $rand_number = rand(1, 2);
        if ($rand_number == 1) {
            $result['text'] = $text['text'];
        } else {
            $result['text'] = $text['text2'];
        }

        return $result;
    }

    /**
     * 升级实体烟, 成功后返回奖品信息
     *
     * @return array
     */
    function upgrade($uid, $shopid, $quan_shopid = 0)
    {
        $is_return = model('building_model')->query_upgrade($uid,9);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //判断用户
        $yan = $this->store_model->detail($uid, $shopid);
        $yan_shop = $this->shop_model->detail($shopid);
        if (!$yan) t_error(2, '用户信息错误，请稍后再试');
        if ($yan['type1'] != 'yan_pin') t_error(3, '请提交品鉴香烟');
        if ($yan['total'] < 1) t_error(3, '你的香烟库存不够了');
        if (!$yan_shop) t_error(3, '商品不存在');
        //判断乐豆是否足够，且是否达到上限
        $user = $this->user_model->detail($uid);
        $st_yan_type = config_item('st_yan_type');
        if ($st_yan_type[$yan_shop['type3']]['money'] > $user['ledou']) t_error(3, '你的乐豆不足，请稍后再来');
        //统计今天乐豆使用情况
        $is_max = $this->user_model->is_ledou_max_total($uid, $st_yan_type[$yan_shop['type3']]['money']);
        if (!$is_max) t_error(3, '你的乐豆今日使用已经到达上限，请稍后再来');

        $this->db->trans_start();
        //消耗乐豆
        $this->user_model->money($uid, 0, -$st_yan_type[$yan_shop['type3']]['money']);
        
        // 写入交易日志表
        model('log_model')->trade($uid, [
            'spend_type' => 9,
            'ledou' => -$st_yan_type[$yan_shop['type3']]['money'],
        ]);

        // 经验值增加
        $xp = model('xp_config_model')->get('shengji');
        $this->user_model->xp($uid, $xp);
        if ($quan_shopid != 0) {
            //判断兑换券是否存在
            $quan_shop = $this->shop_model->detail($quan_shopid);
            if (!$quan_shop) t_error(2, '兑换券信息错误，请稍后再试');
            //判断兑换券和提交的品鉴的烟是否匹配
            if ($yan_shop['type3'] != $quan_shop['type3']) t_error(3, '升级的香烟和兑换券不匹配');
            //判断兑换券库存是否充足
            $quan = $this->store_model->detail($uid, $quan_shopid);
            if ($quan['type1'] != 'daoju') t_error(3, '提交的兑换券错误');
            if ($quan['total'] < 1) t_error(3, '兑换券库存不够了');
            //判断券是否已经过期
            //$end_time = $this->db->query("select end_time from zy_prize WHERE shop1=$quan_shopid")->row_array();
			$end_time = $this->column_sql('end_time',array('shop1'=>$quan_shopid),'zy_prize',0);
            if (t_time() > $end_time['end_time']) t_error(1, '该券截止时间已过');
            // 更新仓库
            $affected = $this->store_model->update_total(-1, $uid, $shopid);
            if (!$affected) t_error(4, '你的品鉴香烟库存不够了，请稍后再试');
            $affected = $this->store_model->update_total(-1, $uid, $quan_shopid);
            if (!$affected) t_error(4, '你的兑换券库存不够了，请稍后再试');
            //$affected = $this->store_model->update_total(1, $uid, $yan_shop['mubiao']);
            //if (!$affected) t_error(4, '实体烟保存失败，请稍后再试');
            //添加到zy_st_message表
            $this->St_message_model->add($uid, $yan_shop['mubiao']);

            $result['success'] = 1;
            $result['mubiao'] = $yan_shop['mubiao'];
        } else {
            //先判断实体烟总数
            //$st_yan = $this->db->query("select id,total,end_time from zy_prize WHERE type1=7 AND shop1=$yan_shop[mubiao]")->row_array();
			$st_yan = $this->column_sql('id,total,end_time',array('shop1'=>$yan_shop[mubiao],'type1'=>7),'zy_prize',0);
            if ($st_yan['total'] <= 0 || (t_time() > $st_yan['end_time'])) t_error(1, '非常抱歉你来晚了，实体烟发完了');
            //获取升级实体烟的概率
            //$rate = $this->db->query("select rate from zy_yan_upgrade_rate WHERE grade=$yan_shop[type2]")->row_array();
			$rate = $this->column_sql('rate',array('grade'=>$yan_shop[type2]),'zy_yan_upgrade_rate',0);
            $temp_arr['rate_start'] = 0;
            $temp_arr['rate_end'] = $rate['rate'] - 1;
            $number = rand(0, 99);
            if ($number >= $temp_arr['rate_start'] && $number <= $temp_arr['rate_end']) {  //成功升级实体烟
                // 更新仓库
                $affected = $this->store_model->update_total(-1, $uid, $shopid);
                if (!$affected) t_error(4, '你的库存不够了，请稍后再试');
                //$affected = $this->store_model->update_total(1, $uid, $yan_shop['mubiao']);
                //if (!$affected) t_error(4, '实体烟保存失败，请稍后再试');
                //添加到zy_st_message表
                $this->St_message_model->add($uid, $yan_shop['mubiao']);
                // 奖品表zy_prize数量减少
                $affected = $this->prize_model->update_total($st_yan['id'], -1);
                if (!$affected) t_error(4, '奖品更新失败，请稍后再试');               

                $result['success'] = 1;
                $result['mubiao'] = $yan_shop['mubiao'];
            } else {
                //升级失败，随机获取奖励
                //$query = $this->db->query("select money,ledou,shop1,shop1_total from zy_prize WHERE type1=8")->result_array();
				$query = $this->column_sql('money,ledou,shop1,shop1_total',array('type1'=>8),'zy_prize',1);
                $rand_key = array_rand($query);
                $prize = $query[$rand_key];
                $result['money'] = $prize['money'];
                $result['ledou'] = $prize['ledou'];
                $result['shopid'] = $prize['shop1'];
                $result['shop_num'] = $prize['shop1_total'];
                $result['success'] = 0;
                $this->user_model->money($uid, $result['money'], $result['ledou']);//奖励存入数据库
                if ($result['shopid'] && $result['shop_num']) {
                    //判断商品是否是实体商品
                    //$row = $this->db->query("select type4 from zy_shop WHERE shopid=$result[shopid]")->row_array();
					$row = $this->column_sql('type4',array('shopid'=>$result[shopid]),'zy_shop',0);
                    if ($row['type4'] == 'st') {
                        //添加到zy_st_message表
                        $this->St_message_model->add($uid, $result['shopid']);
                    } else {
                        $affected = $this->store_model->update_total($result['shop_num'], $uid, $result['shopid'],1);
                        if (!$affected) t_error(4, '商品奖励不成功');
                    }
                }

            }
        }
        $this->db->trans_complete();
        return $result; // 返回奖品信息

    }

    // 判断是否中奖，$rate 中奖率  return bool
    function lottery($rate = 100)
    {
        $number = rand(1, 100);
        if ($number <= $rate) {
            return true;
        } else {
            return false;
        }
    }

    function quan_lists($uid)
    {
        //$list = $this->db->query("SELECT shop1 as shopid FROM zy_prize WHERE type1=6 ")->result_array();
		$list = $this->column_sql('shop1 as shopid',array('type1'=>6),'zy_prize',1);
        if (!empty($list)) {
            foreach ($list as $key => &$value) {
                //$row = $this->db->query("SELECT total FROM zy_store WHERE shopid=$value[shopid] AND uid='$uid'")->row_array();
				$row = $this->column_sql('total',array('shopid'=>$value[shopid],'uid'=>$uid),'zy_store',0);
                $value['total'] = $row['total'] ? $row['total'] : 0;
            }
        }
        return $list;
    }

    // 三星以下烟兑换积分
    function jifen($uid, $shopid)
    {
        $store = $this->store_model->detail($uid, $shopid);
        if (!$store || $store['total'] == 0 || $store['type1'] != 'yan_pin' || $store['type2'] > 3)
            t_error(1, '您的香烟规格有误');

        $this->db->trans_start();
        // 消耗烟
        $this->store_model->update_total(-1, $uid, $shopid);

        $jifen_config = [
            1 => 150,
            2 => 300,
            3 => 800,
        ];
        // 增加积分
        $this->user_model->jifen($uid, $jifen_config[$store['type2']]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '事务提交失败，系统繁忙请稍后再来');
        $user = $this->user_model->detail($uid);

        return ['jifen' => $user['jifen']];
    }


}
