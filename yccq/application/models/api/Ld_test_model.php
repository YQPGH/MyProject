<?php
if (! defined('BASEPATH'))  exit('No direct script access allowed');
    
// 乐豆模型
	
class Ld_test_model extends CI_Model
{
    //private $zlkey = '0b4c6857d00848cc99179fd358b5b756';
    //private $zlapp_id = 'ZYYCCQ';   //111111
    //private $yrurl = 'zl.haiyunzy.com';  //生产环境    wx93024a4137666ab3   wx.zhenlong.wang    zl.haiyunzy.com(测试地址)
    private $zlkey = '123456';
    private $zlapp_id = '123456';   //111111
    private $yrurl = 'wx.thewm.cn';  //生产环境    wx93024a4137666ab3   wx.zhenlong.wang


    function __construct ()
    {
        parent::__construct();
		$this->ip = config_item('ip');
		//$this->sid = $this->sid_arr[config_item('ip')];
    }

    // 查询用户乐豆
    function getYD($openid){
        $key = $this->zlkey;
        $data['app_id'] = $this->zlapp_id;
        $data['openid'] = $openid;
        $data['sign'] = md5($data['app_id'].$data['openid'].$key);

        $url = 'http://'. $this->yrurl .'/integral/integralManage!getUserIntegral.action';
        //$data['key'] = $key;
        //$data['url'] =$url;
        //return $data;exit;
        $return = $this->curlPost($url,$data);
        return json_decode($return,true);
    }

    //消耗乐豆
    function consumeYD($smokeBeans, $openid, $desc){

        $user_obj = $this->getYD($openid);
        //$user_obj = json_decode($user,true);
        $smokeBeansCount = 0;
        if($user_obj['status'] == '0'){
            $smokeBeansCount = $user_obj['smokeBeansCount'];
            if($smokeBeansCount < abs($smokeBeans) || $smokeBeansCount == abs($smokeBeans)){// 如果扣除的乐豆数少于剩余的乐豆数就扣完
                $smokeBeans = $smokeBeansCount;
            }
        }

        $key = $this->zlkey;
        $data['orderno'] = 'yccq_'.$this->randomkeys(6). '_' . time(). '_23'; //流水号
        $data['smokeBeans'] = abs($smokeBeans);
        $data['consumeType'] = '烟草传奇消耗';
        $data['desc'] = $desc;
        $data['app_id'] = $this->zlapp_id;
        $data['openid'] = $openid;
        $data['sign'] = md5($data['app_id'] . $data['orderno'] .$data['openid']. $data['smokeBeans'] .$data['consumeType'] .$data['desc'] .$key);
        $url = 'http://'. $this->yrurl .'/integral/integralManage!consumeIntegral.action';
        $return = $this->curlPost($url,$data);

        //存入数据库
        $data_log['addtime'] = t_time();
        $data_log['desc'] = json_encode($data);
        $data_log['return'] = $return;
        $data_log['browser'] = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : '';
        $data_log['ip'] = ip();
        $data_log['comefrom'] =  $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '';
        $data_log['openid'] =  $openid;
        $data_log['postUrl'] =  $url;
        $this->db->insert('zy_recharge_log',$data_log);

        return json_decode($return,true);
    }

    //充值乐豆
    function rechargeDY($smokeBeans, $openid, $desc){
        $key = $this->zlkey;
        $data['orderno'] = 'yccq_'.$this->randomkeys(6). '_' . time(). '_23'; //流水号
        $data['qrcodeNo'] = '';//二维码的值（没有则为空）
        $data['smokeBeans'] = $smokeBeans;
        $data['smokeBrand'] = '烟草传奇';//香烟牌子
        $data['type'] = '烟草传奇赠送'; //获取乐豆的途径
        $data['desc'] = $desc;
        $data['app_id'] = $this->zlapp_id;
        $data['openid'] = $openid;
        $data['sign'] = md5($data['app_id'] . $data['qrcodeNo']. $data['orderno'] .$data['openid']. $data['smokeBeans'] .$data['smokeBrand'] .$data['desc'] .$key);
        $url = 'http://'. $this->yrurl .'/integral/integralManage!addIntegral.action';
        $return = $this->curlPost($url,$data);

        //存入数据库
        $data_log['addtime'] = t_time();
        $data_log['desc'] = json_encode($data);
        $data_log['return'] = $return;
        $data_log['browser'] = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : '';
        $data_log['ip'] = ip();
        $data_log['comefrom'] =  $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '';
        $data_log['openid'] =  $openid;
        $data_log['postUrl'] =  $url;
        $this->db->insert('zy_recharge_log',$data_log);

        return json_decode($return,true);

    }

    function randomkeys($length)
    {
        $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $key = '';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return strtolower($key);
    }

    public function curl(){
        $data = array();
        $url = 'http://xccq.th00.com.cn/yccq/api/test/curlData';
        $return = $this->curlPost($url,$data);
        return json_decode($return,true);
    }

    //接口POST
    function curlPost($postUrl , $postArr=array()) {
        $curl = curl_init($postUrl);
        $cookie = dirname(__FILE__).'/cache/cookie.txt';
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT,10); //超时设置 (秒)
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); // ?Cookie
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));
        $result = curl_exec($curl);
        echo '<pre>';
        print_r($result);
        echo '</pre>';

        curl_close($curl);
        return $result;
    }

    
}
