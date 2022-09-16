<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  统计
 */
include_once 'Base_model.php';

class Stat_day_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_stat_day';
    }

}
