<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  签到记录
 */
include_once 'Base_model.php';

class Sign_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_sign';
    }

}
