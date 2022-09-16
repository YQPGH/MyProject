<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  配方研究所
 */
include_once 'Base_model.php';

class Peifang_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_peifang';
    }




}
