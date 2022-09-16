<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User: Administrator
 * Date: 2020/11/16
 * Time: 15:36
 */
/*
方案名称：方案A
GoodsNumber:TLJPLTBOBJB
BrandValue:KBBRPP
烟草传奇渠道号：105
MD5Key:e0I1w53RSop2qDu4
AesKey:kgvyr0tparUglj2m
*/
class Orderprize extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/orderprize_model');
    }

    function orderNumberQuery()
    {

        $data['uid'] = $_REQUEST['uid'];
        $data['ordernum'] = $_REQUEST['ordernum'];
       if($data['uid'] || $data['ordernum'])
       {
           $result = $this->orderprize_model->orderNumberQuery($data);
       }
        else
        {
            $result['data'][] = [
                "acceptTime"=> "",
                "acceptAddress"=> "",
                "remark"=> "",
                "statusP"=>""
            ];
        }
        $result['uid'] = $data['uid'];
        $result['ordernum'] = $data['ordernum'];

        $this->load->view('admin/orderprize_list',$result);

    }



}