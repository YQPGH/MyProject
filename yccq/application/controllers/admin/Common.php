<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
* 后台公共页控制器
*/
include_once 'Base.php';

class Common extends Base
{

    function __construct()
    {
        $this->name = '公共模块';
        parent::__construct();
        $this->load->helper('captcha');

    }

    // 框架首页
    public function index()
    {
        if (empty ($this->uid)) {
            redirect('admin/common/login');
            //header ( 'Location: index.php?d=admin&c=common&m=login' );
        }

        redirect('admin/stat/index');
        //$this->load->view('sta/index');
    }

    // 默认搜索页
    public function main()
    {
        if (empty ($this->uid)) {
            redirect('admin.php');
        }

        $this->load->view('admin/main');
    }

    // 登陆页
    public function login()
    {

        if (!empty ($this->uid)) {
            redirect('admin/common');
        }

        $this->load->view('admin/login');
    }

    // 验证登陆
    public function check_login()
    {
        $username = trim($this->input->post('username'));
        $checkcode = trim($this->input->post('checkcode'));


//        if ($checkcode != $this->session->userdata ( 'checkcode' )) {
//        	show_msg ( '验证码不正确，请重新输入', 'login' );
//        }
        $user = $this->admin_model->row(array('username' => $username));
        preg_match('/(?=.*[a-z])(?=.*\d)(?=.*[#@!~%^&*])[a-z\d#@!~%^&*]{8,16}/i',trim($this->input->post('password')),$match);
        $password = get_password(trim($match[0]));

        $_SESSION['error']=empty($_SESSION['error'])?1:$_SESSION['error']+1;
        if($_SESSION['error'] >3 && $_SESSION['error'] <=5){
            //如果session中的验证码与页面上的验证码不同，则验证失败
            if(strtoupper($_SESSION['captcha'])!=strtoupper($this->input->post('captcha'))){
                show_msg('验证码错误！', 'login');
            }
        }

        if($_SESSION['error'] >=5){
           $this->db->set('status', '1', FALSE)->where('id', $user['id'])->update('zy_admin');
           $row =  $this->admin_model->get_row('zy_admin','status',"`id`=$user[id]");
            if ($row['status'] ==1) {
                unset($_SESSION['error']);
                show_msg('您的账号已被锁定，请联系管理员！', 'login');

            }
        }

//        if ($user ['status'] == 1) {
//            show_msg('您的账号已被锁定，请联系管理员！', 'login');
//        }


        if (empty ($user) || ($user['password'] != $password)) {
            show_msg('用户名错误或者密码错误，请重新输入！', 'login');
        }

        $time =  time() - strtotime($user['lastlog_time']);
        if( $time >= config_item('sess_expiration')){
            $this->db->set('online', '0', FALSE)->where('id', $user['id'])->update('zy_admin');

        }


//        if($user['online']==1 && $time <= config_item('sess_expiration')){
//            show_msg('同一账户不允许多台终端登录！', 'login');
//        }

        $count = $this->admin_model->get_row("zy_admin ", 'count(*) online ',"`online`=1");
        if(intval($count['online']) >= intval($user['max_num'])){
            show_msg('当前连接数已达到最大值！', 'login');
        }

        $this->admin_model->update_logins($user['id']); // 更新登录次数和时间


        $session_admin = array(
            'id' => $user['id'],
            'groupid' => $user['groupid'],
            'username' => $user['username'],
            'truename' => $user['truename'],

        );

        $this->session->set_userdata('admin', $session_admin);
        redirect('admin/stat/index?nav=1');
    }

    // 退出
    public function login_out()
    {

        $this->db->set('online', '0', FALSE)->where('id', $_SESSION['admin']['id'])->update('zy_admin');
        $this->session->unset_userdata('admin');
        unset($_SESSION['error']);

        redirect('admin/common/login');
    }

    // 验证码
    public function checkcode()
    {
        include APPPATH . 'libraries/Checkcode.class.php';
        $checkcode = new checkcode();
        $checkcode->doimage();
        $this->session->set_userdata('checkcode', $checkcode->get_code());
        //$_SESSION['checkcode'] = $checkcode->get_code();
    }

    function get_captcha(){
        //设置验证码内容
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < 5; $i++)
        {
            $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
        }
        $word = $str;
        $_SESSION['captcha']=$word;
        $vals = array(

            'word' => $word,
            'img_path' => dirname(BASEPATH).'/uploads/captcha/',
            'img_url' => base_url('uploads/captcha').'/',
            'img_width' => 100,
            'img_height' => 40,
            'expiration' =>30,
            'word_length'   => 8,
            'font_size' => 40,
            'colors'    => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($vals);
        $parrent = "/(href|src)=([\"|']?)([^\"'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i";
        $str = $cap['image'];
        preg_match($parrent,$str,$match);

        echo $match[3];



    }



}

/* End of file welcome.php */




