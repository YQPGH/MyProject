<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User: Administrator
 * Date: 2020/8/20
 * Time: 17:16
 */

include_once 'Base.php';
class Midautumn extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/midautumn_model');
    }

    function activity_time(){
        $time = $this->midautumn_model->activity_time();
        t_json($time);
    }

    function get_user()
    {

        $result = $this->midautumn_model->get_user($this->uid);
        t_json($result);
    }


    function compose()
    {
        $total = intval($this->input->post('total'));
        if(!$total) t_error(9,'制作失败!');
        $result = $this->midautumn_model->compose($this->uid,$total);
        t_json($result);
    }

    //任务列表
    function lists(){

        $result = $this->midautumn_model->task_list($this->uid);
        t_json($result);
    }

    function task_receive(){

        $id = intval($this->input->post('id'));
        if($id == '' ) t_error(1,'操作有误');
        $result = $this->midautumn_model->task_receive($this->uid,$id);
        t_json($result);
    }


    function prize_list()
    {

        $result = $this->midautumn_model->prize_list($this->uid);
        t_json($result);
    }


    /**
     * 抽奖
     */
    function get_prize()
    {
        $total = intval($this->input->post('total'));

        if(!$total) t_error(9,'供奉失效!');
        $result = $this->midautumn_model->get_prize($this->uid,$total);
        t_json($result);
    }

    /**
     * 抽奖记录
     */
//    function prize_record()
//    {
//
//        $result = $this->midautumn_model->prize_record($this->uid);
//        t_json($result);
//    }


    function test()
    {
      $this->midautumn_model->test();

    }


}