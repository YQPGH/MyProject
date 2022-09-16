<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  订单任务
 */
include_once 'Base_model.php';

class Orders_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_orders_config';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/prize_model');
        $this->load->model('api/setting_model');
    }

    /**
     *  订单列表
     *
     * @return int
     */
    function list_all($uid)
    {
//        $is_return = model('building_model')->query_upgrade($uid,10);
        //查询当天已经刷新的次数
        $data = [];
        $today = t_time(0,0);
//        $num = $this->db->query("select count(*) as num from log_orders WHERE uid='$uid' AND type=3 AND add_time>='$today'")->row_array();
        $sql = "select count(*) as num from log_orders WHERE uid=? AND type=? AND add_time>=?";
        $num = $this->db->query($sql, array($uid, 3, $today))->row_array();
        $data['refresh_num'] = $num['num'];
        //从zy_orders表获取用户自己的订单
//        $orders_arr = $this->db->query("select order_index,order_id,next_refresh_time from zy_orders WHERE uid='$uid'ORDER BY id ASC")->result_array();
        $get_sql = "select order_index,order_id,next_refresh_time from zy_orders WHERE uid=? ORDER BY id ASC";
        $orders_arr = $this->db->query($get_sql, array($uid))->result_array();
        foreach($orders_arr as $key=>&$value){
            $temp_shop = [];
            $order_detail = [];
            if($value['order_id']==0 && t_time()>=$value['next_refresh_time']){
                $value['order_id'] = $sys_order_id = $this->sys_refresh($uid,$value['order_index']);
                $value['next_refresh_time'] = '0000-00-00 00:00:00';
//                $order_detail = $this->db->query("select * from zy_orders_config WHERE order_id=$sys_order_id")->row_array();
                $order_sql = "select * from zy_orders_config WHERE order_id=?";
                $order_detail =  $this->db->query($order_sql, array($sys_order_id))->row_array();
            }
            else if($value['order_id'] > 0){
//                $order_detail = $this->db->query("select * from zy_orders_config WHERE order_id=$value[order_id]")->row_array();
                $order_sql = "select * from zy_orders_config WHERE order_id=?";
                $order_detail =  $this->db->query($order_sql, array($value[order_id]))->row_array();
            }
            
            $orders_arr[$key]['name'] = $order_detail['name']?$order_detail['name']:'';//订单名称
            $orders_arr[$key]['content'] = $order_detail['content']?$order_detail['content']:'';//订单详情
            $orders_arr[$key]['game_xp'] = $order_detail['game_xp']?$order_detail['game_xp']:'';//订单奖励经验值
            $orders_arr[$key]['money'] = $order_detail['money']?$order_detail['money']:'';//订单奖励银元
            $shop_arr = explode(',',$order_detail['shopid']);
            $shop_count_arr = explode(',',$order_detail['shop_count']);
            foreach($shop_arr as $k=>$shopid){
                $temp_shop[$k]['shopid'] = $shopid;
                $temp_shop[$k]['shop_count'] = $shop_count_arr[$k];
            }
            $orders_arr[$key]['shop'] = $value['order_id']!=0 ? $temp_shop : array();//订单奖励银元
        }

        $data['list'] = $orders_arr;
        return $data;

    }

    // 设置今日2条订单到设置表
    function set_today()
    {
        $ids = [];
//        $list = $this->lists_sql("SELECT id FROM zy_orders_config WHERE status=0 LIMIT 100");
        $sql = "SELECT id FROM zy_orders_config WHERE status=? LIMIT 100";
        $list = $this->db->query($sql, array(0))->result_array();
        $rands = array_rand($list, 2);
        foreach ($rands as $key) {
            $ids[] = $list[$key]['id'];
        }
        $str = join(',', $ids);
        $temp = $this->setting_model->set_order('order_today', $str);
        return $temp;
        //return $str;
    }

    /**
     *  完成订单
     *
     * @return int
     */
    function complete($uid, $order_index)
    {

        $is_return = model('building_model')->query_upgrade($uid,10);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
//        $row = $this->db->query("select order_id from zy_orders WHERE uid='$uid' AND order_index=$order_index ")->row_array();
        $sql = "select order_id from zy_orders WHERE uid=? AND order_index=?";
        $row = $this->db->query($sql, array($uid, $order_index))->row_array();
        if($row['order_id'] != 0){
            $order_config = $this->row(['order_id' => $row['order_id']]);
            $shop_arr = explode(',',$order_config['shopid']);
            $shop_count_arr = explode(',',$order_config['shop_count']);
            // 检查用户材料数量
            foreach($shop_arr as $key=>$value){
                $store = $this->store_model->detail($uid, $value);
                if ($shop_count_arr[$key] > $store['total']) t_error(1, '你的物品不够了，请稍后再试');
            }

            $this->db->trans_start(); // 事务开始
            // 更新库存
            foreach($shop_arr as $key=>$value){
                $this->store_model->update_total(-$shop_count_arr[$key], $uid, $value);
            }

            // 写入用户订单记录表
            $insert = [
                'uid' => $uid,
                'order_index' => $order_index,
                'order_id' => $row['order_id'],
                'type' => 1,
                'add_time' => t_time(),
            ];
            $this->table_insert('log_orders', $insert);

            $order_id = $this->sys_refresh($uid,$order_index);

//            $jiaoyi_lv = $this->db->query("select jiaoyi_lv from zy_user WHERE uid='$uid'")->row_array();
            $get_sql = "select jiaoyi_lv from zy_user WHERE uid=?";
            $jiaoyi_lv = $this->db->query($get_sql, array($uid))->row_array();
            $jiaoyi_type = config_item('jiaoyi_type');
            $shou_yi = 1;
            if($jiaoyi_lv['jiaoyi_lv']){
                $shou_yi = 1+$jiaoyi_type[$jiaoyi_lv['jiaoyi_lv']]['shou_yi']/100;
            }
            $harvest_xp = ($is_return['is_upgrade']==2)?intval($order_config['game_xp']*$shou_yi*1.1):$order_config['game_xp']*$shou_yi;
            $harvest_money = ($is_return['is_upgrade']==2)?intval($order_config['money']*$shou_yi*1.1):$order_config['money']*$shou_yi;

            // 经验 银元 增加
            $this->user_model->xp($uid, $harvest_xp);
            $this->user_model->money($uid, $harvest_money);
            //添加每日任务
            model('task_model')->update_today($uid, 3);

            $this->user_model->jiaoyi_achieve($uid); //增加交易成就值

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) t_error(2, '订单提交失败了，请稍后再试');

            $today = t_time(0,0);
//            $num = $this->db->query("select count(*) as num from log_orders WHERE uid='$uid' AND type=3 AND add_time>='$today'")->row_array();
            $num_sql = "select count(*) as num from log_orders WHERE uid=? AND type=3 AND add_time>=?";
            $num = $this->db->query($num_sql, array($uid, $today))->row_array();
//            $order_detail = $this->db->query("select * from zy_orders_config WHERE order_id=$order_id")->row_array();
            $order_sql = "select * from zy_orders_config WHERE order_id=?";
            $order_detail = $this->db->query($order_sql, array($order_id))->row_array();
            $order_detail['game_xp'] = ($is_return['is_upgrade']==2)?intval($order_detail['game_xp']*1.1):$order_detail['game_xp'];
            $order_detail['money'] = ($is_return['is_upgrade']==2)?intval($order_detail['money']*1.1):$order_detail['money'];
            $new_orders_arr[0]['order_index'] = $order_index;
            $new_orders_arr[0]['order_id'] = $order_id;
            $new_orders_arr[0]['next_refresh_time'] = '0000-00-00 00:00:00';
            $new_orders_arr[0]['name'] = $order_detail['name']?$order_detail['name']:'';//订单名称
            $new_orders_arr[0]['content'] = $order_detail['content']?$order_detail['content']:'';//订单详情
            $new_orders_arr[0]['game_xp'] = $order_detail['game_xp']?$order_detail['game_xp']:'';//订单奖励经验值
            $new_orders_arr[0]['money'] = $order_detail['money']?$order_detail['money']:'';//订单奖励银元
            $shop_arr = explode(',',$order_detail['shopid']);
            $shop_count_arr = explode(',',$order_detail['shop_count']);

            foreach($shop_arr as $k=>$shopid){
                $temp_shop[$k]['shopid'] = $shopid;
                $temp_shop[$k]['shop_count'] = $shop_count_arr[$k];
            }
            $new_orders_arr[0]['shop'] = $temp_shop;//订单奖励银元

            $this->load->model('api/fragment_model');
            $suipian = $this->fragment_model->get_fragment($uid,'orders');
            if(count($suipian)>0){
                $data['suipian'][] = $suipian;
            }else{
                $data['suipian'] = array();
            }
            $data['refresh_num'] = $num['num'];
            $data['list'] = $new_orders_arr;

            return $data;

        }else{
            t_error(1, '订单无效！');
        }

    }



    //删除订单
    function delete_order($uid,$order_index){
        $is_return = model('building_model')->query_upgrade($uid,10);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        //判断当前订单位置的订单是否已经被删除
//        $num = $this->db->query("select order_id from zy_orders WHERE uid='$uid'AND order_index=$order_index")->row_array();
        $sql = "select order_id from zy_orders WHERE uid=? AND order_index=?";
        $num = $this->db->query($sql, array($uid, $order_index))->row_array();
        if($num['order_id']==0)  t_error(1, '订单已删除！');

        //添加删除订单记录到Log_orders表
        $insert['uid'] = $uid;
        $insert['order_index'] = $order_index;
        $insert['order_id'] = $num['order_id'];
        $insert['type'] = 2;
        $insert['add_time'] = t_time();
        $this->db->insert('log_orders', $insert);

        $next_refresh_time = date("Y-m-d H:i:s",strtotime(t_time())+3600);
        $this->db->set('order_id', 0);
        $this->db->set('next_refresh_time', $next_refresh_time);
        $this->db->where('uid', $uid);
        $this->db->where('order_index', $order_index);
        $this->db->update('zy_orders');
        $result['index'] = $order_index;
        $result['next_refresh_time'] = $next_refresh_time;
        return $result;
    }

    //刷新订单（个人）
    function refresh($uid,$order_index){
        $is_return = model('building_model')->query_upgrade($uid,10);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $data = [];
		$today = t_time(0,0);
        //$orders_row = $this->db->query("select next_refresh_time from zy_orders WHERE uid='$uid' AND order_index=$order_index")->row_array();
        //判断刷新时间是否已经到
        $insert['type'] = 3;
        //每天免费三次刷新机会，超过需花费1乐豆
//        $num = $this->db->query("select count(*) as num from log_orders WHERE uid='$uid' AND type=3 AND add_time>='$today'")->row_array();
        $sql = "select count(*) as num from log_orders WHERE uid=? AND type=? AND add_time>=?";
        $num = $this->db->query($sql, array($uid,3, $today))->row_array();
        if($num['num']>=3){
            $this->load->model('api/user_model');
            $is_max = $this->user_model->is_ledou_max_total($uid,1);
            if($is_max){
                $user = $this->user_model->detail($uid);
                if (1 > $user['ledou']) t_error(1, '您的乐豆不够了');
                // 事务开始
                $this->db->trans_start();
                // 银元乐豆扣除
                $this->user_model->money($uid, 0,-1);//消耗乐豆
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 10,
                    'ledou' => -1
                ]);

                $this->db->trans_complete();
            }else{
                t_error(1, '今日乐豆使用已经超过上限');
            }
        }

        //随机获取一个订单
        $order_id = $this->getRandOrder($uid);

        //添加到log_orders记录表
        $insert['uid'] = $uid;
        $insert['order_index'] = $order_index;
        $insert['order_id'] = $order_id;
        $insert['add_time'] = t_time();
        $this->db->insert('log_orders', $insert);

        $next_refresh_time = '0000-00-00 00:00:00';
        $this->db->set('order_id', $order_id);
        $this->db->set('next_refresh_time', $next_refresh_time);
        $this->db->where('uid', $uid);
        $this->db->where('order_index', $order_index);
        $this->db->update('zy_orders');

