<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * 基础控制器，其他继承这个 
 */
class Base extends CI_Controller
{
    public $category = array();
    public $uid = 0;
    public $tel = 0;
    public $nickname = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->category = $this->category_model->lists_format();
        $this->uid = get_cookie('uid');
        $this->tel = get_cookie('tel');
        $this->nickname = get_cookie('nickname');
    }

    // 返回分页信息
    protected function page_html($url, $count)
    {
        $this->config->load('pagination', true);
        $pagination = $this->config->item('pagination');
        $pagination['base_url'] = $url;
        $pagination['total_rows'] = $count;
        $pagination['per_page'] = $this->per_page;
        $this->load->library('pagination');
        $this->pagination->initialize($pagination);

        return $this->pagination->create_links();
    }


}
