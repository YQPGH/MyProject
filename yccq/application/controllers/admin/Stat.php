<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 新闻资讯控制器
 */
include_once 'Content.php';

class Stat extends Content
{
    function __construct()
    {
        $this->name = '统计';
        $this->control = 'stat';
        $this->list_view = 'stat_index'; // 列表页
        $this->add_view = 'stat_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 1;
        $this->baseurl = site_url('admin/stat/index');
        $this->load->model('admin/stat_model', 'model');
        $this->load->model('admin/stat_day_model');
    }

    // 首页
    public function index()
    {
        $this->name = '统计概况';
        if(!permission('SYS_Stat','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

        $data['year'] = $this->input->post('year');
        if(!$data['year']) $data['year'] = date('Y');

        $result = $this->model->month($data['year']);

        if ($result)
        {
            $data['dates'] = json_encode($result['dates']);
            $data['users'] = join(',', $result['users']);
            $data['active'] = join(',', $result['active']);
            $data['logins'] = join(',', $result['logins']);
            $data['user_gamelv'] = join(',', $result['user_gamelv']);

        }
        //后台访问日志
        $this->log_admin_model->logs('查询统计概况',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function day()
    {
        if(!permission('SYS_Stat_day','read')) show_msg('没有操作权限！');
        $this->baseurl = site_url('admin/stat/day');
        $url_forward = $this->baseurl . '/day?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
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
        $data['count'] = $this->stat_day_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $list = $this->stat_day_model->lists('*', $where, 'stat_day DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['stat_day' => '日期'];
        //后台访问日志
        $this->log_admin_model->logs('查询每日统计',1);
        $this->load->view('admin/stat_day', $data);
    }

    function init_month() {
        //$this->model->xxx();
    }

    function listexcelOut()
    {


        $date = date('Y-m-d');
        $starttime = $this->input->post('starttime');
        $endtime = $this->input->post('endtime');

        $list = $this->db->query("select stat_day,active,new_user,logins,money,ledou,zhiyan,ticket,guanka from zy_stat_day
where unix_timestamp(stat_day)>=? and unix_timestamp(stat_day)<=? ",[strtotime($starttime),strtotime($endtime)])->result_array();

        if(count($list)>0)
        {
            $title = '统计';
            foreach($list as &$value)
            {

                unset($value);
            }

            $table_data = '<table border="1"><tr>
                        <th colspan="9">游戏统计信息</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>
                <th>日期</th>
      			<th>活跃用户</th>
      			<th >新增用户</th>
                <th >游戏次数</th>
                <th >银元交易额</th>
                <th>乐豆交易额</th>
                <th>制烟次数</th>
                <th >品吸劵数</th>
                <th>关卡游戏数</th>
    			</tr>';
            header('Content-Type: text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition: attachment;filename="'.$title.$date.'.xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            foreach ($list as &$line) {

                unset($line['id']);
                $table_data .= '<tr>';

                foreach ($line as  &$item) {

                    // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                    $table_data .= '<td>' . $item . '</td>';
                }
                $table_data .= '</tr>';
            }
            $table_data .= '</table>';
            echo $table_data;
        }
        else
        {
            show_msg('该时间段数据不存在');
        }

    }

}
