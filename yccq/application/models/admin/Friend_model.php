<?php if (!defined('BASEPATH'))  exit ('No direct script access allowed');

/**
 *  好友
 */
include_once 'Base_model.php';

class Friend_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_friend';
    }




}
