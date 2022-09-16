<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  仓库
 */
include_once 'Base_model.php';

class Store_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_store';
    }




}
