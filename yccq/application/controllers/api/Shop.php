<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 商行
include_once 'Base.php';

class Shop extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/shop_model');
    }
    
    // 全部商品列表，商行可见
    public function lists()
    {
        $list = $this->shop_model->list_shop($this->uid);

        t_json($list);
    }

    // 全部物品列表
    public function lists_all()
    {
        $list = $this->shop_model->list_all();

        t_json($list);
    }

    // 全部物品列表
    public function lists_group()
    {

        $list = $this->shop_model->lists_group();

        t_json($list);
    }

    // 单个商品详情
    public function detail()
    {
        $id = intval($this->input->post('id'));
     
        if (!$id) t_error(1, '商品信息为空');

        $value = $this->shop_model->detail($id);
        
        t_json($value);
    }

    // 购买商品
    public function buy()
    {
        $uid = $this->input->post('uid');
        $shopid = intval($this->input->post('shopid'));
        $total = intval($this->input->post('total'));
        if(($shopid==1501 || $shopid==1502 || $shopid==1503) && $total>1)  t_error(2,'不能购买多条！');
        if (!$uid || !$shopid || !$total) t_error(1, '参数不能不能为空');

        $result = $this->shop_model->buy($uid, $shopid, $total);

        t_json($result);
    }


    //神秘商店个人刷新
    public function my_refresh(){
        $result = $this->shop_model->my_refresh($this->uid);
        t_json($result);
    }

}