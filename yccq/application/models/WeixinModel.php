<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 微信公众号模型
 */
class WeixinModel extends CI_Model
{
    private $appID = 'wx0627fd6605fc0c90';//紫云订阅号号
    private $appsecret = '7cb3d6cff24855fb8388366b2f91fa22';//紫云服务号
    private $filename = 'data/access_token.json';
    private $ticket_filename = 'data/js_ticket.json';
    private $wx_config_path = 'data/wx_config.json';
    private $token = 'wxedu';

    function __construct()
    {
        parent::__construct();
    }
    //获取微信推送过来的信息
    public function getMsg()
    {
        $postStr = @file_get_contents('php://input');
        $postObj = false;
        if(!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postObj = json_decode(json_encode($postObj),true);
        }
        return $postObj;
    }
    //回复本文信息
    public function transmitText($object, $content, $flag = 0){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object['FromUserName'], $object['ToUserName'], time(), $content, $flag);
        echo $resultStr;
    }
    //回复图片信息
    public function transmitImage($object, $mediaId){
        $imageTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
        $resultStr = sprintf($imageTpl, $object['FromUserName'], $object['ToUserName'], time(), $mediaId);
        echo $resultStr;
    }
    //回复语音信息
    public function transmitVoice($object, $mediaId){
        $voiceTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>
                    </xml>";
        $resultStr = sprintf($voiceTpl, $object['FromUserName'], $object['ToUserName'], time(), $mediaId);
        echo $resultStr;
    }
    //回复视频信息
    public function transmitVideo($object, $mediaId,$title,$description){
        $videoTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                    </Video>
                    </xml>";
        $resultStr = sprintf($videoTpl, $object['FromUserName'], $object['ToUserName'], time(), $mediaId,$title,$description);
        echo $resultStr;
    }
    //回复音乐信息
    public function transmitMusic($object, $mediaId, $title, $description, $musicUrl, $HQMusicUrl){
        if(!$HQMusicUrl) $HQMusicUrl = $musicUrl;
        $musicTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[music]]></MsgType>
                    <Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                    </Music>
                    </xml>";
        $resultStr = sprintf($musicTpl, $object['FromUserName'], $object['ToUserName'], time(), $title, $description, $musicUrl, $HQMusicUrl, $mediaId);
        echo $resultStr;
    }
    //回复图文信息
    public function transmitNews($object,$items){
        $newsTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>";
                    foreach($items as $v){
                        $newsTpl .= "<item><Title><![CDATA[{$v['Title']}]]></Title>";
                        $newsTpl .= "<Description><![CDATA[{$v['Description']}]]></Description>";
                        $newsTpl .= "<PicUrl><![CDATA[{$v['PicUrl']}]]></PicUrl>";
                        $newsTpl .= "<Url><![CDATA[{$v['Url']}]]></Url></item>";
                    }
                    $newsTpl .= "</Articles></xml>";
        $resultStr = sprintf($newsTpl, $object['FromUserName'], $object['ToUserName'], time(),count($items));
        echo $resultStr;
    }
    //获取access_token
    function get_access_token()
    {
        /*$content = @file_get_contents($this->filename);
        if($content){
            $data = json_decode($content,true);
            if(time() - $data['add_time'] >= $data['expires_in']){
                //过期重新获取access_token
                $data = $this->get_access_token_api();
            }
        }else{
            //新获取access_token
            $access_token_data = $this->get_access_token_api();
            $data = $access_token_data;
        }*/
		$row = $this->db->where(array('key_name'=>'access_token'))->get('zy_weixin_token')->row_array();
		if($row['value']){
            if(time() - $row['add_time'] >= $row['expires_in']){
                //过期重新获取access_token
                $row = $this->get_access_token_api();
            }
		}else{
			//新获取access_token
            $access_token_data = $this->get_access_token_api();
            $row = $access_token_data;
		}
        return $row['value'];
    }

    //请求access_token接口
    private function get_access_token_api()
    {
        $content = t_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appID&secret=$this->appsecret");
        $data = json_decode($content['body'],true);
        if(isset($data['errcode'])){
            show_msg($data['errmsg']);
        }
        $data['add_time'] = time();
        //写文件
		$this->db->where(array('key_name'=>'access_token'))->update('zy_weixin_token',array('value'=>$data['access_token'],'expires_in'=>$data['expires_in'],'add_time'=>$data['add_time']));
        //file_put_contents($this->filename,json_encode($data));
        return $data;
    }

    public function verify()
    {
        $echostr = $this->input->get('echostr');
        if($echostr && $this->checkSignature()){
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

    public function getWXConfig()
    {
        $res = false;
        $content = @file_get_contents($this->wx_config_path);
        if($content){
            $config = json_decode($content,true);
            $res = $config;
        }
        return $res;
    }

    public function getAppId()
    {
        return $this->appID;
    }

    public function getAppsecret()
    {
        return $this->appsecret;
    }

    //获取网页授权code
    public function getCode($redirect_uri,$state="")
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appID}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        redirect($url);
    }

    public function getAuthAccessToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appID}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
        return json_decode(httpGet($url),true);
    }

    //获取素材列表
    public function getMaterialList($type='news', $offset=0, $count=20) {
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->get_access_token();
        $postData = array(
            'type'=> $type,
            'offset'=>$offset,
            'count'=>$count
        );
        //var_dump($postData);
        return json_decode(httpPost($url,json_encode($postData)),true);
    }

    //获取素材
    public function getMaterial($media_id) {
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$this->get_access_token();
        $postData = array(
            'media_id'=> $media_id
        );
        //var_dump($postData);
        return json_decode(httpPost($url,json_encode($postData)),true);
    }
    //获取菜单
    public function getMenu() {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->get_access_token();
        return json_decode(httpGet($url),true);
    }

    //创建自定义菜单
    public function setMenu($menuData) {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->get_access_token();
        return json_decode(httpPost($url,json_encode($menuData,JSON_UNESCAPED_UNICODE)),true);
    }

    //获取ticket
    function getTicket()
    {
        /*$content = @file_get_contents($this->ticket_filename);
        if($content){
            $data = json_decode($content,true);
            if(time() - $data['add_time'] >= $data['expires_in']){
                //过期重新获取access_token
                $data = $this->get_ticket_api();
            }
        }else{
            //新获取ticket
            $access_token_data = $this->get_ticket_api();
            $data = $access_token_data;
        }
        return $data['ticket'];*/
		
		$row = $this->db->where(array('key_name'=>'js_ticket'))->get('zy_weixin_token')->row_array();
		if($row['value']){
            if(time() - $row['add_time'] >= $row['expires_in']){
                //过期重新获取ticket
                $row = $this->get_ticket_api();
            }
		}else{
			//新获取ticket
            $ticket_data = $this->get_ticket_api();
            $row = $ticket_data;
		}
        return $row['value'];
    }

    //请求ticket接口
    private function get_ticket_api()
    {
        $content = t_curl("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$this->get_access_token()}&type=jsapi");
        $data = json_decode($content['body'],true);
        //var_dump($data);
        if(!empty($data['errcode'])){
            show_msg($data['errmsg']);
        }
        $data['add_time'] = time();
        //写文件
		$this->db->where(array('key_name'=>'js_ticket'))->update('zy_weixin_token',array('value'=>$data['ticket'],'expires_in'=>$data['expires_in'],'add_time'=>$data['add_time']));
        //file_put_contents($this->ticket_filename,json_encode($data));
        return $data;
    }

    public function getSignature($url)
    {
        $data = array(
            "noncestr" => $this->createNonceStr(),
            "jsapi_ticket" => $this->getTicket(),
            "timestamp" => time(),
            "url" => $url
        );
        ksort($data,SORT_STRING);
        $string = urldecode(http_build_query($data));
        $data['signature'] = sha1($string);
        //$data['rawString'] = $string;
        return $data;
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
