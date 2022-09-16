<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  路边摊
 */
include_once 'Base_model.php';

class Market_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_market';
    }




}
