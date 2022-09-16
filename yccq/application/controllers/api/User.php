<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 用户的信息
include_once 'Base.php';

class User extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/user_model');
    }
    
    // 详情
    public function detail()
    {
        $value = $this->user_model->detail($this->uid);
        unset($value['id'], $value['status']);
        
        t_json($value);
    }

    // 更新
    public function update()
    {
        $nickname = $this->input->post('nickname');
        $truename = $this->input->post('truename');
        $tel = $this->input->post('tel');
        $address = $this->input->post('address');

        if(!$nickname) t_error(1, '昵称不能为空');
        $data['nickname'] = $nickname;

        if($truename) $data['truename'] = $truename;
        if($tel) $data['tel'] = $tel;
        if($address) $data['address'] = $address;

        $result = $this->user_model->update($data, ['uid' => $this->uid]);
        if (!$result) t_error(1, '更新失败，请稍后再试');

        t_json();
    }

    // 土地建筑状态 所有土地，烘烤室，醇化室，加工厂, 培育中心， 配方研究所
    public function house_status()
    {
        $data = [
            'land' => [],
            'other' => [],
        ];

        $this->load->model('api/land_model');
        $data['land'] = $this->land_model->lists_status($this->uid);

        $this->load->model('api/peiyu_model');
        $data['peiyu'] = $this->peiyu_model->status($this->uid);

        $this->load->model('api/peifang_model');
        $data['peifang'] = $this->peifang_model->status($this->uid);

        $this->load->model('api/event_land_model');
        $data['event_land'] = $this->event_land_model->status($this->uid);

        //建筑升级
        $this->load->model('api/building_model');
        $this->building_model->init_data($this->uid);
        $data['building'] = $this->building_model->status($this->uid);
//        //烘烤室
//        $this->load->model('api/yanye_model');
//        $data['bake'] = $this->yanye_model->bake_status($this->uid);
//        //醇化室
//        $data['aging'] = $this->yanye_model->aging_status($this->uid);
//        //加工厂
//        $this->load->model('api/process_model');
//        $data['process'] = $this->process_model->status($this->uid);

        $value = model('status_model')->getLv($this->uid);
        $data['other'] = $value;
        $this->add_process($this->uid);
        t_json($data);
    }

    //获取总乐豆数量和今日已使用乐豆数量
    public function ledou_list(){
        $result = $this->user_model->ledou_list($this->uid);
        t_json($result);
    }

    //购买闪电
    public function buy_shandian(){
        //$number = $this->input->post('number');
        //if(!$number) t_error(1, '兑换数量须大于0');
        $number = intval($this->input->post('number'));
        if($number<0) t_error(1, '兑换数量须大于0');
        $sale_arr = $this->user_model->is_sale_money_shandian();
        if($sale_arr['is_sale']){
            if($number!=$sale_arr['sale_num']*100 && $number!=$sale_arr['sale_num']*300 && $number!=$sale_arr['sale_num']*680 && $number!=$sale_arr['sale_num']*1280 && $number!=$sale_arr['sale_num']*3000){
                $number = $sale_arr['sale_num']*100;
            }
        }else{
            if($number!=100 && $number!=300 && $number!=680 && $number!=1280 && $number!=3000){
                $number = 100;
            }
        }

        $result = $this->user_model->buy_shandian($this->uid,abs($number));
        t_json($result);
    }
    
    //乐豆转换银元
    public function ledou_to_money(){
        //$number = $this->input->post('number');
        //var_dump($number);exit;
        //if(!$number) t_error(1, '兑换数量须大于0');
        $number = intval($this->input->post('number'));
        if($number<0) t_error(1, '兑换数量须大于0');
        $sale_arr = $this->user_model->is_sale_money_shandian();
        if($sale_arr['is_sale']){
            if($number!=$sale_arr['sale_num']*100 && $number!=$sale_arr['sale_num']*300 && $number!=$sale_arr['sale_num']*680 && $number!=$sale_arr['sale_num']*1280 && $number!=$sale_arr['sale_num']*3000){
                $number = $sale_arr['sale_num']*100;
            }
        }else{
            if($number!=100 && $number!=300 && $number!=680 && $number!=1280 && $number!=3000){
                $number = 100;
            }
        }
        $result = $this->user_model->ledou_to_money($this->uid,abs($number));
        t_json($result);
    }

    // 更新角色
    public function update_role()
    {
        $role = intval($this->input->post('role'));
        if(!$role) t_error(1, '不能为空');
        $data['role'] = $role;

        $result = $this->user_model->update($data, ['uid' => $this->uid]);
        if (!$result) t_error(1, '更新失败，请稍后再试');

        t_json();
    }

    //查询游戏升级后，是否领取相应的奖励
    public function queryGameLvPrize(){
        $game_lv = $this->input->post('game_lv');
        $result = $this->user_model->queryGameLvPrize($this->uid,$game_lv);

        t_json($result);
    }


    //新手礼包
    public function newer_gift(){
        $result = $this->user_model->newer_gift($this->uid);

        t_json($result);
    }

    public function xp(){
        $xp = $this->input->post('xp');
        $result = $this->user_model->xp($this->uid,$xp);

        t_json($result);
    }

    //判断银元、闪电是否打折，打几折
    public function is_sale_money_shandian(){
        $result = $this->user_model->is_sale_money_shandian();
        t_json($result);
    }

    //判断商行是否打折，打几折
    public function is_sale_shop(){
        $result = $this->user_model->is_sale_shop();
        t_json($result);
    }

    //活动列表
    public function rank_index_list(){
        $result = $this->user_model->rank_index_list();
        t_json($result);
    }



    public function getYD(){
        //$openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        //$openid = 'ocoMKt4wvPEJ05tMacR7V646eGS8';
        $this->load->model('api/Ld_model');
        $openid = $this->input->post('openid');
        $result = $this->Ld_model->getYD($openid);
        print_r($result);
    }

    public function rechargeDY(){
        $this->load->model('api/Ld_model');
        $smokeBeans = $this->input->post('number');
        $openid = $this->input->post('openid');
        //$openid = 'oREekjrPFnTPQXOGA5GgYoH-_mEQ';
        $desc = '烟草传奇补偿';
        $result = $this->Ld_model->rechargeDY($smokeBeans, $openid, $desc);
        print_r($result);
    }

    public function consumeYD(){
        $this->load->model('api/Ld_model');
        $smokeBeans = -1;
        $openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        $desc = '烟草传奇公测消耗';
        $result = $this->Ld_model->consumeYD($smokeBeans, $openid, $desc);
        print_r($result);
    }

    public function getHeaderFrameList()
    {
        $result = $this->user_model->getHeaderFrameList($this->uid);
        t_json($result);
    }

    public function setHeaderFrame()
    {
        $frameID = intval($this->input->post('frameid'));
//        if($frameID >= 0 && $frameID <= 19)
//        {
            $result = $this->user_model->setHeaderFrame($this->uid,$frameID);
            t_json($result);
//        }else{
//            t_error(1, '参数范围错误');
//        }
    }

    //查询是否领取节日奖励
    public function queryHolidayGift(){
        $result = $this->user_model->queryHolidayGift($this->uid);
        t_json($result);
    }

    //领取节日奖励
    public function getHolidayGift(){
        $result = $this->user_model->getHolidayGift($this->uid);
        t_json($result);
    }

    //补救被覆盖的工厂数据zy_process
    function ccc(){
        $result = $this->db->query("SELECT DISTINCT t.uid FROM (SELECT A.uid FROM zy_process_record A LEFT JOIN zy_process B ON A.uid=B.uid WHERE B.id IS NULL) t")->result_array();
        foreach($result as $key=>$value){
            $res = $this->db->query("SELECT * FROM zy_process_record WHERE uid='$value[uid]' AND UNIX_TIMESTAMP(start_time)>1578844800 ORDER by id desc limit 0,1")->row_array();
            if(!empty($res)){
                $this->db->insert('zy_process', [
                    'uid' => $res['uid'],
                    'process_index' => 0,
                    'before_shopid' => $res['process_shopid'],
                    'after_shopid' => $res['process_shopid']+100,
                    'status' => 1,
                    'start_time' => $res['start_time'],
                    'stop_time' => $res['stop_time'],
                    'add_time' => t_time()]);

                for($i = 1; $i < 3; $i++){
                    $this->db->insert('zy_process', [
                        'uid' => $res['uid'],
                        'process_index' => $i,
                        'add_time' => t_time()]);
                }
            }else{
                for($i = 0; $i < 3; $i++){
                    $this->db->insert('zy_process', [
                        'uid' => $value['uid'],
                        'process_index' => $i,
                        'add_time' => t_time()]);
                }
            }
        }
    }

    //查询是否初始化了工厂数据，没有则在此添加（因为工厂数据出问题，所以才写此补救代码）
    function ddd(){
        $user['uid'] = '364136316e72447611f195016ecaf595';
        for($i = 0; $i < 3; $i++){
            $process_count = $this->db->query("select count(*) as num from zy_process where uid='$user[uid]' AND process_index=$i")->row_array();
            echo '<pre>';
            print_r($process_count);
            echo '</pre>';
            if(!$process_count['num']){
                //初始化加工厂
                $this->db->insert('zy_process', [
                    'uid' => $user['uid'],
                    'process_index' => $i,
                    'add_time' => t_time()]);
                echo $i;
            }
        }
    }
    //查询是否工厂数据过多，多则删除（因为上次补救代码有问题，所以写此补救代码）
    function eee(){
        $process_count = $this->db->query("SELECT * FROM zy_process GROUP BY uid,process_index HAVING COUNT(*) >1")->result_array();
        foreach($process_count as $key=>$value){
            //统计有多少个编号为0的工厂
            $res = $process_count = $this->db->query("select * from zy_process where uid='$value[uid]' AND process_index=0")->result_array();
            if(count($res)>1){
                for($i=0;$i<count($res)-1;$i++){
                    $this->db->where('id', $res[$i]['id']);
                    $this->db->delete('zy_process');
                }
            }
        }
    }

    //补缺个人工厂数据zy_process
    function add_process($uid){

        $process_count = $this->db->query("select count(*) num from zy_process where uid='$uid' ")->row_array();

        if(empty($process_count['num']))
        {
            for($i = 0; $i < 3; $i++)
            {
                $row  = $this->db->query("select uid from zy_process WHERE uid='$uid' and process_index='$i' ")->row_array();
                if(!$row)
                {
                    $this->db->insert('zy_process', [
                        'uid' => $uid,
                        'process_index' => $i,
                        'add_time' => t_time()]);
                }

            }
        }

    }


}