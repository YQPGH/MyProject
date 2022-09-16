<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Date: 2019/12/16
 * Time: 15:03
 */
include_once 'Base_model.php';
class Address_model extends Base_model{
    protected $cipher = MCRYPT_RIJNDAEL_128;
    //protected $mode = MCRYPT_MODE_ECB;
    protected $mode = MCRYPT_MODE_CBC;
    protected $pad_method = 'pkcs7';
//    protected $secret_key = 'Y5xsjEr97vZmeDsC'; //AesKey正式
    protected $secret_key = 'kgvyr0tparUglj2m'; //AesKey测试
    protected $iv = '0102030405060708';
    private $Channelvalue = "105";
//    private  $MD5Key = "aD2KfcoL2NbexCQX"; //正试
    private  $MD5Key = "e0I1w53RSop2qDu4"; //测试
    private   $BrandValue = "ISTCK3";
    function __construct(){
        parent::__construct();
        $this->table = 'zy_province';

    }



    //保存用户信息
    function savemessage($data){
        $time =  model('energytrees_model')->activity_time();

        if(!$time) t_error(6,'活动已结束');

        $get_sql = "select id,pid,status,add_time,update_time from zy_message where uid=? AND pid=? AND type=?";
        $row = $this->db->query($get_sql,[$data['uid'],$data['id'],$data['type']])->row_array();

//        if(strtotime($row['update_time'])>0)
//        {
//            $time = time() - strtotime($row['update_time']);
//            $a_time = time()  - strtotime($row['add_time']);
//            if( $time < 50 || $a_time<50) t_error(6,'提交时间过短，请稍后再试');
//        }
        if($row) t_error(5,'不可重复提交');

        $table = 'zy_prize_record';
        $check_sql = "select * from  $table where uid=? AND id=?";
        $check = $this->db->query($check_sql,[$data['uid'],$data['id']])->row_array();

        if(empty($check))  t_error(4,'提交失败！');
        $data = [
            'uid'=>$data['uid'],
            'truename'=>$data['truename'],
            'phone'=>$data['phone'],
            'address'=>$data['address'],
            'status'=>1,
            'pid'=>$data['id'],
            'code'=>$data['code'],
            'type'=> $data['type'],
            'add_time' => t_time(),
            'update_time' => t_time()

        ];

//        if($row)
//        {
//            $data['update_time']  = t_time();
//            $this->table_update('zy_message',$data,['uid'=>$data['uid'],'id'=>$row['id']]);
//        }
//        else
//        {
//            $data['add_time'] = t_time();
//            $data['update_time']  = t_time();
            $this->table_insert('zy_message', $data);
//        }

    }



    //地址推送
//    function savemessage($data){
//
//        $time =  model('coolrun_model')->activity_time();
//        if(!$time) t_error(6,'活动已结束');
//
//        $get_sql = "select id,pid,status,add_time,update_time,SourceOrderNo from zy_message where uid=? AND pid=? AND type=?";
//        $row = $this->db->query($get_sql,[$data['uid'],$data['id'],$data['type']])->row_array();
//
////        if(strtotime($row['update_time'])>0)
////        {
////            $time = time() - strtotime($row['update_time']);
////            $a_time = time()  - strtotime($row['add_time']);
////            if( $time < 50 || $a_time<50) t_error(6,'提交时间过短，请稍后再试');
////        }
//        if($row && $row['SourceOrderNo']) t_error(5,'不可重复提交');
//
//        $table = 'zy_prize_record';
//        $check_sql = "select * from  $table where uid=? AND id=?";
//        $check = $this->db->query($check_sql,[$data['uid'],$data['id']])->row_array();
//
//        if(empty($check))  t_error(4,'提交失败！');
//        $prize = $this->db->query("select title,json_data,shop1_total num  from zy_prize WHERE id=?",[$data['id']])->row_array();
//
//        $obj = json_decode($prize['json_data']);
//
//        $this->db->trans_start();
//        $SourceOrderNo = $this->orderNumber();
//        $OrderTime = t_time();;
//        $str = explode(',',$data['address']);
//        $Acode = explode(',',$data['code']);
//        $SProvince = $str[0];
//        $SCity = $str[1];
//        $SCounty = $str[2];
//        $ReceiveAddress = $str[3];
//        $AreaCode = $Acode[2];
//        $message_data = [
//            "MD5Key" => $this->MD5Key,
//            "Channelvalue" =>  $this->Channelvalue,
//            "SourceOrderNo" => $SourceOrderNo,
//            "ReceiveName"=> $data['truename'],
//            "ReceivePhone" => $data['phone'],
//            "SProvince" =>  $SProvince,
//            "SCity" => $SCity,
//            "SCounty" => $SCounty,
//            "ReceiveAddress" => $ReceiveAddress,
//            "ActionName" => $prize['title'],
//            "BrandValue" => $this->BrandValue,
//            "OrderTime" => $OrderTime,
//            "AreaCode" => $AreaCode,
//            "Goods"=>[["GoodsNumber"=>$obj->GoodsNumber,
//                             "GoodsCount" => $prize['num']]],
//            "Sign"=> md5($this->Channelvalue.$this->BrandValue.$data['truename'].$data['phone'].$OrderTime.$this->MD5Key)
//        ];
//        $message_data = json_encode($message_data);
//
//        $encrypted = $this->encrypt($message_data);//aes加密
//
//        $result['Channelvalue'] = $this->Channelvalue;
//        $result['Data'] = $encrypted;
//        $url = 'http://ems.api.atcd.cn/api/ObjectOrder';//测试环境
////        $url = 'https://mmapi.zl88.cn/api/ObjectOrder'; //正式环境
//        $return = $this->curlPost($url,$result);
//       $return = json_decode($return,true);
//
//        if($return['Code'] == "0000")
//        {
//            $message_data = [
//            'uid'=>$data['uid'],
//            'truename'=>$return['Data']['ReceiveName'],
//            'phone'=>$return['Data']['ReceivePhone'],
//            'address'=>$data['address'],
//            'SourceOrderNo' => $return['Data']['SourceOrderNo'],
//            'pid'=>$data['id'],
//            'code'=>$data['code'],
//            'status' => 1,
//            'type'=> $data['type']
//        ];
//
//            if($row)
//            {
//                $message_data['update_time']  = $OrderTime;
//                $this->table_update('zy_message',$message_data,['uid'=>$data['uid'],'id'=>$row['id']]);
//            }
//            else
//            {
//                $message_data['add_time'] = $OrderTime;
//                $this->table_insert('zy_message', $message_data);
//            }
//        }
//        else
//        {
//            t_error($return['Code'],$return['Message']);
//        }
//
//        $this->db->trans_complete();
//
//
//    }

