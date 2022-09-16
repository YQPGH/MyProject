<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/10/25
 * Time: 11:44
 */

include_once 'Base_model.php';
class MsgSender_model extends Base_model{

    function __construct(){
        parent::__construct();
        $this->table = '';

    }


    function msg_send($uid){


    }


//    function msg_send($uid){
//        // 指明给谁推送，为空表示向所有在线用户推送
//        $to_uid = "";
//        // 推送的url地址，使用自己的服务器地址
//        $push_api_url = "http:/192.168.1.38:2121/";
//        $post_data = array(
//            "type" => "publish",
//            "content" => "这个是推送的测试数据",
//            "to" => $to_uid,
//        );
//       $res = $this->http_send($push_api_url,$post_data);
//
//        if($res=='ok'){
//            print_r($post_data);exit;
//        }
//
//    }

    function http_send($push_api_url,$post_data){

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
//        print_r($return);exit;
        return $return;
//        var_export($return);
    }
}