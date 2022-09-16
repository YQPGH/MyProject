<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
class Excel extends CI_Controller{

    function __construct(){
        $this->control = 'excel';
        parent ::__construct();
    }


       // 导出Excel
        public function excelOut()
        {

//            $query = $this->db->query(
//                "select a.uid,a.truename,a.address,b.add_time,b.pid from zy_laxin_message a,zy_laxin_prize_record b WHERE a.out=1 AND a.uid=b.uid AND b.is_real=1");
//            $query = $this->db->query(
//                "select shop_name,uid,truename,address,add_time from zy_scrape_message where status = 1 ");
//            $list = $query->result_array();

            $date = date('Y-m-d');
            $query = $this->db->query(
                "select a.id,a.uid,c.sourceorderno,a.truename,a.phone,a.address,a.add_time,c.add_time receive_time,c.pid
                from zy_message a,zy_prize_record c
                WHERE a.type='trees' AND c.ticket_id<1 and c.type=1 AND a.status=1 and  a.out=0
                AND a.pid=c.id");
            $list = $query->result_array();


//            $where = "  c.is_real=1 and a.out=1 ";
//            $list = $this->db->select('a.truename,a.address,a.uid,b.name,c.add_time')->from('zy_laxin_message a')
//                ->join('zy_laxin_prize_record c','a.pid=c.id ','left')->join('zy_prize b', 'b.id=c.pid','left')
//                ->where($where)->get()->result_array();
            $title = '种植集能量，劳动最闪亮';
            foreach($list as &$value)
            {
                $str = explode(',',$value['address']);


                $row = $this->db->query("select openid,nickname from zy_user WHERE uid='$value[uid]'")->row_array();
                $sql = "select s.name,p.shop1_total from zy_prize p,zy_shop s WHERE  p.shop1=s.shopid AND p.id=? ";
                $prize  = $this->db->query($sql,[$value['pid']])->row_array();

                $value['openid'] = $row['openid'];
                $value['nickname'] = $row['nickname'];
                $value['qdnumber'] = '';
                $value['orderno'] =$value['sourceorderno'];
                $value['name'] = $prize['name'];
                $value['shop_number'] = '';
                $value['num'] = $prize['shop1_total'];
                $value['true_name'] = $value['truename'];
                $value['ph'] = $value['phone'];
                $value['province'] = $str[0];
                $value['city'] = $str[1];
                $value['area'] = $str[2];
                $value['street'] = $str[3];

//                $value['r_time'] = $value['receive_time'];
                $value['time'] = $value['add_time'];
                $value['title'] = $title;
                $value['brandnum'] = '';
                $value['areacode'] = '';
                unset($value['sourceorderno'],$value['truename'],$value['phone'],$value['uid'],$value['pid'],$value['address'],$value['add_time'],$value['receive_time']);
            }

            $table_data = '<table border="1"><tr>
                        <th colspan="17">用户名单</th>
                        </tr>';

            $table_data .= '<table border="1"><tr>
                <th>OPID</th>
      			<th>昵称</th>
      			<th>渠道编码</th>
      			<th>订单号</th>
      			<th>物品名称</th>
      			<th>物品编码</th>
      			<th>物品数量</th>
                <th>姓名</th>
      			<th>电话</th>
      			<th>省份</th>
      			<th>城市</th>
      			<th>区域</th>
      			<th>详细地址</th>
                <th>订单时间</th>
                <th>方案名称</th>
                <th>品牌编码</th>
                <th>区域代码</th>
    			</tr>';
            header('Content-Type: text/xls');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
            header('Content-Disposition: attachment;filename="'.$title.$date.'.xls"');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            foreach ($list as &$line) {

                $this->db->where('id',$line['id'])
                    ->update('zy_message',array('out'=>1));
                unset($line['id']);
                $table_data .= '<tr>';
//                $line['user_address']=urlencode($line['user_address']);//将关键字编码
//                $line['user_address']=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$line['user_address']);
//                $line['user_address']=urldecode($line['user_address']);//将过滤后的关键字解码

                foreach ($line as $key => &$item) {

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

    function excel_reader()
    {

        $this->load->view('excelin');
    }

    // 导入Excel
    function excel()
    {

        if($_FILES['file']['error']){
            switch($_FILES['file']['error']){
                case 1:
                    t_error('文件大小超出了服务器的空间大小',1);
                    die;
                    break;
                case 2:
                    t_error('要上传的文件大小超出浏览器限制',1);
                    die;
                    break;
                case 3:
                    t_error('文件仅部分被上传',1);
                    die;
                    break;
                case 4:
                    t_error('没有找到要上传的文件',1);
                    die;
                    break;
                case 5:
                    t_error('服务器临时问价丢失',1);
                    die;
                    break;
                case 6:
                    t_error('文件写入到临时文件夹出错',1);
                    die;
                    break;
                default:
                    t_error('文件上传未知错误',1);
            }
            die;
        }

        //接收的excel信息
        $info = pathinfo($_FILES['file']['name']);

        $file = $_FILES['file'];

        //验证excel文件类型
        $res = $this->detectUploadFileMIME($file);
        if($res) {
            $tmp_name = $info['filename'];
            $new_name = $tmp_name . '-' . time() . '.' . $info['extension'];
            $root_path = dirname(BASEPATH);

            $new_dir = $root_path . '/data/excel/';
            if (!file_exists($new_dir)) {
                mkdir($new_dir, 0777, true);
            }
            $new_path = $new_dir . $new_name;
            $new_path = str_replace('\\', DIRECTORY_SEPARATOR, $new_path);
            $new_path = str_replace('/', DIRECTORY_SEPARATOR, $new_path);
            $res = move_uploaded_file($_FILES['file']['tmp_name'], $new_path);

            if (!$res) {
                t_error('上传失败', 2);
                die;
            };

            if (!file_exists($new_path)) {
                exit("文件" . $new_path . "不存在");
            }
            /*加载PHPExcel*/
            $this->load->library('PHPExcel.php');
            $this->load->library('PHPExcel/IOFactory.php');
            // $this->load->library('PHPExcel/Reader/Excel5.php');
            $objPHPExcel = new PHPExcel();
            $objProps = $objPHPExcel->getProperties();

            //设置excel格式
            if ($info['extension'] == 'xlsx') {
                $objReader = IOFactory::createReader('excel2007');
            } else {
                $objReader = IOFactory::createReader('Excel5');
            }


            //载入excel文件
            $objPHPExcel = $objReader->load($new_path);
            //读取第一张表
            $sheet = $objPHPExcel->getSheet(0);

            //获取总行数
            $highestRow = $sheet->getHighestRow();
            //获取总列数
            $highestColumn = $sheet->getHighestColumn();
//            print_r($highestColumn);exit;
            $excel_data = array();
            for ($col = 'A'; $col <= $highestColumn; $col++) {

                for ($row = 1; $row <= $highestRow; $row++) {
                    $excel_data[$row -0][] = $sheet->getCell($col . $row)->getValue();
                }


            }
//            print_r($excel_data);
//            die;
//            $this->insert_excel($excel_data);
//            echo "导入成功";
        }

    }

    /**
     * 检测是否为excel文件程序
     *
     *
     */
    public function detectUploadFileMIME($file) {
        // 1.through the file extension judgement 03 or 07
        $flag = 0;
        $file_array = explode ( ".", $file ["name"] );
        $file_extension = strtolower ( array_pop ( $file_array ) );

        // 2.through the binary content to detect the file
        switch ($file_extension) {
            case "xls" :
                // 2003 excel
                $fh = fopen ( $file ["tmp_name"], "rb" );
                $bin = fread ( $fh, 8 );
                fclose ( $fh );
                $strinfo = @unpack ( "C8chars", $bin );
                $typecode = "";
                foreach ( $strinfo as $num ) {
                    $typecode .= dechex ( $num );
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
                break;
            case "xlsx" :
                // 2007 excel
                $fh = fopen ( $file ["tmp_name"], "rb" );
                $bin = fread ( $fh, 4 );
                fclose ( $fh );
                $strinfo = @unpack ( "C4chars", $bin );
                $typecode = "";
                foreach ( $strinfo as $num ) {
                    $typecode .= dechex ( $num );
                }
                // echo $typecode;
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }

        // 3.return the flag
        return $flag;
    }



//插入数据到数据库
    public function insert_excel($data){

        foreach($data as $value)
        {
            $data = array(
                'openid'=>$value[0],
                'nickname' => $value[1],
                'truename' => $value[2],
                'phone' => $value[3],
                'address' => $value[4],
                'prize' => $value[5],
                'num' => $value[6],
                'time' => $value[7],
                'sub_time' => $value[8],
                'theme' => $value[10]

            );
            $this->db->insert('wl_smoke',$data);
        }


    }

    function a()
    {
        $list = $this->db->query("SELECT DISTINCT  u.name,u.add_time FROM a_test_copy u WHERE    u.add_time
NOT IN (SELECT o.add_time FROM a_test o);")->result_array();
        print_r($list);
//        echo $this->db->last_query();
//exit;
        $list = $this->db->query("select id,name,addtime from a_test ")->result_array();

        $a = '43844.018078704';//从excel导入后的时间
        $d = 25569;//excel和php之间相差的时间
        $t = 24 * 60 * 60;//一天24小时
        foreach($list as $value)
        {
            $value['addtime'] = gmdate('Y-m-d H:i:s', ($value['addtime'] - $d) * $t);
//            $row = $this->db->query("select name,addtime from a_test ")->row_array();
            $this->model->table_update(
                'a_test',
                ['add_time'=>$value['addtime']],
                ['id'=>$value['id']]
            );
        }

//print_r($list);


    }


    function t(){

    }



}
