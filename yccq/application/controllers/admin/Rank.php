<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *活动管理
 *
 */
include_once 'Content.php';
class Rank extends Content{

    function __construct(){
        $this->name='排行管理';
        $this->control = 'Rank';
        $this->list_view = 'rank_list';

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/rank/');
        $this->load->model('admin/rank_model','model');

    }

    //首页
    function index(){
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $jifen = $_REQUEST['jifen'];

        if ($jifen) {
            $data['jifen'] = $jifen;
            $url_forward .=  '&jifen_=' . $jifen;
            $where .= " AND jifen_{$jifen}=jifen_{$jifen} ";
        }else{
            $jifen = 1;
        }

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

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("zy_zhiyan_jifen a",$where);

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
//        $where.= 'and a.uid=b.uid';
        $arr = [
            '1' => '第一周',
            '2' => '第二周',
            '3' => '第三周',
            '4' => '第四周',
            '5' => '第五周'
        ];
        $colum = "jifen_{$jifen}";
        // 列表数据
        $list = $this->db->query("SELECT a.update_time_{$jifen} update_time,a.uid,a.$colum as jifen,a.rownum AS ranking FROM
        (SELECT t.update_time_{$jifen} ,t.uid,t.$colum , @rownum := @rownum + 1 AS rownum FROM
        (SELECT @rownum := 0) r, zy_zhiyan_jifen AS t ORDER BY t.$colum DESC) AS a
        WHERE $where limit $offset,$this->per_page;")->result_array();

        foreach($list as $key=>&$value){
            $user = $this->model->get_row('zy_user','nickname',"`uid`='$value[uid]'");
            $value['nickname'] = $user['nickname'];
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        $data['arr'] = $arr;
        //后台访问日志
        $this->log_admin_model->logs('查询制烟排行信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }


    function name_list(){
        if(!permission('SYS_Ranking_Name_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/name_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
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

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('zy_plant_ranking_message','1=1');
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.out=1 AND a.uid=b.uid';
        $list = $this->model->table_lists('zy_plant_ranking_message a,zy_user b','a.pid,a.uid,a.truename,a.address,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as &$value){
            $zz_record  = $this->db->query("select pid,add_time from zy_ranking_zz_prize_record WHERE  uid='$value[uid]' AND `type`=1 AND id='$value[pid]'")->row_array();
            $zy_record  = $this->db->query("select pid,add_time from zy_ranking_zy_prize_record WHERE  uid='$value[uid]' AND `type`=1 AND id='$value[pid]'")->row_array();
            if($zz_record)
            {
                $value['pid'] = $zz_record['pid'];
                $value['add_time'] = $zz_record['add_time'];
            }
            if($zy_record)
            {
                $value['pid'] = $zy_record['pid'];
                $value['add_time'] = $zy_record['add_time'];
            }
            $value['address'] = str_replace(",",'',$value['address']);
            $sql = "select s.name from zy_ranking_jf_prize_config p,zy_shop s WHERE  p.shop3_id=s.shopid AND p.shop3_id>0 AND p.id=?";
            $prize  = $this->db->query($sql,[$value['pid']])->row_array();
            $value['name'] = $prize['name'];
            unset($value['pid']);
        }


        $idArr = array_column($list, 'add_time');
        array_multisort($idArr,SORT_DESC,$list);
        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['b.openid' => 'openID'];
        //后台访问日志
        $this->log_admin_model->logs('查询种植能手大比拼名单',1);

        $this->load->view('admin/ranking_name_list', $data);
    }

}