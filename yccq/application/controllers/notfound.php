<?php defined('BASEPATH') OR exit('No direct script access allowed');


class notfound extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    public function _404()
    {
        //show_error('对不起，您访问的页面不存在',404,'Sorry');
        $this->load->view('errors/index.html');
    }

}
