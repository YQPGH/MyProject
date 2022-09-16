<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 我的物品
include_once 'Base.php';

class Store extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/store_model');
    }

    // 列表
    public function lists()
    {
        $type1 = $this->input->post('type1');
        $type2 = $this->input->post('type2');
        $list = $this->store_model->list_all($this->uid, $type1, $type2);
        
        t_json($list);
    }

    // 列表分组
    public function lists_group()
    {
        $list = $this->store_model->lists_group($this->uid);

        t_json($list);
    }

    // 单个详情
    public function detail()
    {
        $shopid = intval($this->input->post('shopid'));
        if (!$shopid) t_error(1, '商品id不能为空');

        $value = $this->store_model->detail($this->uid, $shopid);

        t_json($value);
    }

    // 出售物品给商行
    public function sale()
    {
        $shopid = intval($this->input->post('shopid'));
        $total = intval($this->input->post('total'));
        if (!$shopid || $total < 1) t_error(1, '参数不能为空');

        $value = $this->store_model->sale($this->uid, $shopid, $total);

        t_json($value);
    }

    // 升级仓库
    public function upgrade()
    {
        $value = $this->store_model->upgrade($this->uid);

        t_json($value);
    }




}