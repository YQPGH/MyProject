<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 建筑升级
 * Date: 2019/10/9
 * Time: 17:06
 */

include_once 'Base_model.php';

class Building_model extends Base_model{
    function __construct(){
        parent::__construct();
        $this->table = 'zy_building';
        $this->load->model('api/store_model');
    }

    function init_data($uid)
    {

        $sql = "select `order`,`type` from zy_building_config ";
        $list = $this->db->query($sql)->result_array();

        foreach($list as $value)
        {
            $sql = "select * from $this->table WHERE uid=? AND `type`=?";
            $row = $this->db->query($sql,[$uid,$value['type']])->row_array();
            if(empty($row))
            {
                $this->insert([
                    'uid'=>$uid,
                    'type'=>$value['type'],
                    'order'=>$value['order'],
                    'status'=>0,
                    'add_time'=>t_time()
                ]);
            }

        }

        $get_sql = "select shopid from zy_shop WHERE type1=?";
        $shop = $this->db->query($get_sql,['building'])->result_array();
        foreach($shop as $value)
        {
            $get_sql = "select shopid,total  from zy_building_store WHERE uid=? AND shopid=?";
            $row = $this->db->query($get_sql,[$uid,$value['shopid']])->row_array();
            if(empty($row))
            {
                $this->table_insert('zy_building_store',[
                    'uid'=>$uid,
                    'shopid'=>$value['shopid'],
                    'addtime'=>t_time()
                ]);
            }
        }
    }

    //查询建筑剩余材料
    function building_lists($uid)
    {

        $sql = "select shopid from zy_shop WHERE type1=?";
        $shop = $this->db->query($sql,['building'])->result_array();
        foreach($shop as $value)
        {
            $get_sql = "select shopid,total  from zy_building_store WHERE uid=? AND shopid=?";
            $row = $this->db->query($get_sql,[$uid,$value['shopid']])->row_array();
            if($row['shopid']==1651)
            {
                $result['stone'] = $row['total'];
            }
            if($row['shopid']==1652)
            {
                $result['wood'] = $row['total'];
            }
            if($row['shopid']==1653)
            {
                $result['paint'] = $row['total'];
            }
        }


        return $result;
    }

    //建筑升级
    function upgrade($uid,$number)
    {

        $sql = "select * from zy_building_config WHERE `order`=?";
        $row = $this->db->query($sql,[$number])->row_array();
        $user_lv = $this->db->query("select game_lv from zy_user WHERE uid='$uid'")->row_array();

        if($row['lv']>$user_lv['game_lv']) t_error(7,'等级不足，请先升级');
        $user = $this->db->query("select * from zy_building WHERE uid='$uid' AND type='$row[type]'")->row_array();
        if($user['is_upgrade']==1) t_error(3,'建筑升级中');
        if($user['is_upgrade']==2) t_error(4,'升级已完成');
        $this->db->trans_start();
        $time = config_item('building_time');

        $get_sql = "select uid,shopid,total from zy_building_store WHERE uid=? AND shopid in ?";
        $list = $this->db->query($get_sql,[$uid,[$row['shopid1'],$row['shopid2'],$row['shopid3']]])->result_array();

        if(empty($list)) t_error(7,'材料不足');
        foreach($list as $k=>$value)
        {
            $k=$k+1;
            if($value['shopid']==$row['shopid1'] && $value['total']<$row['shopid_num1'])
            {
                t_error(4,'瓦石不足');
            }
            else if($value['shopid']==$row['shopid2'] && $value['total']<$row['shopid_num2'])
            {
                t_error(5,'木材不足');
            }
            else if($value['shopid']==$row['shopid3'] && $value['total']<$row['shopid_num3'])
            {
                t_error(6,'油漆不足');
            }
            else
            {
                $this->db->set('total','total-'.$row['shopid_num'.$k],false)
                    ->where('uid',$uid)
                    ->where('shopid',$value['shopid'])
                    ->update('zy_building_store');
            }
        }

        $start_time = t_time();
        $stop_time = t_time(strtotime($start_time)+$time);

        if($user)
        {
            $status = 1;
            $this->update([
                'is_upgrade'=>$status,
                'start_time'=>$start_time,
                'stop_time'=>$stop_time,
                'update_time'=>$start_time
            ],
                [
                    'uid'=>$uid,
                    'type'=>$user['type'],
                    'order'=>$user['order']
                ]);
            $result['start_time'] = $start_time;
            $result['stop_time'] = $stop_time;
        }

        //添加升级记录
        $this->table_insert('zy_building_upgrade_record',[
            'uid'=>$uid,
            'type'=>$row['type'],
            'addtime'=>$start_time
        ]);

        $result['status'] = $status;

        $this->db->trans_complete();
        return $result;
    }

