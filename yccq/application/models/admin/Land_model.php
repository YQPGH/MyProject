<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  土地
 */
include_once 'Base_model.php';

class Land_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_land';
    }




}
