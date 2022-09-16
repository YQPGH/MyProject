<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  种子培育
 */
include_once 'Base_model.php';

class Peiyu_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_peiyu';
    }




}
