<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 微信用户注册登陆入口
class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/user_model');
        $this->load->model('api/Ld_model');
    }


function aaa()
{
    $code = isUrl('dsgdfbhfg');
    if(!isUrl($code))
    {
        echo 7;exit();
    }
    print_r($code);
}


    //内测
    public function test(){
        $list = [];
        $res = $this->db->query("select id,title,type1 from zy_questionnaire_config  WHERE type2=1")->result_array();
        if(!empty($res)){
            foreach($res as $key=>$value){
                $list[$value['id']] = $value;
                $list[$value['id']]['option'] = $this->db->query("select oid,option_name from zy_questionnaire_option  WHERE qid=$value[id]")->result_array();
            }
        }
        $data['uid'] = 'abcc';
        $data['list'] = $list;

        $this->load->view("client/questionnaire_1",$data);
    }

    //问卷2
    public function questionnaire2(){
        $list = [];
        $res = $this->db->query("select id,title,type1 from zy_questionnaire_config  WHERE type2=2")->result_array();
        if(!empty($res)){
            foreach($res as $key=>$value){
                $list[$value['id']] = $value;
                $list[$value['id']]['option'] = $this->db->query("select oid,option_name from zy_questionnaire_option  WHERE qid=$value[id]")->result_array();
            }
        }
        $data['uid'] = 'abcc';
        $data['list'] = $list;

        $this->load->view("client/questionnaire_2",$data);
    }

    //保存问卷一调查的结果
    public function saveQuestionNaire1(){
        $uid = $this->input->post('uid');
        $phone = trim($this->input->post('phone'));
        $list = $this->input->post('value');
        if(!empty($list)){
            foreach($list as $key=>$value){
                if(is_array($value)){
                    foreach($value as $k=>$v){
                        $insert['uid'] = $uid;
                        $insert['qid'] = $key;
                        $insert['oid'] = $v;
                        $insert['add_time'] = t_time();
                        $this->user_model->table_insert('zy_questionnaire_record',$insert);
                    }
                }else{
                    $insert['uid'] = $uid;
                    $insert['qid'] = $key;
                    $insert['oid'] = $value;
                    $insert['add_time'] = t_time();
                    $this->user_model->table_insert('zy_questionnaire_record',$insert);
                }
            }
        }
        if($phone){
            $update['tel'] = $phone;
            $this->user_model->update($update,['uid'=>$uid]);
        }

        $this->load->view("client/dialog");
    }

    //保存问卷二调查的结果
    public function saveQuestionNaire2(){
        $uid = $this->input->post('uid');
        $phone = trim($this->input->post('phone'));
        $list = $this->input->post('value');
        if(!empty($list)){
            foreach($list as $key=>$value){
                if(is_array($value)){
                    foreach($value as $k=>$v){
                        $insert['uid'] = $uid;
                        $insert['qid'] = $key;
                        $insert['oid'] = $v;
                        $insert['add_time'] = t_time();
                        $this->user_model->table_insert('zy_questionnaire_record',$insert);
                    }
                }else{
                    $insert['uid'] = $uid;
                    $insert['qid'] = $key;
                    $insert['oid'] = $value;
                    $insert['add_time'] = t_time();
                    $this->user_model->table_insert('zy_questionnaire_record',$insert);
                }
            }
        }
        if($phone){
            $update['tel'] = $phone;
            $this->user_model->update($update,['uid'=>$uid]);
        }

    }

    //建议
    public function suggestion(){
        //$data['uid'] = 'abcc';
        $data['uid'] = $_SESSION["uid"];
        $this->load->view("client/suggestion",$data);
    }

    //保存建议
    public function saveSuggestion(){
        $uid = $this->input->post('uid');
        $content = $this->input->post('content');
        $insert['uid'] = $uid;
        $insert['content'] = $content;
        $insert['add_time'] = t_time();
        $insert_id = $this->user_model->table_insert('zy_suggestion',$insert);
        if($insert_id){
            $this->load->view("client/msg");
        }else{
            t_error();
        }

    }



    public function invite(){

        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $state_base64 = base64_encode(site_url('api/Laxin/invite?incode='.$code));

            //正式环境《真龙》
            $apiUrl  = 'https:open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
            $apiUrl .=  'response_type=code&state='.$state_base64;
            $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';

            $temp = sprintf($apiUrl, $state_base64);
            header("Location: " . $temp);
            exit;
        }


    }

