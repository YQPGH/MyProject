<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  用户
 */
include_once 'Content.php';

class User extends Content
{
    public $status = ['正常', '锁定', '黑名单'];

    function __construct()
    {
        $this->name = '玩家信息';
        $this->control = 'user';
        $this->list_view = 'user_list'; // 列表页
        $this->add_view = 'user_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/user/');
        $this->load->model('admin/user_model', 'model');
    }

    // 首页
    public function index()
    {

        if(!permission('SYS_User','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $status = $_REQUEST['status'];
        $status = check_id($status);
        if ($status) {
            $data['status'] = $status;
            $url_forward .= '&status=' . $status;
            $where .= " AND status='$status' ";
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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $list = $this->model->list_all($where, $this->per_page, $offset);
        $yannong_type = config_item('yannong_type');
        $zhiyan_type = config_item('zhiyan_type');
        $jiaoyi_type = config_item('jiaoyi_type');
        $pinjian_type = config_item('pinjian_type');

        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['local_img']) $value['local_img'] = base_url($value['local_img']);
        
            $value['status'] = $this->status[$value['status']];
            $value['yannong_lv'] = $yannong_type[$value['yannong_lv']]['name'];
            $value['zhiyan_lv'] = $zhiyan_type[$value['zhiyan_lv']]['name'];
            $value['jiaoyi_lv'] = $jiaoyi_type[$value['jiaoyi_lv']]['name'];
            $value['pinjian_lv'] = $pinjian_type[$value['pinjian_lv']]['name'];
        }
        $time = array_column($list,'last_time');
         array_multisort($time,SORT_DESC,$list);
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = [
            'nickname' => '昵称',
            'uid' => 'uid',
            'openid' => 'openid',
            'truename' => '姓名',
            'tel' => '电话'];

        //后台访问日志
        $this->log_admin_model->logs('查询用户信息',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    //推送通知，并给权限
	
    public function sendMessage(){

        $uid = $this->input->get('uid');
        $uid = check_str($uid);
        // 这条信息
        $result = $this->sendUserInfon($uid);
        if($result['code'] == 0){
            $this->db->set('is_authority', 1 , FALSE);
            $this->db->set('send_times', 'send_times+1' , FALSE);
            $this->db->where('uid', $uid);
            $this->db->update('zy_user');
        }
        //后台访问日志
        $this->log_admin_model->logs('推送通知',1);
        $this->index();
    }

    //推送信息
    public function sendUserInfon($uid){
        $key = 'YCCQTicket';
        $messageType = '1';
        //获取没有资格的用户的openid
        $row = $this->model->get_row('zy_user','openid',"`uid`='$uid'");
        $temp = $key.'[';
        if($row){
            foreach($row as $key=>$value){
                $temp .=  '"'.$value.'",';
                $data[] = $value;
            }
            $temp = rtrim($temp, ',');
            $temp = $temp.']'.$messageType;
            $sign =  md5($temp);
            $res = ['sign'=>$sign,'messageType'=>$messageType,'data'=>$data];
            $url = 'http://ld.thewm.cn/zlbean/frontpage/message/userInfon';
            $result =$this->http($url,$res,1);
            return $result;
        }


    }
	
	//查看调查问卷内容
	function queryQuestionnaire(){
        $uid = $this->input->get('uid');
        $uid = check_str($uid);
        $row = $this->model->get_row('zy_user','openid,nickname',"`uid`='$uid'");
//		echo '微信昵称：'.$row['nickname'].'<br/>';
//		echo 'openid：'.$row['openid'].'<br/><br/>';
		echo "<h2>调查问卷内容</h2>";
		$count['num'] = $this->model->table_count("zy_questionnaire_record", "`uid`='{$uid}'");
		if($count['num']){
            $query = $this->db->select('id,title')->from('zy_questionnaire_config')->where('type2=1')->get();
            $question = $query->result_array();
//			$question = $this->db->query("SELECT id,title FROM zy_questionnaire_config WHERE type2=1")->result_array();
			foreach($question as $k => $v){
				echo '<h4>('.($k+1).')、'.$v['title'].'</h4><br/>';
                $where = array(
                    'uid' => $uid,
                    'qid' => $v['id'],
                );
                $record = $this->db->select('oid')->from('zy_questionnaire_record')->where($where)->get()->result_array();
//				$record = $this->db->query("SELECT oid FROM zy_questionnaire_record WHERE uid='{$uid}' AND qid={$v['id']}")->result_array();
				foreach($record as $r){

                    $oid = $r['oid'];
                    $where = "qid={$v['id']} AND `oid`=$oid";
                    $option = $this->model->get_row('zy_questionnaire_option','option_name',$where);

//					$option = $this->db->query("SELECT option_name FROM zy_questionnaire_option WHERE qid={$v['id']} AND oid={$r['oid']}")->row_array();
					echo $option['option_name'].'<br/>';
				}
				
			}
		}else{
			echo '没有调查问卷记录';
		}
		
		
	}

    //模拟POST提交
    function http($url, $data = NULL, $json = false){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if($json && is_array($data)){
                $data = json_encode( $data ,JSON_UNESCAPED_UNICODE);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($json){
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array(
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length:' . strlen($data))
                );
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            //var_dump("错误：".$errorno);
            return array('errorno' => false, 'errmsg' => $errorno);
        }
        curl_close($curl);
        //var_dump('数据：'.$res);
        return json_decode($res, true);

    }

    // 排行管理
    public function top()
    {
        if(!permission('SYS_User_Top','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/top?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $status = $_REQUEST['status'];
        if ($status) {
            $data['status'] = $status;
            $url_forward .= '&status=' . $status;
            $where .= " AND status='$status' ";
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
        $istop_arr = config_item('istop');
        $order = $_GET['order'] ? $_GET['order'].' DESC' : 'game_lv DESC';
        $list = $this->model->lists('*', $where, $order, $this->per_page, $offset);

        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['local_img']) $value['local_img'] = base_url($value['local_img']);
            $value['status'] = $this->status[$value['status']];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = [
            'nickname' => '昵称',
            'uid' => 'uid',
            'truename' => '姓名',
            'tel' => '电话'];
        //后台访问日志
        $this->log_admin_model->logs('查询用户排行信息',1);
        $this->load->view('admin/user_top', $data);
    }

    // 异常数据管理
    public function warning()
    {

        if(!permission('SYS_User_Warning','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/warning?';

        // 查询条件
        $where = "uid='fbc7505efb8046dd1228ee741d89b426'";
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $status = $_REQUEST['status'];
        if ($status) {
            $data['status'] = $status;
            $url_forward .= '&status=' . $status;
            $where .= " AND status='$status' ";
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
        $istop_arr = config_item('istop');
        $order = $_GET['order'] ? $_GET['order'].' DESC' : 'game_lv DESC';
        $list = $this->model->lists('*', $where, $order, 1, $offset);

        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['local_img']) $value['local_img'] = base_url($value['local_img']);
            $value['status'] = $this->status[$value['status']];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = [
            'nickname' => '昵称',
            'uid' => 'uid',
            'truename' => '姓名',
            'tel' => '电话'];
        //后台访问日志
        $this->log_admin_model->logs('查询用户异常信息',1);
        $this->load->view('admin/user_warning', $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_User','write')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_User','write')) show_msg('没有操作权限！');
        $uid = $this->input->get('uid');
        $uid = check_str($uid);
       
        // 这条信息
        $value = $this->model->detail($uid);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_User','write')) show_msg('没有操作权限！');

        $uid = ($this->input->post('uid'));
//        $value = trims($_POST['value']);
        $value = $this->security->xss_clean(trims($this->input->post('value')));
//        // 生成一张缩略图
//        if($data['thumb']) {
//            thumb( str_replace('/uploads/','uploads/',$data['thumb']), 220, 130 );
//        }

        if ($uid) { // 修改 ===========
            $this->model->update($value, ['uid' => $uid]);
            //后台访问日志
            $this->log_admin_model->logs('修改用户信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加用户信息',1);
            show_msg('添加成功！', 'index');
        }
    }


    public function delete()
    {
        if(!permission('SYS_User','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
