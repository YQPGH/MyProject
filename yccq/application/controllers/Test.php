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
        //$row = $this->db->query("SELECT count(*) FROM zy_user")->row_array();
		//var_dump($row);
		echo session_id();
    }
	
	function bbb(){
		echo var_dump($_POST);
	}

    function count()
    {

    }

}
