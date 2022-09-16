<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  通用状态
 */
include_once 'Base_model.php';

class Status_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_status';
        $this->load->model('api/shop_model');
    }

    // 详情
    function detail($uid)
    {
        $value = $this->row(['uid' => $uid]);
        if ($value['bake_status'] == 1 && $value['bake_stop'] <= t_time()) {
            $value['bake_status'] = 2;
        }
        if ($value['aging_status'] == 1 && $value['aging_stop'] <= t_time()) {
            $value['aging_status'] = 2;
        }
        if ($value['process_status'] == 1 && $value['process_stop'] <= t_time()) {
            $value['process_status'] = 2;
        }
        $id = $value['id'];
        unset($value['id']);
        $this->update($value, $id);

        return $value;
    }

    //获取玩家制烟等级，仓库等级
    function getLv($uid){
        $res = $this->column_sql('zhiyan_lv,store_lv',['uid'=>$uid],'zy_user',0);
        return $res;
    }

}
