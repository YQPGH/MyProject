<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  新手引导
 */
include_once 'Base_model.php';

class Guide_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_guide';
        $this->load->model('api/user_model');
        $this->load->model('api/store_model');
    }

    // 获取当前步骤记录
    function status($uid)
    {
        $row = $this->row(['uid' => $uid]);
        if (!$row) {
            $this->insert(['uid' => $uid]);
            $row = $this->row(['uid' => $uid]);
        }

        return $row;
    }

    //  更新步骤
    function update_step($uid, $step1, $step2 = 0)
    {
        if ($step1 >= 15) t_error(2, '步骤更新有误，请稍后再来');

        // 验证顺序
        $row = $this->row(['uid' => $uid]);
        //if ($step1 != $row['step1'] + 1) t_error(1, '步骤更新有误，请稍后再来');

        $this->db->trans_start();
        $result = $this->update([
            'step1' => $step1,
            'step2' => $step2,
            'update_time' => t_time(),
        ], ['uid' => $this->uid]);

        if ($step1 == 10) {
            $guide_finish = $this->column_sql('guide_finish',['uid'=>$uid],'zy_user',0);
            if($guide_finish['guide_finish'] == 0){
                $this->finish_gift($uid);
            }
            $result = $this->user_model->update([
                'guide_finish' => 1,
                'update_time' => t_time(),
            ], ['uid' => $this->uid]);

        }
        $this->db->trans_complete();
        if (!$result) t_error(3, '步骤更新错误，请稍后再试');

        return $result;
    }

    // 关闭建筑提示
    function close_tips($uid, $building)
    {
        $result = $this->update([
            $building => 1,
            'update_time' => t_time(),
        ], ['uid' => $this->uid]);

        if (!$result) t_error(3, '步骤更新错误，请稍后再试');

        return $result;
    }


    // 引导完成后礼包
    function finish_gift($uid)
    {
        $this->db->trans_start();
        $this->store_model->update_total(3, $uid, 607);   //一星基础调香书*3
        $this->store_model->update_total(10, $uid, 202);  //一星巴西种子*10
        $this->store_model->update_total(5, $uid, 203);   //一星云贵种子*5
        $this->store_model->update_total(5, $uid, 204);   //一星吕宋种子*5
        $this->store_model->update_total(5, $uid, 1007);   //一点红嘴棒*5
        $this->db->trans_complete();
    }


}
