<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tests extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }


    public function index()
    {


        $site_url = 'gametest.gxziyun.com/yccq/tests/getUser';

        $state_base64 = base64_encode($site_url);
        //正式环境《真龙》
//            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
//            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/thirdInterface/thirdInterface!autoLogin3.action&';
//            $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';

        //正式环境《真龙》
        $apiUrl = 'https:open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
        $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
        $apiUrl .= 'response_type=code&state=' . $state_base64;
        $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';

print_r($apiUrl);exit;
        $temp = sprintf($apiUrl, $state_base64);
        header("Location: " . $temp);
        exit;

    }

    /*
     * 获取用户信息
     */
    public  function getUser()
    {

echo 1;exit;
        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);

        $_SESSION['uid'] = $openid;
print_r($_SESSION);exit;
        $url = 'http://gametest.gxziyun.com/yccq/test_01?' . $_SESSION;
        redirect($url);


    }

    function atest()
    {
        $sql = "SELECT 	`type`,order_id,COUNT(*) num FROM log_orders  WHERE uid='31547c84bb49cb881cc4587fc29010ce' AND add_time>'2020-05-09 11:34:33' GROUP BY order_id";
        $list = $this->db->query($sql)->result_array();
        $sum = 0;
        foreach($list as &$v)
        {
            $s = "select a.money from zy_orders_config a,zy_shop b WHERE a.shopid=b.shopid AND a.order_id=$v[order_id]";
            $row = $this->db->query($s)->row_array();
            $t = $row['money']*1.1;
            $s = $t-$row['money'];
            $sum += $s;

        }
        print_r($sum);
    }



}




