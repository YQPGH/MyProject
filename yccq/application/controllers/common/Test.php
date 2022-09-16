<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

define('HOME_STATIC', 'application/views/home/static');

// 首页 文章
include 'base.php';

class Test extends base
{
    public $table = 'fly_news';
    public $ad1 = '';

    function __construct()
    {
        parent::__construct();

        $this->load->model('setting_model');
        $this->load->model('news_model');

        //$this->ad1 = $this->getAD(1,700,90);
    }

    // 网站首页
    public function index()
    {
        $data['slide_list'] = $this->slide_list();// 滑动广告
        $data['list1'] = $this->getNewslist(1, 6);
        $data['list2'] = $this->getNewslist(2, 14);
        $data['list3'] = $this->getNewslist(3, 15);
        $data['list5'] = $this->getNewslist(5, 15);
        $data['list7'] = $this->getNewslist(7, 15);
        $data['list6'] = $this->getNewslist(6, 15);
        $data['list4'] = $this->getNewslist(4, 15);
        $data['list10'] = $this->getNewslist(10, 15);

        $data['head'] = array(
            'title' => $this->setting_model->get('title'),
            'keywords' => $this->setting_model->get('keywords'),
            'description' => $this->setting_model->get('description')
        );

        $this->load->view('home/index', $data);
    }

    // 获取列表
    private function getNewslist($catid, $limit = 10)
    {
        //var_dump(M('news_model'));
        $list = $this->news_model->lists_sql("select id,catid,title,addtime from $this->table where status=1 and catid='$catid' order by sort asc,id desc LIMIT $limit");

        return $list;
    }


    // 列表页
    public function lists()
    {
        // 查询条件
        $where = 'status=1';
        $catid = $_REQUEST['catid'];
        $data['catid'] = $catid;
        $baseurl = site_url('home/lists?catid=' . $catid);
        $where .= " AND catid='$catid' ";

        $keywords = trim($_REQUEST['keywords']);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $baseurl .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);
            $where .= " AND title like '%{$keywords}%' ";
        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->news_model->count($where);
        $data['pages'] = $this->page_html($baseurl, $data['count']);

