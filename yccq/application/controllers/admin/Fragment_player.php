<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/8
 * Time: 14:28
 */

class Fragment_player extends CI_Controller
{

    function __construct()
    {
        $this->control = 'Fragment_player';
        parent::__construct();
    }

    function stat(){

//        echo "<h2>集齐6种碎片用户人数：</h2>";
       $count1 = $this->db->query("select COUNT(*) as num from zy_fragment_compose WHERE status=1")->row_array();
       $count2 = $this->db->query("select COUNT(*) as num from zy_fragment_compose WHERE status=0")->row_array();


        echo "<h2>钥匙统计：</h2>";
        echo "已使用钥匙数量：".$count1['num'].'<br/>';
        echo "未使用钥匙数量：".$count2['num'].'<br/>';

        $prize1 = $this->db->query("select COUNT(*) as num from zy_fragment_prize_record ")->row_array();
//        $prize3 = $this->db->query("select COUNT(*) as num from zy_fragment_prize_record WHERE status=0")->row_array();

        $player = $this->db->query("select COUNT(*) as num from (select COUNT(*)  from zy_fragment_prize_record a group by uid) a")->row_array();


        echo "<br/><h2>京东奖品抽奖统计：</h2>";
        echo "参与京东奖品抽奖总人数：".$player['num'].'<br/>';

        echo '<br>'."参与京东奖品抽奖总次数：".$prize1['num'].'<br/>';

//        echo "奖品未兑换人数：".$prize3['num'].'<br>';

        $prize4 = $this->db->query("select COUNT(*) as num from zy_fragment_prize_record WHERE shopid=1621")->row_array();
        echo '<br>'."20元京东奖品抽奖总次数：".$prize4['num'].'<br/>';

        $prize5 = $this->db->query("select COUNT(*) as num from zy_fragment_prize_record WHERE shopid=1622")->row_array();
        echo "100元京东奖品抽奖总次数：".$prize5['num'].'<br/>';

        $prize6 = $this->db->query("select COUNT(*) as num from zy_fragment_prize_record WHERE shopid=1623")->row_array();
        echo "200元京东奖品抽奖总次数：".$prize6['num'].'<br/>';
    }

    public function excelOut()
    {


        $list  = $this->db->query(
            "select a.uid,a.add_time,c.name
                from zy_fragment_prize_record a,zy_shop c
                WHERE a.shopid=c.shopid")->result_array();


        if(count($list)>0){
            foreach($list as &$value){
                $sql = "select openid,nickname from zy_user WHERE  uid=?";
                $user  = $this->db->query($sql,[$value['uid']])->row_array();

                $value['openid'] = $user['openid'];
                $value['nickname'] = $user['nickname'];

                unset($value['uid']);
            }
        }


        $table_data = '<table border="1"><tr>
                        <th colspan="5">用户名单</th>
                        </tr>';

        $table_data .= '<table border="1"><tr>

                <th>openid</th>
                <th>奖品</th>
                <th>时间</th>
    			</tr>';

        header('Content-Type:text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition:attachment;filename="集碎片活动奖品名单.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($list as &$line) {



            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {

//                     $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;
    }
}