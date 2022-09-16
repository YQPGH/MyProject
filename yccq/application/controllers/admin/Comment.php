<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/*
 * 视频评论
 */

include 'Content.php';

class Comment extends Content
{
    function __construct()
    {
        $this->name = '视频评论';
        $this->control = 'comment';
        $this->model_name = 'comment_model';
        $this->list_view = 'comment_list'; // 列表页
        $this->add_view = 'comment_add'; // 添加页

        parent::__construct();
        $this->baseurl = site_url('admin/comment/');
    }

    // 首页
    public function index()
    {
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        $catid = check_id($catid);
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
        $offset = $_GET['per_page'];
        $offset = check_id($offset);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {

        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = array('title'=>'标题', 'title2'=>'副标题', 'tags'=>'标签');

        $this->load->view('admin/' . $this->list_view, $data);
    }


    // 删除
    public function delete()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            $this->model->delete($id);
        } else {
            $this->model->delete($id_arr);
        }

        // 删除评论

        show_msg('删除成功！', $this->admin['url_forward']);
    }

}
