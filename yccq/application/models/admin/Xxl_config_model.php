<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  xxl
 */
include_once 'Base_model.php';

class Xxl_config_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'xxl_prize_config';
    }





}
