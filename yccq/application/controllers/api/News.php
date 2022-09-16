<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 资讯通知
include_once 'Base.php';

class News extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/news_model');
    }

    // 列表
    public function lists()
    {
        $list = $this->news_model->lists('id,title,thumb,add_time');

        t_json($list);
    }

    // 详情
    public function detail()
    {
        $id = intval($this->input->post('id'));
        if (!$id) t_error(1, '信息为空');

        $value = $this->news_model->row($id);

        t_json($value);
    }

}