//        $order_detail = $this->db->query("select * from zy_orders_config WHERE order_id=$order_id")->row_array();
        $get_sql = "select * from zy_orders_config WHERE order_id=?";
        $order_detail = $this->db->query($get_sql, array($order_id))->row_array();
        $new_orders_arr[0]['order_index'] = $order_index;
        $new_orders_arr[0]['order_id'] = $order_id;
        $new_orders_arr[0]['next_refresh_time'] = '0000-00-00 00:00:00';
        $new_orders_arr[0]['name'] = $order_detail['name']?$order_detail['name']:'';//订单名称
        $new_orders_arr[0]['content'] = $order_detail['content']?$order_detail['content']:'';//订单详情
        $new_orders_arr[0]['game_xp'] = $order_detail['game_xp']?$order_detail['game_xp']:'';//订单奖励经验值
        $new_orders_arr[0]['money'] = $order_detail['money']?$order_detail['money']:'';//订单奖励银元
        $shop_arr = explode(',',$order_detail['shopid']);
        $shop_count_arr = explode(',',$order_detail['shop_count']);
        foreach($shop_arr as $k=>$shopid){
            $temp_shop[$k]['shopid'] = $shopid;
            $temp_shop[$k]['shop_count'] = $shop_count_arr[$k];
        }
        $new_orders_arr[0]['shop'] = $temp_shop;//订单奖励银元

        $data['refresh_num'] = $num['num']+1;
        $data['list'] = $new_orders_arr;
        return $data;
    }

    //随机获取一个订单
    function getRandOrder($uid){
        $type2 = 1;
        $order_ranking = [];
        $order_ranking_init = config_item('order_ranking_init');
        $order_ranking_change = config_item('order_ranking_change');
        $this->load->model('api/user_model');
        $user = $this->user_model->detail($uid);
        if($user['game_lv']>0){
            foreach($order_ranking_init as $key=>$value){
                $order_ranking[$key] = $value+$order_ranking_change[$key]*$user['game_lv'];
            }
        }else{
            $order_ranking = $order_ranking_init;
        }
        $i = 0;
        foreach($order_ranking as $key=>$value){
            $rand_arr[$key]['rate_start'] = $i;
            $i = $i+$value;
            $rand_arr[$key]['rate_end'] = $i;
        }
        $rand_number = rand(0,99);
        foreach($rand_arr as $key=>$value){
            if($rand_number >= $value['rate_start'] && $rand_number < $value['rate_end']){
                $type2 = $key;
            }
        }
        //不能出现今天出现过的订单
        /*$today = strtotime(t_time(0,0));//今天凌晨
        $row = $this->db->query("select order_id from log_orders WHERE uid='$uid' AND UNIX_TIMESTAMP(add_time) > $today ")->result_array();
        if(!empty($row)){
            foreach($row as $key=>$value){
                $str = $value['order_id'].',';
            }
            $str = rtrim($str, ",");
            //获取所有订单id
            $order_arr = $this->db->query("select order_id from zy_orders_config WHERE type2=$type2 AND order_id NOT IN ($str)")->result_array();
        }else{
            //获取所有订单id
            $order_arr = $this->db->query("select order_id from zy_orders_config WHERE type2=$type2 ")->result_array();
        }*/
//        $order_arr = $this->db->query("select order_id from zy_orders_config WHERE type2=$type2 ")->result_array();
        $sql = "select order_id from zy_orders_config WHERE type2=? ";
        $order_arr = $this->db->query($sql, array($type2))->result_array();
        $order_ids = [];
        foreach($order_arr as $key=>$value){
            $order_ids[] = $value['order_id'];
        }

        //从数组中随机选一个
        $rand_key = array_rand($order_ids);
        $order_id = $order_ids[$rand_key];
        return $order_id;

        /*if($order_id){
            return $order_id;
        }else{
            $order_id = rand(1,50);
            return $order_id;   //随机取不到订单则从50个订单中随机取一个
        }*/

    }

    //系统刷新订单
    function sys_refresh($uid,$order_index){

        //获取所有订单id
        /*$order_arr = $this->db->query("select order_id from zy_orders_config where type2 in (1,2)")->result_array();
        $order_ids = [];
        foreach($order_arr as $key=>$value){
            $order_ids[] = $value['order_id'];
        }

        //不能出现今天出现过的订单
        $today = t_time(0,0);
        $row = $this->db->query("select order_id from log_orders WHERE uid='$uid' AND add_time>'$today' AND type in (1,2)")->result_array();
        $temp = [];
        if(!empty($row)){
            foreach($row as $key=>$value){
                $temp[] = $value['order_id'];
            }
            $temp = array_flip(array_flip($temp));
            $use_ids = array_diff($order_ids,$temp);
        }else{
            $use_ids = $order_ids;
        }

        //从数组中随机选一个
        $rand_key = array_rand($use_ids);
        if($use_ids[$rand_key]){
            $order_id = $use_ids[$rand_key];
        }else{
            //如果今天把订单全部刷完，则随机取一个
            $rand_key = array_rand($order_ids);
            $order_id = $use_ids[$rand_key];
        }

        if($order_id){

        }else{
            $order_id = rand(1,50);
        }*/
        $order_id = $this->getRandOrder($uid);

        //添加到log_orders记录表
        $insert['uid'] = $uid;
        $insert['order_index'] = $order_index;
        $insert['order_id'] = $order_id;
        $insert['type'] = 4;
        $insert['add_time'] = t_time();
        $this->db->insert('log_orders', $insert);

        $next_refresh_time = '0000-00-00 00:00:00';
        $this->db->set('order_id', $order_id);
        $this->db->set('next_refresh_time', $next_refresh_time);
        $this->db->where('uid', $uid);
        $this->db->where('order_index', $order_index);
        $this->db->update('zy_orders');

        return $order_id;
    }

    // 获取用户今日已完成订单数
    function today_completed($uid)
    {
        $today = $this->time->today();
//        $value = $this->row_sql("SELECT COUNT(*) total
//                                FROM `log_orders`
//                                WHERE uid='{$uid}' AND add_time > '{$today}' AND type=1
//                                LIMIT 100;");
    $sql = "SELECT COUNT(*) total
                                FROM `log_orders`
                                WHERE uid=? AND add_time > ? AND type=?
                                LIMIT 100";
        $value = $this->db->query($sql, array($uid, $today, 1))->row_array();
        return $value['total'];
    }

    //查询订单需要的物品在仓库的库存
    function is_order_completed($uid,$order_index){
//        $row = $this->db->query("select order_id from zy_orders WHERE uid='$uid' AND order_index=$order_index ")->row_array();
        $sql = "select order_id from zy_orders WHERE uid=? AND order_index=?";
        $row = $this->db->query($sql, array($uid, $order_index))->row_array();;
        if($row['order_id'] != 0){
            $data = [];
            $order_config = $this->row(['order_id' => $row['order_id']]);
            $shop_arr = explode(',',$order_config['shopid']);
            // 检查用户材料数量
            foreach($shop_arr as $key=>$value){
                $store = $this->store_model->detail($uid, $value);
                $data[$value] = $store['total'] ? $store['total'] : 0;
            }
            return $data;

        }else{
            t_error(1, '订单无效！');
        }
    }

    //概率测试
    public function testRank($uid){
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        for($j=0;$j<100;$j++){
            $type2 = 1;
            $order_ranking = [];
            $order_ranking_init = config_item('order_ranking_init');
            $order_ranking_change = config_item('order_ranking_change');
            $this->load->model('api/user_model');
            $user = $this->user_model->detail($uid);
            if($user['game_lv']>0){
                foreach($order_ranking_init as $key=>$value){
                    $order_ranking[$key] = $value+$order_ranking_change[$key]*$user['game_lv'];
                }
            }else{
                $order_ranking = $order_ranking_init;
            }
            $i = 0;
            foreach($order_ranking as $key=>$value){
                $rand_arr[$key]['rate_start'] = $i;
                $i = $i+$value;
                $rand_arr[$key]['rate_end'] = $i;
                $rand_arr[$key]['num'] = $rand_arr[$key]['rate_end'] - $rand_arr[$key]['rate_start'];
            }
            $rand_number = rand(0,99);
            foreach($rand_arr as $key=>$value){
                if($rand_number >= $value['rate_start'] && $rand_number < $value['rate_end']){
                    $type2 = $key;
                }
            }
            $arr[$j] = $type2;
        }

        foreach($arr as $key=>$value){
            if($value==1){
                $a++;
            }elseif($value==2){
                $b++;
            }elseif($value==3){
                $c++;
            }elseif($value==4){
                $d++;
            }elseif($value==5){
                $e++;
            }
        }

        $res[1] = $a;
        $res[2] = $b;
        $res[3] = $c;
        $res[4] = $d;
        $res[5] = $e;
        $res['number'] = $rand_number;
        $res['rand'] = $rand_arr;
        return $res;

    }


}
