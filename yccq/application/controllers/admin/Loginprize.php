<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Loginprize extends Content{

    function __construct(){
        $this->name = '签到记录';
        $this->control = 'loginprize';
        $this->list_view = 'admin/loginprize_record';

        parent ::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/loginprize/');
        $this->load->model('admin/user_model','model');
    }



    /**
     * 春节活动
     * 签到记录
     */
    function login_record()
    {
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/login_record?';
        // 查询条件
        $where = 'a.prize_id=p.id and p.type1=13 ';
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }
        $time =  strtotime('2022-01-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);
        $where .= " and UNIX_TIMESTAMP(a.add_time)>$time";
        $data['count'] = $this->model->table_count("log_prize a,zy_prize p",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $lists = $this->model->table_lists("log_prize a,zy_prize p",'a.uid,a.add_time,p.money,p.shandian,p.shop1,p.shop1_total', $where, 'a.id DESC', $this->per_page, $offset);

        foreach($lists as &$value)
        {

            $user  = $this->db->query("select nickname from zy_user  WHERE  uid=? ",[$value['uid']])->row_array();
            $value['nickname'] = $user['nickname'];
            $value['prize'] = '';
             if($value['shop1'])
             {
                 $sql = "select name from zy_shop  WHERE  shopid=? ";
                 $prize  = $this->db->query($sql,[$value['shop1']])->row_array();
                 $value['prize'] = $prize['name'];
             }
        }

        $data['list'] = ($lists);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        //后台访问日志
        $this->log_admin_model->logs('查询签到记录',1);
        $this->load->view( $this->list_view,$data);
    }


}
