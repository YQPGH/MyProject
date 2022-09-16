<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 微信用户注册登陆入口


class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('api/Ld_model');
    }

    public function index()
    {
        //判断有添加好友想code
        if (isset($_GET['code']) && !empty($_GET['code'])) {
            $code = '?code='.$_GET['code'];
        }

        if (isset($_GET['test'])) {
            $session_id = session_id();
            $phone_os = $_SERVER['HTTP_USER_AGENT'];
            $headurl = "http://wx.qlogo.cn/mmopen/ic1mIRHfNOKadwgKLCjcyTiawicLbALKicZlic16FrK5QpOlU4h2OGq8WWs4pvL8Xto5pepKp8ic8TaKMh17pwibWfD7XSIJeTeQnNb/0";
            $wx_info = array(
                'openid' => 'woM0Mxs3oVcGxDn9vdeEKnL3HpdSo2',
                'nickname' => '牵着你手陪你看日出2',
                'headimgurl' => $headurl,
                'unionid' => 'unionid_' .  ip()
            );
            $url = '&openid=openid_' . ip()  . '&nickName=nickName_'  . ip()  . '&headPhoto=' . $headurl . '&unionid=' . $wx_info['unionid'];
            header('location: index.php?c=Main&m=getUser' . $url);

        } else {
            //http://yccq.gxtianhai.cn/server/api/main/
            $state_base64 = base64_encode('http://yccq.zlongwang.com/server/api/Main/getUser'.$code);
            // wxb22508fbae4f4ef4  新的 wxccb43a09acc5a5c8
            // 测试环境《真龙服务号》
            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxccb43a09acc5a5c8&';
            $apiUrl .= 'redirect_uri=http://zl.haiyunzy.com/thirdInterface/thirdInterface!autoLogin3.action&';
            $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';

            //正式环境《真龙》
            /*
                $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
                $apiUrl .= 'redirect_uri=http://wx.thewm.cn/thirdInterface/thirdInterface!autoLogin3.action&';
                $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';
            */
            $temp = sprintf($apiUrl,$state_base64);
            header("Location: ".$temp);
            exit;
        }
    }

    /*
     * 获取用户信息
     */
    public function getUser()
    {
        //判断有添加好友想code
        if (isset($_REQUEST['code']) && !empty($_REQUEST['code'])) {
            $code = '?code='.$_REQUEST['code'];
        }
        $openid = addslashes($_REQUEST['openid']);
        //$this->check_game_rule('');
        $phone_os 	= addslashes($_SERVER['HTTP_USER_AGENT']);
        $nickname 	= addslashes($_REQUEST['nickName']);
        $headPhoto 	= addslashes($_REQUEST['headPhoto']);
        $data = array();
        /*if (strpos($phone_os, 'MicroMessenger') === false) {
            // 非微信浏览器禁止浏览
            // $this->load->view('tip', $data);
            //  return;
        } else {
            if (strpos($phone_os, 'Windows Phone') === false) {
                // 非微信浏览器禁止浏览
                // $this->load->view('tip', $data);return;
            }
        }*/
        $data['openid'] 	= $openid;
        $data['nickname'] 	= $nickname;
        $data['sex'] 		= 0;
        //$dcurrency = $lb_num['dcurrency'];    //龙币数，接天海后台时候使用
        //$data['total_gold'] = $dcurrency;     //龙币数，接天海后台时候使用
        if($openid == 'ocoMKt4wvPEJ05tMacR7V646eGS8'){
            $ledou_res = $this->Ld_model->getYD($openid);
            //$ledou_res = $this->Ld_model->consumeYD(50, $openid, '烟草传奇消耗');
            //$ledou_res = $this->Ld_model->rechargeDY(5000,$openid,'奖励乐豆');
        }

        $isexit = $this->db->query("select count(*) as num,uid,nickname,head_img, local_img,logins from zy_user where openid='" . $openid . "' ")->row_array();
        $filename = 'uploads/wxheadimg/' . md5($openid) . '.jpg';

        // 登陆初始化
        if ($isexit['num'] > 0) {
            if (!file_exists($filename) || $isexit['head_img'] != $headPhoto) {
                $img_local_url 		= $this->getImg($headPhoto, $filename);
                $headLocalPhoto 	= $img_local_url;
                $data['headimgurl'] = $headLocalPhoto;
            } else {
                $data['headimgurl'] = $isexit['local_img'] ? $isexit['local_img'] : $filename;
            }
            $update_nickname 		= "";
            if ($isexit['nickname'] != $nickname) $update_nickname = "  nickname='" . $nickname . "' , ";

            if($openid == 'ocoMKt4wvPEJ05tMacR7V646eGS8'){
                $this->db->query("update zy_user set {$update_nickname} last_time= '" . t_time() . "' ,head_img = '" . $headPhoto . "' ,local_img = '" . $filename . "' ,logins=logins+1,ledou=$ledou_res[smokeBeansCount] where openid= '" . $openid . "'");//更新烟豆

            }else{
                $this->db->query("update zy_user set {$update_nickname} last_time= '" . t_time() . "' ,head_img = '" . $headPhoto . "' ,local_img = '" . $filename . "' ,logins=logins+1 where openid= '" . $openid . "'");//更新烟豆
            }

            //更新zy_stat_day表
            $row = $this->db->query("select id,update_time from zy_stat_day ORDER BY id DESC")->row_array();
            if($this->time->day($row['update_time'])==$this->time->today()){
                $today = t_time();
                $this->db->query("update zy_stat_day set active=active+1,logins=logins+1,update_time='$today' WHERE id=$row[id]");
            }else{
                $stat_data = array(
                    'stat_day' => t_time(0,0),
                    'active' => 1,
                    'logins' => 1,
                    'update_time' => t_time(),
                );
                $this->db->insert('zy_stat_day', $stat_data);
            }

            session_start();
            $_SESSION['uid'] = $isexit['uid'];

        }else{  // 首次登陆 初始化
            $this->db->trans_start();//事务开启
            if($openid == 'ocoMKt4wvPEJ05tMacR7V646eGS8'){
                $ledou_res = $this->Ld_model->rechargeDY(500,$openid,'新用户奖励乐豆'); //新用户奖励乐豆
                $user_data['ledou'] 	= $ledou_res['smokeBeansCount'];
            }else{
                $user_data['ledou'] 	= 5000;
            }

            $img_local_url 			= $this->getImg($headPhoto, $filename);
            $headLocalPhoto 		= $img_local_url;

            $data['headimgurl'] 	= $headLocalPhoto;

            $user_data['openid'] 	= $openid;
            $user_data['uid'] =  t_rand_str($openid);
            $user_data['nickname'] 	= $nickname;
            $user_data['head_img'] 	= $headPhoto;
            $user_data['local_img'] = $headLocalPhoto;
            $user_data['sex'] 		= 0;
            $user_data['add_time'] 	= t_time();
            $user_data['last_time'] 	= t_time();
            $user_data['game_lv'] 	= 1;
            $user_data['money'] 	= 500000;

            $user_data['shandian'] 	= 0;
            $user_data['logins'] 	= 1;
            $insert_sql = $this->db->insert_string('zy_user', $user_data);
            $insert_sql = str_replace('INSERT', 'INSERT ignore ', $insert_sql);
            $this->db->query($insert_sql);

            //初始化用户土地
            for($i=0;$i<6;$i++){
                $data = array(
                    'uid' => $user_data['uid'],
                    'land_shopid' => 101,
                    'add_time' => t_time()
                );
                $this->db->insert('zy_land', $data);
            }
            //初始化状态表
            $data = array(
                'uid' => $user_data['uid'],
                'aging_lv' => 1,
                'store_lv' => 1
            );
            $this->db->insert('zy_status', $data);
            //赠送6颗戊级津巴布韦种子
            $data = array(
                'uid' => $user_data['uid'],
                'shopid' => 205,
                'type1' => 'zhongzi',
                'type2' => 1,
                'total' => 6,
                'add_time' => t_time()
            );
            $this->db->insert('zy_store', $data);

            //更新zy_stat_day表
            $row = $this->db->query("select id,update_time from zy_stat_day ORDER BY id DESC")->row_array();
            if($this->time->day($row['update_time'])==$this->time->today()){
                $today = t_time();
                $this->db->query("update zy_stat_day set active=active+1,new_user=new_user+1,logins=logins+1,update_time='$today' WHERE id=$row[id]");
            }else{
                $stat_data = array(
                    'stat_day' => t_time(0,0),
                    'active' => 1,
                    'new_user' => 1,
                    'logins' => 1,
                    'update_time' => t_time(),
                );
                $this->db->insert('zy_stat_day', $stat_data);
            }

            //初始化zy_newer_task表
            $data = array(
                'uid' => $user_data['uid'],
            );
            $this->db->insert('zy_newer_task', $data);

            $this->db->trans_complete();//事务结束

            session_start();
            $_SESSION['uid'] = $user_data['uid'];
        }

        //微信分享用到的信息
        $_SESSION['signPackage'] = $this->getSignPackage();

        $url = "http://yccq.zlongwang.com/client/index.php".$code;
        redirect($url);
    }

    public function getSignPackage() {
        $key = 'ka2J9PC326T44D439H6tvcPBY';
        $sign = strtoupper(md5($key));
        //测试环境
        //$get_jsSDK_url = 'http://zl.haiyunzy.com/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;
        //生产环境
        $get_jsSDK_url = 'http://wx.thewm.cn/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;
        $signPackage = json_decode( $this->curlGetData($get_jsSDK_url), true);

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

    function curlGetData($url) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1' );
        $data = curl_exec ( $ch );
        $status = curl_getinfo ( $ch );
        $errno = curl_errno ( $ch );
        curl_close ( $ch );
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
        if(!strstr($url,"wx.qlogo.cn"))  return '';
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
        $this->thumb($filename, 90, 90);
        return $filename;
    }


    /**
     * 生成缩略图函数  剪切
     *
     * @param $imgurl 图片路径
     * @param $width 缩略图宽度
     * @param $height 缩略图高度
     * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
     */
    function thumb ($imgurl, $width = 100, $height = 100)
    {
        if (empty($imgurl))
            return '不能为空';

        include_once 'application/libraries/image_moo.php';
        $moo = new Image_moo();
        $moo->load($imgurl);
        $moo->resize_crop($width, $height);
        $moo->save_pa('','',true);
    }





}