<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Laxin extends Content{

    function __construct(){
        $this->name = '拉新';
        $this->control = 'laxin';
        $this->list_view = 'admin/laxin_list'; //用户信息列表页
        $this->invite_view = 'admin/laxin_invite_list'; //用户信息列表页
        $this->prize_view = 'admin/laxin_prize_list'; //奖品配置列表页
        $this->add_view = 'admin/laxin_prize_add'; //添加页
        parent ::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/laxin/');
        $this->load->model('admin/laxin_model','model');
    }

    //拉新纪录
    function index(){
        if(!permission('SYS_Laxin','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
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
//        $query_count['num'] = $this->model->count($where);
        //$query_count['num'] = $this->model->table_count("zy_laxin a ",$where);
        //$data['count'] = $query_count['num'];
//        $where .= " and a.total_ticket>0 ";
        $sql = "SELECT COUNT(*) as num  FROM zy_laxin a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_laxin a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = $list;

        // 搜索
        $data['fields'] = ['a.uid' => '用户ID','b.nickname'=>'昵称'];
        //后台访问日志
        $this->log_admin_model->logs('查询拉新记录',1);
        $this->load->view( $this->list_view, $data);

    }

    //拉新邀请纪录
    function invite_list(){
        if(!permission('SYS_Laxin','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/invite_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
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
//        $query_count['num'] = $this->model->count($where);
        //$query_count['num'] = $this->model->table_count("zy_laxin a ",$where);
        //$data['count'] = $query_count['num'];

        $sql = "SELECT COUNT(*) as num  FROM zy_laxin_record a  WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_laxin_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
//            $sql = "select add_time from zy_laxin_record  WHERE (";
//            $sql .= "(invited_uid='$value[uid]' and uid!='$value[uid]' ) or " ;
//            $sql .=  "(uid='$value[uid]' and invited_uid!='$value[uid]'))";
            $row  = $this->db->query("SELECT nickname  FROM zy_user WHERE uid='$value[invited_uid]'")->row_array();
            if($row){
                $value['friend_nickname'] = $row['nickname'];
            }
        }
        $data['list'] = $list;

        // 搜索
        $data['fields'] = ['a.uid' => '用户ID','b.nickname'=>'昵称'];
        //后台访问日志
        $this->log_admin_model->logs('查询拉新邀请记录',1);
        $this->load->view( $this->invite_view, $data);

    }


    function laxin_config(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Laxin','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . 'laxin_config?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }

        $where .= " and type2 = 'lx'";

            // URL及分页
        $offset = intval($_GET['per_page']);

        $query_count['num'] = $this->model->table_count("zy_prize a ",$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->table_lists('zy_prize a','a.*', $where, 'a.id ASC', $this->per_page, $offset);
        foreach($list as &$value)
        {
             $value['money'] = '银元：'.$value['money'];
             $value['shandian'] = '闪电：'.$value['shandian'];
        }
        $data['list'] = $list;

        // 搜索
        $data['fields'] = ['a.name' => '奖品名称'];
        $this->load->view( $this->prize_view, $data);
    }

    function name_list(){
        if(!permission('SYS_Laxin_Name_List','read')) show_msg('没有操作权限！');
//        $_SESSION['nav'] = 8;
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
        $data['count'] = $this->model->table_count('zy_laxin_message a','1=1 ');
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.out=1 AND a.uid=b.uid';
        $list = $this->model->table_lists('zy_laxin_message a,zy_user b','a.pid,a.uid,a.truename,a.address,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as &$value)
        {
            $value['address'] = str_replace(",",'',$value['address']);

            $row = $this->db->query("select pid,add_time from zy_laxin_prize_record WHERE uid='$value[uid]' AND id='$value[pid]'")->row_array();
            $sql = "select s.name from zy_prize p,zy_shop s WHERE  p.shop1=s.shopid AND p.id=? ";
            $prize  = $this->db->query($sql,[$row['pid']])->row_array();
            $value['add_time'] = $row['add_time'];

            $value['name'] = $prize['name'];

            unset($value['uid'],$value['pid']);

        }
        $idArr = array_column($list, 'add_time');
        array_multisort($idArr,SORT_DESC,$list);
        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['b.openid' => 'openID'];
        //后台访问日志
        $this->log_admin_model->logs('查询召集制烟师名单',1);

        $this->load->view('admin/laxin_name_list', $data);
    }

    // 添加
    public function add()
    {
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Laxin','write')){
            show_msg('没有操作权限！');
        }
        $value['catid'] = intval($_REQUEST['catid']);

        $data['value'] = $value;

        $this->load->view($this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Laxin','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->db->query("select * from zy_prize WHERE id=?",[$id])->row_array();

        $data['value'] = $value;

        $this->load->view( $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {

        if(!permission('SYS_Laxin','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);

        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->table_update('zy_prize',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改奖励信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->table_insert('zy_prize',$data);
            //后台访问日志
            $this->log_admin_model->logs('添加奖励信息',1);
            show_msg('添加成功！', 'laxin_config');
        }
    }


    public function delete()
    {

        parent::delete();
    }

    // 删除
    public function table_delete()
    {
        if(!permission('SYS_Laxin','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->table_delete('zy_prize',$id);
            }
        } else {
            $this->model->delete('zy_prize',$id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

    //统计奖品兑换次数
    function count_index(){
        $count1 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=394")->row_array();
        $count2 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=395")->row_array();
        $count3 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=396")->row_array();
        $count4 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=397")->row_array();
        $count5 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=398")->row_array();
        $count6 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=399")->row_array();
        $count7 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=400")->row_array();
        $count8 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=401")->row_array();
        $ticket_1300 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=402")->row_array();
        $ticket_1200 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=403")->row_array();
        $ticket_1000 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=404")->row_array();
        $key = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=405")->row_array();
        $ticket_800 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=406")->row_array();
        $ticket_500 = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=407")->row_array();
        $poker = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=408")->row_array();
        $shop = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=409")->row_array();
        $headframe = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid in(410,411,412)")->row_array();
        $money = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=413")->row_array();
        $shandian = $this->db->query("select count(*) as num from zy_laxin_prize_record WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and pid=414")->row_array();

        echo '瑞士军刀：'.$count1['num'].'<br/>';
        echo '车载烟灰缸：'.$count2['num'].'<br/>';
        echo '飞科剃须刀：'.$count3['num'].'<br/>';
        echo '负离子吹风：'.$count4['num'].'<br/>';
        echo '飞科吹风筒：'.$count5['num'].'<br/>';
        echo '冲茶器：'.$count6['num'].'<br/>';
        echo '旅行杯：'.$count7['num'].'<br/>';
        echo '乐扣搅拌杯：'.$count8['num'].'<br/>';
        echo '1300乐豆代金券：'.$ticket_1300['num'].'<br/>';
        echo '1200乐豆代金券：'.$ticket_1200['num'].'<br/>';
        echo '1000乐豆代金券'.$ticket_1000['num'].'<br/>';
        echo '真龙君钥匙扣：'.$key['num'].'<br/>';
        echo '800乐豆代金券：'.$ticket_800['num'].'<br/>';
        echo '500乐豆代金券：'.$ticket_500['num'].'<br/>';
        echo '真龙君扑克牌：'.$poker['num'].'<br/>';
        echo '五星调香书：'.$shop['num'].'<br/>';
        echo '头像框：'.$headframe['num'].'<br/>';
        echo '银元：'.$money['num'].'<br/>';
        echo '闪电：'.$shandian['num'].'<br/>';

    }

    //已导出名单
    function exported_list(){
        $count = $this->db->query("select count(*) as num from zy_laxin_message WHERE uid!='10e4bf3ae3ee123351c5921f9f167bdd' and `out`=1")->row_array();

        $where = " a.uid!='10e4bf3ae3ee123351c5921f9f167bdd' and c.is_real=1 and a.out=1 ";
        $list = $this->db->select('c.id,a.uid,a.truename,a.phone,a.address,b.name')->from('zy_laxin_message a')
            ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
            ->where($where)->get()->result_array();

        echo '<table border="1" cellspacing="0px" width="1000">';
        echo '<caption><h2>已导出名单列表 ('. $count['num'].')</h2></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th width="80">用户奖品id</th><th>用户id</th><th width="60">姓名</th><th>电话</th><th>地址</th><th width="100">奖品</th>';
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

            $where = " a.uid!='10e4bf3ae3ee123351c5921f9f167bdd' and c.is_real=1 and a.out=0 ";
            $list = $this->db->select('a.truename,a.phone,a.address,a.uid,b.name,a.add_time')->from('zy_laxin_message a')
                ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
                ->where($where)->get()->result_array();


//            $where = " a.uid!='10e4bf3ae3ee123351c5921f9f167bdd' and c.is_real=1 and a.out=0";
//            $list = $this->db->select('a.truename,a.phone,a.address,a.add_time,a.uid,b.name')->from('zy_laxin_message a')
//                ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
//                ->where($where)->get()->result_array();

//            $query = $this->db->query(
//                "select truename,phone,address from zy_laxin_message WHERE status=1 AND uid!='10e4bf3ae3ee123351c5921f9f167bdd'");
//            $list = $query->result_array();


            $table_data = '<table border="1"><tr>
                        <th colspan="5">用户名单</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>

                <th >姓名</th>
      			<th >电话</th>
      			<th >邮寄地址</th>
                <th>奖品</th>
                <th>时间</th>
    			</tr>';
            header('Content-Type: text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition: attachment;filename="香草传奇一荐双赢活动奖品名单.xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            foreach ($list as &$line) {
                $this->db->where('uid',$line['uid'])
                    ->update('zy_laxin_message',array('out'=>1));
                unset($line['uid']);
                $table_data .= '<tr>';
                $line['address']=urlencode($line['address']);//将关键字编码
                $line['address']=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$line['address']);
                $line['address']=urldecode($line['address']);//将过滤后的关键字解码
                foreach ($line as $key => &$item) {

                    // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                    $table_data .= '<td>' . $item . '</td>';
                }
                $table_data .= '</tr>';
            }
            $table_data .= '</table>';
            echo $table_data;
        }


    function test(){
//        $where = " a.truename='韦建民' and c.is_real=1 and a.out=0 ";
//        $list = $this->db->select('a.truename,a.phone,a.address,a.uid,b.name,a.add_time')->from('zy_laxin_message a')
//            ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
//            ->where($where)->get()->result_array();

//        SELECT `a`.`truename`, `a`.`phone`, `a`.`address`, `a`.`uid`, `b`.`name`, `a`.`add_time`
//        FROM `zy_laxin_message` `a` LEFT JOIN `zy_laxin_prize_record` `c` ON `a`.`pid`=`c`.`id`
//        LEFT JOIN `zy_prize` `b` ON `b`.`id`=`c`.`pid` WHERE `a`.`truename` = '韦建民'
//        and `c`.`is_real` = 1 and `a`.`out` = 0
//        print_r($list);exit;
    }
}
