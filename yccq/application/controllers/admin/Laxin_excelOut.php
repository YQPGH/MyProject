<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
class Laxin_excelOut extends CI_Controller{

    function __construct(){
        $this->control = 'laxin_excelOut';
        parent ::__construct();
    }

        // 导出Excel
        public function excelOut()
        {
            $day = 25569;//excel和php之间相差的时间
            $time = 24 * 60 * 60;//一天24小时
//echo 1;exit;
            $query = $this->db->query(
                "select openid,nickname,truename,phone,address,prize,num,odd_numbers,time,status from wl_smoke  WHERE theme=4 AND prize='起源'");
//            $query = $this->db->query(
//                "select openid,nickname,truename,phone,address,prize,num,odd_numbers,time,status from wl_smoke  WHERE theme=4 AND prize='鸿韵'");
//            $query = $this->db->query(
//                "select openid,nickname,truename,phone,address,prize,num,odd_numbers,time,status from wl_smoke  WHERE theme=4 AND prize='凌云'");
            $list = $query->result_array();

            $table_data = '<table border="1"><tr>
                        <th colspan="10">用户名单</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>

                <th >openid</th>
      			<th >昵称</th>
      			<th >姓名</th>
      			<th >电话</th>
      			<th >邮寄地址</th>
      			<th >奖品类型</th>
      			<th >数量</th>
      			<th >邮件单号</th>
      			<th >领取时间</th>
      			<th >发货状态</th>

    			</tr>';
            header('Content-Type: text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition: attachment;filename="《香草传奇》游戏新春主题营销活动真龙样品烟B邮寄名单（起源）.xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            $status = ['', '已发货'];
            foreach ($list as &$line) {
                $table_data .= '<tr>';
                $line['time']  = gmdate('Y-m-d H:i:s', ($line['time'] - $day) * $time);

                $line['status'] = $status[$line['status']];
//print_r($line);exit;

                $line['address']=urlencode($line['address']);//将关键字编码
                $line['address']=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$line['address']);
                $line['address']=urldecode($line['address']);//将过滤后的关键字解码
                foreach ($line as $key => &$item) {

                    // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                    $table_data .= '<td>' . $item . '</td>';
                }
                $table_data .= '</tr>';
            }
            $table_data .= '</table>';
            echo $table_data;
        }

}