    //查询用户信息
    function getUsermessage($uid,$id,$type=''){
        $time =  model('energytrees_model')->activity_time();

        if(!$time) t_error(6,'活动已结束');
//        $type = $type?$type:'qixi';
        $sql = "select uid,truename,phone,address,status,add_time,code from zy_message where uid=? AND pid=? AND type=?";
        $row = $this->db->query($sql,[$uid,$id,$type])->row_array();

//        $max_time = 86400;
        if($row)
        {
//            if(time()-strtotime($row['add_time'])>$max_time)
//            {
//                $this->table_update('zy_message',['status'=>1],['uid'=>$uid,'pid'=>$id]);
//                $result['status'] = 1;
//            }
//            else
//            {
//                $result['status'] = 0;
//            }
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
            $result['status'] = $row['status'];
        }
        else
        {

            $sql = "select truename,phone,address,status,add_time,code from zy_message where uid=? AND type=? ORDER BY id desc";
            $row = $this->db->query($sql,[$uid,$type])->row_array();
            $result['status'] = $row['status'];
            $str = explode(',',$row['address']);
            $code = explode(',',$row['code']);
            $result['truename'] = $row['truename'];
            $result['phone'] = $row['phone'];
            $result['province'] = $str[0];
            $result['city'] = $str[1];
            $result['area'] = $str[2];
            $result['street'] = $str[3];
            $result['province_code'] = $code[0];
            $result['city_code'] = $code[1];
            $result['area_code'] = $code[2];
            if(empty($row)) $result = [];

        }
        $result['type'] = $type;
        return $result;
    }




    function get_province($pro_code,$city_code,$area_code){


        if(empty($pro_code)){
            $pro_code = 450000;
            return $this->query_province($pro_code);
        }else{
            $sql1 = "select PROVINCE_CODE pro_code,PROVINCE_NAME pro_name from $this->table";
            $list['province'] = $this->db->query($sql1)->result_array();

            foreach($list['province'] as $key=>&$value){
                if($value['pro_code'] == $pro_code){
                    $info = $value;
                    unset($list['province'][$key]);
                }
            }
            array_unshift($list['province'], $info);
            $sql2 = "select c.CITY_CODE city_code,c.CITY_NAME city_name   from zy_city c,zy_province p WHERE c.PROVINCE_CODE=? AND c.PROVINCE_CODE=p.PROVINCE_CODE";
            $list['city'] = $this->db->query($sql2,$pro_code)->result_array();
            foreach($list['city'] as $key=>&$value){
                if($value['city_code'] == $city_code){
                    $info2 = $value;
                    unset($list['city'][$key]);
                }
            }
            array_unshift($list['city'], $info2);
            $sql3 = "select a.AREA_CODE area_code,a.AREA_NAME area_name from zy_area a,zy_city c  WHERE a.CITY_CODE=? AND c.CITY_CODE=a.CITY_CODE";
            $list['area'] = $this->db->query($sql3,$city_code)->result_array();
            foreach($list['area'] as $k=>&$val){
                if($val['area_code'] == $area_code){
                    $info3 = $val;
                    unset($list['area'][$k]);
                }
            }
            array_unshift($list['area'], $info3);
            return $list;
        }
    }

