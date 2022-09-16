<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 订单
 */
include_once 'Content.php';

class Orders extends Content
{
    function __construct()
    {
        $this->name = '订单任务';
        $this->control = 'orders';
        $this->list_view = 'orders_list'; // 列表页
        $this->add_view = 'orders_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/orders/');
        $this->load->model('admin/orders_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Orders','read')) show_msg('没有操作权限！');

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
        //$where_count = $where.'AND type=1';
        //$data['count'] = $this->model->count($where_count);
        $query_count['num'] = $this->model->table_count("zy_orders a",  $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=c.uid ' ;//获取玩家昵称
        $list = $this->db->select('a.uid,a.order_id,a.add_time,b.name order_name,b.type1,b.shopid,b.shop_count,b.game_xp,b.money , c.nickname')
            ->from('zy_user c,zy_orders a')->join('zy_orders_config b', 'a.order_id=b.order_id','left')
            ->where($where)->order_by('a.id desc')->limit($this->per_page, $offset)->get()->result_array();
//        $list = $this->model->lists_sql("SELECT a.uid,a.order_id,a.add_time,b.name order_name,b.type1,b.shopid,b.shop_count,b.game_xp,b.money , c.nickname
//                                FROM zy_user c,zy_orders a LEFT JOIN zy_orders_config b ON a.order_id=b.order_id  WHERE {$where} LIMIT {$offset},{$this->per_page}" );

        foreach ($list as &$value) {
            if($value['shopid']){
                $query = $this->model->get_row('zy_shop',"name", "`shopid`='$value[shopid]'");
                $value['shop_name'] = $query['name'];
            }
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.order_id' => '订单ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询订单任务记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }


}
