<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 节假日活动 模型
 */
include_once 'Base_model.php';

class Holiday_activities_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_holiday_config';
    }



}
