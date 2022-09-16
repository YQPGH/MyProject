<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Deletetable extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    // 网站首页
    public function index()
    {

        phpinfo();
    }

    //清空所有表数据
    public function initData(){
        $table = ['jd_guyong','jd_jiandie','jd_shouru','log_admin','log_login','log_market','log_money','log_orders','log_prize',
        'log_prize_quan','log_shop','log_task','log_task_prize','log_trade','log_xp','xxl_record','zu_jiandie','zy_aging','zy_aging_record',
        'zy_bake','zy_bake_record','zy_event','zy_event_land','zy_friend','zy_friend_apply','zy_friend_invite','zy_friend_visit','zy_gather_record',
        'zy_guide','zy_hunt_record','zy_land','zy_land_upgrade_record','zy_market','zy_newer_task','zy_news','zy_orders','zy_peifang',
        'zy_peiyu','zy_pinjian_record','zy_process','zy_process_record','zy_question','zy_questionnaire_record','zy_ranking_prize_record',
        'zy_recharge_log','zy_seed_record','zy_shenmi_shop','zy_sign','zy_st_message','zy_stat_day','zy_stat_month','zy_store','zy_store_upgrade_record',
        'zy_suggestion','zy_task_today','zy_ticket_record','zy_unusual','zy_user','zy_user_game','zy_user_login','zy_zulin'
        ];

        $this->db->trans_start();
        foreach($table as $key=>$value){
            $this->db->query("TRUNCATE TABLE `$value`");
        }
        $this->db->trans_complete();

    }

    //删除所有表中个人数据
   public function deletedata(){
        $tabale = [
            'chongzi_send','chongzi_shouru','jd_guyong','jd_jiandie','jd_shouru','log_admin','log_login','log_market','log_money',
            'log_orders','log_prize','log_prize_quan','log_shop','log_task','log_task_prize','log_trade',
            'log_xp','xxl_record','zu_jiandie','zy_aging',
            'zy_aging_record','zy_bake','zy_bake_record','zy_change_record','zy_chongzi','zy_compensate','zy_delete_friend_record',
            'zy_event','zy_event_land','zy_fire','zy_fragment','zy_fragment_compose',
            'zy_fragment_prize_error','zy_fragment_prize_record','zy_fragment_record','zy_fragment_scan',
            'zy_friend','zy_friend_apply','zy_friend_invite','zy_friend_visit',
            'zy_gamelv_prize_record','zy_gather_record','zy_gift_record','zy_guide',
           'zy_hunt_record','zy_jdk_error','zy_jdk_record','zy_kuma','zy_land',
            'zy_land_upgrade_record','zy_laxin','zy_laxin_invite','zy_laxin_invitee_prize','zy_laxin_message','zy_laxin_prize_record',
            'zy_laxin_record','zy_leaf','zy_leaf_invite','zy_leaf_message','zy_leaf_peiyang_record','zy_leaf_position',
            'zy_leaf_record','zy_leaf_prize_record','zy_leaf_sign','zy_leaf_task',
            'zy_loginprize_record','zy_market','zy_newer_task','zy_orders',
            'zy_peifang','zy_peiyu', 'zy_pinjian_record',
            'zy_prize_black','zy_process','zy_process_record','zy_question','zy_question_prize_record','zy_questionnaire_record',
            'zy_ranking_prize_record','zy_ranking_zy_prize_record','zy_ranking_zz_prize_record',
           'zy_reply','zy_seed_record','zy_shenmi_shop','zy_sign',
            'zy_st_message','zy_store','zy_store_upgrade_record',
            'zy_suggestion','zy_task_today','zy_ticket_record','zy_turntable_record',
            'zy_unusual','zy_user','zy_user_game','zy_user_login',
            'zy_yan_jifen','zy_yanye_jifen','zy_zhhongzhi_jifen','zy_zhiyan_jifen','zy_zulin',
            'zy_qixi','zy_task_detail'

        ];

    foreach($tabale as $value){

            $get_sql = "select uid from $value where uid=?";
            $res = $this->db->query($get_sql,['1eca511fdeb0be458eb5d102271600c5'])->row_array();
            if($res){
                $this->db->where('uid','1eca511fdeb0be458eb5d102271600c5')
                    ->delete($value);
            }

    }

}

function test(){

    $list = $this->db->query("select add_time from zy_national_day  limit 0,1749;")->result_array();
//print_r($list);exit;
    foreach($list as $v){

//        $this->db->set('add_time',$v['add_time'])
//            ->where(,['id'<1749])
//        ->update('zy_advert_record');
    }

    /*$start_time = strtotime('2019-10-01 00:00:00');
    $stop_time = strtotime('2019-10-18 23:59:59');
    $number = $this->db->query("select COUNT(*) as num from (select COUNT(*)  from zy_user_login a WHERE UNIX_TIMESTAMP(add_time)<'$stop_time' and UNIX_TIMESTAMP(add_time)>'$start_time' group by uid) a")->row_array();*/

//    echo $this->db->last_query();

//    echo $number['num'].'<br/>';
}


}
