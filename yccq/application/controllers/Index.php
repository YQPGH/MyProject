<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 网站首页
class Index extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo '欢迎来到烟草传奇首页<br>';
        echo t_time();
    }


}
