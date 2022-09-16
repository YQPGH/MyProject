<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/10/25
 * Time: 11:43
 */
include_once 'Base.php';
class MsgSender extends Base{
    function __construct(){
        parent::__construct();
        $this->load->model('api/MsgSender_model');
    }

    function sending(){
        $result = $this->MsgSender_model->msg_send($this->uid);
        t_json($result);
    }
}