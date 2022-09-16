<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 路边摊
include_once 'Base.php';

class Market extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/market_model');
    }

    // 发布出售
    public function start()
    {
        $shopid = intval($this->input->post('shopid'));
        $total = intval($this->input->post('total'));
        $money = intval($this->input->post('money'));
        if (!$shopid || !$total || !$money) t_error(1, '参数不能为空，请检查');
        if ($total <= 0 || $money <= 0) t_error(2, '参数必须大于0，请检查');
        if ($total > 10) t_error(3, '数量不能大于10，请检查');

        $result = $this->market_model->start($this->uid, $shopid, $total, $money);

        t_json($result);
    }

    // 到期手动下架
    public function stop()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '参数不能为空，请检查');

        $result = $this->market_model->stop($this->uid, $number);

        t_json($result);
    }

    // 已售格子清空
    public function sold()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '参数不能为空，请检查');

        $result = $this->market_model->sold($this->uid, $number);

        t_json($result);
    }

    // 购买别人的
    public function buy()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '参数不能为空，请检查');
        
        $result = $this->market_model->buy($this->uid, $number);

        t_json($result);
    }

    // 所有列表
    public function list_all()
    {
        $page = intval($this->input->post('page'));
        if (!$page) $page = 1;
        $result = $this->market_model->list_all($this->uid, $page);
        $data = [
            'page' => $page,
            'list' => $result,
        ];
        t_json($data);
    }
    
    // 我的在售物品
    public function list_my()
    {
        $result = $this->market_model->list_my($this->uid);

        t_json($result);
    }

    // 重新发布广告
    public function restart()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '参数不能为空，请检查');

        $result = $this->market_model->restart($this->uid, $number);

        t_json($result);
    }

    //解锁路边摊
    public function unlock_market(){
        $spend_type = $this->input->post('spend_type');
        if ($spend_type != 'ledou' && $spend_type != 'money') t_error(1, '参数不正确，请检查');
        $result = $this->market_model->unlock_market($this->uid, $spend_type);

        t_json($result);
    }


}