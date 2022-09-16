<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  报警事件
 */
include_once 'Base_model.php';

class Event_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_event';
    }

    


}
