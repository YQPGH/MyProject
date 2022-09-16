<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  元旦刮奖
 */
include_once 'Base_model.php';

class Scrape_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = '';
    }

    //获取元旦、9月粉丝节刮奖获奖记录
    function getAwardLog($uid , $awardType = array(), $activ='none'){
        if (!is_array($awardType)) {
            $awardType = explode(',', $awardType);
        }

        if (!is_array($activ)) {
            $activ = explode(',', $activ);
        }

        $activ = $this->createInClause($activ);

        $data = array();
        //用户元旦刮奖获奖记录
        //$scrape_log = $this->Scrape_model->get_row("zy_scrape_award_log",'*',array('uid'=>$uid,'attri_activ'=>$activ));
        
        $sql = "select * from zy_scrape_award_log where uid = '".$uid."' and attri_activ in (".$activ.")";
        $scrape_log = $this->Scrape_model->lists_sql($sql);


        if ($scrape_log) {
            $scrape_prize_list = array();
            $scrape_list = array();
            foreach ($scrape_log as $kk => $vv) {
                $log_time = $vv['add_time'];
                $s_log_time = strtotime($log_time);
                $money = $vv['money']?check_id($vv['money']):0;
                $shandian = $vv['shandian']?check_id($vv['shandian']):0;

                //插入银元和闪电记获奖录
                $scrape_list[] = array(
                                'log_time'=>$log_time,
                                'money'=>$money,
                                'ledou'=>0,
                                'shandian'=>0,
                                'shop1'=>0,

                );
                $scrape_list[] = array(
                                'log_time'=>$log_time,
                                'money'=>0,
                                'ledou'=>0,
                                'shandian'=>$shandian,
                                'shop1'=>0,

                );
                //处理种子，配方，建筑材料，奖券，实物奖励
                $scrape_shop_arr = json_decode($vv['shop'],JSON_UNESCAPED_UNICODE);
                if ($scrape_shop_arr) {
                    foreach ($scrape_shop_arr as $key => $val) {
                        foreach ($awardType as $k => $v) {
                            if ($val['shop_type'] == $v) {
                                //如果是实物
                                if ($v == 'prize') {

                                    $sql = "select status,address_time,attri_activ from zy_scrape_message where uid = '".$uid."' and attri_activ in (".$activ.")";
                                    $is_receive = $this->Scrape_model->lists_sql($sql);

                                    //$is_receive = $this->Scrape_model->get_row("zy_scrape_message",'status,address_time',array('uid'=>$uid,'attri_activ'=>$activ));

                                    foreach ($is_receive as $kkk => $vvv) {
                                        $status = 0;
                                        if ($vvv['address_time']) {
                                            $can_update_time = $vvv['address_time'] + 86400;
                                            $now_time = time();
                                            if ( $now_time > $can_update_time ) {
                                                $status = 1;//不能变更地址
                                            } 
                                        }

                                        $json_data = $this->Scrape_model->get_row("zy_shop",'json_data',array('shopid'=>$val['shop_id']));
                                        $scrape_prize_list[] = array(
                                            'log_time'=>$s_log_time,
                                            'shopid'=>$val['shop_id'],
                                            'status'=>$status,
                                            'json_data'=>$json_data['json_data'],
                                            'url'=>site_url('api/scrapeMessage/scrapeAddress?activ='.$vvv['attri_activ'])

                                        );
                                    }
                                    
                                }
                                else{
                                    $scrape_list[] = array(
                                        'log_time'=>$log_time,
                                        'money'=>0,
                                        'ledou'=>0,
                                        'shandian'=>0,
                                        'shop1'=>$val['shop_id']
                                    );
                                }
                                
                            }
                        }

                    }
                }
            }
            $data['scrape_list'] = $scrape_list;//道具
            $data['scrape_prize_list'] = $scrape_prize_list;//实物
        }
        return $data;
    }
    

    //为SQL“ IN”子句格式化PHP数组
    function createInClause($arr)
    {
        return '\'' . implode( '\', \'', $arr ) . '\'';
    }
    


}
