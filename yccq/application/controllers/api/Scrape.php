<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 元旦刮奖控制器
 */
include_once 'Base.php';

class Scrape extends Base
{
    function __construct()
    {
        //元旦活动
        $this->newYearActiv  = 'newYear';
        //9月粉丝节活动
        $this->septActiv = 'sept';
        parent::__construct();
        $this->load->model('api/Scrape_model');
    }

    //保存地址
    public function savemessage(){
        $post_data = $this->input->post();
        $uid = $post_data['uid']?trim($post_data['uid']):'';
        $id = $post_data['id']?check_id($post_data['id']):'';
        $truename = $post_data['truename']?trim($post_data['truename']):'';
        $phone = $post_data['phone']?trim($post_data['phone']):'';
        $province = $post_data['province']?trim($post_data['province']):'';
        $city = $post_data['city']?trim($post_data['city']):'';
        $area = $post_data['area']?trim($post_data['area']):'';
        $street = $post_data['street']?trim($post_data['street']):'';
        $save_status = $post_data['save_status']?check_id($post_data['save_status']):3;
        $activ = $post_data['activ']?trim($post_data['activ']):'none';//所属活动

        $address = array($province,$city,$area,$street);
        $address = implode(',', $address);

        $data = array(
            'truename' => $truename,
            'phone' => $phone,
            'address' => $address,
            'update_time' => time(),
        );
        if ($save_status == 1) {//保存
            $data['status'] = 1;
            $data['address_time'] = time();
        }

        if($this->Scrape_model->table_update('zy_scrape_message',$data,array('id'=>$id,'uid'=>$uid,'attri_activ'=>$activ))){
            t_json(null,0,'成功');
        }else{
            t_json(null,1,'失败');
        }
    }

