<?php defined('BASEPATH') OR exit('No  direct script access allowed');
/**
 *
 * User: y'q'p
 * Date: 2020/3/5
 *
 */
include_once 'Base_model.php';

class Plantaddress_model extends Base_model{

    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_plant_ranking_message';

    }

    //保存用户信息
    function savemessage($uid,$id,$truename,$phone,$address,$code){
        $get_sql = "select id,pid,status,add_time,update_time from $this->table where uid=? AND pid=?";
        $row = $this->db->query($get_sql,[$uid,$id])->row_array();

        if(strtotime($row['update_time'])>0)
        {
            $time =strtotime($row['update_time']) - strtotime($row['add_time']);
            if(strtotime($row['update_time']) && $time < 50 ) t_error(6,'提交时间过短，请稍后再试');
        }
        if($row && $row['status'] == 1) t_error(5,'不可修改');

        $check_sql = "select * from zy_ranking_zz_prize_record where uid=? AND id=?";  //用户获得的奖品表
        $check = $this->db->query($check_sql,[$uid,$id])->row_array();
//        if(empty($check))  t_error(4,'保存失败！');
        $data = [
            'uid'=>$uid,
            'truename'=>$truename,
            'phone'=>$phone,
            'address'=>$address,
            'status'=>0,
            'pid'=>$id,
            'code'=>$code
        ];

        if($row)
        {
            $data['update_time']  = t_time();
            $this->update($data,['uid'=>$uid,'id'=>$row['id']]);
        }
        else
        {
            $data['add_time'] = t_time();
            $this->insert($data);
        }

    }

    //查询用户信息
    function getUsermessage($uid,$id){
        $sql = "select uid,truename,phone,address,status,add_time,code from $this->table where uid=? AND pid=?";
        $row = $this->db->query($sql,[$uid,$id])->row_array();

        $max_time = 86400;
        if($row)
        {
            if(time()-strtotime($row['add_time'])>$max_time)
            {
                $this->update(['status'=>1],['uid'=>$uid,'pid'=>$id]);
                $result['status'] = 1;
            }
            else
            {
                $result['status'] = 0;
            }
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
        }
        else
        {

            $sql = "select truename,phone,address,status,add_time,code from $this->table where uid=? ORDER BY id desc";
            $row = $this->db->query($sql,[$uid])->row_array();
            $result['status'] = 0;
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
            if(empty($row)) $result = [];

        }

        return $result;
    }

}

