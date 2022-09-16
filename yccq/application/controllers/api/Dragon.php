<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**社群养龙
 * User: y'q'p
 * Date: 2020/3/2
 * Time: 12:37
 */

//include_once 'Base.php';
class Dragon extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/dragon_model');
    }

    function user_detail()
    {

        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->dragon_model->user_detail($uid);
        t_json($result);
    }

    function team_ranking()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->dragon_model->team_ranking($uid);
        t_json($result);
    }

    function feed()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->dragon_model->feed($uid);
        t_json();
    }

    function task_list()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');

        $result = $this->dragon_model->task_list($uid);
        t_json($result);
    }

    function task_receive()
    {
        $uid = $this->input->post('uid');
        if (!$uid) t_error(1, '用户ID不能为空');
        $task_id = intval($this->input->post('id'));
        $result = $this->dragon_model->task_receive($uid,$task_id);
        t_json();
    }

    function leave()
    {
        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
        $result = $this->dragon_model->leave($uid);
        t_json();
    }

    function team_list()
    {

        $uid = $this->input->post('uid');
//        if (!$uid) t_error(1, '用户ID不能为空');
        $uid =  "abcc";
        $result = $this->dragon_model->team_list($uid);
//        t_json($result);

        $this->load->view('test', $result );
    }

    function onMessage()
    {
        $uid =  "abcc";

        $this->dragon_model->onMessage($uid);
//        $this->load->view('test');
    }

    function test(){
        $this->load->view('test_a');
    }

    function push_msg()
    {
        $uid = '';
        $result = $this->dragon_model->push_msg($uid);
        t_json($result);
    }
}