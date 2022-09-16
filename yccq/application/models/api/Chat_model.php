<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Date: 2020/03/22
 *
 */

require_once '././GatewayWorker/GatewayClient/Gateway.php';

include_once  'Base_model.php';

class Chat_model extends Base_model{
    function __construct(){
        parent::__construct();

        $this->table = '';
        $this->load->model('api/user_model');
        $this->load->library('user_agent');
        $this->load->model('filteredtext/filterwords_model');
    }

    function init($uid)
    {
//        $sql = "select uid from $this->table WHERE uid=?";
//        $row = $this->db->query($sql,[$uid])->row_array();
//        if(!$row)
//        {
//            $this->insert([
//                'uid' => $uid,
//                'addtime' => t_time()
//            ]);
//        }
    }



    function userDetail($uid,$friend_uid)
    {
        $user = $this->user_model->detail($uid);
        $friend_user = $this->user_model->detail($friend_uid);
        $result['nickname'] = $user['nickname'];
        $result['fnickname'] = $friend_user['nickname'];
        return $result;
    }

    //查询消息列表
    function queryMessage($uid,$fuid)
    {
        $user = $this->user_model->detail($uid);
        $friend_user = $this->user_model->detail($fuid);
        $result['nickname'] = $user['nickname'];
        $result['fnickname'] = $friend_user['nickname'];
        $result['list']  = [];

        $days = time()-24*3*60*60;
        $sql  = ' SELECT   uid,friend_uid,id,content,is_read,addtime ';
        $sql .= ' FROM `chat_detail` WHERE (';
        $sql .= ' (  uid=? AND friend_uid =?) OR  ';
        $sql .= ' ( uid=? AND friend_uid =?) ) AND UNIX_TIMESTAMP(addtime)>=?';
        $sql .= ' ORDER BY is_read ASC,`addtime` DESC limit 1000';
        $result['list'] = $this->db->query($sql,[$fuid,$uid,$uid,$fuid,$days])->result_array();

        $this->db->trans_start();
        foreach($result['list'] as &$value)
        {

           $this->table_update('chat_detail',[
               'is_read'=>1,
               'updatetime'=>t_time()
           ],[
               'id' => $value['id'],
               'is_read' => 0

           ]);

            unset($value['id']);
        }
        $this->db->trans_complete();
        return $result;
    }


    function sendMsg($uid,$friend_uid,$msg,$status)
    {

        $ip = model('midautumn_model')->get_real_ip();
        $msg = $this->filterwords_model->getMain($msg);
        $data = [
            'uid'=> $uid,
            'friend_uid' => $friend_uid,
            'is_read' => $status,
            'content'=>$msg,
            'addtime'=>t_time(),
            'updatetime' => t_time(),
            'ip' => $ip,
            'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() .
                $this->agent->version()
        ];
        $this->table_insert('chat_detail',$data);

        return $data;

    }





}
