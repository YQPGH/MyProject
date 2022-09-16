<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 内容总类
*/
include_once 'Base.php';

class Content extends Base
{
    public $name = '内容名称'; // 模块中文名
    public $control = 'content'; // 控制器名称
    public $model_name = 'base_model';
    public $baseurl = 'admin/content/'; // 本控制器的前段URL
    public $list_view = 'content_list'; // 列表页
    public $add_view = 'content_add'; // 添加页
    public $per_page = 20; // 每页显示20条
    public $like_fields = array('title'); // 列表页，模糊查询字段

    function __construct()
    {
        parent::__construct();

        if (empty($this->uid)) {
            show_msg('请先登录', site_url('admin/common/login'));
        }


    }

    // 首页
    public function index()
    {
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
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);
            $where .= " AND (";
            foreach ($this->like_fields as $key => $field) {
                if ($key == 0) {
                    $where .= " $field like '%{$keywords}%' ";
                } else {
                    $where .= " OR $field like '%{$keywords}%' ";
                }
            }
            $where .= ") ";
        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
//        foreach ($list as &$value) {
//            if($value['thumb']) $value['thumb'] = base_url($value['thumb']);
//        }
        $data['list'] = ($list);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $id = $this->input->get('id');
        $id = check_id($id);
        
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = html_escape_move($value);

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {

        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
//         if ($data['title'] == "") {
//             show_msg('标题不能为空');
//         }

//        // 生成两张缩略图
//        if($data['thumb']) {
//            $data['thumb'] = str_replace('../','',$data['thumb']);
//            thumb2($data['thumb']);
//        }

        if ($id) { // 修改 ===========         
            $this->model->update($data, $id);
            show_msg('修改成功！', $this->admin['url_forward']);
        } else { // ===========添加 ===========
            $data['addtime'] = time();
            $this->model->insert($data);
            show_msg('添加成功！', $this->admin['url_forward']);
        }
    }

    // 删除
    public function delete()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->delete($id);
            }
        } else {
            $this->model->delete($id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

    // 审核
    public function update_status()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $status = intval($this->input->post('status'));
        if ($id && strlen($status)) {
            echo $this->model->update_status($status, $id);
        } else {
            echo 0;
        }
    }


    // 导出Excel
    public function excelOut()
    {
        $query = $this->db->query(
            "select id,title,addtime from $this->table where catid='$_GET[catid]'");
        $list = $query->result_array();
        $table_data = '<table border="1"><tr>
      			<th colspan="3">标题在这里哦</th>
    			</tr>';

        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="范德萨发的说法.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($list as $line) {
            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {
                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;
    }

    // 导入Excel
    public function excelIn()
    {
        require_once APPPATH . 'libraries/Spreadsheet_Excel_Reader.php';
        // require_once 'Excel/reader.php'; //加载所需类
        $data = new Spreadsheet_Excel_Reader(); // 实例化
        $data->setOutputEncoding('utf-8'); // 设置编码
        $data->read('test.xls'); // read函数读取所需EXCEL表，支持中文
        print_r($data->sheets[0]['cells']);
        exit();
    }

    // 返回分页信息
    public function page_html($url, $count)
    {
        $this->config->load('pagination', true);
        $pagination = $this->config->item('pagination');
        $pagination['base_url'] = $url;
        $pagination['total_rows'] = $count;
        $pagination['per_page'] = $this->per_page;
        $this->load->library('pagination');
        $this->pagination->initialize($pagination);

        return $this->pagination->create_links();
    }

    function menu_show(){
        echo 1;
    }
}
