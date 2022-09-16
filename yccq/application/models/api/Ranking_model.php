<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  周榜、月表
 */
include_once 'Base_model.php';

class Ranking_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'log_shop';
        $this->load->model('api/user_model');
    }

    //本周乐豆消耗排行榜
    public function consumeLDRanking($uid){
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $data['list'] = $this->db->query("SELECT abs(SUM(c.ledou)) AS total,c.nickname FROM (
SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND a.ledou < 0 AND b.uid=a.uid
)
c GROUP BY uid  ORDER BY total DESC LIMIT 100",[$start_time])->result_array();

        $my_ranking = $this->myConsumeLDRanking($uid,$start_time);//本周个人排名
        $data['my_ranking'] = $my_ranking ? $my_ranking : '无';

        $my_pre_ranking = $this->preMyConsumeLDRanking($uid);//上周个人排名
        $data['my_pre_ranking'] = $my_pre_ranking ? $my_pre_ranking : '无';

        //查询用户是否领取过奖励
        $row = $this->db->query("select * from zy_ranking_prize_record where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=1",[$uid,$start_time])->row_array();
        if(!empty($row)){
            $data['is_receive'] = 1;
            $data['prize_list'] = $this->queryRankingPrize();
        }else{
            $data['is_receive'] = 0;
            $data['prize_list'] = $this->queryRankingPrize(1);
        }

        return $data;
    }

    //本周银元消耗排行榜
    public function consumeMoneyRanking($uid){
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $data['list'] = $this->db->query("SELECT abs(SUM(c.money)) AS total,c.nickname FROM (
SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND a.money < 0 AND b.uid=a.uid
)
c GROUP BY uid  ORDER BY total DESC LIMIT 100",[$start_time])->result_array();

        $my_ranking = $this->myConsumeMoneyRanking($uid,$start_time);//个人排名
        $data['my_ranking'] = $my_ranking ? $my_ranking : '无';

        $my_pre_ranking = $this->preMyConsumeMoneyRanking($uid);//上周个人排名
        $data['my_pre_ranking'] = $my_pre_ranking ? $my_pre_ranking : '无';

        //查询用户是否领取过奖励
        $row = $this->db->query("select * from zy_ranking_prize_record where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=2",[$uid,$start_time])->row_array();
        if(!empty($row)){
            $data['is_receive'] = 1;
            $data['prize_list'] = $this->queryRankingPrize();
        }else{
            $data['is_receive'] = 0;
            $data['prize_list'] = $this->queryRankingPrize(1);
        }

        return $data;
    }

    //获取本周乐豆消耗个人排名
    public function myConsumeLDRanking($uid,$start_time){

        $res = $this->db->query("SELECT g.rank FROM (
	SELECT d.nickname,d.uid,d.total,@curRank := @curRank + 1 AS rank FROM (
		SELECT abs(SUM(c.ledou)) AS total,c.uid,c.nickname FROM
		(
			SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND a.ledou < 0 AND b.uid=a.uid
		)
		c GROUP BY uid  ORDER BY total DESC
	)
	d , (SELECT @curRank := 0) f
)
g WHERE g.uid=?",[$start_time,$uid])->row_array();

        return $res['rank'];
    }

    //获取本周银元消耗个人排名
    public function myConsumeMoneyRanking($uid,$start_time){

        $res = $this->db->query("SELECT g.rank FROM (
	SELECT d.nickname,d.uid,d.total,@curRank := @curRank + 1 AS rank FROM (
		SELECT abs(SUM(c.money)) AS total,c.uid,c.nickname FROM
		(
			SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND a.money < 0 AND b.uid=a.uid
		)
		c GROUP BY uid  ORDER BY total DESC
	)
	d , (SELECT @curRank := 0) f
)
g WHERE g.uid=?",[$start_time,$uid])->row_array();

        return $res['rank'];
    }

    //获取上周个人乐豆消耗排名
    public function preMyConsumeLDRanking($uid){
        date_default_timezone_set('Asia/Shanghai');
        $time = time() - 86400 * 7;
        $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
        $end_time = $start_time + 86400 * 7 -1;

        $res = $this->db->query("SELECT g.rank FROM (
	SELECT d.nickname,d.uid,d.total,@curRank := @curRank + 1 AS rank FROM (
		SELECT abs(SUM(c.ledou)) AS total,c.uid,c.nickname FROM
		(
			SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND UNIX_TIMESTAMP(a.add_time) < ? AND a.ledou < 0 AND b.uid=a.uid
		)
		c GROUP BY uid  ORDER BY total DESC
	)
	d , (SELECT @curRank := 0) f
)
g WHERE g.uid=?",[$start_time,$end_time,$uid])->row_array();

        return $res['rank'];

    }

    //获取上周个人银元消耗排名
    public function preMyConsumeMoneyRanking($uid){
        date_default_timezone_set('Asia/Shanghai');
        $time = time() - 86400 * 7;
        $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
        $end_time = $start_time + 86400 * 7 -1;

        $res = $this->db->query("SELECT g.rank FROM (
	SELECT d.nickname,d.uid,d.total,@curRank := @curRank + 1 AS rank FROM (
		SELECT abs(SUM(c.money)) AS total,c.uid,c.nickname FROM
		(
			SELECT a.*,b.nickname FROM log_shop a,zy_user b WHERE UNIX_TIMESTAMP(a.add_time) > ? AND UNIX_TIMESTAMP(a.add_time) < ? AND a.money < 0 AND b.uid=a.uid
		)
		c GROUP BY uid  ORDER BY total DESC
	)
	d , (SELECT @curRank := 0) f
)
g WHERE g.uid=?",[$start_time,$end_time,$uid])->row_array();

        return $res['rank'];

    }

    //查询所有排名的奖励
    public function queryRankingPrize($type=0){
        date_default_timezone_set('Asia/Shanghai');
        if($type==1){
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
        }else{
            $time = time();
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
        }

        $result = $this->db->query("select rank_start,rank_end,money,ledou,shandian,shop1_id,shop1_total,shop2_id,shop2_total from zy_ranking_prize_config
WHERE UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) < ? ORDER by rank_start ASC",[$start_time,$end_time])->result_array();
        if(!empty($result)){
            foreach($result as $key=>&$value){
                if($value['money']==0) unset($value['money']);
                if($value['ledou']==0) unset($value['ledou']);
                if($value['shandian']==0) unset($value['shandian']);
                if($value['shop1_total']==0){
                    unset($value['shop1_id']);
                    unset($value['shop1_total']);
                }
                if($value['shop2_total']==0){
                    unset($value['shop2_id']);
                    unset($value['shop2_total']);
                }
            }
        }

        return $result;
    }

    //查询所有积分排名的奖励
    public function queryRankingJFPrize($type=0){
        date_default_timezone_set('Asia/Shanghai');
        if($type==1){
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
        }else{
            $time = time();
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
        }

        $result = $this->db->query("select rank_start,rank_end,money,ledou,shandian,shop1_id,shop1_total,shop2_id,shop2_total,shop3_id,shop3_total from zy_ranking_jf_prize_config
WHERE UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) < ? ORDER by rank_start ASC",[$start_time,$end_time])->result_array();
        if(!empty($result)){
            foreach($result as $key=>&$value){
                if($value['money']==0) unset($value['money']);
                if($value['ledou']==0) unset($value['ledou']);
                if($value['shandian']==0) unset($value['shandian']);
                if($value['shop1_total']==0){
                    unset($value['shop1_id']);
                    unset($value['shop1_total']);
                }
                if($value['shop2_total']==0){
                    unset($value['shop2_id']);
                    unset($value['shop2_total']);
                }
                if($value['shop3_total']==0){
                    unset($value['shop3_id']);
                    unset($value['shop3_total']);
                }
            }
        }

        return $result;
    }

    //获取上周氪金排行奖励
    public function getRankingLDPrize($uid){
        //查询是否已经获取奖励
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $this_week_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $row = $this->db->query("select * from zy_ranking_prize_record where uid=? AND type=1 AND UNIX_TIMESTAMP(add_time) > ?",[$uid,$this_week_time])->row_array();
        if(!empty($row)) t_error(1, '奖励已领取');
        //查询用户上周排名
        $rank = $this->preMyConsumeLDRanking($uid);
        if($rank){
            //根据排名查询上周的奖励
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
            $prize = $this->db->query("SELECT * FROM zy_ranking_prize_config WHERE rank_start <= ? AND rank_end >= ? AND
UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) <?",[$rank,$rank,$start_time,$end_time])->row_array();
            if(!empty($prize)){
                $this->db->trans_start();

                if($prize['money']){
                    $this->user_model->money($uid,$prize['money'],0);
                }
                if($prize['ledou']){
                    $this->user_model->money($uid,0,$prize['ledou']);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid,$prize['shandian']);
                }
                if($prize['shop1_id'] && $prize['shop1_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                }
                if($prize['shop2_id'] && $prize['shop2_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                }
                //添加领取记录
                $insert['uid'] = $uid;
                $insert['type'] = 1;
                $insert['pid'] = $prize['id'];
                $insert['ranking'] = $rank;
                $insert['add_time'] = t_time();
                $this->table_insert('zy_ranking_prize_record',$insert);

                $this->db->trans_complete();

                return $prize;
            }
        }else{
            t_error(2, '上周未消耗乐豆，无排名');
        }

    }

    //获取上周壕一个字排行奖励
    public function getRankingMoneyPrize($uid){
        //查询是否已经获取奖励
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $this_week_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $row = $this->db->query("select * from zy_ranking_prize_record where uid=? AND type=2 AND UNIX_TIMESTAMP(add_time) > ?",[$uid,$this_week_time])->row_array();
        if(!empty($row)) t_error(1, '奖励已领取');
        //查询用户上周排名
        $rank = $this->preMyConsumeMoneyRanking($uid);
        if($rank){
            //根据排名查询上周的奖励
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
            $prize = $this->db->query("SELECT * FROM zy_ranking_prize_config WHERE rank_start <= ? AND rank_end >= ? AND
UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) <?",[$rank,$rank,$start_time,$end_time])->row_array();
            if(!empty($prize)){
                $this->db->trans_start();

                if($prize['money']){
                    $this->user_model->money($uid,$prize['money'],0);
                }
                if($prize['ledou']){
                    $this->user_model->money($uid,0,$prize['ledou']);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid,$prize['shandian']);
                }
                if($prize['shop1_id'] && $prize['shop1_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                }
                if($prize['shop2_id'] && $prize['shop2_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                }
                //添加领取记录
                $insert['uid'] = $uid;
                $insert['type'] = 2;
                $insert['pid'] = $prize['id'];
                $insert['ranking'] = $rank;
                $insert['add_time'] = t_time();
                $this->table_insert('zy_ranking_prize_record',$insert);

                $this->db->trans_complete();

                return $prize;
            }
        }else{
            t_error(2, '上周未消耗银元，无排名');
        }

    }


    //本周种植积分排行榜
    public function zZJFRanking($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zy_prize_record';
            }else{
                $table = 'zy_ranking_zz_prize_record';
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zz_prize_record';
            }else{
                $table = 'zy_ranking_zy_prize_record';
            }
        }
        if($show_type['mvalue'] == 1){
            date_default_timezone_set('Asia/Shanghai');
            $now = strtotime('this week');
            $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
            $start_time = $start_time - (3*60); //由于服务器定时器更新表需要时间，此处往后拖延3分钟开奖
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            //$colum = "jifen_1";
            $data['list'] = $this->db->query("SELECT obj.nickname,obj.$colum AS total,@rownum := @rownum + 1 AS rownum FROM (SELECT a.$colum,b.nickname FROM `zy_zhhongzhi_jifen` a,`zy_user` b WHERE a.$colum>0 AND b.uid=a.uid ORDER BY a.$colum DESC limit 0,100) AS obj,(SELECT @rownum := 0) r;")->result_array();

            $data['my_ranking'] = $this->myZZRanking($uid);         //本周个人排名

            $data['my_pre_ranking'] = $this->preMyZZRanking($uid); //上周个人排名


            //查询用户是否领取过奖励
            $row = $this->db->query("select * from `{$table}` where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=1",[$uid,$start_time])->row_array();
            if(!empty($row)){
                $data['is_receive'] = 1;
                $data['prize_list'] = $this->queryRankingJFPrize();
            }else{
                $data['is_receive'] = 0;
                $data['prize_list'] = $this->queryRankingJFPrize(1);
            }

            return $data;
        }else{
            date_default_timezone_set('Asia/Shanghai');
            $now = strtotime('this week');
            $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
            $start_time = $start_time - (3*60); //由于服务器定时器更新表需要时间，此处往后拖延3分钟开奖
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            //$colum = "jifen_1";
            $data['list'] = $this->db->query("SELECT obj.nickname,obj.$colum AS total,@rownum := @rownum + 1 AS rownum FROM (SELECT a.$colum,b.nickname FROM `zy_zhiyan_jifen` a,`zy_user` b WHERE a.$colum>0 AND b.uid=a.uid ORDER BY a.$colum DESC limit 0,100) AS obj,(SELECT @rownum := 0) r;")->result_array();

            $data['my_ranking'] = $this->myZYRanking($uid);         //本周个人排名

            $data['my_pre_ranking'] = $this->preMyZYRanking($uid); //上周个人排名


            //查询用户是否领取过奖励
            $row = $this->db->query("select * from zy_ranking_zz_prize_record where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=1",[$uid,$start_time])->row_array();
            if(!empty($row)){
                $data['is_receive'] = 1;
                $data['prize_list'] = $this->queryRankingJFPrize();
            }else{
                $data['is_receive'] = 0;
                $data['prize_list'] = $this->queryRankingJFPrize(1);
            }

            return $data;
        }

    }

    //获取本周种植积分个人排名
    public function myZZRanking($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        if($show_type['mvalue'] == 1){
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            $res = $this->db->query("SELECT g.$colum as total,g.rownum FROM
( SELECT obj.uid,obj.$colum,@rownum := @rownum + 1 AS rownum FROM
(SELECT uid,$colum FROM `zy_zhhongzhi_jifen`  ORDER BY $colum DESC ) AS obj,
(SELECT @rownum := 0) r ) g WHERE g.uid=?;",[$uid])->row_array();

            if($res['total']){
                return $res['rownum'];
            }else{
                return '无';
            }
        }else{
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            $res = $this->db->query("SELECT g.$colum as total,g.rownum FROM
( SELECT obj.uid,obj.$colum,@rownum := @rownum + 1 AS rownum FROM
(SELECT uid,$colum FROM `zy_zhiyan_jifen`  ORDER BY $colum DESC ) AS obj,
(SELECT @rownum := 0) r ) g WHERE g.uid=?;",[$uid])->row_array();

            if($res['total']){
                return $res['rownum'];
            }else{
                return '无';
            }
        }
    }

    //获取上周个人种植积分排名
    public function preMyZZRanking($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_zhiyan_jifen';
            }else{
                $table = 'zy_zhhongzhi_jifen';
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_zhhongzhi_jifen';
            }else{
                $table = 'zy_zhiyan_jifen';
            }
        }
        if($row['mvalue']>1){
            $mvalue = $row['mvalue'] -1; //取上一周的数据
        }else{
            $mvalue = $row['mvalue'] +4; //取第五周的数据
        }
        $colum = "jifen_".$mvalue;
        $res = $this->db->query("SELECT g.$colum as total,g.rownum FROM
( SELECT obj.uid,obj.$colum,@rownum := @rownum + 1 AS rownum FROM
(SELECT uid,$colum FROM `{$table}`  ORDER BY $colum DESC ) AS obj,
(SELECT @rownum := 0) r ) g WHERE g.uid=?;",[$uid])->row_array();

        if($res['total']){
            return $res['rownum'];
        }else{
            return '无';
        }
    }

    //领取上周种植排行奖励
    public function getRankingZZPrize($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }else{
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZZRanking($uid);
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZZRanking($uid);
            }else{
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }
        }

        //查询是否已经获取奖励
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $this_week_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $row = $this->db->query("select * from `{$table}` where uid=? AND type=1 AND UNIX_TIMESTAMP(add_time) > ?",[$uid,$this_week_time])->row_array();
        if(!empty($row)) t_error(1, '奖励已领取');

        //延迟两分钟开奖
        $now_time = time();
        if($now_time < $this_week_time+120){
            t_error(2, '奖励领取未开始');
        }

        if($rank && $rank!='无'){
            //根据排名查询上周的奖励
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
            $prize = $this->db->query("SELECT * FROM zy_ranking_jf_prize_config WHERE rank_start <= ? AND rank_end >= ? AND
UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) <?",[$rank,$rank,$start_time,$end_time])->row_array();
            if(!empty($prize)){
                $this->db->trans_start();
                if($prize['money']){
                    $this->user_model->money($uid,$prize['money'],0);
                }
                if($prize['ledou']){
                    $this->user_model->money($uid,0,$prize['ledou']);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid,$prize['shandian']);
                }
                if($prize['shop1_id'] && $prize['shop1_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                }
                if($prize['shop2_id'] && $prize['shop2_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                }

                //植树节第三个奖品是实物奖励，所以可以不存仓库表 2020-03-11
                /*if($prize['shop3_id'] && $prize['shop3_total'] ){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop3_total'],$uid,$prize['shop3_id']);
                }*/

                //添加领取记录
                $insert['uid'] = $uid;
                $insert['type'] = 1;
                $insert['pid'] = $prize['id'];
                $insert['ranking'] = $rank;
                $insert['add_time'] = t_time();
                $this->table_insert("$table",$insert);

                $this->db->trans_complete();

                return $prize;
            }
        }else{
            t_error(2, '上周未获得种植积分，无排名');
        }

    }

    //领取上周种植排行奖励
/*    public function getRankingZZPrize($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }else{
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZZRanking($uid);
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZZRanking($uid);
            }else{
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }
        }

        //查询是否已经获取奖励
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $this_week_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $row = $this->db->query("select * from `{$table}` where uid=? AND type=1 AND UNIX_TIMESTAMP(add_time) > ?",[$uid,$this_week_time])->row_array();
        if(!empty($row)) t_error(1, '奖励已领取');

        if($rank && $rank!='无'){
            //根据排名查询上周的奖励
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
            $prize = $this->db->query("SELECT * FROM zy_ranking_jf_prize_config WHERE rank_start <= ? AND rank_end >= ? AND
UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) <?",[$rank,$rank,$start_time,$end_time])->row_array();
            if(!empty($prize)){
                //查询商品表的详情
                $row_shop = $this->db->query("select type4,json_data from zy_shop WHERE shopid=$prize[shop3_id]")->row_array();
                $temp = json_decode($row_shop['json_data'], true);
                //京东卡，需特殊处理
                if($prize['shop3_id'] && $prize['shop3_total'] && $row_shop['type4']=='jdk'){
                    //京东卡的记录
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    $goods_id = $temp['goodsId'];
                    $card_id = t_rand_str($uid);
                    $cardValue = $temp['mubiao'];
                    $retrun_data = $this->ziyunExchangeGoods($openid,$goods_id,$card_id,$cardValue);
                    //先判断调用的接口返回的数据是否正确，正确才添加到京东卡记录表
                    if($retrun_data['status'] == 0){
                        $this->db->trans_start();
                        $data = array(
                            'shopid' => $prize['shop3_id'],
                            'goods_id' => $goods_id,
                            'card_id' => $card_id,
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_jdk_record', $data);

                        if($prize['money']){
                            $this->user_model->money($uid,$prize['money'],0);
                        }
                        if($prize['ledou']){
                            $this->user_model->money($uid,0,$prize['ledou']);
                        }
                        if($prize['shandian']){
                            $this->user_model->shandian($uid,$prize['shandian']);
                        }
                        if($prize['shop1_id'] && $prize['shop1_total']){
                            $this->load->model('api/store_model');
                            $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                        }
                        if($prize['shop2_id'] && $prize['shop2_total']){
                            $this->load->model('api/store_model');
                            $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                        }

                        //添加领取记录
                        $insert['uid'] = $uid;
                        $insert['type'] = 1;
                        $insert['pid'] = $prize['id'];
                        $insert['ranking'] = $rank;
                        $insert['add_time'] = t_time();
                        $this->table_insert("$table",$insert);
                        $this->db->trans_complete();
                    }else{
                        //保存接口返回错误信息，方便查错
                        $data = array(
                            'status' => $retrun_data['status'],
                            'message' => $retrun_data['message'],
                            'shopid' => $prize['shop3_id'],
                            'goods_id' => $goods_id,
                            'card_id' => $card_id,
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_jdk_error', $data);
                        t_error(3, '领取失败，请稍后再试');
                    }
                }

                return $prize;
            }
        }else{
            t_error(2, '上周未获得种植积分，无排名');
        }

    }*/

    //本周制烟积分排行榜
    public function zYJFRanking($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zy_prize_record';
            }else{
                $table = 'zy_ranking_zz_prize_record';
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zz_prize_record';
            }else{
                $table = 'zy_ranking_zy_prize_record';
            }
        }

        if($show_type['mvalue'] == 1){
            date_default_timezone_set('Asia/Shanghai');
            $now = strtotime('this week');
            $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
            $start_time = $start_time - (3*60); //由于服务器定时器更新表需要时间，此处往后拖延3分钟开奖
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            //$colum = "jifen_1";
            $data['list'] = $this->db->query("SELECT obj.nickname,obj.$colum AS total,@rownum := @rownum + 1 AS rownum FROM (SELECT a.$colum,b.nickname FROM `zy_zhhongzhi_jifen` a,`zy_user` b WHERE a.$colum>0 AND b.uid=a.uid ORDER BY a.$colum DESC limit 0,100) AS obj,(SELECT @rownum := 0) r;")->result_array();

            $data['my_ranking'] = $this->myZZRanking($uid);         //本周个人排名

            $data['my_pre_ranking'] = $this->preMyZZRanking($uid); //上周个人排名


            //查询用户是否领取过奖励
            $row = $this->db->query("select * from `{$table}` where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=1",[$uid,$start_time])->row_array();
            if(!empty($row)){
                $data['is_receive'] = 1;
                $data['prize_list'] = $this->queryRankingJFPrize();
            }else{
                $data['is_receive'] = 0;
                $data['prize_list'] = $this->queryRankingJFPrize(1);
            }

            return $data;
        }else{
            date_default_timezone_set('Asia/Shanghai');
            $now = strtotime('this week');
            $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
            $start_time = $start_time - (3*60); //由于服务器定时器更新表需要时间，此处往后拖延3分钟开奖
            $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
            $colum = "jifen_".$row['mvalue'];
            //$colum = "jifen_1";
            $data['list'] = $this->db->query("SELECT obj.nickname,obj.$colum AS total,@rownum := @rownum + 1 AS rownum FROM (SELECT a.$colum,b.nickname FROM `zy_zhiyan_jifen` a,`zy_user` b WHERE a.$colum>0 AND b.uid=a.uid ORDER BY a.$colum DESC limit 0,100) AS obj,(SELECT @rownum := 0) r;")->result_array();

            $data['my_ranking'] = $this->myZYRanking($uid);         //本周个人排名

            $data['my_pre_ranking'] = $this->preMyZYRanking($uid); //上周个人排名

            //$table = 'zy_ranking_zy_prize_record';
            //查询用户是否领取过奖励
            $row = $this->db->query("select * from `{$table}` where uid=? AND UNIX_TIMESTAMP(add_time) > ? AND type=1",[$uid,$start_time])->row_array();
            if(!empty($row)){
                $data['is_receive'] = 1;
                $data['prize_list'] = $this->queryRankingJFPrize();
            }else{
                $data['is_receive'] = 0;
                $data['prize_list'] = $this->queryRankingJFPrize(1);
            }

            return $data;
        }

    }

    //获取本周制烟积分个人排名
    public function myZYRanking($uid){
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        $colum = "jifen_".$row['mvalue'];
        $res = $this->db->query("SELECT g.$colum as total,g.rownum FROM
( SELECT obj.uid,obj.$colum,@rownum := @rownum + 1 AS rownum FROM
(SELECT uid,$colum FROM `zy_zhiyan_jifen`  ORDER BY $colum DESC ) AS obj,
(SELECT @rownum := 0) r ) g WHERE g.uid=?;",[$uid])->row_array();

        if($res['total']){
            return $res['rownum'];
        }else{
            return '无';
        }
    }

    //获取上周个人制烟积分排名
    public function preMyZYRanking($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_zhiyan_jifen';
            }else{
                $table = 'zy_zhhongzhi_jifen';
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_zhhongzhi_jifen';
            }else{
                $table = 'zy_zhiyan_jifen';
            }
        }

        //$table = 'zy_zhiyan_jifen';

        if($row['mvalue']>1){
            $mvalue = $row['mvalue'] -1; //取上一周的数据
        }else{
            $mvalue = $row['mvalue'] +4; //取第五周的数据
        }
        $colum = "jifen_".$mvalue;
        $res = $this->db->query("SELECT g.$colum as total,g.rownum FROM
( SELECT obj.uid,obj.$colum,@rownum := @rownum + 1 AS rownum FROM
(SELECT uid,$colum FROM `{$table}`  ORDER BY $colum DESC ) AS obj,
(SELECT @rownum := 0) r ) g WHERE g.uid=?;",[$uid])->row_array();
        if($res['total']){
            return $res['rownum'];
        }else{
            return '无';
        }

    }

    //领取上周制烟排行奖励
    public function getRankingZYPrize($uid){
        $show_type = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='week_num'")->row_array();
        if($row['mvalue'] == 1){
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }else{
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                //$rank = $this->preMyZZRanking($uid);
                $rank = $this->preMyZYRanking($uid);
            }
        }else{
            if($show_type['mvalue'] == 1){
                $table = 'zy_ranking_zz_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZZRanking($uid);
            }else{
                $table = 'zy_ranking_zy_prize_record';
                //查询用户上周排名
                $rank = $this->preMyZYRanking($uid);
            }
        }

        //查询是否已经获取奖励
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $this_week_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $row = $this->db->query("select * from `{$table}` where uid=? AND type=1 AND UNIX_TIMESTAMP(add_time) > ?",[$uid,$this_week_time])->row_array();
        if(!empty($row)) t_error(1, '奖励已领取');
        if($rank && $rank!='无'){
            //根据排名查询上周的奖励
            $time = time() - 86400 * 7;
            $start_time = mktime( 0,0, 0, date('m',$time) ,date('d',$time) - date('N',$time) + 1 ,date( 'Y',$time ));
            $end_time = $start_time + 86400 * 7 -1;
            $prize = $this->db->query("SELECT * FROM zy_ranking_jf_prize_config WHERE rank_start <= ? AND rank_end >= ? AND
UNIX_TIMESTAMP(add_time) > ? AND UNIX_TIMESTAMP(add_time) <?",[$rank,$rank,$start_time,$end_time])->row_array();
            if(!empty($prize)){

                //查询商品表的详情
                $row_shop = $this->db->query("select type4,json_data from zy_shop WHERE shopid=$prize[shop3_id]")->row_array();
                $temp = json_decode($row_shop['json_data'], true);

                /*if($prize['money']){
                    $this->user_model->money($uid,$prize['money'],0);
                }
                if($prize['ledou']){
                    $this->user_model->money($uid,0,$prize['ledou']);
                }
                if($prize['shandian']){
                    $this->user_model->shandian($uid,$prize['shandian']);
                }
                if($prize['shop1_id'] && $prize['shop1_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                }
                if($prize['shop2_id'] && $prize['shop2_total']){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                }

                //乐豆抵扣券，需特殊处理
                if($prize['shop3_id'] && $prize['shop3_total'] && $row_shop['type4']=='quan'){
                    $this->load->model('api/store_model');
                    $this->store_model->update_total($prize['shop3_total'],$uid,$prize['shop3_id']);
                    //抵扣券的记录
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    $data = array(
                        'shopid' => $prize['shop3_id'],
                        'ticket_id' => t_rand_str($uid),
                        'uid' => $uid,
                        'openid' => $openid,
                        'stat' => 0,
                        'addtime' => time()
                    );
                    $this->db->insert('zy_ticket_record', $data);
                }

                //添加领取记录
                $insert['uid'] = $uid;
                $insert['type'] = 1;
                $insert['pid'] = $prize['id'];
                $insert['ranking'] = $rank;
                $insert['add_time'] = t_time();
                $this->table_insert("$table",$insert);
                */
                $this->db->trans_start();

                if($prize['shop3_id'] && $prize['shop3_total'] && $row_shop['type4']=='jdk'){
                    //京东卡，需特殊处理
                    //京东卡的记录
                    $openid = $this->user_model->queryOpenidByUid($uid);
                    $goods_id = $temp['goodsId'];
                    $card_id = t_rand_str($uid);
                    $cardValue = $temp['mubiao'];
                    $retrun_data = $this->ziyunExchangeGoods($openid,$goods_id,$card_id,$cardValue);
                    //先判断调用的接口返回的数据是否正确，正确才添加到京东卡记录表
                    if($retrun_data['status'] == 0){

                        $data = array(
                            'shopid' => $prize['shop3_id'],
                            'goods_id' => $goods_id,
                            'card_id' => $card_id,
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_jdk_record', $data);

                        if($prize['money']){
                            $this->user_model->money($uid,$prize['money'],0);
                        }
                        if($prize['ledou']){
                            $this->user_model->money($uid,0,$prize['ledou']);
                        }
                        if($prize['shandian']){
                            $this->user_model->shandian($uid,$prize['shandian']);
                        }
                        if($prize['shop1_id'] && $prize['shop1_total']){
                            $this->load->model('api/store_model');
                            $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                        }
                        if($prize['shop2_id'] && $prize['shop2_total']){
                            $this->load->model('api/store_model');
                            $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                        }

                    }else{
                        //保存接口返回错误信息，方便查错
                        $data = array(
                            'status' => $retrun_data['status'],
                            'message' => $retrun_data['message'],
                            'shopid' => $prize['shop3_id'],
                            'goods_id' => $goods_id,
                            'card_id' => $card_id,
                            'uid' => $uid,
                            'openid' => $openid,
                            'stat' => 0,
                            'addtime' => time()
                        );
                        $this->db->insert('zy_jdk_error', $data);
                        t_error(3, '领取失败，请稍后再试');
                    }
                }else{
                    if($prize['money']){
                        $this->user_model->money($uid,$prize['money'],0);
                    }
                    if($prize['ledou']){
                        $this->user_model->money($uid,0,$prize['ledou']);
                    }
                    if($prize['shandian']){
                        $this->user_model->shandian($uid,$prize['shandian']);
                    }
                    if($prize['shop1_id'] && $prize['shop1_total']){
                        $this->load->model('api/store_model');
                        $this->store_model->update_total($prize['shop1_total'],$uid,$prize['shop1_id']);
                    }
                    if($prize['shop2_id'] && $prize['shop2_total']){
                        $this->load->model('api/store_model');
                        $this->store_model->update_total($prize['shop2_total'],$uid,$prize['shop2_id']);
                    }
                }


                //添加领取记录
                $insert['uid'] = $uid;
                $insert['type'] = 1;
                $insert['pid'] = $prize['id'];
                $insert['ranking'] = $rank;
                $insert['add_time'] = t_time();
                $this->table_insert("$table",$insert);

                $this->db->trans_complete();

                return $prize;
            }
        }else{
            t_error(2, '上周未获得制烟积分，无排名');
        }

    }

    //定时周一更新排行榜的奖品
    public function updateRankingPrizeConfig(){
        date_default_timezone_set('Asia/Shanghai');
        $now = strtotime('this week');
        $start_time = mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $res = $this->column_sql('money,ledou,shandian,json_data',['type1'=>10],'zy_prize',1);
        if(!empty($res)){
            foreach($res as $key=>&$value){
                if($value['json_data']){
                    $temp = json_decode($value['json_data'], true);
                    //print_r($temp);
                    $value = array_merge($value, $temp);
                    //unset($value['json_data']);
                }
            }
            foreach($res as $k=>$v){
                //查询本周是否在zy_ranking_prize_config添加相应记录
                $count = $this->db->query("select count(*) as num from zy_ranking_prize_config WHERE rank_start=$v[rank_start] AND rank_end=$v[rank_end] AND UNIX_TIMESTAMP(add_time) > $start_time")->row_array();
                if(!$count['num']){
                    $insert['rank_start'] = $v['rank_start'];
                    $insert['rank_end'] = $v['rank_end'];
                    $insert['money'] = $v['money'];
                    $insert['ledou'] = $v['ledou'];
                    $insert['shandian'] = $v['shandian'];
                    $insert['shop1_total'] = $v['shop1_total'];
                    $insert['shop2_total'] = $v['shop2_total'];
                    if($v['shop1_total']){
                        $insert['shop1_id'] = $this->randShop($v['shop1_type1'],$v['shop1_type2']);
                    }else{
                        $insert['shop1_id'] = 0;
                    }
                    if($v['shop2_total']){
                        $insert['shop2_id'] = $this->randShop($v['shop2_type1'],$v['shop2_type2']);
                    }else{
                        $insert['shop2_id'] = 0;
                    }
                    $insert['add_time'] = t_time();
                    $this->table_insert('zy_ranking_prize_config',$insert);
                }
            }
        }

    }


    /**
     * 根据商品类型type1，商品等级type2随机获取某个商品
     *
     */
    function randShop($type1,$type2){
        $res = $this->column_sql('shopid',['type1'=>$type1,'type2'=>$type2],'zy_shop',1);
        $key = rand(0,count($res)-1);
        if(!empty($res)){
            return $res[$key]['shopid'];
        }
    }

    //获取本周显示种植榜还是制烟榜
    function show_type_ranking(){
        $row = $this->db->query("select mvalue from zy_setting WHERE mkey='show_type'")->row_array();
        //获取本周开始和结束时间
        date_default_timezone_set('Asia/Shanghai');
        $now=strtotime('this week');
        $startTime=mktime(0,0,0,date('m',$now),date('d',$now),date('Y',$now));
        $endTime=$startTime+7*24*60*60-1;
        $stime = date('Y/m/d',$startTime);
        $etime = date('Y/m/d',$endTime);
        //$result['vali_time'] = "活动时间：$stime--$etime";
        $result['vali_time'] = "";
        $result['show_type'] = $row['mvalue'];
        return $result;
        //return $row['mvalue'];
    }

    //京东卡，调用中烟商城接口，生成订单
    public function ziyunExchangeGoods($openid,$goodsId,$cardId,$cardValue){
        $key = '0ekr1cb3e77a338f92f43f220i9d8978';
        $data['goodsId'] = $goodsId;
        $data['cardId'] = $cardId;
        $data['cardValue'] = $cardValue;
        $data['openId'] = $openid;
        $data['sign'] = md5($openid.$key.$cardId);

        //$url = 'http://ld.haiyunzy.com/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //测试地址
        $url = 'http://ld.thewm.cn/zlbean/thirdInterfacePath/ziyunExchange/ziyunExchangeGoods'; //正式生产地址

        $return = $this->http($url,$data,true);
        return $return;
    }

    //接口POST
    function curlPost($postUrl , $postArr=array()) {
        $curl = curl_init($postUrl);
        $cookie = dirname(__FILE__).'/cache/cookie.txt';
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT,10); //超时设置 (秒)
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); // ?Cookie
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    //模拟POST提交
    function http($url, $data = NULL, $json = false){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if($json && is_array($data)){
                $data = json_encode( $data ,JSON_UNESCAPED_UNICODE);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($json){
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array(
                        'Content-type: application/json',
                        'Content-Length:' . strlen($data))
                );
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            var_dump("错误：".$errorno);
            return array('errorno' => false, 'errmsg' => $errorno);
        }
        curl_close($curl);
        //var_dump('数据：'.$res);
        return json_decode($res, true);

    }


}
