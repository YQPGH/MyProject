<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 *  广告
 */
include_once 'Base_model.php';

class Statday_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_stat_day';
    }



}
