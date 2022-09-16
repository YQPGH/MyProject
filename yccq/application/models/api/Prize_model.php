<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  用户的奖品
 */
include_once 'Base_model.php';

class Prize_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_prize';
        $this->load->model('admin/store_model');
        $this->load->model('admin/user_model');
		$this->load->model('api/Scrape_model');
    }

    // 获奖记录
    function logs($uid)
    {
        $list = $this->lists_sql("SELECT log.add_time log_time,p.*
                        FROM log_prize log LEFT JOIN zy_prize p
                        ON log.prize_id=p.id
                        WHERE log.uid=?
                        ORDER BY log.id DESC LIMIT 20;", [$uid]);

        if(count($list)>0)
        {
            $result = [];
            foreach ($list as $value) {


                $shop = [];
                if($value['shop1']){
                    $shop = $this->shop_model->detail($value['shop1']);
                    if($value['shandian'])
                    {
                        $result[] = [
                            'log_time' => $value['log_time'],
                            'money' => 0 ,
                            'ledou' => 0,
                            'shandian' => $value['shandian'],
                            'shop1' => 0
                        ];
                    }
                    if($value['money'] )
                    {
                        $result[] = [
                            'log_time' => $value['log_time'],
                            'money' => $value['money'] ,
                            'ledou' => 0,
                            'shandian' => 0,
                            'shop1' => 0
                        ];
                    }
                }
                if($value['type1'] == 'march')
                {
                    if($value['shop2']){
                        $shop = $this->shop_model->detail($value['shop2']);
                    }

                    $result[] = [
                        'log_time' => $value['log_time'],
                        'money' => $value['money'] ,
                        'ledou' => 0,
                        'shandian' => 0,
                        'shop1' => 0
                    ];
                    $result[] = [
                        'log_time' => $value['log_time'],
                        'money' => 0 ,
                        'ledou' => 0,
                        'shandian' => $value['shandian'],
                        'shop1' => 0
                    ];
                    $result[] = [
                        'log_time' => $value['log_time'],
                        'money' => 0 ,
                        'ledou' => 0,
                        'shandian' => 0,
                        'shop1' => $value['shop2']
                    ];
                }
                if($shop['type4'] != 'quan' ){
                    $row = [];
                    $row['log_time'] = $value['log_time'];
                    $value['money'] ? $row['money']=$value['money'] : $row['money']=0 ;
                    $value['ledou'] ? $row['ledou']=$value['ledou'] : $row['ledou']=0 ;
                    $value['shandian'] ? $row['shandian']=$value['shandian'] : $row['shandian']=0 ;
                    $value['shop1'] ? $row['shop1']=$value['shop1'] : $row['shop1']=0 ;

                    $result[] = $row;
                }
            }

            //获取元旦刮奖，9月粉丝节刮奖获奖记录
            $data = $this->Scrape_model->getAwardLog($uid,array('peifang','zhongzi','building'),'newYear,sept');
//            if ($data['scrape_list']) {
//                foreach ($data['scrape_list'] as $key => $val) {
//                    $result[] = $val;
//                    $ctime_str = date("YmdHis",strtotime($val['log_time']));
//                    $result[$key]['ctime_str'] = $ctime_str;
//                    $datetime[] = $ctime_str;
//                }
//                array_multisort($datetime,SORT_DESC,$result);
//            }

//            $prize = $this->ranking_prize($uid);
            $prize = model('midautumn_model')->prize_log($uid);
            foreach($prize as &$va)
            {
                $result[] = $va;
            }

            $idArr = array_column($result, 'log_time');
            array_multisort($idArr,SORT_DESC,$result);
            return $result;
        }
        else
        {
//            $result = $this->ranking_prize($uid);
            $result = model('midautumn_model')->prize_log($uid);

            //获取元旦刮奖，9月粉丝节刮奖获奖记录
            $data = $this->Scrape_model->getAwardLog($uid,array('peifang','zhongzi','building'),'newYear,sept');
            if ($data['scrape_list']) {
                foreach ($data['scrape_list'] as $key => $val) {
                    $result[] = $val;
                    $ctime_str = date("YmdHis",strtotime($val['log_time']));
                    $result[$key]['ctime_str'] = $ctime_str;
                    $datetime[] = $ctime_str;
                }
                array_multisort($datetime,SORT_DESC,$result);
            }

            return $result;
        }

    }

    function ranking_prize($uid)
    {
        $time = strtotime('2020-03-01 00:00:00');

        $tables = ['zy_ranking_zz_prize_record','zy_ranking_zy_prize_record'];
        $list = [];
        foreach($tables as $value)
        {

            $ranking_list = $this->lists_sql("SELECT log.add_time log_time,p.*
                        FROM $value log LEFT JOIN zy_ranking_jf_prize_config p
                        ON log.pid=p.id
                        WHERE log.uid=?  AND UNIX_TIMESTAMP(log.add_time)>'$time'
                        ORDER BY log.id DESC LIMIT 20;", [$uid]);

            if($ranking_list)
            {
                $list[] =  $ranking_list;
            }

        }

        if(count($list)>0){
            $result = [];
            foreach($list as $key => $value)
            {
                foreach($value as  $v)
                {

                    $row = [];
                    $row['log_time'] = $v['log_time'];
                    $v['money'] ? $row['money'] = $v['money'] : $row['money'] = 0;
                    $v['ledou'] ? $row['ledou'] = $v['ledou'] : $row['ledou'] = 0;
                    $v['shandian'] ? $row['shandian'] = $v['shandian'] : $row['shandian'] = 0;
                    $v['shop1_id'] ? $row['shop1'] = $v['shop1_id'] : $row['shop_id'] = 0;
                    $v['shop2_id'] ? $row['shop1'] = $v['shop2_id'] : $row['shop_id'] = 0;
                    $result[] = [
                        'log_time' => $row['log_time'],
                        'money' => $v['money'] ,
                        'ledou' => 0,
                        'shandian' => 0,
                        'shop1' => 0
                    ];
                    $result[] = [
                        'log_time' => $row['log_time'],
                        'money' => 0 ,
                        'ledou' => 0,
                        'shandian' => $row['shandian'],
                        'shop1' => 0
                    ];
                    $result[] = [
                        'log_time' => $row['log_time'],
                        'money' => 0 ,
                        'ledou' => 0,
                        'shandian' => 0,
                        'shop1' => $row['shop1']
                    ];

                }
            }
            return $result;
        }

    }

    // 获奖记录
    function logs_quan($uid)
    {
        //$list = $this->lists_sql("SELECT log.add_time log_time,log.shopid,log.id,log.status FROM log_prize_quan log WHERE log.uid=? ORDER BY log.id DESC LIMIT 20;", [$uid]);

        //$list = $this->lists_sql("SELECT ticket.addtime log_time,ticket.shopid,ticket.id,ticket.stat status,shop.`json_data` FROM zy_ticket_record ticket, zy_shop shop WHERE ticket.`shopid` = shop.`shopid` AND ticket. uid=? ORDER BY ticket.stat ASC,addtime DESC LIMIT 20", [$uid]);
        //京东卡
//        $list = $this->lists_sql("SELECT ticket.addtime log_time,ticket.shopid,ticket.id,ticket.stat status,shop.`json_data` FROM zy_jdk_record ticket, zy_shop shop WHERE ticket.`shopid` = shop.`shopid` AND ticket. uid=? ORDER BY ticket.stat ASC,addtime DESC LIMIT 20", [$uid]);
        //实物奖品 排行榜
        $list = $this->ranking_list($uid);

        //元旦刮奖实物奖品
        $data = $this->Scrape_model->getAwardLog($uid,'prize','newYear');
        $scrape_prize_list = $data['scrape_prize_list']?$data['scrape_prize_list']:array();
        $list  = array_merge($list,$scrape_prize_list);


        $arr = $this->lists_sql("SELECT ticket.addtime log_time,ticket.shopid,ticket.id,ticket.stat status,shop.`json_data` FROM zy_ticket_record ticket, zy_shop shop WHERE ticket.`shopid` = shop.`shopid` AND ticket. uid=? ORDER BY ticket.stat ASC,addtime DESC LIMIT 20", [$uid]);
        //碎片奖品
//        $fragment_prize = $this->lists_sql("SELECT UNIX_TIMESTAMP(ticket.add_time) log_time,ticket.shopid,ticket.id,ticket.url,ticket.status,shop.`json_data` FROM zy_fragment_prize_record ticket, zy_shop shop WHERE ticket.`shopid` = shop.`shopid` AND ticket. uid=? ORDER BY ticket.status ASC,ticket.add_time DESC LIMIT 20", [$uid]);

        $lists  = array_merge($list,$arr);

        foreach($lists as $k => &$v){
            if(!$v['url']){
                $v['url'] = '';
            }
            if($v['pid']){
                $row = $this->column_sql('shopid,json_data',['shopid'=>$v['shop1']],'zy_shop',0);
                $v['shopid'] = $row['shopid'];
                $v['json_data'] = $row['json_data'];
//                $address = $this->column_sql('pid',['pid'=>$v['id']],'zy_laxin_message',0);
                $v['status'] = 0;
                $v['url'] = site_url('api/plantaddress/getUsermessage?id='.$v['id']);
                unset($v['pid']);
                unset($v['shop1']);
            }
            $obj = json_decode($v['json_data']);
            $lists[$k]['vali'] = date('Y-m-d H:i:s',$v['log_time']+($obj->vali)*24*3600);
            if(($v['log_time']+($obj->vali)*24*3600) < time()){
                $lists[$k]['is_overtime'] = 1;
            }else{
                $lists[$k]['is_overtime'] = 0;
            }
            unset($lists[$k]['json_data']);

            if(!$v['laxin']){
                $v['laxin'] = 0;
            }


        }
        $prize =  model('energytrees_model')->prize_record($uid);
        foreach($prize as &$va)
        {
            $lists[] = $va;
        }
        $idArr = array_column($lists, 'log_time');
        array_multisort($idArr,SORT_DESC,$lists);
        return $lists;
    }


    function ranking_list($uid){

        $time = strtotime('2020-03-01 00:00:00');

        //实物奖品
        $tables = ['zy_ranking_zz_prize_record','zy_ranking_zy_prize_record'];
        $array = [];
        foreach($tables as $value)
        {

            $list = $this->lists_sql("SELECT UNIX_TIMESTAMP(ticket.add_time) log_time,ticket.id,ticket.pid,c.shop3_id shop1
                     FROM $value ticket, zy_ranking_jf_prize_config c
                     WHERE c.shop3_id>0 and ticket.`pid` = c.`id`  AND ticket.uid=?
                     AND UNIX_TIMESTAMP(ticket.add_time)>'$time'
                     ORDER BY ticket.add_time DESC LIMIT 20", [$uid]);

            if($list && count($list)>0)
            {
                foreach($list as $key => $value)
                {
                    $array[] = $value;
                }
            }
        }

        return $array;

    }

    // 保存日志
    function log_save($uid, $prize_id, $jifen = 0, $shopid = 0)
    {
        $insert_id = $this->table_insert('log_prize', [
            'uid' => $uid,
            'prize_id' => $prize_id,
            'xh_jifen' => $jifen,
            'xh_shopid' => $shopid,
            'add_time' => t_time(),
        ]);

        return $insert_id;
    }

    //每日抽奖奖品列表
    function every_day_reward_list($uid)
    {
//        $result['list'] = $this->db->query("select money,shop1 as shopid,shop1_total as shop_num from zy_prize WHERE type1=4")->result_array();
        $sql = "select money,shop1 as shopid,shop1_total as shop_num from zy_prize WHERE type1=?";
        $result['list'] = $this->db->query($sql, array(4))->result_array();
        //查看log_prize表是否有当天的抽奖记录
        $today = t_time(0, 0);
//        $last_row = $this->db->query("select COUNT(*) as num from log_prize WHERE uid='$uid' AND add_time > '$today'")->row_array();
        $get_sql = "select COUNT(*) as num from log_prize WHERE uid=? AND add_time > ?";
        $last_row  = $this->db->query($get_sql, array($uid,$today))->row_array();
        //$last_sign_day = $this->time->day($last_row['add_time']);
        if ($last_row['num']) {
            $result['is_reward'] = 1;
        } else {
            $result['is_reward'] = 0;
        }
        return $result;
    }


    //每日抽奖开始摇奖
    function reward_start($uid)
    {
        $is_return = model('building_model')->query_upgrade($uid,11);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
//        $result = $this->db->query("select id,money,shop1 as shopid,shop1_total as shop_num ,get_rate from zy_prize WHERE type1=4")->result_array();
        $sql = "select id,money,shop1 as shopid,shop1_total as shop_num ,get_rate from zy_prize WHERE type1=?";
        $result = $this->db->query($sql, array(4))->result_array();
        $temp = 1;
        $temp_arr = array();
        foreach ($result as $key => $value) {
            $temp_arr[$key]['rate_start'] = $temp;
            $temp = $temp + $value['get_rate'];
            $temp_arr[$key]['rate_end'] = $temp - 1;
        }
        $number = rand(1, 100);
        foreach ($temp_arr as $key => $value) {
            if ($number >= $value['rate_start'] && $number <= $value['rate_end']) {
                $rand_key = $key;
                break;
            }
        }

        // 事务开始
        $this->db->trans_start();
        //查看log_prize表是否有当天的抽奖记录
        $today = t_time(0, 0);
//        $last_row = $this->db->query("select COUNT(*) as num from log_prize WHERE uid='$uid' AND add_time > '$today'")->row_array();
        $sql = "select COUNT(*) as num from log_prize WHERE uid=? AND add_time > ?";
        $last_row =  $this->db->query($sql, array($uid,$today))->row_array();
        if ($last_row['num']) {
            //今天已经抽过，再抽需要消耗乐豆
            $this->load->model('api/user_model');
            $user = $this->user_model->detail($uid);
            if (2 > $user['ledou']) t_error(3, '你的乐豆不足，请稍后再来');
            //统计今天乐豆使用情况
            $is_max = $this->user_model->is_ledou_max_total($uid, 2);
            if (!$is_max) t_error(3, '你的乐豆今日使用已经到达上限，请稍后再来');
            $this->user_model->money($uid, 0, -2);//消耗乐豆

            // 写入交易日志表
            model('log_model')->trade($uid, [
                'spend_type' => 12,
                'ledou' => -2
            ]);

        } else {
            $result['is_reward'] = 0;
        }
        //添加到抽奖记录表（log_prize）
        $data = array(
            'uid' => $uid,
            'prize_id' => $result[$rand_key]['id'],
            'reward_type' => 4,
            'type1' => '',
            'money' => $result[$rand_key]['money'],
            'ledou' => 0,
            'xp' => 0,
            'shopid' => $result[$rand_key]['shopid'],
            'add_time' => t_time()
        );
        $this->db->insert('log_prize', $data);
        //将奖品存入数据库(奖品暂时定银元和商品表里的物品，如果添加其他的奖品类型，需修改奖品入库代码)
        if ($result[$rand_key]['money'] && $result[$rand_key]['shopid'] == 0) {
            //抽中的是银元
            $this->load->model('api/user_model');
            $this->user_model->money($uid, $result[$rand_key]['money'], 0);
        } else {
            //抽中商品
            $this->load->model('api/store_model');
            $this->store_model->update_total($result[$rand_key]['shop_num'], $uid, $result[$rand_key]['shopid']);
        }
        $this->db->trans_complete();
        // 事务结束
        unset($result[$rand_key]['id']);
        unset($result[$rand_key]['get_rate']);
        $result[$rand_key]['index'] = $rand_key + 1;
        return $result[$rand_key];
    }


    // 积分抽奖奖品列表
    function jifen_list()
    {
        //获取每周抽奖随机编号的奖励
        $this->load->model('api/setting_model');
        $type2 = $this->setting_model->get('type2');
//        $list = $this->lists_sql("SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=7 AND type2=$type2");
       $sql = "SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? AND type2=?";
        $list =$this->db->query($sql, array(7,$type2))->result_array();
        return $list;
    }

    // 积分开始摇奖, 消耗积分
    function jifen_result($uid)
    {
        $is_return = model('building_model')->query_upgrade($uid,11);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $user = $this->user_model->detail($uid);
        if (!$user || $user['jifen'] < 800)
            t_error(1, '您的积分不够了');

        // 计算概率抽奖,获取奖品记录
        $prize = $this->rate_row(7,$uid);

        // 事务开始
        $this->db->trans_start();
        // 消耗积分
        $this->user_model->jifen($uid, -800);
        // 奖品入库
        if ($prize['money']) {
            $this->user_model->money($uid, $prize['money'],0);
        }
        if ($prize['ledou']) {
            $this->user_model->money($uid, 0,$prize['ledou']);
        }
        // 奖品入库
        if ($prize['shandian']) {
            $this->user_model->shandian($uid, $prize['shandian']);
        }
        if ($prize['shopid']) {
            $shop = $this->shop_model->detail($prize['shopid']);
            $this->store_model->update_total($prize['shop_num'], $uid, $prize['shopid']);
            $openid = $this->user_model->queryOpenidByUid($uid);
            //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
            if($shop['type4'] == 'quan'){
                //根据uid获取openid
                $data = array(
                    'shopid' => $prize['shopid'],
                    'ticket_id' => t_rand_str($uid),
                    'uid' => $uid,
                    'openid' => $openid,
                    'stat' => 0,
                    'addtime' => time()
                );
                $this->db->insert('zy_ticket_record', $data);
            }
        }
        // 奖品日志保存
        $this->log_save($uid, $prize['id'], 800);

        $this->db->trans_complete();

        unset($prize['get_rate']);
        return $prize;
    }

    // 4星烟抽奖奖品列表
    function yan4_list()
    {
        //获取每周抽奖随机编号的奖励
        $this->load->model('api/setting_model');
        $type2 = $this->setting_model->get('type2');
//        $list = $this->lists_sql("SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=8 AND type2=$type2");
        $sql = "SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? AND type2=?";
        $list =  $this->db->query($sql, array(8,$type2))->result_array();
        return $list;
    }

    // 4星开始摇奖, 消耗烟
    function yan4_result($uid, $yan_id)
    {
        $is_return = model('building_model')->query_upgrade($uid,11);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $yan = $this->store_model->detail($uid, $yan_id);

        if (!$yan || $yan['total'] == 0 || $yan['type2'] != 4 || $yan['type1'] != 'yan_pin')
            t_error(1, '您的四星香烟不够了');

        // 计算概率抽奖,获取奖品记录
        $prize = $this->rate_row(8,$uid);

        // 事务开始
        $this->db->trans_start();
        // 消耗烟
        $this->store_model->update_total(-1, $uid, $yan_id);
        // 奖品入库
        if ($prize['money']) {
            $this->user_model->money($uid, $prize['money']);
        }
        // 奖品入库
        if ($prize['shandian']) {
            $this->user_model->shandian($uid, $prize['shandian']);
        }
        if ($prize['shopid']) {
            $shop = $this->shop_model->detail($prize['shopid']);
            $this->store_model->update_total($prize['shop_num'], $uid, $prize['shopid']);
            $openid = $this->user_model->queryOpenidByUid($uid);
            //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
            if($shop['type4'] == 'quan'){
                //根据uid获取openid
                $data = array(
                    'shopid' => $prize['shopid'],
                    'ticket_id' => t_rand_str($uid),
                    'uid' => $uid,
                    'openid' => $openid,
                    'stat' => 0,
                    'addtime' => time()
                );
                $this->db->insert('zy_ticket_record', $data);
            }
        }
        // 奖品日志保存
        $this->log_save($uid, $prize['id'], 0, $yan_id);
        $this->db->trans_complete();

        unset($prize['get_rate']);
        return $prize;
    }

    // 五星烟抽奖奖品列表
    function yan5_list()
    {
        //获取每周抽奖随机编号的奖励
        $this->load->model('api/setting_model');
        $type2 = $this->setting_model->get('type2');
//        $list = $this->lists_sql("SELECT id,name,money,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=9 AND type2=$type2");
       $sql = "SELECT id,name,money,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? AND type2=?";
        $list = $this->db->query($sql, array(9,$type2))->result_array();
        return $list;
    }

    // 五星开始摇奖, 消耗烟
    function yan5_result($uid, $yan_id)
    {
        $is_return = model('building_model')->query_upgrade($uid,11);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        $yan = $this->store_model->detail($uid, $yan_id);
        if (!$yan || $yan['total'] == 0 || $yan['type2'] != 5 || $yan['type1'] != 'yan_pin')
            t_error(1, '您的五星香烟不够了');

        // 计算概率抽奖,获取奖品记录
        $prize = $this->rate_row(9,$uid);

        // 事务开始
        $this->db->trans_start();
        // 消耗烟
        $this->store_model->update_total(-1, $uid, $yan_id);
        // 奖品入库
        if ($prize['money']) {
            $this->user_model->money($uid, $prize['money']);
        }
        // 奖品入库
        if ($prize['shandian']) {
            $this->user_model->shandian($uid, $prize['shandian']);
        }
        if ($prize['shopid']) {
            $shop = $this->shop_model->detail($prize['shopid']);
            $this->store_model->update_total($prize['shop_num'], $uid, $prize['shopid']);
            $openid = $this->user_model->queryOpenidByUid($uid);
            //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
            if($shop['type4'] == 'quan'){
                //根据uid获取openid
                $data = array(
                    'shopid' => $prize['shopid'],
                    'ticket_id' => t_rand_str($uid),
                    'uid' => $uid,
                    'openid' => $openid,
                    'stat' => 0,
                    'addtime' => time()
                );
                $this->db->insert('zy_ticket_record', $data);
            }
        }
        $this->log_save($uid, $prize['id'], 0, $yan_id);
        $this->db->trans_complete();

        unset($prize['get_rate']);
        return $prize;
    }

    // 根据类别，计算概率抽奖,获取奖品记录
    function rate_row($type,$uid)
    {
        $this->load->model('api/setting_model');
        $type2 = $this->setting_model->get('type2');
        if($type==8 || $type==9){
            //判断当月抵扣券是否消耗完
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $begin_moon =  strtotime($BeginDate);
            $end_moon = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))));

            $sql = "select count(*) as num from zy_ticket_record WHERE addtime > ? AND `type`=?";
            $count = $this->db->query($sql, array($begin_moon,0))->row_array();

            //黑名单false，不是的话为true
            $is_prize_black = $this->is_prize_black($uid);


            //判断用户当月是否已经抽中抵扣券（限制单个人过多抽中抵扣券）
            $my_sql = "select count(*) as num from zy_ticket_record WHERE uid=? AND addtime > ? AND addtime < ?";
            $my_count = $this->db->query($my_sql, array($uid,$begin_moon,$end_moon))->row_array();
            $time = time();
            $limit_num = $this->db->query("select total from zy_ticket_config WHERE '$time' >=UNIX_TIMESTAMP(starttime) and '$time'<= UNIX_TIMESTAMP(endtime)")->row_array();
            $limit_num = $limit_num ? $limit_num['total']:0;
            if($count['num'] < $limit_num && $is_prize_black && $my_count['num']<1){
                $get_sql = "SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num, get_rate FROM zy_prize WHERE type1=? AND type2=? AND get_rate>? LIMIT 100";
                $list = $this->db->query($get_sql, array($type,$type2,0))->result_array();
            }else{
                $get_sql = "SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num, get_rate FROM zy_prize WHERE type1=? AND type2=? AND get_rate>? LIMIT 0,9";
                $list = $this->db->query($get_sql, array($type,$type2,0))->result_array();
            }

        }else{
            $get_sql = "SELECT id,name,money,ledou,shandian,shop1 shopid,shop1_total shop_num, get_rate FROM zy_prize WHERE type1=? AND type2=? AND get_rate>? LIMIT 100";
            $list = $this->db->query($get_sql, array($type,$type2,0))->result_array();
        }

        $temp = 1;
        $temp_arr = array();
        foreach ($list as $key => $value) {
            $temp_arr[$key]['rate_start'] = $temp;
            $temp = $temp + $value['get_rate'];
            $temp_arr[$key]['rate_end'] = $temp - 1;
        }

        $number = rand(1, $temp - 1);
        foreach ($temp_arr as $key => $value) {
            if ($number >= $value['rate_start'] && $number <= $value['rate_end']) {
                $rand_key = $key;
                break;
            }
        }

        $list[$rand_key]['index'] = $rand_key;

        return $list[$rand_key];
    }

    //查询是否是抽奖黑名单里的人，是则不允许中有高价值的东西（抵扣券，京东卡等等）
    function is_prize_black($uid){
        $get_sql = "SELECT count(*) as num  FROM zy_prize_black WHERE uid=? AND status=1";
        $row = $this->db->query($get_sql, array($uid))->row_array();
        if($row['num']){
            return false; //是黑名单中的人，直接返回false
        }else{
            return true; //是黑名单中的人，直接返回false
        }
    }



}
