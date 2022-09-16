<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/user_model');
    }

    //跳转填写用户联系电话、地址信息
    public function tel_address_view(){
        $uid = $this->input->get('uid');
        $shopid = $this->input->get('shopid');
        $id = $this->input->get('id');
        if(!$uid) t_error(1,'用户ID不能为空');
        if(!$shopid) t_error(1,'shopid不能为空');
        if(!$id) t_error(1,'id不能为空');
        $count = $this->db->query("select COUNT(*) as num from zy_user WHERE uid='$uid'")->row_array();
        if($count['num']==0) t_error(1,'用户不存在');
        $row = $this->db->query("select a.name,b.id,b.shopid,b.status,b.truename,b.tel,b.address from zy_shop a, zy_st_message b WHERE b.uid='$uid' AND b.id=$id AND a.shopid=$shopid")->row_array();
        if($row['shopid'] != $shopid) t_error(1,'提交的信息不匹配');
        if($row['status']) t_error(1,'信息已填写，不可再次修改');
        $data['value'] = $row;
        $this->load->view('api/tel_address_view', $data);
    }

    public function save(){
        $id = intval($this->input->post('id'));
        $data['truename'] = $this->input->post('truename');
        $data['tel'] = $this->input->post('tel');
        $data['address'] = $this->input->post('address');
        //$data = $this->input->post('value');
        if (empty($data['truename'])||empty($data['tel'])||empty($data['address'])) t_error(1,'缺少必要信息');
        //判断截止时间是否已过
        $row = $this->db->query("select b.end_time from zy_st_message a,zy_prize b WHERE a.id=$id AND b.shop1=a.shopid")->row_array();
        if(t_time() > $row['end_time']) t_error(1,'截止时间已过');
        $data = array(
            'status' => 1,
            'truename' => $data['truename'],
            'tel' => $data['tel'],
            'address' => $data['address'],
            'update_time' => t_time()
        );
        $this->db->where('id', $id);
        $affect = $this->db->update('zy_st_message', $data);
        if($affect){
            t_json();
        }else{
            t_error();
        }

    }

    

}