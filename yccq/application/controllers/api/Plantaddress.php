<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User: y'q'p
 * Date: 2020/3/2
 * Time: 12:37
 */

//include_once 'Base.php';
class Plantaddress extends CI_Controller
{
    function __construct()
    {

        parent::__construct();
        $this->load->model('api/plantaddress_model');
    }

    function savemessage(){
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $pid = trim($this->input->post('id'));
        $truename = $this->input->post('truename');
        $phone = $this->input->post('phone');
        $address = $this->input->post('address');
        $code = $this->input->post('code');
        $phone = is_mobile_phone($phone);
        if(!$phone) t_error(2,'请输入有效号码');
        if(empty($truename) || empty($address)) t_error(3,'信息不全，请重新填写！');

        $result = $this->plantaddress_model->savemessage($uid,$pid,$truename,$phone,$address,$code);
        t_json();
    }


    function getUsermessage(){

//        $uid = 'abcc';

        $uid = $_SESSION['uid'];
        if (!$uid) t_error(11, '用户ID不能为空');
        $id = trim($_REQUEST['id']);
//        $id = 15;
        $result = $this->plantaddress_model->getUsermessage($uid,$id);
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


        $this->load->view("client/plantranking_address",$result);

    }


}