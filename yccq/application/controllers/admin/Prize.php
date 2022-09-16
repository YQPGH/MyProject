<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  奖品配置
 */
include_once 'Content.php';

class Prize extends Content
{
    function __construct()
    {
        $this->name = '商品';
        $this->control = 'prize';
        $this->list_view = 'prize_list'; // 列表页
        $this->add_view = 'prize_add'; // 添加页
        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/prize/');
        $this->load->model('admin/prize_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Prize','read')) show_msg('没有操作权限！');
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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        //搜索
        $data['fields'] = ['name' => '名称', 'shop1' => '编号'];
        //后台访问日志
        $this->log_admin_model->logs('查询奖励信息',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Prize','write')){
            show_msg('没有操作权限！');
        }
        $value['total'] = 1000;
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Prize','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {

        if(!permission('SYS_Prize','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (!$data['name']) show_msg('奖品名称不能为空');

        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改奖励信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加奖励信息',1);
            show_msg('添加成功！', 'index');
        }
    }

    //获取
    public function get_shopid(){
        $arr = [0=>'zhongzi',1=>'peifang',2=>'yanye',3=>'yanye_kao',4=>'yanye_chun',5=>'yan',6=>'yan_pin',7=>'lvzui',8=>'daoju',9=>'shiwu'];
        $arr_name = [0=>'种子',1=>'配方',2=>'烟叶',3=>'烟叶-烤',4=>'烟叶-醇',5=>'烟',6=>'烟叶-品',7=>'滤嘴',8=>'道具',9=>'实物'];
        foreach($arr as $key=>$value){
            $list[$key] = $this->model->get_row('zy_shop', 'shopid,name' , "`type1`='$value'");
        }
        $temp_list = [];
        for($i=1;$i<6;$i++){
            foreach($list as $key=>$value){
                foreach($value as $k=>$val){
                    if($i == $val['type2']){
                        $temp_list[$key][$i][] = $val;
                    }
                    if($val['type2']==0){
                        if($i==1){
                            $temp_list[$key][$i][] = $val;  //券、实体烟没有等级
                        }
                    }
                }
            }
        }

        $data['list'] = $temp_list;
        $data['arr'] = $arr;
        $data['arr_name'] = $arr_name;

        $this->load->view('admin/shopid_view', $data);
    }

    public function delete()
    {
        if(!permission('SYS_Prize','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }


}
