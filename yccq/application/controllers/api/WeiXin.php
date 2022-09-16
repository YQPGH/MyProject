<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 微信用户注册登陆入口
class WeiXin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/user_model');
        $this->load->model('api/Ld_model');
    }

    public function index()
    {
        //http://yccq.gxtianhai.cn/server/api/main/
        $state = urlencode(site_url('api/WeiXin/getUser'));
        $appid = 'wx3bb0d0229126aa35';
        $redirect_uri = urlencode('http://yangwangzhai.com/wechat/admin/WebPageAuthorize/getCode');

        $apiUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&";
        $apiUrl .= "redirect_uri=$redirect_uri&";
        $apiUrl .= "response_type=code&scope=snsapi_base&state=$state#wechat_redirect";

        header("Location: " . $apiUrl);
        exit;

    }

    /*
     * 获取用户信息
     */
    public function getUser()
    {
        $data['openid'] = $openid = addslashes($_REQUEST['openid']);
        //$data['phone_os'] = $phone_os = addslashes($_SERVER['HTTP_USER_AGENT']);
        $data['nickname'] = $nickname = addslashes($_REQUEST['nickname']);
        $data['headimg'] = $headimg = addslashes($_REQUEST['headimgurl']);
        $data['subscribe'] = $subscribe = addslashes($_REQUEST['subscribe']);
        if($subscribe == 1){
            echo "已关注";
        }else{
            echo "未关注";
        }
        exit;
        $headPhoto = str_replace("/0","/132" , $headimg); // 用小图即可

        $filename = 'uploads/wxheadimg/' . md5($openid) . '.jpg';
        $user = $this->user_model->row(['openid'=>$openid]);

        // 登陆初始化
        if ($user) {
            if (!file_exists($filename) || $user['head_img'] != $headPhoto) {
                $this->getImg($headPhoto, $filename);
            }
            $uid = $this->user_model->login($openid, $nickname, $headPhoto);

        } else {  // 首次登陆 初始化
            $headLocalPhoto = $this->getImg($headPhoto, $filename);
            $uid = $this->user_model->init($openid, $nickname, $headPhoto, $headLocalPhoto);
        }

        $_SESSION['uid'] = $uid;

        //微信分享用到的信息
        //$_SESSION['signPackage'] = $this->getSignPackage();

        $url = site_url("client/index" . $code);
        redirect($url);
    }

    public function getSignPackage()
    {
        $key = 'ka2J9PC326T44D439H6tvcPBY';
        $sign = strtoupper(md5($key));
        //测试环境
        //$get_jsSDK_url = 'http://zl.haiyunzy.com/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;
        //生产环境
        $get_jsSDK_url = 'http://wx.thewm.cn/thirdInterface/thirdInterface!getJsapiTicket.action?sign=' . $sign;
        $signPackage = json_decode($this->curlGetData($get_jsSDK_url), true);

        $appid = $signPackage['appid'];
        $jsapiTicket = $signPackage['JsapiTicket']; //$signPackage['ticket'] ;

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    function curlGetData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
        $data = curl_exec($ch);
        $status = curl_getinfo($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        return $data;
    }

    /*
    *@通过curl方式获取指定的图片到本地
    *@ 完整的图片地址
    *@ 要存储的文件名
    */
    private function getImg($url = "", $filename = "")
    {
        //去除URL连接上面可能的引号
        //$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
        if (!strstr($url, "wx.qlogo.cn")) return '';
        $hander = curl_init();
        $fp = fopen($filename, 'wb');
        curl_setopt($hander, CURLOPT_URL, $url);
        curl_setopt($hander, CURLOPT_FILE, $fp);
        curl_setopt($hander, CURLOPT_HEADER, 0);
        curl_setopt($hander, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
        curl_setopt($hander, CURLOPT_TIMEOUT, 60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
        return $filename;
    }

    public function test(){
//        $oid = addslashes($_REQUEST['oid']);
//        $url_get = "http://yangwangzhai.com/whackmole/api/Main/main?sid=sid_123456_".$oid;
//        header("Location: " . $url_get);
    }

	public function getJsapiTicket(){
		$sign = $this->input->get('sign',true);
		$key = 'ka2J9PC326T44D439H6tvcPBY';
        if(strtoupper(md5($key)) == $sign){
			$this->load->model('WeixinModel');
			$res['appid'] = $this->WeixinModel->getAppId();
			$res['JsapiTicket'] = $this->WeixinModel->getTicket();

			$json_str = json_encode($res);
			echo $json_str;
		}else{
			echo 'sign error!';
		}
	}

}