        // 列表数据
        $list = $this->news_model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['addtime'] = times($value['addtime'], 1);
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
        }
        $data['list'] = $list;


        $cate = config_item('news_type');
        $data['head'] = array(
            'title' => $cate[$catid] . ' - ' . $this->setting_model->get('title'),
            'keywords' => $cate[$catid],
            'description' => $cate[$catid]
        );

        $data['list5'] = $this->getNewslist(5, 15);
        $data['catname'] = $cate[$catid];

        $this->load->view('home/news_list', $data);
    }

    // 详细页
    public function show($id)
    {
        // $id = intval($this->input->get('id'));
        //echo $id;
        $value = $this->news_model->row(['id' => $id, 'status' => 1]);


        if (empty ($value)) {
            show_404('没有找到该信息，');
        }


        $data['head'] = array(
            'title' => $value[title] . ' - ' . $this->setting_model->get('title'),
            'keywords' => $value[title],
            'description' => $value[title]
        );

        $data['list5'] = $this->getNewslist(5, 15);
        $cate = config_item('news_type');
        $data['catname'] = $cate[$value[catid]];
        $data ['value'] = $value;

        $this->load->view('home/news_show', $data);
    }

    // 单页详细页
    public function pages()
    {

        $id = intval($this->input->get('id'));
        $query = $this->db->query("select * from fly_pages where id=$id and status=1 limit 1");
        $value = $query->row_array();
        if (empty ($value)) {
            show_404('没有找到该信息，');
        }

        $cate = config_item('cate');
        $data['head'] = array(
            'title' => $value[title] . ' - ' . $this->head['title'],
            'keywords' => $value[title],
            'description' => $value[title]
        );

        $data['list5'] = $this->getNewslist(5, 15);
        $data['catname'] = $cate[$value[catid]];
        $data ['value'] = $value;

        $this->load->view('news_show', $data);
    }

    // 广告显示
    public function adshow()
    {

        $id = intval($this->input->get('id'));
        $query = $this->db->query("select * from fly_ad where id=$id limit 1");
        $value = $query->row_array();
        if (empty ($value)) {
            show_404('没有找到该信息，');
        }

        $cate = config_item('cate');
        $data['head'] = array(
            'title' => $value[title] . ' - ' . $this->head['title'],
            'keywords' => $value[title],
            'description' => $value[title]
        );

        $data['list5'] = $this->getNewslist(5, 15);
        $data['catname'] = $cate[$value[catid]];
        $data ['value'] = $value;

        $this->load->view('news_show', $data);
    }

    // 通知 详细页
    public function insert()
    {
        $cate = getCategoryChild(0);
        foreach ($cate as $catid) {
            $this->insert_db($catid['id']);
            // 二级分类
            $child = getCategoryChild($catid['id']);
            if ($child) {
                foreach ($child as $catid2) {
                    $this->insert_db($catid['id'], $catid2['id']);
                }
            }
        }
        echo 'ok';
    }

    function insert_db($catid, $catid2 = 0)
    {
        if ($catid2) {
            $title = getCategoryName($catid2);
        } else {
            $title = getCategoryName($catid);
        }
        $title = str_repeat($title, 8);
        for ($i = 1; $i <= 50; $i++) {
            // 插入数据
            $data = array(
                'catid' => $catid,
                'catid2' => $catid2,
                'thumb' => '/gx886/uploads/file/20150927/20150927154349_71902.jpg',
                'title' => $title,
                'description' => '暨南大学新闻与传播专业综合能力考研复习精编，高屋建瓴,暨南大学新闻与传播专业综合能力考研复习精编高屋建瓴且对考查重点进行了分析说明，考生通过此部分内容可洞悉考试出题难度和题型，明确复习方向。',
                'content' => '1、高屋建瓴，提纲挈领　　构建章节主要考点框架、梳理全章主体内容与结构，使考生复习之初即可对专业课有深度把握和宏观了解。<br />2、去繁取精，高度浓缩　　高度浓缩初试参考书目中各章节核心考点要点并展开详细解析、以星级多寡标注知识点重次要程度，内容详略得当、考点明晰、重点突出， 便于高效复习。<br />3、往年真题，深入剖析　　包含往年试题及详细答案解析，且对考查重点进行了分析说明，考生通过此部分内容可洞悉考试出题难度和题型，明确复习方向。<br />4、层层递进，省时高效　　冲刺阶段可直接脱离教材而仅使用核心考点解析进行理解和背诵，复习效率和效果将比直接复习教材高达5-10倍;同时该内容相当于笔记，但比笔记更权威、更系统、更全面、重难点也更分明。
					1、高屋建瓴，提纲挈领　　构建章节主要考点框架、梳理全章主体内容与结构，使考生复习之初即可对专业课有深度把握和宏观了解。<br />2、去繁取精，高度浓缩　　高度浓缩初试参考书目中各章节核心考点要点并展开详细解析、以星级多寡标注知识点重次要程度，内容详略得当、考点明晰、重点突出， 便于高效复习。<br />3、往年真题，深入剖析　　包含往年试题及详细答案解析，且对考查重点进行了分析说明，考生通过此部分内容可洞悉考试出题难度和题型，明确复习方向。<br />4、层层递进，省时高效　　冲刺阶段可直接脱离教材而仅使用核心考点解析进行理解和背诵，复习效率和效果将比直接复习教材高达5-10倍;同时该内容相当于笔记，但比笔记更权威、更系统、更全面、重难点也更分明。
					1、高屋建瓴，提纲挈领　　构建章节主要考点框架、梳理全章主体内容与结构，使考生复习之初即可对专业课有深度把握和宏观了解。<br />2、去繁取精，高度浓缩　　高度浓缩初试参考书目中各章节核心考点要点并展开详细解析、以星级多寡标注知识点重次要程度，内容详略得当、考点明晰、重点突出， 便于高效复习。<br />3、往年真题，深入剖析　　包含往年试题及详细答案解析，且对考查重点进行了分析说明，考生通过此部分内容可洞悉考试出题难度和题型，明确复习方向。<br />4、层层递进，省时高效　　冲刺阶段可直接脱离教材而仅使用核心考点解析进行理解和背诵，复习效率和效果将比直接复习教材高达5-10倍;同时该内容相当于笔记，但比笔记更权威、更系统、更全面、重难点也更分明。',
                'addtime' => time(),
            );
            $this->db->insert($this->table, $data);
        }
    }


    // 获取一条广告
    public function getAD($id, $width, $height)
    {

        $query = $this->db->query("select id,title,thumb from fly_ad where id='$id' LIMIT 1");
        $value = $query->row_array();
        $adstr = "<a href=\"index.php?c=news&m=adshow&id={$value[id]}\"><img src=\"{$value[thumb]}\" width=\"{$width}\" height=\"{$height}\" alt='{$value[title]}'></a>";

        return $adstr;
    }

    // 返回分页信息
    public function page_html($url, $count)
    {
        $this->config->load('pagination', true);
        $pagination = $this->config->item('pagination');
        $pagination['base_url'] = $url;
        $pagination['total_rows'] = $count;
        $pagination['per_page'] = 20;
        $this->load->library('pagination');
        $this->pagination->initialize($pagination);

        return $this->pagination->create_links();
    }


    // 返回分页信息
    public function curl()
    {

        dump(t_curl('http://t.gxycy.cn/teacher/bind/save'));
    }


}
