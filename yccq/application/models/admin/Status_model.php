<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  建筑状态
 */
include_once 'Base_model.php';

class Status_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_status';
    }




}
