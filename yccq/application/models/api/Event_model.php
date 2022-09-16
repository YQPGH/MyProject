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

    // 为土地列表增加 事件状态
    function append_list($list)
    {
        foreach ($list as &$land) {
            $land['event_status'] = 0;
            $row = $this->row(['uid' => $land['uid'], 'land_id' => $land['id']]);
            if ($row) {
                $land['event_status'] = $row['type2'];
            }
        }
        
        return $list;
    }
    


}
