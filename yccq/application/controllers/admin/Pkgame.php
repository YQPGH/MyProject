<?php

if (! defined('BASEPATH'))   exit('No direct script access allowed');
include_once 'Content.php';
class Pkgame extends Content
{
    function __construct ()
    {
        $this->name='pk游戏';
        $this->control = 'Pkgame';
        $this->list_view = 'pkplayer_list';
        $this->recore_view  = 'pktrade_log';
        $this->unusual_view = 'pkunusual_list';
        parent::__construct();

        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/pkgame/');
        $this->load->model('admin/pkgame_model','model');
    }

    //参与用户
    public function index(){
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);

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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $result = $this->model->lists('*', $where, 'addtime', $this->per_page, $offset);
        foreach($result as $key=>&$val){
            $win_lost_query = $this->db->query("SELECT SUM(gold) as num,COUNT(*) AS times FROM zy_mahjong_game_log WHERE openid='$val[openid]'")->row_array();
            $win_times_query = $this->db->query("SELECT COUNT(*) AS win_times FROM zy_mahjong_game_log WHERE openid='$val[openid]' AND gold>0")->row_array();
            $result[$key]['win_lost'] = intval($win_lost_query['num'])?intval($win_lost_query['num']):0;
            $result[$key]['times'] = intval($win_lost_query['times'])?intval($win_lost_query['times']):0;
            $result[$key]['win_times'] = intval($win_times_query['win_times'])?intval($win_times_query['win_times']):0;
            $result[$key]['lose_times'] = $result[$key]['times']-$result[$key]['win_times'];
            $val['addtime'] = t_time($val['addtime']);
            $val['last_time'] = t_time($val['last_time']);
        }

        $data['list'] = ($result);

        // 搜索
        $data['fields'] = ['openid' => 'openid'];

        //后台访问日志
        $this->log_admin_model->logs('查询pk游戏参与用户',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    //游戏记录
    public function game_record(){
        $url_forward = $this->baseurl . '/game_record?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);

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
        $query_count['num'] = $this->model->table_count("zy_mahjong_game", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $result = $this->model->table_lists("zy_mahjong_game",'*', $where, 'id', $this->per_page, $offset);
        foreach( $result as $kye=>&$val){
            $take_query = $this->db->query("select gold,take_gold from zy_mahjong_game_log WHERE game_id=$val[id] AND gold>0")->row_array();

            $result[$kye]['take_bet'] = $take_bet =  $take_query['take_gold'];
            $result[$kye]['win_bet'] = $win_bet = $take_query['gold'];
            $player=$this->get_player($val['player_openid']);
            $dealer=$this->get_player($val['dealer_openid']);
            $val['player_nickname']=$player['nickname'];
            $val['player_local_img']=$player['local_img'];
            $val['dealer_nickname']=$dealer['nickname'];
            $val['dealer_local_img']=$dealer['local_img'];
            $val['status']=$val['status']==2?'庄=><font color="green">赢('.$win_bet.')</font>， 闲=><font color="red">输('.$val['bet'].')</font>':'庄=><font color="red">输('.$val['bet'].')</font>， 闲=><font color="green">赢('.$win_bet.')</font>';
            $val['addtime'] = t_time($val['addtime']);
            if(strlen($val['dealerText'])<=1){
                $val['dealerText'].='点';
            }elseif(strlen($val['dealerText'])==2){
                $val['dealerText']='对白板';
            }else{
                $point_one=substr($val['dealerText'],0,1);
                $point_two=substr($val['dealerText'],1,4);
                if($point_two=='half')
                {
                    $val['dealerText']=$point_one.'点半';
                }else
                {
                    $val['dealerText']='对'.$point_one.'筒';
                }
            }
            //闲家的牌
            if(strlen($val['playerText'])<=1){
                $val['playerText'].='点';
            }elseif(strlen($val['playerText'])==2){
                $val['playerText']='对白板';
            }else{
                $point_one=substr($val['playerText'],0,1);
                $point_two=substr($val['playerText'],1,4);
                if($point_two=='half')
                {
                    $val['playerText']=$point_one.'点半';
                }else{
                    $val['playerText']='对'.$point_one.'筒';
                }
            }
        }
        $data['list'] = ($result);
        // 搜索
        $data['fields'] = ['openid' => '玩家openid'];
        //后台访问日志
        $this->log_admin_model->logs('查询pk游戏记录',1);
        $this->load->view('admin/pkgame_record', $data);
    }

    function trade_log(){
        $url_forward = $this->baseurl . '/trade_log?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);

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
        $query_count['num'] = $this->model->table_count("zy_mahjong_trade_log", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $result = $this->model->table_lists("zy_mahjong_trade_log",'*', $where, 'id', $this->per_page, $offset);
        foreach($result as &$val){
            $val['status'] = $val['status'] == 1 ? '<font color="#009900">成功</font>' : '<font color="#FF0000">失败</font>';
            $val['addtime'] = t_time($val['addtime']);
        }
        $data['list'] = ($result);
        // 搜索
        $data['fields'] = ['openid' => 'openid'];
        //后台访问日志
        $this->log_admin_model->logs('查询pk游戏结算记录',1);
        $this->load->view('admin/'.$this->recore_view, $data);
    }

    function game_unusual(){
        $url_forward = $this->baseurl . '/game_unusual?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);

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
        $query_count['num'] = $this->model->table_count("zy_mahjong_error_log", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $result = $this->model->table_lists("zy_mahjong_error_log",'*', $where, 'id', $this->per_page, $offset);
        foreach( $result as $kye=>&$val){
            $player=$this->get_player($val['openid']);
            $val['nickname']=$player['nickname'];
            $val['local_img']=$player['local_img'];
            $val['add_time'] = t_time($val['add_time']);
            switch($val['type']){
                case 1:
                    $val['error_type'] = '加注';
                    break;
                case 2;
                    $val['error_type'] = '跟注';
                    break;
                case 3;
                    $val['error_type'] = '开牌';
                    break;
                case 4;
                    $val['error_type'] = '弃牌';
                    break;
                case 5;
                    $val['error_type'] = '发牌';
                    break;
                case 6;
                    $val['error_type'] = '超时';
                    break;
                default:
                    $val['error_type'] = '未知错误';
            }
        }

        $data['list'] = ($result);
        // 搜索
        $data['fields'] = ['openid' => 'openid'];
        //后台访问日志
        $this->log_admin_model->logs('查询pk游戏异常记录',1);
        $this->load->view('admin/'.$this->unusual_view, $data);
    }

    private function get_player($openid)
    {
        if(!$openid){
            return false;
        }
        $where['openid']=$openid;
        $data = $this->db->get_where('zy_mahjong_player',$where)->row_array();

        return $data;
    }





}
