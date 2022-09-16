<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 意见反馈
 */
include_once 'Content.php';

class Suggestion extends Content
{
    function __construct()
    {
        $this->name = '意见反馈';
        $this->control = 'suggestion';
        $this->list_view = 'suggestion_list'; // 列表页
        $this->add_view = 'reply_add'; // 添加页
        $this->reply_list_view = 'reply_list'; // 意见回复列表页

        parent::__construct();
        $_SESSION['nav'] = 5;
        $this->baseurl = site_url('admin/suggestion/');
        $this->load->model('admin/suggestion_model', 'model');
        $this->load->model('admin/menu_model');

    }

    // 首页
    public function index()
    {

        if(!permission('SYS_Suggestion_Record','read')) show_msg('没有操作权限！');

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

        // 列表数据
        $where .= 'AND b.uid=c.uid';
        // URL及分页
        $offset = intval($_GET['per_page']);
//        $query_count = $this->db->query("select count(b.id) as num from zy_suggestion b LEFT JOIN zy_user c on b.uid=c.uid WHERE $where")->row_array();
        $query_count['num'] = $this->model->table_count('zy_suggestion b,zy_user c',$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $list = $this->model->leftInner('zy_suggestion b,zy_user c','zy_reply a','a.rid = b.id',
            'b.*,c.openid,a.r_content,a.add_time as radd_time,a.update_time',
            $where,'id desc', $this->per_page, $offset);

        foreach($list as &$value){

            if ($value['img'])
            {
//                $value['img'] = base_url($value['img']);
                $value['img'] = "http://118.89.24.47/yccq/".$value['img']?"http://118.89.24.47/yccq/".$value['img']:"http://123.207.24.204/yccq/".$value['img'];

            }

        }

        $data['list'] = $list;

        // 搜索
        $data['fields'] = [
            'b.uid' => '用户ID',
            'c.openid' => 'openid'
        ];

        //后台访问日志
        $this->log_admin_model->logs('查询意见反馈信息',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }


    // 添加
    public function add()
    {
        if(!permission('SYS_Suggestion_Record','write')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $data['parents'] = $this->menu_model->get_child(0);
        $data['ids'] = [];

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Suggestion_Record','write')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
       
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $data['parents'] = $this->menu_model->get_child(0);
        $data['ids'] = explode(',', $value['menu_ids']);
        
        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {

        if(!permission('SYS_Suggestion_Record','write')) show_msg('没有操作权限！');

        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        $ids = $_POST['ids'];
        if($ids) {
            $data['menu_ids'] = join(',', $ids);
        }


        if (empty($data['r_content'])) {
            show_msg('回复内容不能为空');
        }

//       $row = $this->db->query("select count(*) as num,id from zy_reply where rid='$id'")->row_array();
        $row = $this->model->get_row("zy_reply ", 'count(*) as num,id',"`rid`='$id'");

        if ($row['num'] && $row['id']) { // 修改 ===========
            $id = $row['id'];
            $data['update_time'] = t_time();
            $this->model->table_update('zy_reply',$data,$id);
            //后台访问日志
            $this->log_admin_model->logs('修改意见反馈信息',1);
            show_msg('修改成功！', 'index');
        } else { // ===========添加 ===========

            $data['add_time'] = t_time();
            $data['rid'] = $id;
            $this->model->table_insert('zy_reply',$data);
            //后台访问日志
            $this->log_admin_model->logs('添加意见反馈信息',1);
            show_msg('添加成功！', 'index');
        }
    }

    // 导出Excel
    public function listexcelOut()
    {

        $yannong_type = config_item('yannong_type');
        $zhiyan_type = config_item('zhiyan_type');
        $jiaoyi_type = config_item('jiaoyi_type');
        $pinjian_type = config_item('pinjian_type');

        $query = $this->db->select('c.openid,c.nickname,c.uid,c.game_lv,c.game_xp,c.money,c.ledou,c.yannong_lv,c.zhiyan_lv,c.jiaoyi_lv,c.pinjian_lv,
            a.content,a.add_time,b.r_content,b.add_time as radd_time')->from('zy_suggestion a')
            ->join('zy_reply b', 'a.id=b.rid','left')->join('zy_user c','a.uid=c.uid','left')->get();

         $list = $query->result_array();

        foreach ($list as &$value) {

            $value['yannong_lv'] = $yannong_type[$value['yannong_lv']]['name'];
            $value['zhiyan_lv'] = $zhiyan_type[$value['zhiyan_lv']]['name'];
            $value['jiaoyi_lv'] = $jiaoyi_type[$value['jiaoyi_lv']]['name'];
            $value['pinjian_lv'] = $pinjian_type[$value['pinjian_lv']]['name'];
        }

        $table_data = '<table border="1"><tr>
      			<th colspan="3">烟草传奇</th>
    			</tr>';
        $table_data .= '<table border="1"><tr>
                <th >openid</th>

                <th >昵称</th>
      			<th >uid</th>
      			<th >游戏信息</th>
      			<th >成就信息</th>
      			<th >反馈内容</th>
      			<th >反馈时间</th>
      			<th >回复内容</th>
      			<th >回复时间</th>
    			</tr>';

        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="意见反馈.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');


        foreach (($list) as $line) {
            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {

                switch($key){

//                    case head_img: $table_data .= '<td>' .$img.'</td>' ;
//                        break;
                    case game_lv: $table_data .= '<td>' . '等级：' .$item;
                        break;
                    case game_xp:$table_data .='<br>'.' 经验值：' .$item;
                        break;
                    case money:$table_data .='<br>'.'银元：' .$item;
                        break;
                    case ledou: $table_data .='<br>' .'乐豆：'. $item .'</td>' ;
                        break;
                    case yannong_lv:$table_data .= '<td>' . '烟农：'.$item ;
                        break;
                    case zhiyan_lv:$table_data .= '<br>' . '制烟：'.$item ;
                        break;
                    case jiaoyi_lv:$table_data .= '<br>' . '交易：'.$item ;
                        break;
                    case pinjian_lv:$table_data .='<br>' .'品鉴：'. $item .'</td>';
                        break;
                   default:$table_data .= '<td>' . $item . '</td>';
                }
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';

        echo $table_data;
    }

    public function delete()
    {
        if(!permission('SYS_Suggestion_Record','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
