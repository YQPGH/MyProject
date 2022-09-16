<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User:y2020
 * Date: 2020/8/20
 * Time: 10:46
 */

include_once 'Base_model.php';

class Midautumn_model extends Base_model
{
    public $material = 5; //月饼制作材料所需数量
    public $rate = 30; //30%获得月饼材料
    public $prize_total = 5; //每次最多获得5份奖励
    function  __construct()
    {
        parent::__construct();
        $this->table = 'zy_midautumn_player';
        $this->load->model('api/user_model');
        $this->load->library('user_agent');
    }

    //活动时间
    function activity_time(){
        $sql = "select UNIX_TIMESTAMP(start_time) start_time,UNIX_TIMESTAMP(end_time) end_time from zy_activity_config WHERE `name`=?";
        $time = $this->db->query($sql,['mid_autumn'])->row_array();
        if(time()>$time['start_time'] && time()<$time['end_time'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //初始化用户信息
    function initdata($uid){

        $time = $this->activity_time();
        $openid = model('user_model')->queryOpenidByUid($uid);
        if(!$openid) t_error(9,'该用户不存在');
        if($time)
        {
            if($uid=='undefined') t_error(2,'操作有误');
            $sql = "select * from $this->table WHERE uid=?";
            $res = $this->db->query($sql,[$uid])->row_array();

            if(empty($res))
            {
                $this->insert([
                    'uid' => $uid,
                    'addtime'=> t_time(),
                    'updatetime'=> t_time()
                ]);
            }

            $task_row = $this->table_row('zy_mid_autumn_task', ['uid' => $uid]);
            if(empty($task_row))
            {
                $this->table_insert('zy_mid_autumn_task',[
                    'uid'=>$uid,
                    'add_time'=>t_time()
                ]);
            }

        }

    }


    function get_user($uid)
    {
        $this->initdata($uid);
        $sql = "select a.nickname,a.head_img,b.cake,b.skin mskin,b.stuffing from zy_user a,zy_midautumn_player b WHERE b.uid=? AND a.uid=b.uid";
        $res = $this->db->query($sql,[$uid])->row_array();

        return $res;
    }

    /**
     * 合成
     */
    function compose($uid,$total)
    {
        $time = $this->activity_time();
        if(!$time) t_error(1,'活动已结束');
        if($total<1) t_error(3,'请选择月饼制作数量');
        $row = $this->row(array('uid'=>$uid));
        $number = intval($this->material*$total);
        if($row['skin'] < $number) t_error(2,'面皮不足');
        if($row['stuffing'] < $number) t_error(2,'馅料不足');

        $this->db->trans_start();
        $shop = $this->db->query("select shopid shop,`name` from zy_shop WHERE type1=? and type4=?",['mid_autumn','cake'])->row_array();
        $this->db->set('skin', 'skin - '.$number, FALSE)
                ->set('stuffing', 'stuffing - ' .$number, FALSE)
                ->set('cake', 'cake + '.$total, FALSE)
                ->set('updatetime',t_time())
                ->where('uid', $uid)
                ->update($this->table);

        $this->table_insert('zy_midautumn_compose',[
            'uid' => $uid,
            'addtime' => t_time()
        ]);

        $this->db->trans_complete();
        $result['shop'] = $shop['shop'];
        $result['name'] = $shop['name'];

        return $result;
    }



    /**
     *  任务领取
     *
     */
    function task_receive($uid, $id)
    {
        $time = $this->activity_time();
        if(!$time) t_error(2,'活动已结束');
//        $task = $this->db->query("select task_num,task_id from zy_task  WHERE type=? and task_id=?;",['mid_autumn',$id])->row_array();
        $sql = "select * from zy_mid_autumn_task where uid=?";
        $row = $this->db->query($sql,[$uid])->row_array();

        if ($id>1) t_error(4, '任务不存在');
        // 判断是否已领奖
        if ($row['task'.$id]) t_error(3, '已领取');
        $this->db->trans_start();
        $shop = $this->db->query("select b.shopid,b.total,b.type2 from zy_setting a,zy_shop b WHERE a.mkey=? and a.mvalue=b.type2 AND a.mkey=b.type1",['mid_autumn'])->row_array();

        // 更新领奖状态
        $this->table_update('zy_mid_autumn_task', ['task' . $id  => 1], ['uid' => $uid]);

        $type1 = 0;
        $type2 = 0;

        if($shop['type2'])
        {
            $type2 =$shop['total'];
        }
        else
        {
            $type1 =$shop['total'];
        }

        $this->update_value($uid,$type1,$type2);
        $this->db->trans_complete();
        $result['shopid'] = $shop['shopid'];
        $result['total'] = $shop['total'];
        return $result;
    }

    //任务
    function task_list($uid)
    {
        $today_row = $this->table_row('zy_mid_autumn_task', ['uid' => $uid]);
        $list = $this->db->query( "select task_id id,task_num from zy_task where  type=?",['mid_autumn'])->result_array();

        foreach($list as $key=>&$value)
        {
            $value['is_receive'] = 0;
            $value['finish_num'] = 0;
            $value['is_finish'] = 0;
            for($i =1; $i<=2; $i++)
            {
                if($i == $value['id'])
                {
                    if($i == 2)
                    {

                        $value['finish_num'] = $today_row['skin']+$today_row['stuffing'];
                        if($value['finish_num']>0)
                        {
                            $value['is_receive'] = 1;
                            $value['is_finish'] = 1;
                        }
                    }
                    if($i == 1)
                    {
                        $shop = $this->db->query("select b.shopid from zy_setting a,zy_shop b WHERE a.mkey=? and a.mvalue=b.type2 AND a.mkey=b.type1",['mid_autumn'])->row_array();

                        $value['shopid'] = $shop['shopid'];
                        if($today_row['task'.$i]) $value['is_receive'] = 1;
                         $value['finish_num'] = 1;
                         $value['is_finish'] = 1;

                    }
                }
            }

            unset($value['task_num'],$value['uid'],$value['add_time'],$value['update_time']);
        }

        return $list;
    }


    function update_value($uid,$type1=0,$type2=0)
    {
        $this->db->set('skin','skin+'.$type1,false)
            ->set('stuffing','stuffing+'.$type2,false)
            ->set('updatetime',t_time())
            ->where('uid',$uid)
            ->update($this->table);

    }

    function update_total($uid)
    {
        $time = $this->activity_time();
        $this->db->trans_start();

        $rand = rand(1,100);
        $result = [];

        if($time && $rand <= $this->rate)
        {

            $this->initdata($uid);
            $row = $this->db->query("select task_id id,task_num from zy_task where  `type`=? AND task_id=?",['mid_autumn',2])->row_array();
            $today_row = $this->table_row('zy_mid_autumn_task', ['uid' => $uid]);
            $type1 = 0;
            $type2 = 0;
            $task = 0;
            $array = [0,1];
            $rand_key = array_rand($array,1);
            $rand_num = $array[$rand_key];
            if($rand_num)
            {
                if($today_row['skin'] < $row['task_num'])
                {
                    $type1 = 1;
                    $task = 1;
                    $shop = $this->db->query("select shopid,`name` from zy_shop WHERE type1=? and type2=?",['mid_autumn',0])->row_array();
                    $result['shop'] = $shop['shopid'];
                    $result['name'] = $shop['name'];
                    $result['total'] = 1;
                }
            }
            else
            {
                if($today_row['stuffing'] < $row['task_num'])
                {
                    $type2 = 1;
                    $task = 1;
                    $shop = $this->db->query("select shopid,`name` from zy_shop WHERE type1=? and type2=?",['mid_autumn',1])->row_array();
                    $result['shop'] = $shop['shopid'];
                    $result['name'] = $shop['name'];
                    $result['total'] = 1;
                }
            }

            $this->db->set('skin','skin+'.$type1,false)
                ->set('stuffing','stuffing+'.$type2,false)
                ->set('task2','task2+'.$task,false)
                ->set('update_time',t_time())
                ->where('uid',$uid)
                ->update('zy_mid_autumn_task');

            $this->update_value($uid,$type1,$type2);
        }

        $this->db->trans_complete();
        return $result;
    }

    /**
     * 奖品列表
     */
    function prize_list($uid)
    {
        $get_sql = "SELECT money,shandian,shop1 shopid,shop1_total shop_num FROM zy_prize WHERE type1=? ";
        $list = $this->db->query($get_sql, array('mprize'))->result_array();

        $result['prize'] = $list;
        return $result;
    }

    /**
     * 抽奖
     */
    function get_prize($uid,$total)
    {

        $time = $this->activity_time();
        if(!$time) t_error(1,'活动已结束');
        $row = $this->db->query("select cake from $this->table WHERE uid=?;",[$uid])->row_array();
        if(!$row['cake']) t_error(2,'请先合成月饼！');
        if($row['cake']<$total) t_error(4,'您的月饼不足');
        if($total > $this->prize_total) t_error(3,'已超过供奉数量');
        // 事务开始
        $this->db->trans_start();

        $result = [];
        $is_prize_back = model('prize_model')->is_prize_black($uid);
        $year = strtotime(date('Y-01-01'));
        for($i = 1; $i <= $total; $i++)
        {
            $sql = "select COUNT(*) as num from zy_midautumn_prize_record WHERE  uid=? AND type=? and unix_timestamp(add_time)>'$year' ";
            $count =  $this->db->query($sql,[$uid,1])->row_array();
            $prize = $this->prize_return();
//            $is_limit = model('qixi_model')->limit_player($uid,$prize['shop']);
            $ip = $this->get_real_ip();
            $ip_limit = $this->db->query("select COUNT(ip) num from zy_midautumn_prize_record WHERE  `type`=1 AND ip=? and unix_timestamp(add_time)>'$year' GROUP  BY ip",[$ip])->row_array();

            if($ip_limit['num']>=2 ||  $count['num']>=2 || !$is_prize_back  )
            {
                $prize = $this->prize_shop();
            }
            // if($uid==""){$prize = $this->prize_test();}
            // 奖品入库


            if ($prize['money'])
            {
                $this->user_model->money($uid, $prize['money']);
            }
            // 奖品入库
            if ($prize['shandian'])
            {
                $this->user_model->shandian($uid, $prize['shandian']);
            }
            $insert_id = 0;
            if($prize['shop'])
            {
                $shop = $this->shop_model->detail($prize['shop']);
                if($prize['type2'])
                {
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    //如果抽到的是抵扣券，不加入仓库，直接存入log_prize_quan表
                    if($shop['type4'] == 'quan')
                    {
                        //根据uid获取openid
                        $data = array(
                            'shopid' => $prize['shop'],
                            'ticket_id' => t_rand_str($uid),
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'type' =>1,

                            'addtime' => time()
                        );
                        $insert_id = $this->table_insert('zy_ticket_record', $data);
                        //更新奖品数量
                        $this->db->set('total','total-1',false)
                            ->set('update_time',t_time())
                            ->where('id',$prize['id'])
                            ->update('zy_prize');
                    }

                }
                else
                {
                    //抽中商品
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop_num'], $uid, $prize['shop']);
                }
            }


            $data = [
                'uid'=>$uid,
                'pid' => $prize['id'],
                'ticket_id'=>$insert_id,
                'type'=> $prize['type2'],
                'add_time'=>t_time(),
               // 'ip' => $this->input->ip_address(),
                'ip' => $ip,
                'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() .
                    $this->agent->version()
            ];
            $this->table_insert('zy_midautumn_prize_record',$data);
            unset($prize['id'],$prize['type2']);
            $result['prize'][] = $prize;
        }

        //更新用户抽奖次数
        $this->db->set('cake', 'cake - '.$total, FALSE)
            ->set('updatetime',t_time())
            ->where('uid', $uid)
            ->update($this->table);

        $this->db->trans_complete();

        return $result;
    }

    /**
     * 抽奖记录
     */
    function prize_record($uid)
    {


        $list = $this->lists_sql("SELECT p.id,p.pid,p.add_time log_time,c.shop1 shopid FROM zy_midautumn_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1='mprize' AND p.uid=? AND p.type=? AND p.ticket_id=?
                                  ORDER BY p.id DESC LIMIT 30", [$uid,1,0]);

        foreach($list as $k => &$v)
        {

            if($v['shopid'])
            {
                $shop = $this->shop_model->detail($v['shopid']);
                $sql = "select * from zy_message WHERE uid=? AND  pid=? ";
                $row  = $this->db->query($sql,[$uid,$v['id']])->row_array();

                $v['status'] = $row? 1: 0;
                $v['url'] = site_url('api/address/getUsermessage?id='.$v['id'].'&type1=midautumn');

                $obj = json_decode($shop['json_data']);
                $v['vali'] = date('Y-m-d H:i:s',strtotime($v['log_time'])+($obj->vali)*24*3600);
                if((strtotime($v['log_time'])+($obj->vali)*24*3600) < time())
                {
                    $v['is_overtime'] = 1;
                }
                else
                {
                    $v['is_overtime'] = 0;
                }
            }
            unset($v['pid']);
        }
        return $list;
    }

    function prize_log($uid)
    {
        $year = date('Y-01-01');
        $result = [];
        $list = $this->lists_sql("SELECT log.add_time log_time,p.*
                        FROM zy_midautumn_prize_record log LEFT JOIN zy_prize p
                        ON log.pid=p.id
                        WHERE log.uid=? AND log.type=? AND unix_timestamp(log.add_time)>'$year'
                        ORDER BY log.id DESC LIMIT 20;", [$uid,0]);
        if(count($list))
        {
            foreach($list as &$value)
            {
                $row = [];
                $row['log_time'] = $value['log_time'];
                $value['money'] ? $row['money']=$value['money'] : $row['money']=0 ;
                $value['ledou'] ? $row['ledou']=$value['ledou'] : $row['ledou']=0 ;
                $value['shandian'] ? $row['shandian']=$value['shandian'] : $row['shandian']=0 ;
                $value['shop1'] ? $row['shop1']=$value['shop1'] : $row['shop1']=0 ;
                $result[] = $row;
            }
        }

        return $result;
    }

    function prize_return(){


        $prize_array = $this->db->query("select id,get_rate,total,type2 from zy_prize WHERE type1='mprize'")->result_array();

        foreach($prize_array  as $key=>$value)
        {
            $proArr[$value['id']] = $value['get_rate'];
            if($value['type2'])
             {
                if($value['get_rate']==0 || $value['total']==0)
                {
                    unset($proArr[$value['id']]);
                }
             }
        }

        $prize_id = $this->get_rand_num($proArr);
        $result = $this->column_sql(" id,shandian,money,shop1 shop,shop1_total shop_num,type2",array('id'=>$prize_id),'zy_prize',0);

        return $result;
    }

    //如果奖品数量已抽完或者奖品拥有数量上限，则随机返回其他奖品
    function prize_shop(){

        $type = rand(1,5);

        $result = $this->column_sql('id,shandian,money,shop1 shop,shop1_total shop_num,type2',array('type1'=>'mprize','type2'=>0,'type3'=>$type),'zy_prize',0);

        return $result;
    }

    private function get_rand_num($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur)
        {

            $randNum = mt_rand(0, $proSum);
            if($randNum <= $proCur)
            {
                $result = $key;
                break;
            }
            else
            {
                $proSum -= $proCur;
            }
        }

        unset ($proArr);
        return $result;
    }



     //奖品测试
    function  prize_test()
    {
        $id = 435;
        $result = $this->column_sql('id,shandian,money,shop1 shop,shop1_total shop_num,type2',array('id'=>$id),'zy_prize',0);
        return $result;
    }



    function get_real_ip()
    {
        if (getenv('HTTP_CLIENT_IP'))
        {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        if (getenv('HTTP_X_REAL_IP'))
        {
            $ip = getenv('HTTP_X_REAL_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            $ips = explode(',', $ip);
            $ip = $ips[0];
        }
        elseif (getenv('REMOTE_ADDR'))
        {
            $ip = getenv('REMOTE_ADDR');
        }
        else
        {
            $ip = '0.0.0.0';
        }

        return $ip;
    }


    function test()
    {

        $list = ['oREekjlGM1jIfb7-aHywkX77lXCQ','oREekjqOk_B8X_Zq4-1cT9WuNrbQ','oREekjlvpKS96ia86c2bWRNVf570','oREekjniuNG0gTGGsvW4mCLyncec',
            'oREekjrf6CbtyuMVJheETiebcLOU','oREekjhSRPZfdVgGGJ6aR6qqeg90','oREekjoQJvlJSdJBpHolCO35mM2w','oREekjl6xILcTdf7crcR-P6Ijk6U','oREekjlGFZT_p3znC4i-Cuu1oIiI',
        'oREekjojAXJBlaHeTCInEChM_ubA','oREekjqMnx3FqNcaqBNlkXgH7TDI'];

        $list1 = $this->db->query("select * from yccq where openid NOT IN ('oREekjlGM1jIfb7-aHywkX77lXCQ','oREekjqOk_B8X_Zq4-1cT9WuNrbQ','oREekjlvpKS96ia86c2bWRNVf570','oREekjniuNG0gTGGsvW4mCLyncec',
            'oREekjrf6CbtyuMVJheETiebcLOU','oREekjhSRPZfdVgGGJ6aR6qqeg90','oREekjoQJvlJSdJBpHolCO35mM2w','oREekjl6xILcTdf7crcR-P6Ijk6U','oREekjlGFZT_p3znC4i-Cuu1oIiI',
        'oREekjojAXJBlaHeTCInEChM_ubA','oREekjqMnx3FqNcaqBNlkXgH7TDI')")->result_array();

        $this->db->trans_start();
        foreach($list1 as $val)
        {
            $count = $this->db->query("SELECT COUNT(*) num FROM zy_midautumn_prize_record WHERE uid=? and type=?",[$val['uid'],1])->row_array();
            if($count['num']>2)  continue;

            $data = array(
                'shopid' => $val['shopid'],
                'ticket_id' => $val['code'],
                'uid' => $val['uid'],
                'openid' => $val['openid'],
                'stat' => 0,
                'type' =>1,
                'addtime' => $val['add_time']
            );
            $insert_id = $this->table_insert('zy_ticket_record', $data);

            $row = $this->db->query("select id from zy_prize WHERE type1='mprize' AND shop1=?",[$val['shopid']])->row_array();
            $array = [
                'uid'=>$val['uid'],
                'pid' =>$row['id'],
                'ticket_id'=>$insert_id,
                'type'=> 1,
                'add_time'=>t_time($val['add_time']),
                'ip' =>'',
                'user_agent' => ''
            ];
            $this->table_insert('zy_midautumn_prize_record',$array);
        }

        $this->db->trans_complete();

    }
}
