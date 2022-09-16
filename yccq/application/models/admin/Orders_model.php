<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  订单
 */
include_once 'Base_model.php';

class Orders_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_orders';
    }


}
