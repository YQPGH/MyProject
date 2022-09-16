<?php defined('BASEPATH') Or exit('No direct script access allowed') ;
/**
 * User: Administrator
 * Date: 2020/11/16
 * Time: 15:31
 */

//烟草传奇渠道号：105
//MD5Key:e0I1w53RSop2qDu4
//AesKey:kgvyr0tparUglj2m

include_once 'Base_model.php';

class Orderprize_model extends Base_model{
    private $MD5Key = "aD2KfcoL2NbexCQX";//正式环境
    //private $MD5Key = "e0I1w53RSop2qDu4";//测试环境
    private $Channelvalue = "105";

    function __construct()
    {
        parent::__construct();
        $this->table = '';
        $this->load->model('api/user_model');

    }


    //订单物流查询接口
    public function orderNumberQuery($data) {

        $sql = "select * from zy_message WHERE uid=? or SourceOrderNo=?";
        $user = $this->db->query($sql,[$data['uid'],$data['ordernum']])->row_array();

        $time = $this->msectime();
        $result = [];
        if($user)
        {
            $data = [
                'Channelvalue' =>  $this->Channelvalue,
                'SourceOrderNo' => $user['SourceOrderNo'],
                'TimeStamp' => $time,
                'Sign' =>  md5($this->Channelvalue.$user['SourceOrderNo'].$time.$this->MD5Key)
            ];

            $data  =  json_encode($data);

            //$url = "http://ems.api.atcd.cn/api/LogisticsQuery";//测试环境
            $url = "https://mmapi.zl88.cn/api/LogisticsQuery";//正试环境
            $return = $this->https_request($url,$data);
            $return = json_decode($return,true);

            if($return['Code'] == '0000')
            {
                $result['data'] = $return['Data'];
            }
            else
            {
                $result['data'] = $return['Message'];
            }
        }

        return $result;
    }

   //获取当前时间毫秒  yyyymmddhhmmssfff
    function msectime()
    {
        list($usec, $sec) = explode(" ", microtime());

        $cn_time=date('YmdHis', time()).round($usec*1000);
        return $cn_time;
    }

    /**
     * PHP发送Json对象数据
     *
     * @param $url 请求url
     * @param $jsonStr 发送的json字符串
     * @return array
     */
    public function https_request($url,$jsonStr='')
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        $response = curl_exec($ch);
//        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //状态码
        curl_close($ch);

//        return array($httpCode, $response);
        return $response;
    }


}