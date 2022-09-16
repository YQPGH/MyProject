
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Date: 2020/03/22
 *
 */

class Chat extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('api/chat_model');

    }




    function index()
    {
        $data['uid'] = '10e4bf3ae3ee123351c5921f9f167bdd';
        $data['friend_uid'] = '5ab22a008af355e3a1af8b41036a3fa9';

        $result = $this->chat_model->queryMessage($data['uid'],$data['friend_uid']);

        $data['nickname'] = $result['nickname'];
        $data['fnickname'] = $result['fnickname'];
        $data['list'] = $result['list'];
//print_r($result);exit;
        $this->load->view('chat',$data);

    }

    function chatB()
    {
        $data['uid'] = '5ab22a008af355e3a1af8b41036a3fa9';
        $data['friend_uid'] = '10e4bf3ae3ee123351c5921f9f167bdd';
        $result = $this->chat_model->queryMessage($data['uid'],$data['friend_uid']);
        $data['nickname'] = $result['nickname'];
        $data['fnickname'] = $result['fnickname'];
        $data['list'] = $result['list'];
        $this->load->view('chat_a',$data);
    }

    function chatC()
    {
        $data['uid'] = '544db551789f6a28b3b3d4a000e18c60';
        $data['friend_uid'] = '10e4bf3ae3ee123351c5921f9f167bdd';
        $result = $this->chat_model->queryMessage($data['uid'],$data['friend_uid']);
        $data['nickname'] = $result['nickname'];
        $data['fnickname'] = $result['fnickname'];
        $data['list'] = $result['list'];

        $this->load->view('chat_c',$data);
    }



    function sendMsg()
    {
        $uid = $this->input->post('uid');
        $friend_uid = $this->input->post('friend_uid');
        $msg = filter_keyword($this->input->post('content'));
        $status = $this->input->post('is_read');
        if(empty($msg)) t_error(1,'发送内容为空，请重新输入');
        $result = $this->chat_model->sendMsg($uid,$friend_uid,$msg,$status);
        t_json($result);
    }
}


