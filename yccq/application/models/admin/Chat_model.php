<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 活动 模型
 */
include_once 'Base_model.php';

class Chat_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'chat_detail';
    }



}
