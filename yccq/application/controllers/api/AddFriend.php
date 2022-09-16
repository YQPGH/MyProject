<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 session_start();
// 土地
//include_once 'Base.php';

class AddFriend extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // $this->load->model('api/my_common_model', 'common');
        $this->load->model('api/friend_model');
    }


    public function index()
    {
		//判断有添加好友想code
		if (isset($_GET['code']) && !empty($_GET['code'])) {
			$_SESSION['code'] = $_GET['code'];
		}
			
        
            //http://yccq.gxtianhai.cn/server/api/main/
            $state_base64 = base64_encode('http://xccq.th00.com.cn/server/api/AddFriend/getUser');
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

    /*
     * 获取用户信息
     */
    public function getUser()
    {
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
        //$isexit = $this->db->query("select count(*) as num,uid,nickname,head_img, local_img,logins from zy_user where openid='" . $openid . "' ")->row_array();
        $isexit = $this->friend_model->column_sql("uid,nickname,head_img, local_img,logins",['openid'=>$openid],'zy_user',$type=0);

        $filename = 'uploads/wxheadimg/' . md5($openid) . '.jpg';

        if (!empty($isexit)) {
            /*if (!file_exists($filename) || $isexit['head_img'] != $headPhoto) {
                $img_local_url 		= $this->getImg($headPhoto, $filename);
                $headLocalPhoto 	= base_url() . $img_local_url;
                $data['headimgurl'] = $headLocalPhoto;
            } else {
                $data['headimgurl'] = $isexit['local_img'] ? $isexit['local_img'] : base_url() . $filename;
            }*/
            $update_nickname 		= "";
            if ($isexit['nickname'] != $nickname) $update_nickname = "  nickname='" . $nickname . "' , ";
            //$this->db->query("update zy_user set {$update_nickname} last_time= '" . t_time() . "' ,head_img = '" . $headPhoto . "' ,local_img = '' ,logins=logins+1 where openid= '" . $openid . "'");//更新烟豆
            $update_data['update_nickname'] = $nickname;
            $update_data['last_time'] = t_time();
            $update_data['head_img'] = $headPhoto;
            $update_data['local_img'] = '';
            $update_data['logins'] = $isexit['logins'] + 1;
            $this->friend_model->table_update('zy_user', $data, ['openid'=>$openid]);

            $data['uid'] = $isexit['uid'];
            session_start();
            $_SESSION['uid'] = $isexit['uid'];
        }else{
            //$img_local_url 			= $this->getImg($headPhoto, $filename);
            //$headLocalPhoto 		= base_url() . $img_local_url;

            //$data['headimgurl'] 	= $headLocalPhoto;

            $user_data['openid'] 	= $openid;
            $user_data['uid'] =  t_rand_str($openid);
            $user_data['nickname'] 	= $nickname;
            $user_data['head_img'] 	= $headPhoto;
            $user_data['local_img'] = '';
            $user_data['sex'] 		= 0;
            $user_data['add_time'] 	= t_time();
            $user_data['last_time'] 	= t_time();
            $user_data['game_lv'] 	= 1;
            $user_data['money'] 	= 500000;
            $user_data['ledou'] 	= 50000;
            $user_data['logins'] 	= 1;

            $this->db->trans_start();//事务开启

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
                'uid' => $user_data['uid']
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

            $this->db->trans_complete();//事务结束
			$data['uid'] = $user_data['uid'];
            session_start();
            $_SESSION['uid'] = $user_data['uid'];
        }
		
		
	    //微信分享用到的信息
      $_SESSION['signPackage'] = $this->getSignPackage();	
      $this->load->view('addfriend_view', $data);


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