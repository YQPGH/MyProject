<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  土地事件
 */
include_once 'Base_model.php';

class Event_land_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_event_land';
        $this->load->model('api/user_model');
        $this->load->model('api/event_model');
    }

    // 查询土地事件状态
    function status($uid)
    {
        $today = $this->time->today();

        // 首次不开启
        $row = $this->row(['uid' => $uid]);
        if (empty($row)) {
            $this->insert([
                'uid' => $uid,
                'status' => 0,
                'update_time' => $today,
            ]);
            return 0;
        }

        //指引未完成，不触发事件
        $guide = $this->column_sql('step1',array('uid'=>$uid),'zy_guide',0);
        if($guide['step1'] < 10) return 0;

        // 是今天，直接返回状态
        if ($row['update_time'] == $today) {
            return (int)$row['status'];
        }

        // 判断三天内只触发一次
        $day = $this->time->days_between($row['update_time'], $today);
        if ($day < 3) {  // 关闭
            $this->update([
                'status' => 0,
            ], ['uid' => $uid]);
            return 0;
        }

        if ($day >= 7) { // 开启
            $status = rand(1, 2);
            $this->update([
                'status' => $status,
                'update_time' => $today,
            ], ['uid' => $uid]);
            return $status;

        } else { // 3-7天 随机开启
            $status = rand(0, 2);
            $data['status'] = $status;
            if ($status > 0) $data['update_time'] = $today;
            $this->update($data, ['uid' => $uid]);
            return $status;
        }
    }


    // 用户每天开始种植50次触发一次
    function start($uid, $land_id)
    {
        $status = $this->status($uid);
        if ($status == 0) return 0;

        $title_array = [
            1 => '田里突然出现了好多虫子,请尽快处理',
            2 => '地都有点干旱了，请尽快处理'
        ];

        // 检查是否累计50块田触发事件
        $total = $this->table_count('zy_seed_record', ['uid' => $uid]);
        if ($total > 1 && $total % 50 == 0) {
            $this->event_model->insert([
                'type1' => 1,
                'type2' => $status,
                'uid' => $uid,
                'land_id' => $land_id,
                'title' => $title_array[$status],
                'status' => 0,
                'add_time' => t_time(),
            ]);
            return $status;
        } else {
            return 0;
        }
    }



}