    //领取元旦刮奖奖励
    public function setAwardReceive(){
        $uid = $this->input->post('uid');
        $data = array();
        try
        {
            $user_info = $this->Scrape_model->get_row("zy_user",'openid,money,shandian',array('uid'=>$uid));
            if (!$user_info) {
                $code = 101;
                $msg = '用户不存在！';
                throw new Exception($msg);
            }

            //是否进行刮奖
            $is_join = $this->Scrape_model->get_row("zy_scrape_record",'id,status',array('uid'=>$uid,'attri_activ'=>$this->newYearActiv));
            //是否领奖
            if (!$is_join) {
                $code = 102;
                $msg = '未参与刮奖，不能领取奖励！';
                throw new Exception($msg);
            }
            if ($is_join['status'] == 1) {
                $code = 103;
                $msg = '只能领取一次奖励！';
                throw new Exception($msg);
            }

            //获取奖励内容
            $award_info = $this->getScrapeAward($uid);
            if (!$award_info['super_shop'] &&  !$award_info['general_shop']) {
                $code = 104;
                $msg = '获取奖励内容失败';
                throw new Exception($msg);
            }

            //以下开始发放奖励
            $openid = $user_info['openid'];

            $money = $award_info['money'];
            $shandian = $award_info['shandian'];
            $super_shop_arr = $award_info['super_shop']?$award_info['super_shop']:array();
            $general_shop_arr = $award_info['general_shop']?$award_info['general_shop']:array();
            
            //发放银元，闪电
            $i_money = $user_info['money']+$money;
            $i_shandian = $user_info['shandian']+$shandian;
            $data = array(
                    'money'=>$i_money,
                    'shandian'=>$i_shandian,
            );
            if (!$this->Scrape_model->table_update("zy_user",$data,array('uid'=>$uid))) {
                $code = 105;
                $msg = '银元闪电发放失败！';
                throw new Exception($msg);
            }

            //log用
            $shop_arr = array();
            //发放种子，调香书
            foreach ($general_shop_arr as $key => $val) {

                //zy_store是否有记录
                $where = 'uid = "'.$uid.'" and shopid = '.$val['shop_id'];
                $store_info = $this->Scrape_model->get_row("zy_store",'id,total',$where);
                $shop_info = $this->Scrape_model->get_row("zy_shop",'type1,type2',array('shopid'=>$val['shop_id']));
                if ($store_info) {
                    $total = $store_info['total'] + $val['shop_num'];
                    $data = array(
                            'total'=>$total,
                            'update_time'=>t_time()
                    );

                    if (!$this->Scrape_model->table_update("zy_store",$data,$where)) {
                        $code = 106;
                        $msg = '游戏道具发放失败！';
                        throw new Exception($msg);
                    }
                }
                else{
                    $data = array(
                        'uid' => $uid,
                        'shopid' => $val['shop_id'],
                        'type1' => $shop_info['type1'],
                        'type2' => $shop_info['type2'],
                        'total' => $val['shop_num'],
                        'add_time' => t_time()
                    );
                    if (!$this->db->insert('zy_store', $data)) {
                        $code = 107;
                        $msg = '游戏道具发放失败！';
                        throw new Exception($msg);
                    }
                }

                $shop_arr[]= array(
                        'shop_id'=>$val['shop_id'],
                        'shop_num'=>'+'.$val['shop_num'],
                        'shop_type'=>$shop_info['type1']
                );
                
            }
            
            //发放品吸券，实物
            foreach ($super_shop_arr as $key => $val) {
                //查询商品是实物还是券
                $shop_info = $this->Scrape_model->get_row("zy_shop",'type1,name',array('shopid'=>$val['shop_id']));
                if ($shop_info['type1'] == 'daoju') {
                    //品吸券
                    $data = array(
                            'shopid'=>$val['shop_id'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                    );
                    if (!$this->Scrape_model->table_insert("zy_ticket_record",$data)) {
                        $code = 108;
                        $msg = '品吸券发放失败！';
                        throw new Exception($msg);
                    }
                }
                elseif ($shop_info['type1'] == 'prize') {
                    //实物
                    $data = array(
                            'shop_id'=>$val['shop_id'],
                            'shop_name' => $shop_info['name'],
                            'uid' => $uid,
                            'add_time' => time(),
                            'attri_activ'=>$this->newYearActiv
                    );
                    if (!$this->Scrape_model->table_insert("zy_scrape_message",$data)) {
                        $code = 109;
                        $msg = '实物发放失败！';
                        throw new Exception($msg);
                    }
                }

                $shop_arr[] = array(
                        'shop_id'=>$val['shop_id'],
                        'shop_num'=>'+'.$val['shop_num'],
                        'shop_type'=>$shop_info['type1']
                );
            }

            $code = 0;
            $msg = 'OK';
            $data = array();

            $up_data = array(
                        'status'=>1,
                        'award_id'=>$award_info['id'],
                        'update_time'=>time()
            );
            //修改领奖记录表状态
            $this->Scrape_model->table_update("zy_scrape_record",$up_data,array('uid'=>$uid,'attri_activ'=>$this->newYearActiv));

            //刮奖奖励实发记录
            $shop = json_encode($shop_arr,JSON_UNESCAPED_UNICODE);
            $log_data = array(
                        'uid'=>$uid,
                        'money'=>'+'.$money,
                        'shandian'=>'+'.$shandian,
                        'shop'=>$shop,
                        'add_time'=>t_time(),
                        'attri_activ'=>$this->newYearActiv
            );
            $this->Scrape_model->table_insert("zy_scrape_award_log",$log_data);

        }
        catch (Exception $e)
        {
            $msg = $e->getMessage();
        }

        t_json($data,$code,$msg);
    }

    // 获取元旦刮奖状态
    public function getScrapeStatus()
    {
        $uid = $this->input->post('uid');
        $data = array();
        try
        {
            $start_time = strtotime(date( "2020-01-14"));
            $end_time = strtotime(date( "2020-02-10"));
            $now_time = time();
            if ($now_time < $start_time) {
                $code = 101;
                $msg = '活动未开始!';
                throw new Exception($msg);
            }
            if ($now_time > $end_time) {
                $code = 102;
                $msg = '活动已结束!';
                throw new Exception($msg);
            }

            if (empty($uid)) {
                $code = 103;
                $msg = '用户id为空!';
                throw new Exception($msg);
            }

            if (!$this->Scrape_model->get_row("zy_user",'id',array('uid'=>$uid))) {
                $code = 104;
                $msg = '该用户不存在!';
                throw new Exception($msg);
            }

            //是否已经刮奖
            $is_join = $this->Scrape_model->get_row("zy_scrape_record",'status',array('uid'=>$uid,'attri_activ'=>$this->newYearActiv));
            if (empty($is_join)) {
                $now_time = time();
                $insert_data = array('uid'=>$uid,'add_time'=>$now_time,'attri_activ'=>$this->newYearActiv);
                $res = $this->Scrape_model->table_insert("zy_scrape_record",$insert_data);
                if (!$res){
                    $code = 105;
                    $msg = '刮奖初始记录添加失败!';
                    throw new Exception($msg);
                }
                $code = 0;
                $msg = 'OK';
                $is_top = 1;
                //根据uid获取用户刮奖奖励
                $award_info = $this->getScrapeAward($uid);
            }
            else{
                if ($is_join['status'] == 1) {
                    //已刮奖
                    $code = 0;
                    $msg = '已刮奖';
                    $is_top = 0;
                    $award_info = array();
                }
                else{
                    //未刮奖
                    $code = 0;
                    $msg = 'OK';
                    $is_top = 1;
                    //根据uid获取用户刮奖奖励
                    $award_info = $this->getScrapeAward($uid);
                }
            }
            $data = $award_info;
            $data['is_top'] = $is_top;
        }
        catch (Exception $e)
        {
            $msg = $e->getMessage();
        }
        t_json($data,$code,$msg);
    }

    //获取刮奖奖励//写死条件：奖励排名1399，排除rank为-1的用户，-1只做是否参与排名标识
    public function getScrapeAward($uid){
        $where = '1';//没测试过，防止339行出错
        //游戏等级
        $level_info = $this->Scrape_model->get_row("zy_user",'game_lv',array('uid'=>$uid));

        //幸运抽奖排名,排除排名==-1
        $rank_info = $this->Scrape_model->get_row("zy_lucky_lottery_rank",'rank',array('uid'=>$uid,'rank !='=>-1));

        //超过阶段排名奖励，按是否满级条件查询奖励
        if ($rank_info) {
            if ($rank_info['rank']>=1399 && $level_info['game_lv'] == 50) {
                $where = 'rank_start = 1399 and level = 50';
            }
            elseif ($rank_info['rank']>=1399 && $level_info['game_lv'] < 50) {
                $where = 'rank_start = 1399 and level = 0';
            }
            else{
                $where = 'rank_start <= '.$rank_info['rank'].' and rank_end >= '.$rank_info['rank'];
            }
        }
        else{
            if ($level_info['game_lv'] == 50) {
                $where = 'rank_start = 1399 and level = 50';
            }
            else{
                $where = 'rank_start = 1399 and level = 0';
            }
        }
        $where .= ' and attri_activ = newYear ';

        //刮奖奖励
        $award_info = $this->Scrape_model->get_row("zy_scrape_award_config",'id,money,shandian,super_shop,general_shop',$where);
        $super_shop = json_decode($award_info['super_shop'],JSON_UNESCAPED_UNICODE);
        $general_shop = json_decode($award_info['general_shop'],JSON_UNESCAPED_UNICODE);
        if ($super_shop) {
            foreach ($super_shop as $key => $val) {
                $shop_name = $this->Scrape_model->get_row("zy_shop",'name',array('shopid'=>$val['shop_id']));
                $super_shop[$key]['shop_name'] = $shop_name['name'];
            }
        }
        if ($general_shop) {
            foreach ($general_shop as $key => $val) {
                $shop_name = $this->Scrape_model->get_row("zy_shop",'name',array('shopid'=>$val['shop_id']));
                $general_shop[$key]['shop_name'] = $shop_name['name'];
            }
        }

        $award_info['super_shop'] = $super_shop?$super_shop:array();
        $award_info['general_shop'] = $general_shop?$general_shop:array();
        return $award_info;
    }




    //9月活动-获取刮奖状态
    public function getSeptScrapeStatus()
    {
        $uid = $this->input->post('uid');
        $config_id = '';
        $data = array();
        try
        {
            //正式活动时间
            // $start_time = strtotime(date( "2020-09-23 00:00:00"));
            // $end_time = strtotime(date( "2020-09-24 00:00:00"));
            //测试活动时间
            $start_time = strtotime(date( "2020-08-26 00:00:00"));
            $end_time = strtotime(date( "2020-09-24 00:00:00"));
            $now_time = time();
            
            if ($now_time < $start_time) {
                $code = 101;
                $msg = '活动未开始!';
                throw new Exception($msg);
            }
            if ($now_time > $end_time) {
                $code = 102;
                $msg = '活动已结束!';
                throw new Exception($msg);
            }

            if (empty($uid)) {
                $code = 103;
                $msg = '用户id为空!';
                throw new Exception($msg);
            }

            if (!$this->Scrape_model->get_row("zy_user",'id',array('uid'=>$uid))) {
                $code = 104;
                $msg = '该用户不存在!';
                throw new Exception($msg);
            }

            //是否已经刮奖
            $is_join = $this->Scrape_model->get_row("zy_scrape_record",'status',array('uid'=>$uid,'attri_activ'=>$this->septActiv));
            if (empty($is_join)) {
                $now_time = time();
                $insert_data = array('uid'=>$uid,'add_time'=>$now_time,'attri_activ'=>$this->septActiv);
                $res = $this->Scrape_model->table_insert("zy_scrape_record",$insert_data);
                if (!$res){
                    $code = 105;
                    $msg = '刮奖初始记录添加失败!';
                    throw new Exception($msg);
                }
                $code = 0;
                $msg = 'OK';
                $is_top = 1;//显示刮奖条
                //根据uid获取用户刮奖奖励
                $award_info = $this->getSeptScrapeAward($config_id);
                //以这次获取的奖励为准，先存入数据库
                $this->Scrape_model->table_update("zy_scrape_record",array('award_id'=>$award_info['id']),array('uid'=>$uid,'attri_activ'=>$this->septActiv));
            }
            else{
                if ($is_join['status'] == 1) {
                    //已刮奖
                    $code = 0;
                    $msg = '已刮奖';
                    $is_top = 0;
                    $award_info = array();
                }
                else{
                    //未刮奖
                    $code = 0;
                    $msg = 'OK';
                    $is_top = 1;
                    //根据uid获取用户刮奖奖励
                    $award_info = $this->getSeptScrapeAward($config_id);
                    //以这次获取的奖励为准，先存入数据库
                    $this->Scrape_model->table_update("zy_scrape_record",array('award_id'=>$award_info['id']),array('uid'=>$uid,'attri_activ'=>$this->septActiv));
                }
            }
            

            $data = $award_info;
            $data['is_top'] = $is_top;
        }
        catch (Exception $e)
        {
            $msg = $e->getMessage();
        }
        t_json($data,$code,$msg);
    }

    //9月活动-获取刮奖奖励
    public function getSeptScrapeAward($config_id=''){
        if ($config_id == '') {
            //按照三个礼包概率，随机抽取其中一个礼包
            //获取礼包id,name,概率
            $pr_arr = $this->Scrape_model->table_lists("zy_scrape_award_config",'id,name,pr',array('attri_activ'=>'sept'),'id asc');

            $rand_result = $this->get_rand($pr_arr);
            $config_id = $rand_result['id'];
        }

        //刮奖奖励
        $award_info = $this->Scrape_model->get_row("zy_scrape_award_config",'id,money,shandian,general_shop,name',array('id'=>$config_id));
        if ($award_info) {
            $general_shop = json_decode($award_info['general_shop'],JSON_UNESCAPED_UNICODE);
            if ($general_shop) {
                foreach ($general_shop as $key => $val) {
                    $shop_name = $this->Scrape_model->get_row("zy_shop",'name',array('shopid'=>$val['shop_id']));
                    $general_shop[$key]['shop_name'] = $shop_name['name'];
                }
            }
            $award_info['general_shop'] = $general_shop?:array();
        }
        else{
            $award_info = array();
        }
        
        return $award_info;
    }

    //9月活动-领取9月刮奖奖励
    public function setSeptAwardReceive(){
        $uid = $this->input->post('uid');
        $data = array();
        try
        {
            $user_info = $this->Scrape_model->get_row("zy_user",'openid,money,shandian',array('uid'=>$uid));
            if (!$user_info) {
                $code = 101;
                $msg = '用户不存在！';
                throw new Exception($msg);
            }

            //是否进行刮奖
            $is_join = $this->Scrape_model->get_row("zy_scrape_record",'id,status,award_id',array('uid'=>$uid,'attri_activ'=>$this->septActiv));
            
            //是否领奖
            if (!$is_join) {
                $code = 102;
                $msg = '未参与刮奖，不能领取奖励！';
                throw new Exception($msg);
            }

            if ($is_join['status'] == 1) {
                $code = 103;
                $msg = '只能领取一次奖励！';
                throw new Exception($msg);
            }

            //获取奖励内容
            $config_id = $is_join['award_id'];
            $award_info = $this->getSeptScrapeAward($config_id);

            if (!$award_info['super_shop'] &&  !$award_info['general_shop']) {
                $code = 104;
                $msg = '获取奖励内容失败';
                throw new Exception($msg);
            }

            //以下开始发放奖励
            $openid = $user_info['openid'];

            $money = $award_info['money'];
            $shandian = $award_info['shandian'];
            $super_shop_arr = $award_info['super_shop']?$award_info['super_shop']:array();
            $general_shop_arr = $award_info['general_shop']?$award_info['general_shop']:array();
            
            //发放银元，闪电
            $i_money = $user_info['money']+$money;
            $i_shandian = $user_info['shandian']+$shandian;
            
            $data = array(
                    'money'=>$i_money,
                    'shandian'=>$i_shandian,
            );

            if (!$this->Scrape_model->table_update("zy_user",$data,array('uid'=>$uid))) {
                $code = 105;
                $msg = '银元闪电发放失败！';
                throw new Exception($msg);
            }

            //log用
            $shop_arr = array();
            //发放种子，调香书
            foreach ($general_shop_arr as $key => $val) {

                //zy_store是否有记录
                $where = 'uid = "'.$uid.'" and shopid = '.$val['shop_id'];
                //材料库存记录
                $store_info = $this->Scrape_model->get_row("zy_store",'id,total',$where);
                //建筑材料库存记录
                $bd_store_info = $this->Scrape_model->get_row("zy_building_store",'id,total',$where);
                //材料详细信息
                $shop_info = $this->Scrape_model->get_row("zy_shop",'name,type1,type2',array('shopid'=>$val['shop_id']));

                //建筑材料存储至zy_building_store表
                if ($shop_info['name'] == '瓦石' ||  $shop_info['name'] == '木材' || $shop_info['name'] == '油漆') {

                    if ($bd_store_info) {
                        $total = $bd_store_info['total'] + $val['shop_num'];
                        $data = array(
                                'total'=>$total,
                                'updatetime'=>t_time()
                        );

                        if (!$this->Scrape_model->table_update("zy_building_store",$data,$where)) {
                            $code = 106;
                            $msg = '建筑材料发放失败！';
                            throw new Exception($msg);
                        }
                        
                    }
                    else{
                        $data = array(
                            'uid' => $uid,
                            'shopid' => $val['shop_id'],
                            'total' => $val['shop_num'],
                            'addtime' => t_time()
                        );
                        if (!$this->db->insert('zy_building_store', $data)) {
                            $code = 107;
                            $msg = '建筑材料发放失败！';
                            throw new Exception($msg);
                        }
                    }
                }
                else{
                    if ($store_info) {
                        $total = $store_info['total'] + $val['shop_num'];
                        $data = array(
                                'total'=>$total,
                                'update_time'=>t_time()
                        );

                        if (!$this->Scrape_model->table_update("zy_store",$data,$where)) {
                            $code = 106;
                            $msg = '游戏道具发放失败！';
                            throw new Exception($msg);
                        }
                    }
                    else{
                        $data = array(
                            'uid' => $uid,
                            'shopid' => $val['shop_id'],
                            'type1' => $shop_info['type1'],
                            'type2' => $shop_info['type2'],
                            'total' => $val['shop_num'],
                            'add_time' => t_time()
                        );
                        if (!$this->db->insert('zy_store', $data)) {
                            $code = 107;
                            $msg = '游戏道具发放失败！';
                            throw new Exception($msg);
                        }
                    }
                }
                

                $shop_arr[]= array(
                        'shop_id'=>$val['shop_id'],
                        'shop_num'=>'+'.$val['shop_num'],
                        'shop_type'=>$shop_info['type1']
                );
                
            }
            
            //发放品吸券，实物
            foreach ($super_shop_arr as $key => $val) {
                //查询商品是实物还是券
                $shop_info = $this->Scrape_model->get_row("zy_shop",'type1,name',array('shopid'=>$val['shop_id']));
                if ($shop_info['type1'] == 'daoju') {
                    //品吸券
                    $data = array(
                            'shopid'=>$val['shop_id'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                    );
                    if (!$this->Scrape_model->table_insert("zy_ticket_record",$data)) {
                        $code = 108;
                        $msg = '品吸券发放失败！';
                        throw new Exception($msg);
                    }
                }
                elseif ($shop_info['type1'] == 'prize') {
                    //实物
                    $data = array(
                            'shop_id'=>$val['shop_id'],
                            'shop_name' => $shop_info['name'],
                            'uid' => $uid,
                            'add_time' => time(),
                            'attri_activ'=>$this->septActiv
                    );
                    if (!$this->Scrape_model->table_insert("zy_scrape_message",$data)) {
                        $code = 109;
                        $msg = '实物发放失败！';
                        throw new Exception($msg);
                    }
                }

                $shop_arr[] = array(
                        'shop_id'=>$val['shop_id'],
                        'shop_num'=>'+'.$val['shop_num'],
                        'shop_type'=>$shop_info['type1']
                );
            }

            $code = 0;
            $msg = 'OK';
            $data = array();

            $up_data = array(
                        'status'=>1,
                        'award_id'=>$award_info['id'],
                        'update_time'=>time()
            );
            //修改领奖记录表状态
            $this->Scrape_model->table_update("zy_scrape_record",$up_data,array('uid'=>$uid,'attri_activ'=>$this->septActiv));

            //刮奖奖励实发记录
            $shop = json_encode($shop_arr,JSON_UNESCAPED_UNICODE);
            $log_data = array(
                        'uid'=>$uid,
                        'money'=>'+'.$money,
                        'shandian'=>'+'.$shandian,
                        'shop'=>$shop,
                        'add_time'=>t_time(),
                        'attri_activ'=>$this->septActiv,
            );
            $this->Scrape_model->table_insert("zy_scrape_award_log",$log_data);

        }
        catch (Exception $e)
        {
            $msg = $e->getMessage();
        }

        t_json($data,$code,$msg);
    }

    //按概率随机分配
    function get_rand($proArr) {   
        // $proArr = array(   
        //     //v 表示中奖概率
        //     array('id'=>1,'name'=>'游戏道具大礼包','v'=>10),
        //     array('id'=>2,'name'=>'游戏道具礼包','v'=>25),
        //     array('id'=>3,'name'=>'道具礼包','v'=>65)
        // );  
        $num = count($proArr);
        for($i = 0; $i < $num; $i++) { 
            $arr[$i] = $i == 0 ? $proArr[$i]['pr'] : $proArr[$i]['pr'] + $arr[$i-1]; 
        } 
        //var_dump($arr); //$arr  对等各等级奖及之前的中奖概率之和
         
        $proSum = $arr[$num-1] * 100; //为更公平，扩大一下范围       
        $randNum = mt_rand(1, $proSum) % $arr[$num-1] + 1;  //$randNum 一定不大于 $arr[$num-1] 抽奖仅需一次即可
        // 概率数组循环   
        foreach ($arr as $k => $v) {   
              
            if ($randNum <= $v) {   
                $result = $proArr[$k];   
                break;   
            }        
        }   
        return $result;   
    }


}
