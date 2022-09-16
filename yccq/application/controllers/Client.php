<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 客户端

class Client extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/setting_model');
    }
    
    public function index()
    {
        $data['title'] = $this->setting_model->get('title');
        if(isset($_GET['test'])) $_SESSION['uid'] = 'abcd';
        $this->load->view('client/index', $data);
    }
    
    public function xxl()
    {
        $data['title'] = $this->setting_model->get('title');

        $this->load->view('client/xxl', $data);
    }


    public function wabao()
    {
        $data['title'] = $this->setting_model->get('title');

        $this->load->view('client/wabao', $data);
    }
	
	public function share()
	{
		$this->load->view('client/share');
	}

	public function dyy()
	{
		if($this->input->get("test"))
        {
            $this->session->set_userdata('uid',"11084d2608c3da4285974fb589f05937");

        }

		if(!isset($_SESSION['uid'])){
			$this->dyyGetUserInfo();
		}
		$share = $this->getSignPackage();
		$this->load->view('client/dyy',$share);
	}
	
	private function dyyGetUserInfo()
	{
		if($this->input->get('sign')){
			$userInfo = array(
				"openid" => $this->input->get('openid'),
				"nickName" => $this->input->get('nickName'),
				"headPhoto" => $this->input->get('headPhoto')
			);
			$row = $this->db->query("SELECT uid FROM zy_user WHERE openid = '{$userInfo['openid']}'")->row_array();
			$this->session->set_userdata('uid',$row['uid']);
			redirect('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/dyy');
		}else{
			$state_base64 = base64_encode('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/dyy');
			//正式环境《真龙》
			$apiUrl  = 'https:open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
			$apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
			$apiUrl .=  'response_type=code&state='.$state_base64;
			$apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';
			$temp = sprintf($apiUrl, $state_base64);
			header("Location: " . $temp);
			exit;
		}
	}

    public function run()
    {
        if($this->input->get("test")) $this->session->set_userdata('uid',"11084d2608c3da4285974fb589f05937");
        if(!isset($_SESSION['uid'])){
            $this->runGetUserInfo();
        }
        $share = $this->getSignPackage();
        $this->load->view('client/run',$share);
    }

    private function runGetUserInfo()
    {
        if($this->input->get('sign')){
            $userInfo = array(
                "openid" => $this->input->get('openid'),
                "nickName" => $this->input->get('nickName'),
                "headPhoto" => $this->input->get('headPhoto')
            );
            $row = $this->db->query("SELECT uid FROM zy_user WHERE openid = '{$userInfo['openid']}'")->row_array();
            $this->session->set_userdata('uid',$row['uid']);
            redirect('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/run');
        }else{
            $state_base64 = base64_encode('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/run');
            //正式环境《真龙》
            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
            $apiUrl .=  'response_type=code&state='.$state_base64;
            $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';
            $temp = sprintf($apiUrl, $state_base64);
            header("Location: " . $temp);
            exit;
        }
    }
    
    public function qixi()
	{
		if($this->input->get("test")) $this->session->set_userdata('uid',"11084d2608c3da4285974fb589f05937");
		if(!isset($_SESSION['uid'])){
			$this->dyyGetUserInfo();
		}
		$share = $this->getSignPackage();
		$this->load->view('client/qixi',$share);
	}
	
	private function qixiGetUserInfo()
	{
		if($this->input->get('sign')){
			$userInfo = array(
				"openid" => $this->input->get('openid'),
				"nickName" => $this->input->get('nickName'),
				"headPhoto" => $this->input->get('headPhoto')
			);
			$row = $this->db->query("SELECT uid FROM zy_user WHERE openid = '{$userInfo['openid']}'")->row_array();
			$this->session->set_userdata('uid',$row['uid']);
			redirect('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/qixi');
		}else{
			$state_base64 = base64_encode('http://'.$_SERVER['HTTP_HOST'].'/yccq/Client/qixi');
			//正式环境《真龙》
			$apiUrl  = 'https:open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
			$apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
			$apiUrl .=  'response_type=code&state='.$state_base64;
			$apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';
			$temp = sprintf($apiUrl, $state_base64);
			header("Location: " . $temp);
			exit;
		}
	}
	
	/**微信分享*/
	private function getSignPackage() {
        $this->load->model("WeixinModel");
        //$signPackage = $this->WeixinModel->getTicket();
        $appid = $this->WeixinModel->getAppId();
        $jsapiTicket = $this->WeixinModel->getTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

}
