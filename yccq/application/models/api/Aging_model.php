<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  醇化
 */
include_once 'Base_model.php';

class Aging_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_aging';
        $this->load->model('api/store_model');
        $this->load->model('api/shop_model');
        $this->load->model('api/user_model');
    }

    /**
     *  获取醇化室各个槽的状态
     *
     * @return int
     */
    function lists_status($uid)
    {
        /*$lists = $this->lists_sql("SELECT aging_index,before_shopid,after_shopid,status,start_time,stop_time
                                   FROM zy_aging
                                   WHERE uid='{$uid}'
                                   LIMIT 100;");*/
        $lists = $this->column_sql("aging_index,before_shopid,after_shopid,status,start_time,stop_time",['uid'=>$uid],"zy_aging",1);

        return $lists;
    }

    /**
     * 开始醇化
     *
     * @return int
     */
    function aging_start($uid, $aging_index,$before_shopid)
    {
        // 更新仓库表
        $this->db->trans_start();
        $aging_index = explode(',', $aging_index);
        $ids = explode(',', $before_shopid);
        //判断烘烤烟叶数量是否大于5片
        if (count($aging_index) > 6) t_error(1, '每次最多有4个醇化槽');
        if (count($ids) > 6) t_error(1, '每次最多可醇化6片烟叶');
        $is_return = model('building_model')->query_upgrade($uid,7);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        $message = array();
        $building_time = config_item('building_jiasu');
        foreach ($aging_index as $key=>$index) {
            // 判断醇化室状态
            $status = $this->column_sql('status',['uid'=>$uid,'aging_index'=>$index],'zy_aging',0);
            if ($status['status'] == 0){
                //判断提交的物品是否为烟叶
                $shop = $this->shop_model->detail($ids[$key]);
                if (!empty($shop) || $shop['type1']!='yanye_kao'){
                    $store = $this->store_model->detail($uid, $ids[$key]);
                    if ($store['total'] > 0){
                        $result = $this->store_model->update_total(-1, $uid, $ids[$key]);
                        $time = config_item('aging_time');

                        if ($result){
                            $start_time = $this->sortTime($uid);

                            $stop_time = ($is_return['is_upgrade']==2)?($start_time + $time[$shop['type2']]*$building_time['aging']):($start_time + $time[$shop['type2']]);

                            $this->aging_model->update([
                                'status' => 1,
                                'before_shopid' => $ids[$key],
                                'after_shopid' => $shop['mubiao'],
                                'start_time' => t_time($start_time),
                                'stop_time' => t_time($stop_time),
                            ], ['uid' => $uid,'aging_index'=>$index]);

                            //保存醇化记录
                            $this->table_insert('zy_aging_record', [
                                'uid' => $uid,
                                'aging_shopid' => $ids[$key],
                                'start_time' => t_time($start_time),
                                'stop_time' => t_time($stop_time),
                                'add_time' => time()
                            ]);

                            //添加每日任务
//                            model('task_model')->update_today($uid, 5);

                            $message[$key]['aging_index'] = $index;
                            $message[$key]['before_shopid'] = $ids[$key];
                            $message[$key]['after_shopid'] = $shop['mubiao'];
                            $message[$key]['status'] = 1;
                            $message[$key]['start_time'] = t_time($start_time);
                            $message[$key]['stop_time'] = t_time($stop_time);

                        }else{
                            t_error(2, '你的烟叶库存不够，请稍后再来');
                        }
                    }else{
                        t_error(2, '库存不够了，请稍后再试');
                    }
                }else{
                    t_error(1, '商品或烟叶不存在');
                }
            }else{
                t_error(1, '醇化室忙碌中，请稍后再来');
            }
        }

        // 随机发生事件 3%
        /*$user = $this->user_model->detail($uid);
        $rand_number = rand(1,100);
        if ($rand_number <= 50 && $user['game_lv'] >= 6) {
            $this->event_model->insert([
                'type1' => 2,
                'uid' => $uid,
                'title' => '烘烤室发生事件，请尽快处理。',
                'add_time' => $now_time,
            ]);
        }*/

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(2, '更新失败，系统繁忙请稍后再来');

        return $message;
    }


    /**
     * 计算醇化排队时间
     */
    public function sortTime($uid){
        //$result = $this->db->query("select stop_time from zy_aging WHERE uid='$uid' AND status=1 ORDER BY stop_time DESC limit 1")->row_array();
        $result =  $this->aging_model->column_order_sql("stop_time",['uid'=>$uid,'status'=>1],"zy_aging","stop_time","DESC",1,0,0);
        if(empty($result) || strtotime($result['stop_time']) < time()){
            $time = time();
        }else{
            $time = strtotime($result['stop_time']) + 1;
        }
        return $time;
    }

    public function aging_gather($uid, $aging_index){
        $this->db->trans_start();
        $aging_index = explode(',', $aging_index);
        if (count($aging_index) > 6) t_error(1, '每次最多有4个醇化槽');
        $arr = [];
        $result['list'] = array();
        $is_return = model('building_model')->query_upgrade($uid,7);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        foreach ($aging_index as $key=>$index) {
            // 判断醇化室状态
            $query = $this->column_sql('after_shopid,stop_time',['uid'=>$uid,'aging_index'=>$index],'zy_aging',0);
            if($query['after_shopid']&&$query['stop_time']){
                $time = strtotime($query['stop_time']);
                if($time <= time()){
//                    isset($arr[$query['after_shopid']]) ? $arr[$query['after_shopid']]++ : $arr[$query['after_shopid']] =1 ;
                    $this->store_model->update_total(1, $uid, $query['after_shopid'],1);
                    $this->load->model('api/fragment_model');
//                    $suipian = $this->fragment_model->get_fragment($uid,'aging');
                    $val['after_shopid'] = $query['after_shopid'];
                    $result['list'][] = $val;

                    $this->aging_model->update([
                        'status' => 0,
                        'before_shopid' => 0,
                        'after_shopid' => 0,
                        'start_time' => 0,
                        'stop_time' => 0,
                    ], ['uid' => $uid,'aging_index'=>$index]);
                    //$store = $this->store_model->detail($uid, $query['after_shopid']);
                    ///$result[$key]['shopid'] = $query['after_shopid'];
                    //$result[$key]['num'] = $store['total'];
                    // 经验值增加
                    $xp = 0;
                    $type2 = $this->column_sql('type2',['shopid'=>$query['after_shopid']],'zy_shop',0);
                    if($type2){
                        $config_xp = config_item('aging_xp');
                        $xp = $config_xp[$type2['type2']];
                    }
                    $this->user_model->xp($uid, $xp);
                    //添加每日任务
                    model('task_model')->update_today($uid, 5);
//                    model('coolrun_model')->update_total($uid,1);
                    $suipian = model('midautumn_model')->update_total($uid);
                    model('energytrees_model')->updateTotal($uid,4);
//                    model('nationalday_model')->update_num($uid); //国庆期间任务
                    model('leaf_model')->update_task_value($uid);//叠烟叶每日任务

                    if(count($suipian)>0){
                        $result['suipian'][] = $suipian;
                    }
                }
            }
        }

        if(empty($result['suipian'])){
            $result['suipian'] = array();
        }
        $data = ['uid' => $uid];
        $this->load->model('api/turntable_model');
        $result['draws_times'] = $this->turntable_model->is_activity($data,'aging');

        $this->db->trans_complete();
//        $result[] = array();
//        $i = 0;
//
//        foreach($arr as $k=>$v){
//            $result[$i]['shopid'] = $k;
//            $result[$i]['num'] = $v;
//            $i++;
//        }

        return $result;
    }

    public function aging_jiasu($uid, $aging_index){
        $this->db->trans_start();
        $aging_index = explode(',', $aging_index);
        if (count($aging_index) > 6) t_error(1, '每次最多有6个醇化槽');
        $is_return = model('building_model')->query_upgrade($uid,7);
        if($is_return['is_upgrade']==1) t_error(3,'建筑升级中');
        //获取指引步骤
        //$guid_step_row = $this->db->query("select step1,step2 from zy_guide where uid='$uid'")->row_array();
        $guid_step_row = $this->aging_model->column_sql("step1,step2",['uid'=>$uid],"zy_guide",0);
        $time = t_time();
        foreach($aging_index as $key=>$value){
            $status = $this->table_row('zy_aging', ['uid'=>$uid,'aging_index'=>$value]);
            if(!$status['before_shopid'] || !$status['after_shopid']) t_error(9, '加速失败！');
            $user = $this->user_model->detail($uid);
            //判断所需闪电
            if($guid_step_row['step1']!=6 || $guid_step_row['step2']!=1){
                $number = count_shandian(strtotime($status['stop_time'])-time());
                if ($number > $user['shandian']) t_error(3, '你的闪电不够了，请稍后再来');
                // 扣除闪电
                $this->user_model->shandian($uid, -$number);
                // 写入交易日志表
                model('log_model')->trade($uid, [
                    'spend_type' => 16,
                    'shandian' => -$number,
                ]);
            }
            //更新状态表
            $this->table_update('zy_aging', ['stop_time' => $time],['uid'=>$uid,'aging_index'=>$value]);
            //$res = $this->db->query("select aging_index,start_time,stop_time from zy_aging where uid='$uid' AND status!=0 AND stop_time>'$time' order by start_time ASC")->result_array();
            $res = $this->aging_model->column_order_sql("aging_index,start_time,stop_time",['uid'=>$uid,'status !='=>0,'stop_time >'=>$time],"zy_aging","start_time","ASC",100,0,1);
            //print_r($res);
            if(!empty($res)){
                foreach($res as $k=>&$v){
                    //echo "aging_index=".$v['aging_index'];echo "<br/>";
                    if($k==0){
                        //判断第一个是否已经开始
                        if(strtotime($v['start_time']) > strtotime($time)){
                            //计算时间段
                            $temp_all_time = strtotime($v['stop_time']) - strtotime($v['start_time']);
                            $v['start_time'] = t_time(strtotime($time)+1);
                            $v['stop_time'] = t_time(strtotime($v['start_time'])+$temp_all_time);
                        }
                    }else{
                        //计算时间段
                        $temp_all_time = strtotime($v['stop_time']) - strtotime($v['start_time']);
                        $v['start_time'] = t_time(strtotime($res[$k-1]['stop_time'])+1);
                        $v['stop_time'] = t_time(strtotime($v['start_time'])+$temp_all_time);
                    }

                }
                foreach($res as $kk=>$vv){
                    $this->table_update('zy_aging', ['start_time'=>$vv['start_time'],'stop_time' =>$vv['stop_time']],['uid'=>$uid,'aging_index'=>$vv['aging_index']]);
                }
            }

        }
        $this->db->trans_complete();

        //$result = $this->db->query("select aging_index,start_time,stop_time from zy_aging where uid='$uid' AND status!=0 AND stop_time>'$time' order by start_time ASC")->result_array();
        $result = $this->aging_model->column_order_sql("aging_index,start_time,stop_time",['uid'=>$uid,'status !='=>0,'stop_time >'=>$time],"zy_aging","start_time","ASC",100,0,1);
        return $result;
    }

    function aaa($uid){
        //$this->db->query("select count(*) as num from zy_shop WHERE shopid=$id AND type1='yanye'")->row_array();
        //$result = $this->aging_model->column_sql("count(*) as num",['game_lv>'=>20],"zy_user",0);
        $key = "week_num";
        $this->table = "zy_store";
        //$result = $this->row_sql("select mvalue from `zy_setting` where mkey=? limit 1",[$key]);
        //$result = $this->row_sql("select mvalue from ? where mkey=? limit 1",[$this->table,$key]);
        //return $result;
        //$lists = $this->column_order_sql("id,money,shandian,shopid,shop_num,description",['uid'=>$uid,'status'=>0],"$this->table",'','',100,0,1);
        //return $lists;
        //$type1 = "zhongzi";
        //$type2 = 2;
        //$where = " uid='{$uid}' AND type1='{$type1}' ";
        //if ($type2) $where .= " AND type2='{$type2}' ";
        //$list = $this->db->select('*')->from($this->table)->where($where)->order_by('shopid','ASC')->limit(1000)->row_array();
        //return $this->db->last_query();
        //return $list;
        //$sql = "select * from zy_question_prize_record WHERE uid=? ORDER BY id DESC";
        //$last_row = $this->db->query($sql, array($uid))->result_array();
        //return $last_row;
    }


}
