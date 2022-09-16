<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * 碎片管理
 */

include_once 'Base_model.php';

class Fragment_model extends Base_model
{

    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_fragment';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');

    }

//    function dayPutnum(){
//
//        $type = 1;
//        $row = $this->db->query("select * from zy_fragment_set")->row_array();
//        if($row["number_{$type}"] < 0){
//            return $index = 0;
//        }
//
//    }

    //碎片 获取
    function get_fragment($uid,$type)
    {
        $rand = rand(0, 99);
        $this->db->trans_start();

        $name = config_item('fragment_rate');
        $num = config_item('suipian_type');
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();


        //更新每天获得最大碎片数量
        $today = date("Y-m-d");
        if ($row['update_time'] < $today) {
            $this->update(['max_num'=>0],['uid'=>$uid]);
        }
        if ($rand < $name[$type]['rate']) {
//                if($row['max_num']<4){
            $arr = [1,2,3,4,5,6]; //对应碎片A B C D E F
            $rand_key = array_rand($arr,1);
            $type_num = $arr[$rand_key];

            $this->fragment_total($uid,$type_num,$name[$type]['name']);

            $result = $num[$type_num];
//                }else{
//                    $result =0;
//                }
        }else{
            $result =0;
        }
        $this->db->trans_complete();

        return $result;
    }


    //添加 更新
    function fragment_total($uid, $rand_num,$type)
    {
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        if($row){
            $this->db->set('number_'.$rand_num, 'number_'.$rand_num.'+1', FALSE);
            if($type == '新手礼包'){
                $this->db->set('max_num', 'max_num+0', FALSE);
            }else{
                $this->db->set('max_num', 'max_num+1', FALSE);
            }
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
        }else{
            $this->insert([
                'uid' => $uid,
                'number_'.$rand_num => 1,
                'max_num' => 1,
                'add_time' => t_time(),
                'update_time' => t_time()
            ]);
        }

        $this->insert_record($uid,$rand_num,$type);

    }


    //指定表名添加一条记录
    function insert_record($uid,$rand_num,$type){
        $data = [
            'uid' => $uid,
            'type' => $rand_num,
            'resource' => $type,
            'add_time' => t_time()
        ];
        $this->table_insert('zy_fragment_record',$data);
    }


    //查询拥有碎片
    function fragment_num($uid){
        $row = $this->column_sql('number_1,number_2,number_3,number_4,number_5,number_6',['uid'=>$uid],$this->table,0);

        $result['list'] = array();
        $i=1;
        foreach($row as $k=>$v){
            $result['list'][$i] = $v;
            $i++;
        }

        return $result;
    }

    //查询合成剩余次数
    function queryKeynum($uid){
        $row = $this->column_sql('key_num num',['uid'=>$uid],$this->table,0);
        return $row;

    }
    // 新手扫码礼包
    function newer_scan($uid){
        //判断是否已经领取过礼包
        //  闪电*300  银元*80000
        //  随机碎片*1 三星改良调香书*3 三星津巴布韦种子*8
        $query = $this->db->query("select is_newer_scan from zy_user WHERE uid=?",[$uid])->row_array();
        if($query['is_newer_scan']==0){
            $this->db->trans_start();
            $this->db->set('money', 'money+' . 80000, FALSE);
            $this->db->set('shandian', 'shandian+' . 300, FALSE);
            $this->db->set('is_newer_scan', 1, FALSE);
            $this->db->where('uid', $uid);
            $this->db->update($this->table);

            $this->store_model->update_total(3, $uid, 622);   // 三星改良调香书 3
            $this->store_model->update_total(8, $uid, 225);   //三星津巴布韦种子 8

            $arr = [1,2,3,4,5,6]; //对应碎片A B C D E F
            $rand_key = array_rand($arr,1);
            $type = $arr[$rand_key];
            $this->saveFragment($uid,$type,1);

            $this->db->trans_complete();


            return ;
        }else{
            t_error(1, '已经领取过新手礼包');
        }

    }

    //用户扫码返回随机碎片
    function randGetsuipian($uid,$status){

        $rand = rand(0, 99);
        $this->db->trans_start();
        $name = config_item('fragment_rate');
        $num = config_item('suipian_type');
        $arr = [1,2,3,4,5,6]; //对应碎片A B C D E F
        $rand_key = array_rand($arr,1);
        $type_num = $arr[$rand_key];
        if($status == 0){
            $result['type'] = $num[$type_num];
        }elseif ($rand < $name['scan']['rate']) {
            $this->insert([
                'uid' => $uid,
                'add_time' => t_time(),
                'update_time' => t_time()
            ]);
            $result['type'] = $num[$type_num];
        }else{
            $result['type'] =0;
        }

        $result = $this->scanRecord($uid,$result['type']);
        $result['add_time'] = t_time();
        $this->db->trans_complete();
        return $result;

    }

    //更新新用户碎片
    function saveFragment($uid,$type,$num){
        $sql = "select * from $this->table WHERE uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();
        if($row){
            $this->db->set('number_'.$type, 'number_'.$type.' +1', FALSE)
                ->set('update_time',t_time())
                ->where('uid' , $uid)
                ->update($this->table);
        }else{
            $this->insert([
                'uid' => $uid,
                'number_'.$type => $num,
                'add_time' => t_time(),
                'update_time' => t_time()
            ]);
        }
    }

    //扫码记录表
    function scanRecord($uid,$type){
        $data = [
            'uid'=>$uid,
            'code'=>t_rand_str($uid),
            'type'=>$type,
            'status'=>0,
            'add_time'=>t_time(),
            'update_time'=>t_time(),
        ];
        $result['insert_id'] = $this->table_insert('zy_fragment_scan',$data);
        return $result;
    }

    //查询扫码返回的碎片
    function queryFragment($uid){
        $num = config_item('suipian_type');
        $name = config_item('fragment_rate');
        $row = $this->column_sql('*',['uid'=>$uid,'type >'=>0],'zy_fragment_scan',0);
        if($row && $row['status']==0){
            $this->table_update('zy_fragment_scan',
                ['status'=>1,'update_time'=>t_time()],
                ['uid'=>$row['uid'],'type'=>$row['type'],'code'=>$row['code']]);
            $this->insert_record($uid,$row['type'],$name['scan']['name']);
            $type['type'] = $num[$row['type']];
            return $type;
        }else{
            return 0;
        }
    }

    //合成
    function composeFragment($uid){
        $row = $this->column_sql('number_1,number_2,number_3,number_4,number_5,number_6',['uid'=>$uid],$this->table,0);
        if($row['number_1']>0 && $row['number_2']>0 && $row['number_3']>0 && $row['number_4']>0 && $row['number_5']>0 && $row['number_6']>0){
            $this->table_insert('zy_fragment_compose',[
                'uid'=>$uid,
                'add_time'=>t_time(),
                'update_time'=>t_time()
            ]);
            $this->db->set('number_1', 'number_1 -1', FALSE)
                ->set('number_2', 'number_2 -1', FALSE)
                ->set('number_3', 'number_3 -1', FALSE)
                ->set('number_4', 'number_4 -1', FALSE)
                ->set('number_5', 'number_5 -1', FALSE)
                ->set('number_6', 'number_6 -1', FALSE)
                ->set('update_time',t_time())
                ->where('uid' , $uid)
                ->update($this->table);
            //更新用户合成次数
            $this->db->set('key_num', 'key_num +1', FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
        }else{
            t_error(2,'您的碎片不足');
        }
    }

    //拼图碎片抽奖
    function prize_exchange($uid){

        $this->db->trans_start();

        //是否有足够的碎片
        $sql = "select * from zy_fragment_compose where  uid=? AND status=?";
        $row = $this->db->query($sql,[$uid,0])->row_array();
        if($row){
            $result = $this->getPrize();
            $this->table_update('zy_fragment_compose',['status'=>1,'update_time'=>t_time()],['id'=>$row['id']]);
            //更新用户合成次数
            $this->db->set('key_num', 'key_num -1', FALSE);
            $this->db->set('update_time', t_time());
            $this->db->where('uid', $uid);
            $this->db->update($this->table);
//            if($result['prize']['shopid']){
//
//                $this->store_model->update_total($result['prize']['shop1_total'],$uid,$result['prize']['shopid']);
//                $openid = $this->user_model->queryOpenidByUid($uid);   //根据uid获取openid
//                //查询商品表的详情
//                $row_shop = $this->db->query("select type4,json_data from zy_shop WHERE shopid=$result[prize][shopid]")->row_array();
//                $temp = json_decode($row_shop['json_data'], true);
//                $goods_id = $temp['goodsId'];
//                $prize_id = t_rand_str($uid);
//
//                $retrun_data = $this->ziyunExchangeGoods($openid,$goods_id,$prize_id);
//                //如果抽到的是打火机、香烟，不加入仓库，直接存入log_prize_quan表
//                if($retrun_data['status'] == 0){
//                    $data = array(
//                        'shopid' => $result['prize']['shopid'],
//                        'ticket_id' => t_rand_str($uid),
//                        'uid' => $uid,
//                        'openid' => $openid,
//                        'stat' => 0,
//                        'addtime' => time()
//                    );
//                    $this->db->insert('zy_ticket_record', $data); //物料
//                }else{
//                    //保存接口返回错误信息，方便查错
//                    $data = array(
//                        'status' => $retrun_data['status'],
//                        'message' => $retrun_data['message'],
//                        'shopid' => $result['prize']['shopid'],
//                        'goods_id' => $goods_id,
//                        'prize_id' => $prize_id,
//                        'uid' => $uid,
//                        'openid' => $openid,
//                        'stat' => 0,
//                        'addtime' => time()
//                    );
//                    $this->db->insert('zy_suipian_prize_error', $data);
//                    $result = $this->prize_shop();
//                    if($result['prize']['shopid']){
//
//                        $this->store_model->update_total($result['prize']['shop1_total'],$uid,$result['prize']['shopid']);
//                    }
//
//                }
//
//            }

            if($result['prize']['shopid']){

                $this->store_model->update_total($result['prize']['shop1_total'],$uid,$result['prize']['shopid']);
                if($result['prize']['shopid'] == 1621 ||$result['prize']['shopid']== 1622){
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    $data = array(
                        'shopid' => $result['prize']['shopid'],
                        'ticket_id' => t_rand_str($uid),
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_ticket_record', $data); //物料
                }

            }
            if($result['prize']['money']){
                $this->user_model->money($uid, $result['prize']['money'], 0);  //银元
            }
            if($result['prize']['shandian']){
                $this->user_model->shandian($uid, $result['prize']['shandian']); //闪电
            }
            $this->log_save($uid, $result['prize']['id'], 0, 0);

            $result['list'] = $this->prize_lists($result['prize']['id']);
            unset($result['prize']['id']);
        }else{
            t_error(2,'请先合成拼图！');
        }


        $this->db->trans_complete();
        return $result;

    }

    //显示剩下的奖品
    function prize_lists($index){
//     $index = 1;
        $list = $this->db->query("select money,shandian,shop1 shopid,shop1_total from zy_prize WHERE type1=14 AND id !=$index")->result_array();
        foreach($list as $key=>$value){
            $result[$key+1] = $value;
        }

        return $result;

    }
    //如果返回的奖品信息有误，则随机获取其他奖品
    function prize_shop(){
        $arr = [3,4,5,6];
        $rand_key = array_rand($arr,1);
        $result = $this->column_sql('id,money,shandian,shop1 shopid,shop1_total',['type1'=>14,'type2'=>$arr[$rand_key]],'zy_prize',0);

        return $result;
    }

    //随机获取奖品
    function getPrize(){

        $sql = "select id,get_rate from zy_prize WHERE type1=?";
        $list = $this->db->query($sql,[14])->result_array();
        foreach($list as $key=>$value){
            $proArr[$value['id']] = $value['get_rate'];
        }
        $prize_id = $this->get_rand_num($proArr);
        $sql = "select id,money,shandian,shop1 shopid,shop1_total from zy_prize WHERE id=? AND type1=?";
        $result['prize'] = $this->db->query($sql,[$prize_id,14])->row_array();
        return $result;
    }

    private function get_rand_num($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    function log_save($uid, $prize_id, $jifen = 0, $shopid = 0){

        // 奖品日志保存
        $insert_id = $this->table_insert('log_prize', [
            'uid' => $uid,
            'prize_id' => $prize_id,
            'xh_jifen' => $jifen,
            'xh_shopid' => $shopid,
            'add_time' => t_time(),
        ]);
    }
    function insert_prize($uid,$type){

        $data = [
            'uid' => $uid,
            'type' => $type,
            'add_time' => t_time()
        ];
        $this->table_insert('zy_fragment_exchange',$data);
    }



//    //物料，调用中烟商城接口，生成订单
//    public function ziyunExchangeGoods($openid,$goodsId,$prizeId){
//        $key = '0olp1hd3e89a365f92e43e220i9d9532';
//        $data['goodsId'] = $goodsId;
//        $data['prizeId'] = $prizeId;
//        $data['openId'] = $openid;
//        $data['sign'] = md5($openid.$key.$prizeId);
//
//        $url = 'http://ld.haiyunzy.com/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //测试地址
////        $url = 'http://ld.thewm.cn/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //正式生产地址
//
//        $return = $this->http($url,$data,true);
//        return $return;
//    }
//
//    //模拟POST提交
//    function http($url, $data = NULL, $json = false){
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        if (!empty($data)) {
//            if($json && is_array($data)){
//                $data = json_encode( $data ,JSON_UNESCAPED_UNICODE);
//            }
//            curl_setopt($curl, CURLOPT_POST, 1);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//            if($json){
//                curl_setopt($curl, CURLOPT_HEADER, 0);
//                curl_setopt($curl, CURLOPT_HTTPHEADER,
//                    array(
//                        'Content-type: application/json',
//                        'Content-Length:' . strlen($data))
//                );
//            }
//        }
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $res = curl_exec($curl);
//        $errorno = curl_errno($curl);
//        if ($errorno) {
//            var_dump("错误：".$errorno);
//            return array('errorno' => false, 'errmsg' => $errorno);
//        }
//        curl_close($curl);
//        //var_dump('数据：'.$res);
//        return json_decode($res, true);
//
//    }

    /**
     * 保存分享信息记录
     *
     */
    function saveShare($uid,$share_type,$suipian_index){
        $rand = t_rand_str();
        $exist = $this->column_sql('uid,openid',['uid'=>$uid],'zy_user',0);
        //判断领取的人是否参与过游戏
        if($exist) {
            $data['from_uid'] = $uid;
            $data['share_type'] = $share_type;
            $data['suipian_type'] = $suipian_index;
            $data['rand'] = $rand;
            $data['add_time'] = t_time();
            $insert_id = $this->db->insert('zy_fragment_share', $data);
            $result['rand'] =  $rand ;
            $result['share_id'] = $insert_id;
            return $result;
        }else{
            t_error(3,'该用户不存在');
        }

    }

    /**
     * 索要、赠送的链接，填写此地址
     */
    function  askGiveShareUrl($func,$rand,$type){

        if($func &&  $rand && $type){
            $sql = "select id from zy_fragment_share WHERE  rand=? AND share_type=?";
            $row = $this->db->query($sql,[$rand,$type])->row_array();
            return $row;
        }
    }

    /**
     * 处理赠送的碎片
     */
    function Give($openid,$uid,$nickname,$headPhoto,$r){

        $data['openid']    = $openid;
        $data['uid']       = $uid;
        $data['nickname']  = $nickname;
        $data['head_img']  = $headPhoto;
        $share_id = $r[1];
        $rand = $r[0];

        if($share_id && $rand){

            $row = $this->column_sql('*',['id' => $share_id, 'rand' => $rand],'zy_fragment_share',0);
            $show_text = '';

            //判断是否存在该分享记录
            if($row ){
                //用户是否存在
                $exist = $this->column_sql('uid,openid',['openid'=>$openid],'zy_user',0);
                //判断领取的人是否参与过游戏，没有则初始化用户表
                if(empty($exist)){
                    $uid = $this->user_model->init($openid, $nickname, $headPhoto);
                    $this->insert([
                        'uid' => $uid,
                        'add_time' => t_time(),
                        'update_time' => t_time()
                    ]);
                }
                //如果没人领取
                if($row['to_uid'] == ''){
                    //判断是否是自己进入赠送页面
                    if($uid == $row['from_uid']){
                        // '您的碎片暂时未被领取！';
                        $data['give_type'] = 5;
                        $show_text = '您赠送的碎片暂时未被领取';
                    }else{
                        //判断是否有足够的碎片赠送
                        $suipian_id = $this->getSuipian($row['from_uid'], $row['suipian_type']);
                        $ask_user = $this->column_sql('nickname',['uid'=>$row['from_uid']],'zy_user',0);
                        $data['share_nickname'] = $ask_user['nickname'];
                        if($suipian_id){
                            $data['give_type'] = 0;
                            $show_text = '您收到['.$data['share_nickname'].']\n赠送的碎片\n';
                        }else{
                            // '碎片被领取了或者碎片不足';
                            $data['give_type'] = 9;
                            $show_text = '碎片数量不足';
                        }
                    }
                }else{
                    $ask_user = $this->column_sql('nickname',['uid'=>$row['from_uid']],'zy_user',0);
                    $data['share_nickname'] = $ask_user['nickname'];
                    //判断是否是自己进入赠送页面
                    if($uid == $row['from_uid']){
                        $ask_user = $this->column_sql('nickname',['uid'=>$row['to_uid']],'zy_user',0);
                        $data['share_nickname'] = $ask_user['nickname'];
                        // '您的碎片被谁领取！';
                        $data['give_type'] = 2;
                        $show_text = '碎片已被['.$data['share_nickname'].']领取';
                    }else if($uid == $row['to_uid']){
                        // '您领取了谁的碎片';
                        $data['give_type'] = 3;
                        $show_text = '您领取了['.$data['share_nickname'].']赠送的碎片';
                    }else{
                        // '碎片被领取了';
                        $data['give_type'] = 4;
                        $show_text = '碎片已被领取';
                    }
                }
            }else{
                // '碎片不存在';
                $data['give_type'] = 1;
            }
            $data['show_text'] = $show_text;
            if($data['give_type'] == 0 || $data['give_type'] == 3){
                $data['show_text'] .= '集齐碎片拼图，赢取精美好礼';
            }
            $data['uid'] = $uid;
            $data['suipian_type'] = $row['suipian_type'];
            $data['share_id'] = $share_id;
            $data['share_rand'] = $rand;

            return $data;
        }else{
            t_error(2,'缺少参数');
        }
    }

    /**
     * 处理索要的碎片
     */
    function Ask($openid,$uid,$nickname,$headPhoto,$r){
        $data['openid']    = $openid;
        $data['uid']   = $uid;
        $data['nickname']  = $nickname;
        $data['head_img']= $headPhoto;
        $share_id = $r[1];
        $rand = $r[0];
        $show_text = '';
        if($share_id && $rand){
            $row = $this->column_sql('*',['id' => $share_id, 'rand' => $rand],'zy_fragment_share',0);
            //判断是否存在该分享记录
            if($row ){
                //用户是否存在
                $exist = $this->column_sql('uid,openid',['openid'=>$openid],'zy_user',0);
                //判断领取的人是否参与过游戏，没有则初始化用户表
                if(empty($exist)){
                    $uid = $this->user_model->init($openid, $nickname, $headPhoto);
                    $this->insert([
                        'uid' => $uid,
                        'add_time' => t_time(),
                        'update_time' => t_time()
                    ]);
                }
                //如果没人同意赠送
                if($row['to_uid'] == ''){
                    //判断是否是自己进入赠送页面
                    if($uid == $row['from_uid']){
                        // '您索要的碎片暂时没人赠送！';
                        $data['ask_type'] = 5;
                        $show_text = '您索要的碎片暂时没人赠送';
                    }else{
                        //判断是否有足够的碎片赠送
                        $suipian_id = $this->getSuipian($uid, $row['suipian_type']);
                        if($suipian_id > 0){
                            $ask_user = $this->column_sql('nickname',['uid' => $row['from_uid']],'zy_user',0);
                            $data['share_nickname'] = $ask_user['nickname'];
                            // 有足够的碎片赠送;
                            $data['ask_type'] = 0;
                            $show_text = '['.$data['share_nickname'].']\n向您索要碎片\n';
                        }else{
                            // '碎片不足';
                            $data['ask_type'] = 6;
                            $show_text = [$row['suipian_type']].'的碎片数量不足';
                        }
                    }
                }else{
                    $ask_user = $this->column_sql('nickname',['uid' => $row['from_uid']],'zy_user',0);
                    $data['share_nickname'] = $ask_user['nickname'];
                    if($uid == $row['from_uid']){
                        $ask_user = $this->column_sql('nickname',['uid' => $row['to_uid']],'zy_user',0);
                        $data['share_nickname'] = $ask_user['nickname'];
                        // '您成功向谁索要碎片！';
                        $data['ask_type'] = 2;
                        $show_text = '您成功向['.$data['share_nickname'].']\n索要了一片碎片\n';
                    }else if($uid == $row['to_uid']){
                        // '谁成功向您索要碎片';
                        $data['ask_type'] = 3;
                        $show_text = '['.$data['share_nickname'].']成功向您索要了一片碎片';
                    }else{
                        // '谁成功索要碎片完毕';
                        $data['ask_type'] = 4;
                        $show_text = '['.$data['share_nickname'].']成功索要了一片碎片';
                    }
                }
            }else{
                // '碎片不存在';
                $data['ask_type'] = 1;
            }

            $data['show_text'] = $show_text;
            if($data['ask_type'] == 2 || $data['ask_type'] == 0 || $data['ask_type'] == 3){
                $data['show_text'] .= '集齐碎片拼图，赢取精美好礼';
            }
            $data['uid'] = $uid;
            $data['suipian_type'] = $row['suipian_type'];
            $data['share_id'] = $share_id;
            $data['share_rand'] = $rand;
            return $data;
        }else{
            t_error(9,'信息不全');
        }
    }




    /**
     * 领取碎片
     * 玩家发出赠送碎片链接后，另外的玩家点击链接，点击“确定”领取，前端调用此方法
     */
    function toReceiveSuipian($uid,$share_id,$rand){

        //领取的人
        $exist = $this->column_sql('uid,openid',['uid'=>$uid],'zy_user',0);
        if(!$exist) t_error(2,'用户不存在！');

        $row = $this->column_sql('*',['id' => $share_id, 'rand' => $rand],'zy_fragment_share',0);

        //判断是否存在该分享记录
        if ($row) {
            //如果没人领取
            if ($row['to_uid'] == '') {
                if($uid == $row['from_uid']){
                    t_error(3,'同一用户，自己不能领取自己所赠碎片！');
                }else{

                    $this->db->trans_start();
                    //判断是否有足够的碎片赠送
                    $suipian_id = $this->getSuipian($row['from_uid'], $row['suipian_type']);
                    if ($suipian_id > 0) {
                        //更新用户表的碎片获得情况
                        $this->db->set('number_'.$row['suipian_type'], "number_$row[suipian_type]-1", FALSE);
                        $this->db->set('update_time', t_time());
                        $this->db->where('uid', $row['from_uid']);
                        $this->db->update($this->table);

                        //更新碎片的拥有者
                        $this->table_update('zy_fragment_share',['to_uid'=> $uid,'status'=> 1,'receive_time'=>t_time()],['id' => $share_id]);

                        $type = config_item('suipian_type');
                        $msg =  '您获得碎片'.$type[$row['suipian_type']];
                        $this->db->trans_complete();

                        return $msg;
                    } else {
                        t_error(4,'碎片被领取了');
                    }
                }
            }else{
                t_error(5,'碎片被领取了');
            }
        }
    }



    /**
     * 赠送碎片
     * 玩家发出索要碎片链接后，另外的玩家点击链接，点击“确定”赠送，前端调用此方法
     */
    function toSendSuipian($uid,$share_id,$rand){
        //赠送的人
        $exist = $this->column_sql('uid,openid',['uid'=>$uid],'zy_user',0);
        if(!$exist) t_error(1,'用户不存在！');
        $row = $this->column_sql('*',['id' => $share_id, 'rand' => $rand],'zy_fragment_share',0);
        //判断是否存在该分享记录
        if ($row) {
            //如果没人赠送
            if ($row['to_uid'] == '') {
                if($uid == $row['from_uid']){
                    t_error(2,'同一用户，不能向自己索要碎片！');
                }else{
                    //判断是否有足够的碎片赠送
                    $suipian_id = $this->getSuipian($uid, $row['suipian_type']);
                    if ($suipian_id > 0) {
                        $this->db->trans_start();
                        //更新用户表的碎片获得情况
                        $this->db->set('number_'.$row['suipian_type'], "number_$row[suipian_type]-1", FALSE);
                        $this->db->set('update_time', t_time());
                        $this->db->where('uid', $row['from_uid']);
                        $this->db->update($this->table);

                        //更新碎片的拥有者
                        $this->table_update('zy_fragment_share',['to_uid'=> $uid,'status'=> 1,'receive_time'=>t_time()],['id' => $share_id]);

                        $type = config_item('suipian_type');
                        $msg =  '您获得碎片'.$type[$row['suipian_type']];
                        $this->db->trans_complete();
                        return $msg;
                    } else {
                        t_error(3,'您的碎片不足');
                    }
                }
            }else{
                t_error(4,'赠送过了');
            }
        }

    }


    /**
     * 检查是否有足够的碎片
     */
    function getSuipian($uid,$type){

        $sql = "select id from $this->table WHERE  uid=? AND number_{$type} > ?";
        $res = $this->db->query($sql,[$uid,0])->row_array();

        if($res){
            return $res['id'];
        }else{
            return 0;
        }
    }

    //赠送记录(自己主动索要和别人赠送)
//    function giveRecord($uid){
//        $sql  = ' SELECT   `from_uid`, `to_uid`, share_type, suipian_type, receive_time ';
//        $sql .= ' FROM `zy_fragment_share` WHERE (';
//        $sql .= ' ( share_type=1 AND to_uid=\''. $uid .'\' AND from_uid !=\'\') OR  ';
//        $sql .= ' ( share_type=2 AND to_uid != \'\' AND from_uid=\''. $uid .'\' ) )';
//        $sql .= ' ORDER BY receive_time DESC ';
//        $list = $this->db->query($sql)->result_array();
//        $type = config_item('suipian_type');
//        foreach($list as &$value){
//            if($value['share_type'] == 1){
//               $nickname  = $this->column_sql('nickname',['uid'=>$value['from_uid']],'zy_user',0);
//            }else{
//                $nickname = $this->column_sql('nickname',['uid'=>$value['to_uid']],'zy_user',0);
//            }
//            $value['type'] = $type[$value['suipian_type']];
//            $value['nickname'] = $nickname['nickname'];
//            unset($value['from_uid']);
//            unset($value['to_uid']);
//            unset($value['share_type']);
//            unset($value['suipian_type']);
//        }
//        $result['list'] = $list;
//        return $result;
//    }

    //索要记录(自己主动送出和别人索要)
//    function askRecord($uid){
//        $sql  = ' SELECT   `from_uid`, `to_uid`, share_type, suipian_type, receive_time ';
//        $sql .= ' FROM `zy_fragment_share` WHERE (';
//        $sql .= ' ( share_type=2 AND to_uid=\''. $uid .'\' AND from_uid !=\'\') OR  ';
//        $sql .= ' ( share_type=1 AND to_uid != \'\' AND from_uid=\''. $uid .'\' ) )';
//        $sql .= ' ORDER BY receive_time DESC ';
//        $list = $this->db->query($sql)->result_array();
//        $type = config_item('suipian_type');
//        foreach($list as &$value){
//            if($value['share_type'] == 1){
//                $nickname = $this->column_sql('nickname',['uid'=>$value['to_uid']],'zy_user',0);
//            }else{
//                $nickname = $this->column_sql('nickname',['uid'=>$value['from_uid']],'zy_user',0);
//            }
//            $value['type'] = $type[$value['suipian_type']];
//            $value['nickname'] = $nickname['nickname'];
//            unset($value['from_uid']);
//            unset($value['to_uid']);
//            unset($value['share_type']);
//            unset($value['suipian_type']);
//        }
//        $result['list'] = $list;
//        return $result;
//    }

    //别人向自己索要与自己主动赠送记录
    function shareRecord($uid){
        $type = config_item('suipian_type');
        $sql = "select  from_uid, to_uid, share_type, suipian_type, receive_time from zy_fragment_share WHERE to_uid=? AND status=? AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= receive_time";
        $list = $this->db->query($sql,[$uid,1])->result_array();

        foreach($list as &$value){

            $nickname = $this->column_sql('nickname',['uid'=>$value['from_uid']],'zy_user',0);
            $type1 = ($value['share_type'] == 1)?'赠送':'索要';

            $value['type'] = $type[$value['suipian_type']];
            $value['nickname'] = $nickname['nickname'].$type1;
            unset($value['from_uid']);
            unset($value['to_uid']);
            unset($value['share_type']);
            unset($value['suipian_type']);
        }
        $result['list'] = $list;
        return $result;
    }

    //获得记录
    function getRecord($uid){
        $type = config_item('suipian_type');
        $list = $this->db->query("select type,resource,add_time from zy_fragment_record  where uid='$uid' AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= add_time")->result_array();
        $arr = $this->db->query("select type,update_time add_time from zy_fragment_scan  where uid='$uid' AND status=1 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= update_time")->result_array();
        foreach($arr as &$value){
            $value['resource'] = '扫码';
        }
        $array = array_merge($list,$arr);
        foreach($array as &$value){
            $value['type'] = $type[$value['type']];
        }

        $result['list'] = $array;
        return $result;
    }
}
