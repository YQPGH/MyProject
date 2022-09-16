<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 订单排行 模型
 */
include_once 'Base_model.php';

class Order_rank_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_order_rank_record';
    }



}
