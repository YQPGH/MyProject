<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 叠老鼠 模型
 */
include_once 'Base_model.php';

class Mouse_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_diemouse';
    }



}
