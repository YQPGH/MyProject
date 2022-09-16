<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 刮奖填写地址控制器
 */
//include_once 'Base.php';

class ScrapeMessage extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Scrape_model');
        $this->load->model('api/address_model');
    }

    //填写地址页面
    function scrapeAddress(){
        $uid = $_SESSION['uid'];
        $activ = $this->input->get('activ');
        try
        {
            if (!$uid) {
                $msg = '获取用户信息异常，即将重新进入游戏';
                throw new Exception($msg);
            }

            //$uid = '10e4bf3ae3ee123351c5921f9f167bdd';
            //用户元旦奖励地址信息
            $address_info = $this->Scrape_model->get_row("zy_scrape_message",'*',array('uid'=>$uid,'attri_activ'=>$activ));

            
            $address = $address_info['address']?explode(',',$address_info['address']):'';
            $pro_name = $address[0];
            $city_name = $address[1];
            $area_name = $address[2];
            $street = $address[3];

            $pro_info = $this->Scrape_model->get_row("zy_province",'PROVINCE_CODE',array('PROVINCE_NAME'=>$pro_name));
            $city_info = $this->Scrape_model->get_row("zy_city",'CITY_CODE',array('CITY_NAME'=>$city_name));
            $area_info = $this->Scrape_model->get_row("zy_area",'AREA_CODE',array('AREA_NAME'=>$area_name));

            $pro_code = $pro_info['PROVINCE_CODE']?:'';
            $city_code = $city_info['CITY_CODE']?:'';
            $area_code = $area_info['AREA_CODE']?:'';

            $province_arr = $this->address_model->get_province($pro_code,$city_code,$area_code);

            $province_name = $province_arr['province'];
            $city_name =$province_arr['city'];
            $area_name = $province_arr['area'];

            $save_status = 3;
            //保存，修改，不能变更
            if ($address_info['address_time']) {
                $can_update_time = $address_info['address_time'] + 86400;
                $now_time = time();
                if ( $now_time > $can_update_time ) {
                    $save_status = 3;//不能变更地址
                } 
                else{
                    $save_status = 2;//修改地址
                }
            }
            else{
                $save_status = 1;//保存地址
            }
            

            $data = array(
                    'id'=>$address_info['id'],
                    'uid'=>$uid,
                    'truename'=>$address_info['truename'],
                    'phone'=>$address_info['phone'],                
                    'save_status'=>$save_status,
                    'province_name'=>$province_name,
                    'city_name'=>$city_name,
                    'area_name'=>$area_name,
                    'street'=>$street,
                    'activ'=>$activ
            );

            $this->load->view('client/scrape_address', $data);

        }
        catch (Exception $e)
        {
            $msg = $e->getMessage();
            echo"<script>alert('$msg');history.go(-1);</script>";
        }

        
        
    }

    //刮奖地址导出
    function scrapeExcelOut(){
        $activ = isset($_GET['activ'])?$_GET['activ']:'none';
        //导出后，给数据库添加一个已经发货字段，识别重复导出
        $now_time = time();
        //导出前一天24小时的地址
        $out_time = $now_time-86400;
        $query = $this->db->query(
            "select shop_name,uid,truename,phone,address,address_time,update_time from zy_scrape_message where is_out = 0 and address_time < '$out_time' and attri_activ = '$activ'");
        $list = $query->result_array();
        //为当前导出的数据添加已导出
        $this->Scrape_model->table_update('zy_scrape_message',array('is_out'=>1),array('address_time<'=>$out_time,'is_out'=>0,'attri_activ'=>$activ));
        $data = array();
        foreach ($list as $key => $val) {
            $user_info = $this->Scrape_model->get_row("zy_user",'openid,nickname',array('uid'=>$val['uid']));
            $data[$key]['nickname'] = $user_info['nickname'];
            $data[$key]['uid'] = $val['uid'];
            $data[$key]['openid'] = $user_info['openid'];
            $data[$key]['shop_name'] = $val['shop_name'];
            $data[$key]['truename'] = $val['truename'];
            $data[$key]['phone'] = $val['phone'];
            $address = str_ireplace(",","",$val['address']);
            $data[$key]['address'] = $address;
            $add_time = $val['update_time']?:$val['address_time'];
            $add_time = date("Y-m-d H:i:s",$add_time);
            $data[$key]['add_time'] = $add_time;

        }
        $table_data = '<table border="1"><tr>
                <th>昵称</th>
                <th>uid</th>
                <th>openid</th>
                <th>奖品名称</th>
                <th>姓名</th>
                <th>手机号码</th>
                <th>地址</th>
                <th>填写时间</th>
                </tr>';
        
        foreach ($data as $line) {
            $table_data .= '<tr>';

            foreach ($line as $key => &$item) {
                // $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                $table_data .= '<td>' . $item . '</td>';
            }
            $table_data .= '</tr>';
        }
        $table_data .= '</table>';

        $filename = date("m-d",time());
        header('Content-Type: text/xls');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        // $str = mb_convert_encoding($file_name, 'gbk', 'utf-8');
        header('Content-Disposition: attachment;filename='.$filename."刮奖地址.xls");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        
        echo $table_data;
    }




}
