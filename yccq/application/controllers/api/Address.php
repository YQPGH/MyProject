<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Address extends CI_Controller
{


    function __construct()
    {
        parent::__construct();

        $this->load->model('api/address_model');
    }

    function get_address(){
        $name = $this->input->post('name');
        $province = $this->input->post('province_code');
        $city = $this->input->post('city_code');
        $area = $this->input->post('area_code');

        $result  = $this->address_model->get_address($name,$province,$city,$area);

        t_json($result);
    }

    function getUsermessage(){
        $uid = $_SESSION['uid'];
//      $uid = '10e4bf3ae3ee123351c5921f9f167bdd';

        $type = $_REQUEST['type1'];
     // $type = 'run';
        if (!$uid) t_error(11, '用户ID不能为空');
        $id = trim($_REQUEST['id']);
//        $id = 86;
        $result = $this->address_model->getUsermessage($uid,$id,$type);
        $result['uid'] = $uid;
        $result['id'] = $id;
        $this->load->model('api/address_model');
        $address = $this->address_model->get_province($result['province_code'],$result['city_code'],$result['area_code']);
        $result['province_name'] = $address['province'];
        $result['city_name'] = $address['city'];
        $result['area_name'] = $address['area'];
        if($result['province_code']){
            unset($result['province_code'],$result['city_code'],$result['area_code']);
        }
//print_r($result);exit;
       $this->load->view("address/trees_address",$result);


    }

    function savemessage(){

        $data = [
            'uid' => $this->input->post('uid'),
            'id' => trim($this->input->post('id')),
            'truename'=> $this->input->post('truename'),
            'phone' => is_mobile_phone($this->input->post('phone')),
            'address' => $this->input->post('address'),
            'code' => $this->input->post('code'),
            'type' => $this->input->post('type')
        ];

        if (!$data['uid']) t_error(11, '用户ID不能为空');
        if(!$data['phone']) t_error(1,'请输入有效号码');

        if(empty($data['truename']) || empty($data['address'])) t_error(3,'信息不全，请重新填写！');

        $result = $this->address_model->savemessage($data);
        t_json();
    }

}
