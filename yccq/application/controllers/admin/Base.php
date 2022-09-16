<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/* 
 * 后台基础类，其他类必须继承该类  code by tangjian
 */
class Base extends CI_Controller
{
    public $uid = 0; // 管理员id
    public $admin = array(); // 管理员信息
    
    function __construct ()
    {
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('admin/log_admin_model');
        $this->load->model('admin/user_model');
        $this->load->library('session');
        $this->load->library('user_agent');
        $this->load->config('config_admin');
        
        //session_start();
        //header("Cache-control: private");
        $this->admin = $this->session->userdata('admin');
        if(!empty($this->admin)) {
            $this->uid = $this->admin['id'];
        }

        // 后台访问日志
        //$this->log_admin_model->logs();
    }

    // 保存上一级网址,通过session $this->admin['url_forward'] 上页的URL,用来 修改和 删除后做跳转的
    function url_forward($url){
        $this->admin['url_forward'] = $url;
        $this->session->set_userdata ( 'admin', $this->admin );
    }

}
