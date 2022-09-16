<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
class Activity_count extends CI_Controller{

    function __construct(){
        $this->control = 'Activity_count';
        parent ::__construct();
    }



    function qixi()
    {
        $count = $this->db->query(
            "select count(*) as num,pid from zy_qixi_prize_record  GROUP BY pid"
        )->result_array();

        $query = $this->db->query(
            "select a.id,a.uid,a.truename,a.phone,a.address,a.add_time,a.out,c.add_time receive_time,c.pid
                from zy_message a,zy_qixi_prize_record c
                WHERE c.ticket_id<1 and c.type=1
                AND a.pid=c.id");
        $list = $query->result_array();
        $countprize = [];
        $countout = [];

        foreach($list as &$value)
        {

            $sql = "select `name` from zy_prize WHERE  id=? ";
            $prize  = $this->db->query($sql,[$value['pid']])->row_array();

            $value['name'] = $prize['name'];
            array_push($countprize,$value['name']);
            if($value['out'] == 1)
            {
                array_push($countout,$value['name']);

            }

        }

        $countprize = array_count_values($countprize);
        $countout = array_count_values($countout);

        echo '<h2>奖品抽奖数量 / 已填写数量 / 已导出</h2>';
        echo '真龙君招财进宝煤油打火机： '.$count[1]['num'].
            ' / <span style="color:#ff0000">'.$countprize['真龙君招财进宝煤油打火机'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['真龙君招财进宝煤油打火机'].'</span>'.'<br>';
        echo '乐豆中心粗陶陆宝快客杯： '.$count[4]['num'].
            ' / <span style="color:#ff0000">'.$countprize['乐豆中心粗陶陆宝快客杯'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['乐豆中心粗陶陆宝快客杯'].'</span>'.'<br>';
        echo '佐罗充气打火机： '.$count[2]['num'].
            ' / <span style="color:#ff0000">'.$countprize['佐罗充气打火机'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['佐罗充气打火机'].'</span>'.
            '<br>';
        echo '真龙君钥匙扣： '.$count[0]['num'].
            ' / <span style="color:#ff0000">'.$countprize['真龙君钥匙扣'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['真龙君钥匙扣'].'</span>'.'<br>';
        echo '喷漆（蓝）打火机： '.$count[3]['num'].
            ' / <span style="color:#ff0000">'.$countprize['喷漆（蓝）打火机'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['喷漆（蓝）打火机'].'</span>'.'<br>';
        echo '口粮礼包： '.$count[9]['num'].
            ' / <span style="color:#ff0000">'.$countprize['口粮礼包'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['口粮礼包'].'</span>'.'<br>';
        echo '1300乐豆口粮代金券： '.$count[5]['num'].'<br>';
        echo '1200乐豆口粮代金券： '.$count[6]['num'].'<br>';
        echo '800乐豆口粮代金券： '.$count[7]['num'].'<br>';


        echo '<h2>2020年七夕每日抽奖次数统计</h2>';
        //统计一个月内信息
        $april = '08';
        $year = '2020';
        $april_day = cal_days_in_month(CAL_GREGORIAN, $april, $year);   //当月最后一天
        $april_min = $year.'-'.$april.'-01 00:00:00';
        $max_april = $year.'-'.$april.'-'.$april_day.' 23:59:59';

        $april_sql = "select count(*) as num,add_time  from zy_qixi_prize_record where add_time>'$april_min' and add_time<'$max_april' group by date_format(add_time,'%Y-%c-%d') order by add_time";
        $april_count = $this->db->query($april_sql)->result_array();

        foreach($april_count  as $key=>$value)
        {
            $date = date('Y-m-d',strtotime($value['add_time']));
            echo $date.'抽奖次数：'.$value['num'].'<br/>';
        }
        echo  '<br/>';
        $month = '09';
        $max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);   //当月最后一天
        $min = $year.'-'.$month.'-01 00:00:00';
        $max = $year.'-'.$month.'-'.$max_day.' 23:59:59';

        $sql = "select count(*) as num,add_time  from zy_qixi_prize_record where add_time>'$min' and add_time<'$max' group by date_format(add_time,'%Y-%c-%d') order by add_time";
        $return = $this->db->query($sql)->result_array();
        foreach($return  as $value)
        {
            $date = date('Y-m-d',strtotime($value['add_time']));

            echo $date.'抽奖次数：'.$value['num'].'<br/>';
        }
    }

    function scrape()
    {

        $query = $this->db->query(
            "select shop_name,COUNT(*) num from zy_scrape_message where is_out = 1 GROUP BY shop_id");
        $list = $query->result_array();
        echo '<h2>您有一份礼物待查收</h2>';
        foreach ($list as $key => $val) {
            echo $val['shop_name'].'：'.$val['num'].'<br>';

        }
    }


    //五一活动叠金叶奖品统计
    function count()
    {


        $time = strtotime('2020-04-01 00:00:00');
        $c = $this->db->query(
            "select count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)<'$time' GROUP BY pid"
        )->result_array();

        $lists = $this->db->query(
            "select a.id,a.uid,a.truename,a.phone,a.address,a.add_time,a.out,c.add_time receive_time,c.pid
                from zy_leaf_message a,zy_leaf_prize_record c
                WHERE c.ticket_id<1 and c.type=1 AND UNIX_TIMESTAMP(a.add_time)<'$time'
                AND a.pid=c.id")->result_array();
        $prize = [];
        $out = [];

        foreach($lists as &$value)
        {

            $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE  p.shop=s.shopid AND p.id=? ";
            $prizes  = $this->db->query($sql,[$value['pid']])->row_array();

            $value['name'] = $prizes['name'];
            array_push($prize,$value['name']);
            if($value['out'] == 1)
            {
                array_push($out,$value['name']);

            }

        }

        $prize = array_count_values($prize);
        $out = array_count_values($out);

        echo '<h2>奖品抽奖数量 / 已填写数量 / 已导出</h2>';
        echo '1200乐豆口粮代金券： '.$c[3]['num'].'<br>';
        echo '真龙君逍遥游扑克牌： '.$c[4]['num'].
            ' / <span style="color:#ff0000">'.$prize['真龙君逍遥游扑克牌'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['真龙君逍遥游扑克牌'].'</span>'.'<br>';
        echo '真龙君钥匙扣： '.$c[5]['num'].
            ' / <span style="color:#ff0000">'.$prize['真龙君钥匙扣'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['真龙君钥匙扣'].'</span>'.'<br>';
        echo '洁丽雅纯棉舒适面巾： '.$c[6]['num'].
            ' / <span style="color:#ff0000">'.$prize['洁丽雅纯棉舒适面巾'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['洁丽雅纯棉舒适面巾'].'</span>'.'<br>';
        echo '车载烟灰缸： '.$c[7]['num'].
            ' / <span style="color:#ff0000">'.$prize['车载烟灰缸'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['车载烟灰缸'].'</span>'.'<br>';

        echo '香草传奇抱枕被： '.$c[8]['num'].
            ' / <span style="color:#ff0000">'.$prize['香草传奇抱枕被'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['香草传奇抱枕被'].'</span>'.'<br>';
        echo '瑞士军刀： '.$c[9]['num'].
            ' / <span style="color:#ff0000">'.$prize['瑞士军刀'].'</span>'.
            ' / <span style="color:#0080FF">'.$out['瑞士军刀'].'</span>'.'<br>';



        $count = $this->db->query(
            "select count(*) as num from zy_leaf_prize_record WHERE UNIX_TIMESTAMP(add_time)>'$time' GROUP BY pid"
        )->result_array();

        $query = $this->db->query(
            "select a.id,a.uid,a.truename,a.phone,a.address,a.add_time,a.out,c.add_time receive_time,c.pid
                from zy_leaf_message a,zy_leaf_prize_record c
                WHERE c.ticket_id<1 and c.type=1 AND UNIX_TIMESTAMP(a.add_time)>'$time'
                AND a.pid=c.id");
        $list = $query->result_array();
        $countprize = [];
        $countout = [];

        foreach($list as &$value)
        {

            $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE  p.shop=s.shopid AND p.id=? ";
            $prize  = $this->db->query($sql,[$value['pid']])->row_array();

            $value['name'] = $prize['name'];
            array_push($countprize,$value['name']);
            if($value['out'] == 1)
            {
                array_push($countout,$value['name']);

            }

        }

        $countprize = array_count_values($countprize);
        $countout = array_count_values($countout);

        echo '<h2>奖品抽奖数量 / 已填写数量 / 已导出</h2>';
        echo '乐豆中心超静音创意桌面暖风机： '.$count[0]['num'].
            ' / <span style="color:#ff0000">'.$countprize['乐豆中心超静音创意桌面暖风机'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['乐豆中心超静音创意桌面暖风机'].'</span>'.'<br>';
        echo '乐豆中心水晶烟灰缸： '.$count[1]['num'].
            ' / <span style="color:#ff0000">'.$countprize['乐豆中心水晶烟灰缸'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['乐豆中心水晶烟灰缸'].'</span>'.'<br>';
        echo '香草传奇定制加长加宽鼠标垫： '.$count[2]['num'].
            ' / <span style="color:#ff0000">'.$countprize['香草传奇定制加长加宽鼠标垫'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['香草传奇定制加长加宽鼠标垫'].'</span>'.
            '<br>';
        echo '洁丽雅纯棉舒适面巾： '.$count[3]['num'].
            ' / <span style="color:#ff0000">'.$countprize['洁丽雅纯棉舒适面巾'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['洁丽雅纯棉舒适面巾'].'</span>'.'<br>';
        echo '1200乐豆口粮代金券： '.$count[4]['num'].'<br>';
        echo '香薰蜡烛礼盒装： '.$count[5]['num'].
            ' / <span style="color:#ff0000">'.$countprize['香薰蜡烛礼盒装'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['香薰蜡烛礼盒装'].'</span>'.'<br>';
        echo '真龙君笔记本： '.$count[6]['num'].
            ' / <span style="color:#ff0000">'.$countprize['真龙君笔记本'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['真龙君笔记本'].'</span>'.'<br>';
        echo '800乐豆口粮代金券： '.$count[7]['num'].'<br>';
        echo 'SWISSGEAR洗漱包： '.$count[8]['num'].
            ' / <span style="color:#ff0000">'.$countprize['SWISSGEAR洗漱包'].'</span>'.
            ' / <span style="color:#0080FF">'.$countout['SWISSGEAR洗漱包'].'</span>'.'<br>';

        echo '<h2>2020年叠金叶每日抽奖次数统计</h2>';
      //统计一个月内信息
        $april = '04';
        $year = '2020';
        $april_day = cal_days_in_month(CAL_GREGORIAN, $april, $year);   //当月最后一天
        $april_min = $year.'-'.$april.'-01 00:00:00';
        $max_april = $year.'-'.$april.'-'.$april_day.' 23:59:59';

        $april_sql = "select count(*) as num,add_time  from zy_leaf_prize_record where add_time>'$april_min' and add_time<'$max_april' group by date_format(add_time,'%Y-%c-%d') order by add_time";
        $april_count = $this->db->query($april_sql)->result_array();

        foreach($april_count  as $key=>$value)
        {
            $date = date('Y-m-d',strtotime($value['add_time']));
            echo $date.'抽奖次数：'.$value['num'].'<br/>';
        }
        echo  '<br/>';
        $month = '05';
        $max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);   //当月最后一天
        $min = $year.'-'.$month.'-01 00:00:00';
        $max = $year.'-'.$month.'-'.$max_day.' 23:59:59';

        $sql = "select count(*) as num,add_time  from zy_leaf_prize_record where add_time>'$min' and add_time<'$max' group by date_format(add_time,'%Y-%c-%d') order by add_time";
        $return = $this->db->query($sql)->result_array();
        foreach($return  as $key=>$value)
        {
            $date = date('Y-m-d',strtotime($value['add_time']));

            echo $date.'抽奖次数：'.$value['num'].'<br/>';
        }


    }

    //名单分类统计
    function group_count()
    {
        $time =  strtotime('2020-04-01 00:00:00');

        $list = $this->db->query("select b.openid,b.nickname,a.pid,a.uid,a.truename,a.address from zy_leaf_message a,zy_user b
        WHERE  a.uid=b.uid AND  UNIX_TIMESTAMP(a.add_time)>'$time' ;")->result_array();

        foreach($list as &$value)
        {

            $row = $this->db->query("select pid,add_time from zy_leaf_prize_record WHERE uid='$value[uid]' AND id='$value[pid]' AND UNIX_TIMESTAMP(add_time)>'$time'")->row_array();
            $sql = "select s.name from zy_leaf_prize_config p,zy_shop s WHERE p.type2=? AND p.shop=s.shopid AND p.id=? ";
            $prize  = $this->db->query($sql,['prize',$row['pid']])->row_array();
            $value['name'] = $prize['name'];
            $value['uaddress'] = str_replace(",",'',$value['address']);

            $value['add_time'] = $row['add_time'];


            unset($value['address'],$value['uid'],$value['pid']);

        }
        $idArr = array_column($list, 'name');
        array_multisort($idArr,SORT_DESC,$list);
        $table_data = '<table border="1"><tr>
                        <th colspan="6">用户名单</th>
                        </tr>';

        $table_data .= '<table border="1"><tr>
                <th>openid</th>
      			<th>昵称</th>
      			<th>姓名</th>
                <th>奖品</th>
                <th>邮寄地址</th>
                <th>领取时间</th>
    			</tr>';
        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="叠金叶活动用户名单.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($list as &$line) {

            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {

                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;

    }
       // 导出Excel
        public function excelOut()
        {
//            $query = $this->db->query(
//                "select a.uid,a.truename,a.address,b.add_time,b.pid from zy_laxin_message a,zy_laxin_prize_record b WHERE a.out=1 AND a.uid=b.uid AND b.is_real=1");
//            $query = $this->db->query(
//                "select shop_name,uid,truename,address,add_time from zy_scrape_message where status = 1 ");
//            $list = $query->result_array();

            $query = $this->db->query(
                "select a.id,a.uid,a.truename,a.phone,a.address,a.add_time,c.add_time receive_time,c.pid
                from zy_leaf_message a,zy_leaf_prize_record c
                WHERE a.out=0 AND c.ticket_id<1 and c.type=1 AND a.status=1
                AND a.pid=c.id AND a.add_time>'2020-11-01 00:00:00'");
            $list = $query->result_array();

//            $where = "  c.is_real=1 and a.out=1 ";
//            $list = $this->db->select('a.truename,a.address,a.uid,b.name,c.add_time')->from('zy_laxin_message a')
//                ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
//                ->where($where)->get()->result_array();
            $day = date('m-d');
            foreach($list as &$value)
            {

                $row = $this->db->query("select openid,nickname from zy_user WHERE uid='$value[uid]'")->row_array();
                $sql = "select b.type1,b.description,b.`name` from zy_leaf_prize_config a,zy_shop b WHERE a.type2=? AND  b.shopid=a.shop AND a.id=?";
                $prize  = $this->db->query($sql,['nov_11',$value['pid']])->row_array();
                $value['openid'] = $row['openid'];
                $value['nickname'] = $row['nickname'];
                $value['true_name'] = $value['truename'];
                $value['ph'] = $value['phone'];
                $value['user_address'] = $value['address'];
                $value['name'] = $prize['type1'] ? $prize['name'] : $prize['description'];;
                $value['prize_num'] = 1;
                $value['r_time'] = $value['receive_time'];
                $value['time'] = $value['add_time'];
                $value['order_number'] = '';
                unset($value['truename'],$value['phone'],$value['uid'],$value['pid'],$value['address'],$value['add_time'],$value['receive_time'],$prize['type1'],$prize['description']);
            }

            $table_data = '<table border="1"><tr>
                        <th colspan="10">用户名单</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>
                <th>OPID</th>
      			<th>昵称</th>
                <th>姓名</th>
      			<th>电话</th>
      			<th>邮寄地址</th>
                <th>奖品</th>
                <th>发货数量</th>
                <th>领取时间</th>
                <th>填写时间</th>
                <th>快递单号</th>
    			</tr>';
            header('Content-Type: text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition: attachment;filename="双十一活动用户名单"'.$day.'".xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            foreach ($list as &$line) {

                $this->db->where('id',$line['id'])
                    ->update('zy_leaf_message',array('out'=>1));
                unset($line['id']);
                $table_data .= '<tr>';
                $line['user_address']=urlencode($line['user_address']);//将关键字编码
                $line['user_address']=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$line['user_address']);
                $line['user_address']=urldecode($line['user_address']);//将过滤后的关键字解码

                foreach ($line as $key => &$item) {

                    // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                    $table_data .= '<td>' . $item . '</td>';
                }
                $table_data .= '</tr>';
            }
            $table_data .= '</table>';
            echo $table_data;
        }

    function plant()
    {
        $time = strtotime('2020-03-01 00:00:00');
        $tables = ['zy_ranking_zz_prize_record','zy_ranking_zy_prize_record'];
        $array = [];
        foreach($tables as $value)
        {

            $list = $this->db->query("SELECT ticket.uid,ticket.add_time,ticket.id,ticket.pid,c.shop3_id shop1
                     FROM $value ticket, zy_ranking_jf_prize_config c
                     WHERE c.shop3_id>0 and ticket.`pid` = c.`id`
                     AND UNIX_TIMESTAMP(ticket.add_time)>'$time'
                     ORDER BY ticket.add_time ")->result_array();

            if($list && count($list)>0)
            {
                foreach($list as $key => $value)
                {
                    $array[] = $value;
                }
            }
        }


        $count_list = [];
        foreach($array as &$value)
        {
            $res = $this->db->query("select b.openid,b.nickname,a.truename,a.address from zy_plant_ranking_message a,zy_user b WHERE a.uid='$value[uid]' AND a.pid='$value[id]' AND a.uid=b.uid")->row_array();


            $row = $this->db->query("select `name` from zy_shop WHERE shopid='$value[shop1]'")->row_array();

            if($res)
            {
                $value['openid'] =  $res['openid'] ;
                $value['nickname'] =  $res['nickname'] ;
                $value['truename'] = $res['truename'];
                $value['name'] = $row['name'];
                $value['address'] = $res['address'];
                $value['time'] = $value['add_time'];
                unset($value['uid'],$value['id'],$value['pid'], $value['shop1'],$value['add_time']);
                $count_list[] = $value;
            }

        }

        $idArr = array_column($count_list, 'name');
        array_multisort($idArr,SORT_DESC,$count_list);
        $table_data = '<table border="1"><tr>
                        <th colspan="6">用户名单</th>
                        </tr>';

        $table_data .= '<table border="1"><tr>
                <th>openid</th>
      			<th>昵称</th>
      			<th>姓名</th>
                <th>奖品</th>
                <th>邮寄地址</th>
                <th>领取时间</th>
    			</tr>';
        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="种植排行大比拼活动用户名单.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($count_list as &$line) {

            $table_data .= '<tr>';

            foreach ($line as  &$item) {

                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;
    }

    //查询出A表中相对于B表多余的数据
    function query()
    {
       $result = $this->db->query("SELECT DISTINCT  u.uid,u.id,u.pid,u.add_time FROM zy_leaf_prize_record u WHERE u.type>0
AND u.ticket_id<1 AND u.pid=19  AND u.add_time>'2020-04-01 00:00:00'
AND u.add_time<'2020-05-05 09:30:00' AND u.id
NOT IN (SELECT o.pid FROM zy_leaf_message o);")->result_array();

    }

    function test()
    {


//        $result = $this->db->query("SELECT a.openid,a.nickname,	a.name,a.prize,a.address,b.number
// FROM a_test a,a_test_copy b WHERE a.name=b.name AND a.prize=b.prize;")->result_array();
        $result = $this->db->query("SELECT DISTINCT  a.openid,a.nickname,	a.name,a.prize,a.address,a.num FROM bag_test a WHERE
 a.address
 not IN (SELECT b.number FROM bag_test_send b) ;")->result_array();


        $result = $this->db->query("SELECT a.openid,a.nickname,	a.name,a.prize,a.address,a.num
 FROM bag_test a ;")->result_array();

        foreach($result as &$v)
        {
            $row = $this->db->query("select `number` from bag_test_send WHERE `name`='$v[name]' AND prize='$v[prize]'")->row_array();
            $v['number'] = $row?$row['number']:'';
        }
        print_r($result);exit;
        $table_data = '<table border="1"><tr>
                        <th colspan="7">用户名单</th>
                        </tr>';

        $table_data .= '<table border="1"><tr>
                <th>OPID</th>
      			<th>昵称</th>
      			<th>姓名</th>
                <th>奖品</th>
                <th>邮寄地址</th>
                <th>数量</th>
                <th>邮政</th>
    			</tr>';
        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="名单.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($result as &$line) {

            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {

                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;

    }

function getPrize()
{
//    $array = [
//        '6aa3f9bbf92de3d7f4def8c3c517f648','32bf2067a6c32e1a265c1258b208720a','475a279b0f5c4bd088d63bd2fd1047ef','10e4bf3ae3ee123351c5921f9f167bdd'
//    ];
//    print_r($array);exit;
//    foreach($array as $value)
//    {
//        $res = $this->db->query("select * from zy_leaf WHERE  uid=?",[$value])->row_array();
//        if(!$res['luck_times']) continue;
//        $user = $this->db->query("select * from zy_leaf_prize_record WHERE  pid=22 AND uid=?",[$value])->row_array();
//        if($user) continue;
//        $data = [
//            'uid'=>$value,
//            'pid' => 22,
//            'ticket_id'=>0,
//            'shandian' => 0,
//            'money' => 0,
//            'type'=> 1,
//            'add_time'=>t_time(),
//            'ip' => $this->get_real_ip(),
//            'user_agent' => 'Android/Chrome77.0.3865.120'
//        ];
//
//
//        $this->db->insert('zy_leaf_prize_record',$data);
//        //更新用户抽奖次数
//
//        $this->db->set('luck_times','luck_times-1',false)
//            ->where('uid',$value)
//            ->update('zy_leaf');
//    }
}




function aa()
{



    $list1 = $this->db->query("SELECT COUNT(*) FROM ( SELECT uid,add_time,COUNT(*) num FROM zy_user_login WHERE
 add_time>'2020-08-25 00:00:00' AND add_time<'2020-09-15 23:59:59'  GROUP BY uid ) a
GROUP BY  DATE_FORMAT( add_time,'%j')")->result_array();


    $list1 = array_sum(array_column($list1, 'COUNT(*)'));

    exit;
}

    function dd()
    {

        $list = $this->db->query("SELECT uid,shopid,COUNT(*) FROM zy_store GROUP BY uid,shopid  HAVING(COUNT(shopid))>1")->result_array();
        foreach($list as $v)
        {
            $store = $this->db->query("select uid,id from zy_store WHERE uid=? and shopid=? AND total=0",[$v['uid'],$v['shopid']])->row_array();
            if($store)
            {
                $this->table_delete('zy_store',['uid'=>$store['uid'],'id'=>$store['id']]);

            }

        }
    }


    function groupDay()
    {
//        $starttime  = ;//trim($this->input->get('start')).' 00:00:00';
//        $endtime  = ;//trim($this->input->get('end')).' 23:59:59';

        $data = $this->db->query("SELECT DATE_FORMAT(add_time,'%Y-%m-%d') add_time
        FROM zy_user_login
        WHERE add_time BETWEEN '2021-05-01 00:00:00' AND '2021-05-31 23:59:59' GROUP BY  DATE_FORMAT( add_time,'%j')")->result_array();

        foreach($data as &$value)
        {
            $starttime  = $value['add_time'].' 00:00:00';
            $endtime  = $value['add_time'].' 23:59:59';
            $row = $this->db->query("SELECT COUNT(*) num FROM (SELECT COUNT(*) FROM zy_user_login
WHERE add_time BETWEEN '$starttime' AND '$endtime'  GROUP BY uid) a")->row_array();
            $value['total'] = $row['num'];
        }

        $table_data = '<table border="1"><tr>
                        <th colspan="2">用户统计</th>
                        </tr>';

        $table_data .= '<table border="1"><tr>
                <th>日期</th>
      			<th>统计</th>
    			</tr>';
        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename="统计.xls"');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        foreach ($data as &$line) {


            foreach ($line as $key => &$item) {

                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';
        echo $table_data;

//        $list = $this->db->query("SELECT COUNT(*) '访问量' FROM (SELECT COUNT(*) FROM zy_user_login
//WHERE add_time BETWEEN '$starttime' AND '$endtime'  GROUP BY uid) a")->result_array();

    }

    public  function getMonth($time = '', $format='Y-m-d'){

        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('d', $time);
        $date = [];
        for ($i=1; $i<= date('t', $time); $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-$week .' days', $time));
        }
//print_r($date);exit;
        return $date;
    }

}
