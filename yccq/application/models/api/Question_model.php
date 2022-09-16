<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 每日问题
 */
include_once 'Base_model.php';

class Question_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_question_config';
        $this->load->model('api/setting_model');
    }

    // 随机获取一题, 每天不能重复
    function lists_today($uid)
    {
        //$this->set_today();//查看今天题库(zy_settint)是否有题目记录。正式上线由定时器处理
        //获取玩家今天答过的题目
        $day = t_time(0, 0);
//        $answered = $this->db->query("select qid from zy_question WHERE uid='$uid' AND add_time='$day'")->result_array();
        $where = "uid='$uid' AND add_time='$day'";
        $answered = $this->column_sql('qid',$where,'zy_question',1);
        $questions = $this->setting_model->get('question_today');//获取今天的题目
        if (count($answered)) {
            $answered_ids = array();
            foreach ($answered as $k => $v) {
                $answered_ids[] = $v['qid'];
            }
            $questions_arr = explode(',', $questions);
            $temp = array_diff($questions_arr, $answered_ids);
            //答题正确率
//          $right_num = $row = $this->row_sql("SELECT COUNT(id) right_num FROM zy_question WHERE uid='{$uid}' AND add_time='{$day}' AND `right`=1 LIMIT 100;");
            $sql = "SELECT COUNT(id) right_num FROM zy_question WHERE uid=? AND add_time='{$day}' AND `right`=1 LIMIT 100";
            $right_num = $row = $this->db->query($sql,[$uid])->result_array();
            if (count($temp)) {
                $str_ids = '';
                foreach ($temp as $key => $value) {
                    $str_ids .= $value . ',';
                }
                $str_ids = rtrim($str_ids, ',');
                $data['list'] = $this->lists_sql("SELECT * FROM zy_question_config WHERE status=0 AND id in({$str_ids}) LIMIT 5");
                $data['is_finish'] = 0;
                $data['finish_num'] = 5 - count($temp);
                $data['finish_num'] = 5 - count($temp);
                $data['questions_num'] = 5;
            } else {
                $data['list'] = array();
                $data['is_finish'] = 1;
                $data['finish_num'] = 5;
                $data['questions_num'] = 5;
            }
            $data['right_num'] = $right_num['right_num'];
        } else {
           $data['list'] = $this->lists_sql("SELECT * FROM zy_question_config WHERE status=0 AND id in({$questions}) LIMIT 5");
            //$get_where = "status=0 AND id in({$questions})";
            //$data['list'] = $this->get_row('zy_question_config','*',$get_where,5);
            $data['is_finish'] = 0;
            $data['finish_num'] = 0;
            $data['questions_num'] = 5;
            $data['right_num'] = 0;
        }

        foreach ($data['list'] as &$value) {
            unset($value['answer']);
        }

        return $data;
    }

    // 回答题目
    function answer($uid, $id, $option)
    {
        $is_return = model('building_model')->query_upgrade($uid,15);
        if($is_return['is_upgrade']==1) t_error(6,'建筑升级中');
        // 限制每天只能回答一次
        $answer = $this->table_row('zy_question', ['qid' => $id, 'uid' => $uid]);
        if ($answer && $this->time->day($answer['add_time']) == $this->time->today()) {
            t_error(1, '该题目您已经回答了，请明天再来');
        }

        $questions = $this->setting_model->get('question_today');
        $questions = explode(',', $questions);
        if (!in_array($id, $questions)) t_error(1, '不是今天可回答的题目');

        $question = $this->row($id);
        if (!$question) t_error(1, '错误的题目');

        // 写入记录表
        $data = [
            'uid' => $uid,
            'qid' => $id,
            'right' => $question['answer'] == $option ? 1 : 0,
            'add_time' => $this->time->now(),
        ];

        $this->table_insert('zy_question', $data);

        // 获得奖励
        //$this->user_model->money($uid, 10, 0);

        return ['right' => $data['right']];
    }

    // 设置今日5题到设置表
    function set_today()
    {
        $ids = [];
//        $list = $this->lists_sql("SELECT id FROM zy_question_config WHERE status=0 LIMIT 100");
        $sql = "SELECT id FROM zy_question_config WHERE  status = ? limit =100";
        $list = $this->db->query($sql, array(0))->result_array();
        $rands = array_rand($list, 5);
        foreach ($rands as $key) {
            $ids[] = $list[$key]['id'];
        }
        $str = join(',', $ids);
        $temp = $this->setting_model->set('question_today', $str);
        return $temp;
        //return $str;
    }

    // 获取结果信息和奖品
    function result($uid)
    {
        model('prize_model');
        $this->load->model('api/store_model');
        $today = $this->time->today();
//        $is_finish = $this->row_sql("SELECT COUNT(id) total FROM zy_question WHERE uid='{$uid}' AND add_time='{$today}'");
        $sql = "SELECT COUNT(id) total FROM zy_question WHERE uid=? AND add_time= ? ";
        $is_finish = $this->db->query($sql, array($uid,$today))->row_array();
        // 判断是否答完5道题
       if ($is_finish['total'] < 5) {
           t_error(1, '题目未答完');
       }
        //判断是否领奖
//        $last_row = $this->db->query("select * from zy_question_prize_record WHERE uid='$uid' ORDER BY id DESC")->row_array();
        $sql = "select * from zy_question_prize_record WHERE uid=? ORDER BY id DESC";
        $last_row = $this->db->query($sql, array($uid))->row_array();
        $last_time = strtotime($last_row['add_time']);
        $today_time = strtotime(date('Y-m-d'));
        $time = $this->db->query("select UNIX_TIMESTAMP(start_time) start_time,UNIX_TIMESTAMP(end_time) end_time from zy_activity_config WHERE `name`=?",['question'])->row_array();

        if ($last_time > $today_time) {
           //$row = $this->row_sql("SELECT COUNT(id) total FROM zy_question WHERE uid='{$uid}' AND add_time='{$today}' AND `right`=1 LIMIT 100;");
           $sql = "SELECT COUNT(id) total FROM zy_question WHERE uid=? AND add_time='$today' AND `right`=1 LIMIT 100";
            $row = $this->db->query($sql, array($uid))->row_array();

            if ($row) {

                if(time()>$time['start_time'] && time()<$time['end_time']){
                    $prize = $this->db->query("select money,ledou,json_data from zy_prize WHERE `type1`=? and `type3`=?",['question',$row['total']])->row_array();
                    $json_data = json_decode($prize['json_data']);
                    unset($prize['json_data']);
                    $result = $prize;
                    for($i=0;$i<3;$i++)
                    {
                        $value['shopid'] = $json_data[$i]->shop;
                        $value['shop_num'] = $json_data[$i]->shop_num;
                        $result['shop'][] = $value;
                    }
                }
                else
                {
                    // 奖品
                    if ($row['total'] == 1 || $row['total'] == 2) {
                        $prize = $this->prize_model->row(8);
                    } else if ($row['total'] == 3 || $row['total'] == 4) {
                        $prize = $this->prize_model->row(9);
                    } else if ($row['total'] == 5) {
                        $prize = $this->prize_model->row(10);
                    }
                    $result['money'] = $prize['money'];
                    $result['ledou'] = $prize['ledou'];
                    $result['money'] = $prize['money'];
                    $prizedata['shopid'] = $prize['shop1'];
                    $prizedata['shop_num'] = $prize['shop1_total'];
                    $result['shop'][] = $prizedata;


                }

                $result['right'] = $row['total'];
            }
            t_error(1, '已领奖', $result);
        } else {

//            $row = $this->row_sql("SELECT COUNT(id) total FROM zy_question WHERE uid='{$uid}' AND add_time='{$today}' AND `right`=1 LIMIT 100;");
            $sql = "SELECT COUNT(id) total FROM zy_question WHERE uid=? AND add_time='$today' AND `right`=1 LIMIT 100";
            $row = $this->db->query($sql, array($uid))->row_array();
            if ($row['total'] > 0) {
                if(time()>$time['start_time'] && time()<$time['end_time']){
                    $prize = $this->db->query("select id,money,ledou,json_data,xp,shandian from zy_prize WHERE `type1`=? and `type3`=?",['question',$row['total']])->row_array();
                    $json_data = json_decode($prize['json_data']);

                    for($i=0;$i<3;$i++)
                    {
                        $value['shopid'] = $json_data[$i]->shop;
                        $value['shop_num'] = $json_data[$i]->shop_num;
                        $result['shop'][] = $value;
                        $this->store_model->update_total($json_data[$i]->shop_num, $uid, $json_data[$i]->shop);
                    }


                }
                else
                {
                    // 奖品
                    if ($row['total'] == 1 || $row['total'] == 2) {
                        $prize = $this->prize_model->row(8);
                    } else if ($row['total'] == 3 || $row['total'] == 4) {
                        $prize = $this->prize_model->row(9);
                    } else {
                        $prize = $this->prize_model->row(10);
                    }


                    $prizedata['shopid'] = $prize['shop1'];
                    $prizedata['shop_num'] = $prize['shop1_total'];
                    $result['shop'][] = $prizedata;
                }

                $result['money'] = $prize['money'];
                $result['ledou'] = $prize['ledou'];
                $result['right'] = $row['total'];

                $this->db->trans_start();

                $data = array(
                    'uid' => $uid,
                    'reward_type' => 2,
                    'prize_id' => $prize['id'],
                    'money' => $prize['money'],
                    'ledou' => $prize['ledou'],
                    'shandian' => $prize['shandian'],
                    'xp' => $prize['xp'],
                    'add_time' => t_time()
                );
                $this->db->insert('zy_question_prize_record', $data);

                // 将奖品存入仓库
                // 银元、乐豆增加
                if ($prize['money'] || $prize['ledou']) {
                    $this->user_model->money($uid, $prize['money'], $prize['ledou']);
                }
                //奖励商品
                if ($prize['shop1']) {

                    $this->store_model->update_total($prize['shop1_total'], $uid, $prize['shop1']);
                }

                //奖励经验
                if ($prize['xp']) {
                    $this->load->model('api/user_model');
                    $this->user_model->xp($uid, $prize['xp']);
                }

                $this->db->trans_complete();


            } else {
                t_error(1, '你今日未答对题');

            }
        }

       
        return $result;
    }

}
