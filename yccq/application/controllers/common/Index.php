<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

// 首页 文章
include 'Base.php';

class Index extends Base
{
    function __construct()
    {
        parent::__construct();
    }

    // 网站首页
    public function index()
    {
        $this->load->model('setting_model');
        $this->load->model('video_model');
        $this->load->model('ad_model');

        // 幻灯片
        $data['list_swiper'] = $this->ad_model->list_swiper();
        
        // 友情链接
        $this->load->model('friendlink_model');
        $data['friendlink'] = $this->friendlink_model->lists();

        // 推荐
        $data['list_recommend'] = $this->video_model->list_recommend(10);

        // 分类1
        $data['list1'] = $this->video_model->list_catid(3,8);
        $data['list1_hot'] = $this->video_model->list_hot(3,10);
        foreach ($data['list1_hot'] as &$value) {
            $value['title'] = mb_strcut($value['title'], 0,33, 'UTF-8' );
            $value['title2'] = mb_strcut($value['title2'],0, 40,'UTF-8' );
        }

        $data['title'] = HEAD_TITLE;
        $data['keywords'] = $this->setting_model->get('keywords');
        $data['description'] = $this->setting_model->get('description');
        $data['nav_index'] = 'active';
        $this->load->view('home/index', $data);
    }

    function app() {
        $this->load->view('home/app');
    }

    function down_android() {
        $this->load->model('version_model');
        $value = $this->version_model->android_latest();
        redirect($value['url']);
    }

}
