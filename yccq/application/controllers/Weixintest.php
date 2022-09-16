<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2021/1/31
 * Time: 12:38
 */

class Weixintest extends CI_Controller
{

    private $appid = "wx983f830af3aac07b";
    private $appsecret = "a7baddfc61cc969c215205b54aa31e53";
    private $token = 'weixin';

    public function __construct()
    {
        parent::__construct();


    }

    function index()
    {

//    $state_base64 = base64_encode(site_url('api/Weixin/getUser'));
        $redirect_url = urlencode('https://yccq.zlongwang.com/yccq/Weixintest/getUserInfo');

        $apiUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}";
        $apiUrl .= "&redirect_uri={$redirect_url}";
        $apiUrl .= "&response_type=code&scope=snsapi_userinfo&state=1&connect_redirect=1&";
        $apiUrl .= "#wechat_redirect";
//    $temp = sprintf($apiUrl, $state_base64);

        header("Location: " . $apiUrl);

        exit;


    }

    function getUserInfo()
    {

        $code = $_GET["code"];//预定义的 $_GET 变量用于收集来自 method="get" 的表单中的值。

        if ($code)//判断code是否存在
        {
            $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
            $oauth2 = t_curl($oauth2Url);

            $oauth2_array = json_decode($oauth2['body'],true);//对 JSON 格式的字符串进行解码，转换为 PHP 变量，自带函数

            //获取access_token
            $access_token = $oauth2_array['access_token'];//获取access_token对应的值

            //获取openid
            $openid = $oauth2_array['openid'];//获取openid对应的值

            $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";

            $userinfo_json = t_curl($userinfo_url);

            $userinfo_array = json_decode($userinfo_json['body'],ture);

            return $userinfo_array;
        }


    }

    /*
        * 获取用户信息
        */
    public function getUser()
    {


        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);

        $phone = addslashes($_SERVER['HTTP_USER_AGENT']);

        $nickname = addslashes($_REQUEST['nickName']);
        $nickname = urldecode($nickname);

        $headPhoto = addslashes($_REQUEST['headPhoto']);
        $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可

        $filename = 'uploads/wxheadimg/' . md5($openid) . '.jpg';

        print_r($openid);exit;



    }
    //配置认证
    public function verify()
    {
        $echostr = $this->input->get('echostr');
        if($echostr && $this->checkSignature()){
            ob_clean();
            echo $echostr;
        }
        exit();
    }
    //检验signature
    public function checkSignature()
    {
        $signature = $this->input->get("signature");
        $timestamp = $this->input->get("timestamp");
        $nonce = $this->input->get("nonce");

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    function https_request($url)//自定义函数,访问url返回结果
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)){
            return 'ERROR'.curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }
}