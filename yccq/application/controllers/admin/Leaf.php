<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Leaf extends Content{

    function __construct(){
        $this->name = '叠叶子';
        $this->control = 'leaf';
        $this->list_view = 'admin/leaf_name_list'; //用户信息列表页
        $this->leaf_prize_view = 'admin/leaf_prize';
        $this->mayday_list_view = 'admin/leaf_mayday_name_list';  //叠金叶五一名单列表
        $this->mayday_prize_view = 'admin/leaf_mayday_prize';//五一叠金叶奖品信息
        $this->springfest_prizeview = 'admin/leaf_springfest_prize';//春节叠金叶奖品信息
        $this->springfest_recordview = 'admin/leaf_springfest_record';//春节叠金叶奖品信息
        $this->springfest_userview = 'admin/leaf_springfest_user';//春节叠金叶奖品信息
        parent ::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/leaf/');
        $this->load->model('admin/leaf_model','model');
    }


    function name_list(){
        if(!permission('SYS_Leaf_Name_List','read')) show_msg('没有操作权限！');

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
        $time =  strtotime('2020-04-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);
//        $data['count'] = $this->model->table_count("zy_leaf_message a","UNIX_TIMESTAMP(a.add_time)<'$time'");
        $data['count'] = $this->model->table_count("zy_leaf_prize_record a","UNIX_TIMESTAMP(a.add_time)<'$time'");
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= " AND a.pid=b.id and UNIX_TIMESTAMP(a.add_time)<'$time'";
//        $list = $this->model->table_lists('zy_leaf_message a,zy_user b','a.pid,a.uid,a.truename,a.address,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);
//        foreach($list as &$value)
//        {
//            $value['address'] = str_replace(",",'',$value['address']);
//
//            $row = $this->db->query("select pid,add_time from zy_leaf_prize_record WHERE uid='$value[uid]' AND id='$value[pid]' AND UNIX_TIMESTAMP(add_time)<'$time'")->row_array();
//            $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE p.type2<11 AND p.shop=s.shopid AND p.id=? ";
//            $prize  = $this->db->query($sql,[$row['pid']])->row_array();
//            $value['add_time'] = $row['add_time'];
//
//            $value['name'] = $prize['name'];
//
//            unset($value['uid'],$value['pid']);
//
//        }
//        $idArr = array_column($list, 'add_time');
//        array_multisort($idArr,SORT_DESC,$list);
        $list = $this->model->table_lists('zy_leaf_prize_record a,zy_leaf_prize_config b','a.add_time,a.uid,b.*', $where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as &$value)
        {
            $row = $this->db->query("select nickname from zy_user WHERE uid='$value[uid]'")->row_array();
            $shop = $this->db->query("select `name` from zy_shop WHERE shopid='$value[shop]'")->row_array();

            $value['nickname'] = $row['nickname'];
            $value['money'] = '银元：'.$value['money'];
            $value['shandian'] = '闪电：'.$value['shandian'];
            $value['shop_name'] = '商品：'.$shop['name'];
            $value['shop_num'] = '数量：'.$value['shop_num'];
        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶名单',1);

        $this->load->view($this->list_view, $data);
    }


    function mayday_name_list(){
        if(!permission('SYS_Leaf_Mayday_Name_List','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/mayday_name_list?';

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
        $time =  strtotime('2020-04-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);
//        $data['count'] = $this->model->table_count('zy_leaf_message a',"UNIX_TIMESTAMP(a.add_time)>'$time'");

        $data['count'] = $this->model->table_count("zy_leaf_prize_record a","UNIX_TIMESTAMP(a.add_time)>'$time'");
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= " AND a.pid=b.id and UNIX_TIMESTAMP(a.add_time)<'$time'";
        // 列表数据
//        $where .= " AND a.uid=b.uid and UNIX_TIMESTAMP(a.add_time)>'$time'";
//        $list = $this->model->table_lists('zy_leaf_message a,zy_user b','a.pid,a.uid,a.truename,a.address,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);
//
//        foreach($list as &$value)
//        {
//            $value['address'] = str_replace(",",'',$value['address']);
//
//            $row = $this->db->query("select pid,add_time from zy_leaf_prize_record WHERE uid='$value[uid]' AND id='$value[pid]' AND UNIX_TIMESTAMP(add_time)>'$time'")->row_array();
//            $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE p.type2=? AND p.shop=s.shopid AND p.id=? ";
//            $prize  = $this->db->query($sql,['prize',$row['pid']])->row_array();
//            $value['add_time'] = $row['add_time'];
//
//            $value['name'] = $prize['name'];
//
//            unset($value['uid'],$value['pid']);
//
//        }
//        $idArr = array_column($list, 'add_time');
//        array_multisort($idArr,SORT_DESC,$list);

        $list = $this->model->table_lists('zy_leaf_prize_record a,zy_leaf_prize_config b','a.add_time,a.uid,b.*', $where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as &$value)
        {
            $row = $this->db->query("select nickname from zy_user WHERE uid='$value[uid]'")->row_array();
            $shop = $this->db->query("select `name` from zy_shop WHERE shopid='$value[shop]'")->row_array();

            $value['nickname'] = $row['nickname'];
            $value['money'] = '银元：'.$value['money'];
            $value['shandian'] = '闪电：'.$value['shandian'];
            $value['shop_name'] = '商品：'.$shop['name'];
            $value['shop_num'] = '数量：'.$value['shop_num'];
        }
        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶名单',1);

        $this->load->view($this->mayday_list_view, $data);
    }

    function leaf_prize()
    {
        if(!permission('SYS_Leaf_Prize','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/leaf_prize?';

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
        $time =  strtotime('2020-04-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);

        $data['count'] = 0;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);


        $count = $this->db->query(
            "select pid,count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)<'$time' GROUP BY pid"
        )->result_array();


        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num from zy_leaf_message a,zy_leaf_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND UNIX_TIMESTAMP(a.add_time)<'$time'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        foreach($count as &$value)
        {
            $sql = "select s.name,s.type1,p.number from zy_leaf_prize_config p,zy_shop s WHERE  p.shop=s.shopid AND p.id=? ";
            $prize  = $this->db->query($sql,[$value['pid']])->row_array();

            if($value['pid']==1)
            {
                $value['name'] = '银元';

            }
            if($value['pid']==2)
            {
                $value['name'] = '闪电';

            }
            $value['number'] = '不限';
            if($prize)
            {

                $value['number'] = $prize['number']?$prize['number']:'不限';
                $value['name']  = $prize['name'];
            }
            $value['address_num'] = 0;
            foreach($lists as $v)
            {
                if($v['pid'] == $value['pid'])
                {
                    $value['address_num'] = $v['num'];

                }

            }
            unset($value['pid']);
        }

        $data['list'] = ($count);

        // 搜索
//        $data['fields'] = ['' => ''];

        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶奖品信息',1);
        $this->load->view($this->leaf_prize_view,$data);
    }

    function mayday_prize()
    {
        if(!permission('SYS_Leaf_Mayday_Prize','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/mayday_prize?';
        $this->name = '劳动光荣 勤劳兴“叶”奖品信息';
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
        $time =  strtotime('2020-04-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);

//        $data['count'] = $this->model->table_count('zy_leaf_message a',"UNIX_TIMESTAMP(a.add_time)>'$time'");
//        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $count = $this->db->query(
            "select pid,count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)>'$time' AND type=1 GROUP BY pid"
        )->result_array();

        $money_count = $this->db->query(
            "select money,count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)>'$time' AND money>0;")->row_array();
        $shandian_count = $this->db->query(
            "select shandian,count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)>'$time' AND shandian>0;"
        )->row_array();
        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num from zy_leaf_message a,zy_leaf_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND UNIX_TIMESTAMP(a.add_time)>'$time'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        array_push($count,$money_count,$shandian_count);

        foreach($count as $key=>&$value)
        {
            $sql = "select s.name,p.number from zy_leaf_prize_config p,zy_shop s WHERE  p.shop=s.shopid AND p.id=? ";
            $prize  = $this->db->query($sql,[$value['pid']])->row_array();


            if($value['money'])
            {
                $value['name'] = '银元';
            }
            if($value['shandian'])
            {
                $value['name'] = '闪电';
            }
            $value['number'] = '不限';
            if($prize)
            {

                $value['number'] = $prize['number']?$prize['number']:'不限';
                $value['name']  = $prize['name'];
            }
            $value['address_num'] = 0;
            foreach($lists as $v)
            {
                if($v['pid'] == $value['pid'])
                {
                    $value['address_num'] = $v['num'];
                }

            }
            unset($value['pid'],$value['money'],$value['shandian']);
        }

        $data['list'] = ($count);

        // 搜索
        $data['fields'] = ['' => ''];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶奖品信息',1);


        $this->load->view($this->mayday_prize_view,$data);
    }

    function nov11_prize()
    {
        if(!permission('SYS_Leaf_Nov11_Prize','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/nov11_prize?';
        $this->name = '金叶1+1,快乐双11';
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
        $time =  strtotime('2020-11-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);

//        $data['count'] = $this->model->table_count('zy_leaf_message a',"UNIX_TIMESTAMP(a.add_time)>'$time'");
//        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $sql = "select * from zy_leaf_prize_config  WHERE  type2=? ";
        $prize  = $this->db->query($sql,['nov_11'])->result_array();

        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num from zy_leaf_message a,zy_leaf_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND UNIX_TIMESTAMP(a.add_time)>'$time'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        foreach($prize as &$value) {
            $shop = $this->db->query("select type1,description,`name`  from zy_shop  WHERE  shopid=?;", [$value['shop']])->row_array();
            $count = $this->db->query(
                "select pid,count(*) as num from zy_leaf_prize_record WHERE pid=?;", [$value['id']]
            )->row_array();
            $value['num'] = $count['num'];
            if ($value['money']) {
                $value['name'] = '闪电、银元';
            }

            if ($shop) {
                $value['name'] = $shop['type1'] ? $shop['name'] : $shop['description'];
            }
            $value['number'] = $value['number'] ? $value['number'] : '不限';
            $value['address_num'] = 0;
            if (count($lists)) {
                foreach ($lists as $v) {
                    if ($v['pid'] == $value['id']) {
                        $value['address_num'] = $v['num'];
                    }

                }
            }

        }
        $data['list'] = ($prize);

        // 搜索
        $data['fields'] = ['' => ''];
        //后台访问日志
        $this->log_admin_model->logs('查询双十一奖品信息',1);


        $this->load->view($this->mayday_prize_view,$data);
    }


    /**
     * 春节叠金叶抽奖记录
     */
    function springfest_prize()
    {
        if(!permission('SYS_Leaf_Prize','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/springfest_prize?';
        // 查询条件
        $where = '1=1 ';
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
        $time =  strtotime('2021-01-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);
        $where .= " and a.pid=p.id and UNIX_TIMESTAMP(a.add_time)>$time";
        $data['count'] = $this->model->table_count("zy_leaf_prize_record a,zy_leaf_prize_config p",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $lists = $this->model->table_lists("zy_leaf_prize_record a,zy_leaf_prize_config p",'a.uid,a.add_time,a.shandian,a.money,p.shop,p.shop_num,p.type', $where, 'a.id DESC', $this->per_page, $offset);
        foreach($lists as &$value)
        {
            if($value['shandian'] || $value['money'])
            {
                $value['shop'] = '';
                $value['shop_num'] = 0;
            }
            else
            {
                $sql = "select s.name from zy_shop s WHERE  s.shopid=? ";
                $prize  = $this->db->query($sql,[$value['shop']])->row_array();

                $value['shop'] = $prize['name'];

            }
        }

        $data['list'] = ($lists);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶奖品信息',1);
        $this->load->view( $this->springfest_prizeview,$data);
    }

    /**
     * 春节活动
     * 叠金叶培养记录
     */
    function springfest_record()
    {
        if(!permission('SYS_Leaf_Prize','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/springfest_record?';
        // 查询条件
        $where = '1=1 ';
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
        $time =  strtotime('2021-01-01 00:00:00');
        // URL及分页
        $offset = intval($_GET['per_page']);
        $where .= " and UNIX_TIMESTAMP(a.add_time)>$time";
        $data['count'] = $this->model->table_count("zy_leaf_peiyang_record a",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $lists = $this->model->table_lists("zy_leaf_peiyang_record a",'a.*', $where, 'a.id DESC', $this->per_page, $offset);

//        foreach($lists as &$value)
//        {
//
//        }

        $data['list'] = ($lists);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶培养信息',1);
        $this->load->view( $this->springfest_recordview,$data);
    }
    /**
     * 春节活动
     * 叠金叶用户信息
     */
    function springfest_user()
    {
        if(!permission('SYS_Leaf_Prize','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/springfest_user?';
        // 查询条件
        $where = '1=1 ';
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

        $data['count'] = $this->model->table_count("zy_leaf a",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $lists = $this->model->table_lists("zy_leaf a",'a.*', $where, 'a.id DESC', $this->per_page, $offset);
//        foreach($lists as &$value)
//        {
//
//        }

        $data['list'] = ($lists);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠金叶用户信息',1);
        $this->load->view( $this->springfest_userview,$data);
    }
    //统计奖品兑换次数
    function count_index(){
        $count1 = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE pid=10")->row_array();
        $count2 = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE  pid=9")->row_array();
        $count3 = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE pid=8")->row_array();
        $count4 = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE  pid=7")->row_array();
        $key = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE  pid=6")->row_array();
        $poker = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE  pid=5")->row_array();
        $ticket_1200 = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE  pid=4")->row_array();
        $shop = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE pid=3")->row_array();
        $money = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE pid=1")->row_array();
        $shandian = $this->db->query("select count(*) as num from zy_leaf_prize_record WHERE pid=2")->row_array();

        $count = $this->db->query("select count(*) as num from zy_leaf_prize_record ")->row_array();
        echo "<h2>抽奖次数总数：$count[num]</h2>";
        echo '瑞士军刀：'.$count1['num'].'<br/>';
        echo '香草传奇抱枕被：'.$count2['num'].'<br/>';
        echo '车载烟灰缸：'.$count3['num'].'<br/>';
        echo '洁丽雅纯棉舒适面巾：'.$count4['num'].'<br/>';
        echo '真龙君钥匙扣：'.$key['num'].'<br/>';
        echo '真龙君扑克牌：'.$poker['num'].'<br/>';
        echo '1200乐豆品吸机会代金券：'.$ticket_1200['num'].'<br/>';
        echo '四星原生态调香书：'.$shop['num'].'<br/>';
        echo '闪电：'.$money['num'].'<br/>';
        echo '银元：'.$shandian['num'].'<br/>';


    }

    function prize_list()
    {

        $time = strtotime('2020-03-01 00:00:00');
        $tables = ['zy_ranking_zz_prize_record','zy_ranking_zy_prize_record'];
        $array = [];
        foreach($tables as $value)
        {

            $list = $this->db->query("SELECT ticket.uid,ticket.add_time log_time,ticket.id,ticket.pid,c.shop3_id shop1
                     FROM $value ticket, zy_ranking_jf_prize_config c
                     WHERE c.shop3_id>0 and ticket.`pid` = c.`id`
                     AND UNIX_TIMESTAMP(ticket.add_time)>'$time'
                     ORDER BY ticket.add_time ")->result_array();

            if($list && count($list)>0)
            {
                foreach($list as $key => $value)
                {
                    $array[] = $value;
                }
            }
        }


        $count_list = [];
        $countprize = [];
        foreach($array as &$value)
        {
            $res = $this->db->query("select id as a_id,truename,phone,address,add_time from zy_plant_ranking_message WHERE uid='$value[uid]' AND pid='$value[id]'")->row_array();
            $value['truename'] = $res?$res['truename']:'';

            $row = $this->db->query("select `name` from zy_shop WHERE shopid='$value[shop1]'")->row_array();
            if($row)
            {
                $value['name'] = $row['name'];
                 array_push($count_list,$value['name']);
            }
            $value['a_id'] = $res?'是':'<span style="color:#FF0000">无</span>';
            if($res)
            {
                array_push($countprize,$value['name']);
            }

            $value['addtime'] =  $value['log_time'] ;
            unset($value['log_time'],$value['id'],$value['pid'], $value['shop1']);
        }
        $idArr = array_column($array, 'name');
        array_multisort($idArr,SORT_DESC,$array);
        $count = array_count_values($count_list);
        $countprize = array_count_values($countprize);

        echo '<h3>已领取奖品人数</h3>';
        echo '水晶烟灰缸：'.$count['乐豆中心水晶烟灰缸'].'&nbsp 填写：'.$countprize['乐豆中心水晶烟灰缸'].'<br/>';
        echo '暖风机：'.$count['乐豆中心超静音创意桌面暖风机'].'&nbsp  '.$countprize['乐豆中心超静音创意桌面暖风机'].'<br/>';
        echo '香草传奇定制加长加宽鼠标垫：'.$count['香草传奇定制加长加宽鼠标垫'].'&nbsp  '.$countprize['香草传奇定制加长加宽鼠标垫'].'<br/>';
        echo '洁丽雅纯棉舒适面巾：'.$count['洁丽雅纯棉舒适面巾'].'&nbsp  '.$countprize['洁丽雅纯棉舒适面巾'].'<br/>';
        echo '真龙君笔记本：'.$count['真龙君笔记本'].'&nbsp   '.$countprize['真龙君笔记本'].'<br/>';
        echo '真龙君钥匙扣：'.$count['真龙君钥匙扣'].'&nbsp  '.$countprize['真龙君钥匙扣'].'<br/>';
        echo '香薰蜡烛：'.$count['香薰蜡烛礼盒装'].'&nbsp  '.$countprize['香薰蜡烛礼盒装'].'<br/>';
        echo '<table border="1" cellspacing="0px" >';
        echo '<tr bgcolor="#dddddd">';
        echo '<th >用户uid</th><th >姓名</th><th width="120">奖品</th><th >是否填写地址</th><th width="150">领取时间</th>';
        echo '</tr>';

        foreach ($array as $key=>$value)
        {
            echo '<tr>';
            foreach($value as $v)
            {
                echo "<td>{$v}</td>";
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    //已导出名单
    function exported_list(){

        $count = $this->db->query("select count(*) as num from zy_plant_ranking_message WHERE `out`=1")->row_array();
        $list = $this->return_list();

        foreach($list as &$va)
        {
            $row = $this->db->query("select `name` from zy_shop WHERE shopid='$va[shop]'")->row_array();



            $va['name'] = $row['name'];

            unset($va['shop'],$va['pid']);
        }
        $idArr = array_column($list, 'name');
        array_multisort($idArr,SORT_DESC,$list);
        echo '<table border="1" cellspacing="0px" >';
        echo '<caption><h2>已导出名单列表 ('. $count['num'].')</h2></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th width="80">用户奖品id</th><th>用户id</th><th width="60">姓名</th><th>电话</th><th>地址</th><th width="120">奖品</th>';
        echo '</tr>';

        foreach ($list as $key=>$value)
        {
            echo '<tr>';
            foreach($value as $v)
            {
                echo "<td>{$v}</td>";
            }
            echo '</tr>';
        }
        echo '</table>';
    }

        // 导出Excel
        public function excelOut()
        {

            $where = "  ticket_id=0 and c.type=1 and a.out=0 ";
            $list = $this->db->select('a.truename,a.phone,a.address,a.uid,b.name,a.add_time,c.add_time receive_time')->from('zy_leaf_message a')
                ->join('zy_leaf_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
                ->where($where)->get()->result_array();
//            $tables = ['zy_ranking_zz_prize_record','zy_ranking_zy_prize_record'];
//            foreach($tables as $value)
//            {
//                $array  = $this->db->query(
//                    "select a.id,a.truename,a.phone,a.address,a.uid,a.add_time,c.pid,c.add_time receive_time
//                from zy_plant_ranking_message a,$value c
//                WHERE  a.uid=c.uid AND c.type=1 and a.out=0 AND a.status=1
//                AND a.pid=c.id")->result_array();
//                if($array && count($array)>0)
//                {
//                    foreach($array as $key => $value)
//                    {
//                        $list[] = $value;
//                    }
//                }
//            }
//
//
//
//            if(count($list)>0){
//                foreach($list as &$value){
//                    $sql = "select s.name from zy_ranking_jf_prize_config p,zy_shop s WHERE  p.shop3_id=s.shopid AND p.shop3_id>0 AND p.id=?";
//                    $prize  = $this->db->query($sql,[$value['pid']])->row_array();
//
//                    $value['name'] = $prize['name'];
//                    $value['receivetime'] = $value['receive_time'];
//                    $value['addtime'] = $value['add_time'];
//                    unset($value['pid'],$value['add_time'],$value['receive_time']);
//                }
//            }


            $table_data = '<table border="1"><tr>
                        <th colspan="6">用户名单</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>

                <th>姓名</th>
      			<th>电话</th>
      			<th>邮寄地址</th>
                <th>奖品</th>
                <th>领取时间</th>
                <th>填写时间</th>
    			</tr>';

            header('Content-Type:text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition:attachment;filename="种植大比拼活动奖品名单.xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            foreach ($list as &$line) {

                $this->db->where('id',$line['id'])
                    ->update('zy_leaf_message',array('out'=>1));
                unset($line['uid'],$line['id']);

                $table_data .= '<tr>';
                $line['address']=urlencode($line['address']);//将关键字编码
                $line['address']=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$line['address']);
                $line['address']=urldecode($line['address']);//将过滤后的关键字解码
                foreach ($line as $key => &$item) {

//                     $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                    $table_data .= '<td>' . $item . '</td>';
                }
                $table_data .= '</tr>';
            }
            $table_data .= '</table>';
            echo $table_data;
        }

    function test(){
        $query = $this->db->query(
            "select a.id,a.uid,a.add_time,c.pid
                from zy_leaf_message a,zy_leaf_prize_record c
                WHERE  c.ticket_id=0 and c.type=1
                AND a.pid=c.id");
        $list = $query->result_array();
        if(count($list)>0){
            foreach($list as &$value){
                $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE  p.shop=s.shopid AND p.id=?";
                $prize  = $this->db->query($sql,[$value['pid']])->row_array();
                $value['name'] = $prize['name'];
                $value['addtime'] = $value['add_time'];
                unset($value['pid'],$value['add_time']);
            }
        }
        print_r($list);
    }







}