    function get_address($name='',$province='',$city='',$area=''){

        if($name == 'province'){
            return $this->query_province($province);
        }
        if($name == 'city'){

            $sql2 = "select c.CITY_CODE city_code,c.CITY_NAME  city_name from zy_city c,zy_province p WHERE c.PROVINCE_CODE=? AND c.PROVINCE_CODE=p.PROVINCE_CODE";
            $list['city'] = $this->db->query($sql2,[$province])->result_array();
            foreach($list['city'] as $key=>&$value){
                if($value['city_code'] == $city){
                    $city = $value;
                    unset($list['city'][$key]);
                }
            }
            array_unshift($list['city'], $city);
            foreach($list['city'] as $key=>&$value){
                if($key==0){
                    $sql3 = "select AREA_CODE area_code,AREA_NAME area_name from zy_area  WHERE CITY_CODE=?";
                    $list['area'] = $this->db->query($sql3,$value['city_code'])->result_array();
                }
            }

            return $list;
        }
        if($name == 'area'){

            $sql3 = "select a.AREA_CODE area_code,a.AREA_NAME area_name from zy_area a,zy_city c WHERE a.CITY_CODE=? AND a.CITY_CODE=c.CITY_CODE";
            $list['area'] = $this->db->query($sql3,[$city])->result_array();

            foreach($list['area'] as $key=>&$value){
                if($value['area_code'] == $area){
                    $area = $value;
                    unset($list['area'][$key]);
                }
            }
            array_unshift($list['area'], $area);
            return $list;
        }
    }

    function query_province($pro_code){
        $sql1 = "select PROVINCE_CODE pro_code,PROVINCE_NAME pro_name from $this->table";
        $list['province'] = $this->db->query($sql1)->result_array();

        foreach($list['province'] as $key=>&$value){
            if($value['pro_code'] == $pro_code){
                $info = $value;
                unset($list['province'][$key]);
            }
        }
        array_unshift($list['province'], $info);
        $sql2 = "select c.CITY_CODE city_code,c.CITY_NAME city_name   from zy_city c,zy_province p WHERE c.PROVINCE_CODE=? AND c.PROVINCE_CODE=p.PROVINCE_CODE";
        $list['city'] = $this->db->query($sql2,$pro_code)->result_array();
        foreach($list['city'] as $key=>&$value){
            if($key==0){
                $sql3 = "select AREA_CODE area_code,AREA_NAME area_name from zy_area  WHERE CITY_CODE=?";
                $list['area'] = $this->db->query($sql3,$value['city_code'])->result_array();
            }
        }


        return $list;
    }

    public function set_cipher($cipher)
    {
        $this->cipher = $cipher;
    }

    public function set_mode($mode)
    {
        $this->mode = $mode;
    }

    public function set_iv($iv)
    {
        $this->iv = $iv;
    }

    public function set_key($key)
    {
        $this->secret_key = $key;
    }

    public function require_pkcs7()
    {
        $this->pad_method = 'pkcs7';
    }

    protected function pad_or_unpad($str, $ext)
    {
        if ( is_null($this->pad_method) )
        {
            return $str;
        }
        else
        {
            $func_name = __CLASS__ . '::' . $this->pad_method . '_' . $ext . 'pad';
            if ( is_callable($func_name) )
            {
                $size = mcrypt_get_block_size($this->cipher, $this->mode);
                return call_user_func($func_name, $str, $size);
            }
        }
        return $str;
    }

    protected function pad($str)
    {
        return $this->pad_or_unpad($str, '');
    }

    protected function unpad($str)
    {
        return $this->pad_or_unpad($str, 'un');
    }

    public function encrypt($str)
    {
        $str = $this->pad($str);
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');
        if ( empty($this->iv) )
        {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
        mcrypt_generic_init($td, $this->secret_key, $iv);
        $cyper_text = mcrypt_generic($td, $str);
        $rt = strtoupper(bin2hex($cyper_text));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $rt;
    }

    public function decrypt($str){
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');

        if ( empty($this->iv) )
        {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }

        mcrypt_generic_init($td, $this->secret_key, $iv);
        //$decrypted_text = mdecrypt_generic($td, self::hex2bin($str));
        $decrypted_text = mdecrypt_generic($td, base64_decode($str));
        $rt = $decrypted_text;
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $this->unpad($rt);
    }

    public static function hex2bin($hexdata) {
        $bindata = '';
        $length = strlen($hexdata);
        for ($i=0; $i< $length; $i += 2)
        {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    public static function pkcs7_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs7_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
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
        curl_close($curl);
        return $result;
    }



}