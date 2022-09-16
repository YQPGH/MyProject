<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    // 网站首页
    public function index()
    {
        phpinfo();
        //连接本地的 Redis 服务
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        echo "Connection to server sucessfully";
        //查看服务是否运行
        echo "Server is running: " . $redis->ping();
    }

    //清空数据
    public function initData(){
        $table = ['jd_guyong','jd_jiandie','jd_shouru','log_admin','log_login','log_market','log_money','log_orders','log_prize',
        'log_prize_quan','log_shop','log_task','log_task_prize','log_trade','log_xp','xxl_record','zu_jiandie','zy_aging','zy_aging_record',
        'zy_bake','zy_bake_record','zy_event','zy_event_land','zy_friend','zy_friend_apply','zy_friend_invite','zy_friend_visit','zy_gather_record',
        'zy_guide','zy_hunt_record','zy_land','zy_land_upgrade_record','zy_market','zy_newer_task','zy_news','zy_orders','zy_peifang',
        'zy_peiyu','zy_pinjian_record','zy_process','zy_process_record','zy_question','zy_questionnaire_record','zy_ranking_prize_record',
        'zy_recharge_log','zy_seed_record','zy_shenmi_shop','zy_sign','zy_st_message','zy_stat_day','zy_stat_month','zy_store','zy_store_upgrade_record',
        'zy_suggestion','zy_task_today','zy_ticket_record','zy_unusual','zy_user','zy_user_game','zy_user_login','zy_zulin'
        ];

        $this->db->trans_start();
        foreach($table as $key=>$value){
            $this->db->query("TRUNCATE TABLE `$value`");
        }
        $this->db->trans_complete();

    }

    public function order_detail(){
        //$res = $this->db->query("select name from zy_orders_config WHERE id < 7")->result_array();
        $res = [0=>['name'=>'杂货铺老板的订单'],1=>['name'=>'王媒婆的订单'],2=>['name'=>'地主的订单'],
            3=>['name'=>'吴麻子的订单'],4=>['name'=>'钱掌柜的订单'],5=>['name'=>'风水先生的订单']];
        //print_r($res); exit;

        $result = $this->db->query("select id from zy_orders_config WHERE id > 110")->result_array();
        //print_r($result);exit;
        foreach($result as $key=>$value){
            $i = $key%6;

            $title = $res[$i]['name'];

            //echo $title;echo "<br/>";
            $this->db->query("update zy_orders_config set name='$title' WHERE id=$value[id]");
        }

    }

    function testError(){
        //$this->abc();
        //$this->db->query("update zy_orders_config set name123='顶顶顶' WHERE id=1");
        //log_message('error', '数据库查询出错了了！！');
    }


    function tt(){
        $arr = [];
        for($j=0;$j<5000;$j++){
            $text = '';
            for($i=0;$i<5;$i++){
                $rand1 = 0;
                $rand1 = rand(0,25);
                $arr_rand1[] = $rand1;
                $str = 'abcdefghijklmnopqrstuvwxyz';
                $arr_str1[] = $str;
                $text .= substr($str,$rand1,1);
            }
            for($i=0;$i<3;$i++){
                $rand2 = 0;
                $rand2 = rand(0,9);
                $arr_rand2[] = $rand2;
                $number = '0123456789';
                $arr_str2[] = $str;
                $text .= substr($number,$rand2,1);
            }
            //file_put_contents("D:\\test.txt", $text.PHP_EOL, FILE_APPEND);
            $arr[] = $text;
        }
        $res = array_unique($arr);
        echo count($res);
        echo '<pre>';
        print_r($arr_rand1);
        print_r($arr_rand2);
        print_r($arr_str1);
        print_r($arr_str2);
        print_r($arr);
        print_r($res);
        echo '</pre>';
        exit;
        /*foreach($arr as $key=>$value){
            file_put_contents("D:\\test.txt", $value.PHP_EOL, FILE_APPEND);
        }*/


    }
	
	function aaa(){
		echo $_SERVER['PATH_INFO'];
		echo phpinfo();
		exit();
        //发送一个原生的HTTP头 
		header("content-type:image/png");
		//新建一个真彩色画布
		$img = imagecreatetruecolor(100,100);
		//为图像分配颜色
		$red = imagecolorallocate($img,0xFF,0x00,0x00);
		//区域填充
		imagefill($img);
		//以 PNG 格式将图像输出到浏览器或文件
		imagepng($img);
		//销毁一图像 释放内存
		imagedestroy($img);
    }


}
