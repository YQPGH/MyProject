<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  种子培育中心
 */
include_once 'Base_model.php';

class Ticket_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_ticket_record';
    }

    public function getTicket($openid, $type){
        if($type==1){
            $stat = ' AND a.stat=1';
        }else if($type==2){
            $stat = '';
        }else {
            $stat = ' AND a.stat=0';
        }
        $where = "a.openid='$openid' $stat ";
        //$res = $this->db->query("select a.shopid, a.ticket_id,a.stat,a.addtime from zy_ticket_record a  WHERE $where")->result_array();
		$res = $this->column_sql('a.shopid, a.ticket_id,a.stat,a.addtime',$where,'zy_ticket_record a',1);
        if(!empty($res)){
            foreach($res as $key=>&$value){
                $this->load->model('admin/shop_model');
                $shop = $this->shop_model->detail($value['shopid']);
                $value['value'] = $shop['mubiao'];
                $value['name'] = $shop['name'];
                $value['vali'] = $shop['vali'] ? date('Y-m-d H:i:s', $value['addtime']+$shop['vali']*24*3600) : '';
                if(($value['addtime']+$shop['vali']*24*3600) < time())
                {
                    unset($res[$key]);
                }
                unset($value['shopid']);
            }
            $res =array_merge($res);
        }
        return $res;
    }

    public function queryTicket($openid, $ticket_id){
        $row = $this->db->query("select a.shopid,a.stat,a.addtime from zy_ticket_record a WHERE openid=? AND ticket_id=?",[$openid,$ticket_id])->row_array();
        if(!empty($row)){
                $this->load->model('admin/shop_model');
                $shop = $this->shop_model->detail($row['shopid']);
                $row['value'] = $shop['mubiao'];
                $row['name'] = $shop['name'];
                $row['vali'] = $shop['vali'] ? date('Y-m-d H:i:s', $row['addtime']+$shop['vali']*24*3600) : '';
                unset($row['shopid']);
        }
        return $row;
    }

    public function subTicket($openid, $ticket_id){
        $row = $this->row(['openid'=>$openid,'ticket_id'=>$ticket_id]);
        if(!empty($row)){
            $this->load->model('admin/shop_model');
            $shop = $this->shop_model->detail($row['shopid']);
            if($row['stat'] == 1){
                t_error(1, '抵扣券已使用！');
            }else if(($row['addtime']+$shop['vali']*24*3600) < time()){
                t_error(1, '抵扣券已过期！');
            }else{
                //更新
                $result = $this->update(['stat'=>1,'updatetime'=>time()],['openid'=>$openid,'ticket_id'=>$ticket_id]);
                //仓库减少一个
                $this->load->model('api/store_model');
                $uid = $this->db->query("select uid from zy_user WHERE openid=?",[$openid])->row_array();
                $this->store_model->update_total(-1, $uid['uid'], $row['shopid']);
                if($result){
                    t_error(0, '抵扣成功！');
                }else{
                    t_error(1, '抵扣失败！');
                }
            }
        }else{
            t_error(1, '抵扣券不存在！');
        }

    }

    //查询本月抵扣券使用了几次
    public function queryNumMonth($openid){
        date_default_timezone_set('Asia/Shanghai');
        $beginThismonth = mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth = mktime(23,59,59,date('m'),date('t'),date('Y'));
        $num = $this->db->query("select count(*) as num from zy_ticket_record WHERE openid=? AND stat=1 AND updatetime>? AND updatetime<?",[$openid,$beginThismonth,$endThismonth])->row_array();

        return $num['num'];
    }






}