//叠叶子，点击分享链接跳转到分享页面
    public function leafInvite(){

        $code = $_REQUEST['incode'];
        if(!isUrl($code))
        {
            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
            $state_base64 = base64_encode($url.'/yccq/api/Leaf/invite?incode='.$code);

            //正式环境《真龙》
            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
            $apiUrl .=  'response_type=code&state='.$state_base64;
            $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';

            $temp = sprintf($apiUrl, $state_base64);

            header("Location: " . $temp);
            exit;
        }


    }

    function trees_share($code)
    {
        if(!isUrl($code))
        {
            $user = $this->user_model->row(['fid'=>$code]);
            $state_base64 = base64_encode(site_url('api/Energytrees/trees_share?incode='.$user['id']));

            //正式环境《真龙》
            $apiUrl  = 'https:open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
            $apiUrl .=  'response_type=code&state='.$state_base64;
            $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';

            $temp = sprintf($apiUrl, $state_base64);
            header("Location: " . $temp);
            exit;
        }


    }

    public function index()
    {

        //$this->load->view("client/tingjiweihu");
        //return;
//        $code = $_REQUEST['code'];
//        $code = $_REQUEST['fid'];

        $activitytime = model('user_model')->query_holiday_time('trees');
        $code = time()>$activitytime['start_time'] && time()<$activitytime['end_time'] ?$_REQUEST['fid']:$_REQUEST['code'];

        if(time()>$activitytime['start_time'] && time()<$activitytime['end_time']  && $code)
        {
            if(!isUrl($code))
            {
                $this->trees_share($code);
            }

        }
        else
        {
            //判断有添加好友想code
            if (isset($_GET['code']) && !empty($_GET['code'])) {
                $code = '?code=' . $_GET['code'];
            }

            if (isset($_GET['test'])) {
                $session_id = session_id();
                $phone_os = $_SERVER['HTTP_USER_AGENT'];
                $headurl = "http://wx.qlogo.cn/mmopen/ic1mIRHfNOKadwgKLCjcyTiawicLbALKicZlic16FrK5QpOlU4h2OGq8WWs4pvL8Xto5pepKp8ic8TaKMh17pwibWfD7XSIJeTeQnNb/0";
                $wx_info = array(
                    'openid' => 'woM0Mxs3oVcGxDn9vdeEKnL3HpdSo2',
                    'nickname' => '牵着你手陪你看日出2',
                    'headimgurl' => $headurl,
                    'unionid' => 'unionid_' . ip()
                );
                $url = '&openid=openid_' . ip() . '&nickName=nickName_' . ip() . '&headPhoto=' . $headurl . '&unionid=' . $wx_info['unionid'];
                header('location: index.php?c=Main&m=getUser' . $url);

            } else {

                if(!isUrl($code))
                {
                    $state_base64 = base64_encode(site_url('api/Main/getUser' . $code));
                    // wxb22508fbae4f4ef4  新的 wxccb43a09acc5a5c8
                    // 测试环境《真龙服务号》
                    /*$apiUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxccb43a09acc5a5c8&';
                    $apiUrl .= 'redirect_uri=http://zl.haiyunzy.com/thirdInterface/thirdInterface!autoLogin3.action&';
                    $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';*/

                    //正式环境《真龙》
//            $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
//            $apiUrl .= 'redirect_uri=http://wx.thewm.cn/thirdInterface/thirdInterface!autoLogin3.action&';
//            $apiUrl .= 'response_type=code&scope=snsapi_base&state=%s#wechat_redirect';

                    //正式环境《真龙》
                    $apiUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaa13dc461510723a&';
                    $apiUrl .= 'redirect_uri=http://wx.thewm.cn/getunionidapi/thirdInterface/getUserInfos&';
                    $apiUrl .=  'response_type=code&state='.$state_base64;
                    $apiUrl .= '&scope=snsapi_userinfo#wechat_redirect';


                    $temp = sprintf($apiUrl, $state_base64);
                    header("Location: " . $temp);
                    exit;
                }

            }
        }


    }

    /*
     * 获取用户信息
     */
    public function getUser()
    {

        //$this->load->view("client/tingjiweihu");
        //return;

        //判断有添加好友想code
        if (isset($_REQUEST['code']) && !empty($_REQUEST['code'])) {
            $code = '?code=' . $_REQUEST['code'];
        }
        $openid = addslashes($_REQUEST['openid']);
        $openid = urldecode($openid);

        if($openid == '0000'){
            $data['msg'] = '请重新进入游戏！';
            $this->load->view('client/tip', $data);
            return;
        }
        $openid_array = [
            'oREekjnJ9TXGXdo7Dq6XHb9zgFqA','oREekjrPFnTPQXOGA5GgYoH-_mEQ','oREekjgMMTV9HSQgbi2qv4fnqnoE',
            'oREekjrKJqGkufkVNggjQaJSgGA4','oREekjljkTwZVmxiNYUHMkDxQjPc','oREekjpgKPOY954vv2tap5pTF9OU'

       ];

        if(!in_array($openid,$openid_array))//挂机维护
        {
            date_default_timezone_set('PRC');
            //获得当日凌晨的时间戳
//            $today = strtotime(date("Y-m-d"),time());
//            $start_time = strtotime('2022-01-24 23:15:00');
//            $end_time = strtotime('2020-04-05 00:00:00');

            $time = model('user_model')->query_holiday_time('weihu');
            if(time()>$time['start_time'] && time()<$time['end_time'])
            {
                $this->load->view("client/weihu");
                return;
            }

        }


        $phone_os = addslashes($_SERVER['HTTP_USER_AGENT']);
        $nickname = addslashes($_REQUEST['nickName']);

        $nickname = urldecode($nickname);
        $headPhoto = addslashes($_REQUEST['headPhoto']);
        $headPhoto = str_replace("/0","/132" , $headPhoto); // 用小图即可

        $filename = 'uploads/wxheadimg/' . md5($openid) . '.jpg';
        $user = $this->user_model->row(['openid'=>$openid]);

        // 登陆初始化
        if ($user) {
            /*if (!file_exists($filename) || $user['head_img'] != $headPhoto) {
                $this->getImg($headPhoto, $filename);
            }*/
            //判断当前登陆是否是当天
            $last_time = strtotime($user['last_time']);
            $today_time =  strtotime(date('Y-m-d'));
            if($last_time < $today_time){
                //更新每日任务
                $this->load->model('api/task_model');
                $this->task_model->init_today_task_times($user['uid']);
            }
            $uid = $this->user_model->login($openid, $nickname, $headPhoto);

        } else {  // 首次登陆 初始化
            //$headLocalPhoto = $this->getImg($headPhoto, $filename);
            $uid = $this->user_model->init($openid, $nickname, $headPhoto);
        }

        $_SESSION['uid'] = $uid;
        //获取用户是否涉嫌作弊
        $sql = "select is_black from zy_user where uid=?";
        $is_black = $this->db->query($sql,[$uid])->row_array();
        $_SESSION['is_black'] = $is_black['is_black'];
        //微信分享用到的信息
        //$_SESSION['signPackage'] = $this->getSignPackage();

        $url = site_url("client/index" . $code);
        redirect($url);

        /*if($user){
            if($user['is_authority']== 1){
                $url = site_url("client/index" . $code);
                redirect($url);
            }else{
                //判断用户是否提交过问卷调查
                $questionnaire_count = $this->db->query("select count(*) as num from zy_questionnaire_record WHERE uid='$uid'")->row_array();
                if($questionnaire_count['num']){
                    //$data['msg'] = '未获得游戏资格，请联系管理员！';
                    //$this->load->view('client/tip', $data);
					$this->load->view("client/dialog");
                }else{
                    $list = [];
                    $res = $this->db->query("select id,title,type1 from zy_questionnaire_config  WHERE type2=1")->result_array();
                    if(!empty($res)){
                        foreach($res as $key=>$value){
                            $list[$value['id']] = $value;
                            $list[$value['id']]['option'] = $this->db->query("select oid,option_name from zy_questionnaire_option  WHERE qid=$value[id]")->result_array();
                        }
                    }
                    $data['uid'] = $uid;
                    $data['list'] = $list;
                    //print_r($data);
                    $this->load->view("client/questionnaire_1",$data);
                }
            }
        }else{
            $stop_time = strtotime('2018-05-26 00:00:00');
            if(time() < $stop_time){
                $list = [];
                $res = $this->db->query("select id,title,type1 from zy_questionnaire_config  WHERE type2=1")->result_array();
                if(!empty($res)){
                    foreach($res as $key=>$value){
                        $list[$value['id']] = $value;
                        $list[$value['id']]['option'] = $this->db->query("select oid,option_name from zy_questionnaire_option  WHERE qid=$value[id]")->result_array();
                    }
                }
                $data['uid'] = $uid;
                $data['list'] = $list;
                //print_r($data);
                $this->load->view("client/questionnaire_1",$data);
            }else{
                $this->load->view("client/closingdate");
            }

        }*/

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

    //定时周一更新排行榜的奖品
    public function updateRankingPrizeConfig(){
        $this->load->model('api/ranking_model');
        $this->ranking_model->updateRankingPrizeConfig();
    }

    //模拟POST提交
    function http($url, $data = NULL, $json = false){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if($json && is_array($data)){
                $data = json_encode( $data ,JSON_UNESCAPED_UNICODE);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($json){
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array(
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length:' . strlen($data))
                );
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            //var_dump("错误：".$errorno);
            return array('errorno' => false, 'errmsg' => $errorno);
        }
        curl_close($curl);
        //var_dump('数据：'.$res);
        return json_decode($res, true);

    }

    public function sendUserInfon(){
        $key = 'YCCQTicket';
        $messageType = '1';
        //获取没有资格的用户的openid
        $row = $this->db->query("select openid from zy_user WHERE is_authority=0 ORDER BY id ASC limit 0,10")->result_array();
        $temp = $key.'[';
        if($row){
            foreach($row as $key=>$value){
                $temp .=  '"'.$value['openid'].'",';
                $data[] = $value['openid'];
            }
            $temp = rtrim($temp, ',');
            $temp = $temp.']'.$messageType;
            $sign =  md5($temp);
            $res = ['sign'=>$sign,'messageType'=>$messageType,'data'=>$data];
            $url = 'http://ld.thewm.cn/zlbean/frontpage/message/userInfon';
            $result =$this->http($url,$res,1);
            print_r($result);
        }

    }

    public function sendUserInfonTest(){
        $key = 'YCCQTicket';
        $messageType = '1';
        $data = ['oREekjnJ9TXGXdo7Dq6XHb9zgFqA','oREekjgaak6Wn6x7xP5-pxnd5O8M'];
        $temp = json_encode($data);
        echo $temp;echo "<br>";
        $sign =  md5($key.$temp.$messageType);
        $res = ['sign'=>$sign,'messageType'=>$messageType,'data'=>$data];
        print_r(json_encode($res));
        //$url = 'http://ld.thewm.cn/zlbean/frontpage/message/userInfon';
        //$result =$this->http($url,$res,1);
        //print_r($result);


    }


    public function weihu(){
        date_default_timezone_set('PRC');
        //获得当日凌晨的时间戳
        /*$today = strtotime(date("Y-m-d"),time());
        $start_time = $today - 30*60;
        $end_time = $today + (7*3600+30*60);
        if(time()>$start_time && time()<$end_time){
            //$this->load->view("client/weihu");
            echo "维护！";
            return;
        }*/
        $h = date('H:i',time());
        echo $h;
        var_dump($h);
    }

    public function ywzTest(){
        $row = $this->db->query("select id,uid from zy_zhhongzhi_jifen ORDER by id asc limit 41000,2000")->result_array();
        foreach($row as $key=>&$value){
            $num = $this->db->query("select sum(jf) as num from zy_yanye_jifen WHERE uid='$value[uid]'")->row_array();
            $row[$key]['num'] = $num['num'] ? $num['num'] : 0;
            $update['jifen_1'] = $num['num'] ? $num['num'] : 0;
            $this->db->update('zy_zhhongzhi_jifen', $update, array('uid' => $value['uid']));
        }
        echo '<pre>';
        print_r($row);
        echo '</pre>';
        exit;
    }


}
