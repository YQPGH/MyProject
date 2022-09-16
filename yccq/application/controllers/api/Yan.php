<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 香烟
include_once 'Base.php';

class Yan extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/yan_model');
    }

    // 品鉴
    public function pinjian()
    {
        $shopid = intval($this->input->post('shopid'));
        if (!$shopid) t_error(1, '参数不能为空，请检查');

        $result = $this->yan_model->pinjian($this->uid, $shopid);

        t_json($result);
    }

    // 升级实体烟
    public function upgrade()
    {
        $shopid = intval($this->input->post('shopid'));
        $quan_shopid = intval($this->input->post('quan_shopid'));
        if (!$shopid) t_error(1, '参数不能为空，请检查');

        $result = $this->yan_model->upgrade($this->uid, $shopid, $quan_shopid);

        t_json($result);
    }

    //获取券列表
    public function quan_lists()
    {
        $result = $this->yan_model->quan_lists($this->uid);
        t_json($result);
    }


    // 三星以下烟兑换积分
    public function jifen()
    {
        $shopid = intval($this->input->post('shopid'));
        $result = $this->yan_model->jifen($this->uid, $shopid);

        t_json($result);
    }




}