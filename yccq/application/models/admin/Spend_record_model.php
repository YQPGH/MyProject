<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  土地
 */
include_once 'Base_model.php';

class Spend_record_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_shop';
    }




}