    //更换皮肤
    function init_interface($uid,$number,$status)
    {

        $sql = "select `type` from zy_building_config WHERE `order`=?";
        $row = $this->db->query($sql,[$number])->row_array();

        $user = $this->db->query("select * from zy_building WHERE uid='$uid' AND type='$row[type]'")->row_array();
        $this->db->trans_start();
        if($user['is_upgrade'] == 0) t_error(2,'请先升级');
        if($user['is_upgrade'] == 1) t_error(3,'建筑升级中');
        if($status == $user['status']) t_error(4,'皮肤使用中');

        if($user['is_upgrade']==2)
        {
            $this->update(['status'=>$status, 'update_time'=>t_time()],[ 'uid'=>$uid, 'type'=>$row['type']]);

        }

        $this->db->trans_complete();

    }




    function insert_record($uid,$array,$type)
    {

        $this->db->trans_start();
        foreach($array as $value)
        {
            $sql = "select uid from zy_building_store WHERE uid=? AND shopid=?";
            $row = $this->db->query($sql,[$uid,$value['shop']])->row_array();
            if($row)
            {
                $this-> update_store($uid,$value['shop'],'+'.$value['total']);
            }
            else
            {
                $this->table_insert('zy_building_store',[
                    'uid'=>$uid,
                    'shopid'=>$value['shop'],
                    'total'=>$value['total'],
                    'addtime'=>t_time()
                ]);
            }
            //添加建筑材料获取记录
            $this->table_insert('zy_building_get_record',[
                'uid'=>$uid,
                'shopid'=>$value['shop'],
                'type'=>$type,
                'addtime'=>t_time()
            ]);
        }

        $this->db->trans_complete();

    }


    function update_store($uid,$shop,$num)
    {
        $this->db->set('total', 'total'.$num, FALSE);
        $this->db->set('updatetime',t_time());
        $this->db->where('uid',$uid);
        $this->db->where('shopid',$shop);
        $this->db->update('zy_building_store');
    }

    //任务领取
    function task_receive($uid)
    {
        $today = strtotime(t_time(0,0));
        $sql = "select * from zy_building_get_record WHERE uid=? and type=? AND UNIX_TIMESTAMP(addtime)>'$today' ORDER BY id DESC";
        $row = $this->db->query($sql,[$uid,0])->row_array();
        $today_row = $this->table_row('zy_task_today', ['uid' => $uid]);
        if($today_row['task1']>0 && $today_row['task2']>0 &&  $today_row['task3']>0 )
        {
            if(empty($row))
            {
                $this->load->model('api/setting_model');
                $type1 = $this->setting_model->get('building');
                $list = $this->column_sql('shop1',['type2'=>'building','type1'=>$type1],'zy_prize',0);
                $rand = array_rand($list,1);
                $shopid = $list[$rand];
                $arrray[]['shop'] = $shopid['shop1'];
                $arrray[0]['total'] = 1;

                $this->insert_record($uid,$arrray,0);
                return $arrray;
            }
            else
            {
                t_error(2,'已领取');
            }
        }
        else
        {
            t_error(1,'任务未完成');
        }
    }

    //查询建筑是否升级
    function query_upgrade($uid,$type)
    {

        $this->upgrade_status($uid);
        $sql = "select  `type`,is_upgrade from $this->table WHERE uid=?  AND `order`=? ";
        $row = $this->db->query($sql,[$uid,$type])->row_array();
//        $row =  $this->column_sql('type,is_upgrade',['uid'=>$uid,'order'=>$type],'zy_building',0);
//        $res = $this->column_sql('*',['uid'=>$uid,'type'=>$row['type']],'zy_building_upgrade_record',0);


        $result['is_upgrade'] = $row['is_upgrade'];
        $result['status'] = ($row['is_upgrade']==2)?1:0;
        return $result;

    }

    function status($uid)
    {

        $sql = "select `order` number,status,is_upgrade,start_time,stop_time from $this->table WHERE uid=? ";
        $list = $this->db->query($sql,[$uid])->result_array();

        $result = [];
        if(count($list)>0)
        {
            foreach($list as $k=>&$v)
            {
                $result[$v['number']] =  $v;
                unset($result[$v['number']]['number']);

            }
        }
        return $result;
    }

    function upgrade_status($uid)
    {
        $sql = "select uid,is_upgrade,UNIX_TIMESTAMP(stop_time) as stop_time from $this->table WHERE uid=?";
        $list = $this->db->query($sql,[$uid])->result_array();

        if(count($list)>0)
        {
            $time = time();
            foreach($list as $v)
            {
                if($v['stop_time']<$time && $v['is_upgrade'] ==1)
                {
                    $this->update(['is_upgrade'=>2,'status'=>1,'update_time'=>t_time()],['uid'=>$v['uid'],'is_upgrade' =>1]);
                }
            }
        }
    }


}