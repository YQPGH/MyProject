<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 过滤词库
 */
class Filterwords extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('common/filterwords_model');

    }


    function getMain()
    {
//        $content = ' 的傻';
//        $result = $this->filterwords_model->getMain($content);
//        print_r($result);

    }


}
