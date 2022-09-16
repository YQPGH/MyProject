<?php defined('BASEPATH') OR exit('No direct script access allowed');
// header('Content-type:text/json');

/* 
 *  游戏客户端基础控制器，其他类继承这个
 */

class Base extends CI_Controller
{
    public $uid = '';

    function __construct()
    {
        parent::__construct();

//        $start_time = strtotime('2020-09-29 23:00:00');
//        $time = strtotime('2020-09-30 07:30:00');
//        if(time()>$start_time && time()< $time){
//            t_error(1, '系统维护中，请稍后再来');
//            return;
//        }

        $this->uid = $this->input->post('uid');
        if (!$this->uid) t_error(1, '用户ID不能为空');
        // 判断请求来源
        //if (strpos($_SERVER['HTTP_REFERER'], '192.168.1.178') === false) t_error(2, '没有权限');
    }
}
