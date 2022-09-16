<?php  defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 放火
 *
 */

include_once 'Base_model.php';

class Fire_model extends Base_model{
    function __construct(){
        parent::__construct();
        $this->table='zy_fire';
        $this->load->model('api/fire_model');
        $this->load->model('api/friend_model');
        $this->load->model('api/user_model');
    }

    /**
     * 开始到好友家放火
     * code 好友随机码
     * @return array
     */
    function start($uid,$code){
        $this->db->trans_start();//开启事务日志
        $friend_uid = $this->friend_model->get_uid($uid, $code);


        $number = config_item('setfire');
        //获取今日
        $today = date("Y-m-d");
        $sql = "select id,start_time from zy_fire  where uid=? ORDER BY id DESC limit 1";
        $query = $this->db->query($sql,[$uid])->row_array();

      //判断是否今天第一次放火
        if($query['start_time']< $today){
            $this->table_update('zy_user',['fire_number' => $number['fire_number']],['uid' =>$uid]);

        }

        $sql = "select burned_num from zy_user  WHERE uid=?";
        $result = $this->db->query($sql,[$friend_uid])->row_array();

        if($result['burned_num'] == $number['burned_num']) t_error(1,'今日该好友添柴次数已上限');

        $sql = "select * from zy_fire  WHERE friend_uid=? ORDER BY id DESC ";
        $fire = $this->db->query($sql,[$friend_uid])->row_array();
        if($fire['is_onfire'] == 0 && strtotime($fire['stop_time'])>time()) t_error(2,'该好友烘烤室已添柴');
        if(strtotime($fire['start_time'])<strtotime($today)){
             $this->table_update('zy_user',['burned_num' => 0],['uid' => $friend_uid]);
        }
        $res = $this->column_sql('fire_number,nickname',['uid'=>$uid],'zy_user',0);

        if($res['fire_number'] == 0) t_error(3,'今日添柴已达上限，请明天再来！');

//        $sql = "select count(*) as num,stop_time from zy_bake where uid=? AND status=? ";
//        $row = $this->db->query($sql,[$friend_uid,1])->row_array();
//        if($row['num']==0) t_error(4,'好友未烘烤烟叶，不可添柴');

        $time = config_item('fire_time');
        $result['nickname'] = $res['nickname'];
        $result['start_time'] = t_time(time());
        $result['jiasu_time'] = t_time($time['jiasu_time']);
        $result['destroy_time'] = t_time($time['destroy_time']);
        $result['stop_time'] = t_time($time['stop_time']);
        $result['number'] = t_rand_str();
        $result['money'] = $number['money'];
        $insert_id = $this->insert([
            'uid' => $uid,
            'friend_uid' => $friend_uid,
            'number' => $result['number'],
            'start_time' => $result['start_time'],
            'jiasu_time' => $result['jiasu_time'],
            'destroy_time' => $result['destroy_time'],
            'stop_time' => $result['stop_time'],
        ]);
        //更新用户表
        $this->db->set('fire_number', 'fire_number-1', FALSE);
        $this->db->set('money', "money+{$number['money']}", FALSE);
        $this->db->where('uid', $uid);
        $this->db->update('zy_user');

        $this->db->set('burned_num', 'burned_num+1', FALSE);
        $this->db->where('uid', $friend_uid);
        $this->db->update('zy_user');


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '系统繁忙请稍后再来');
        unset($result['burned_num']);
        return $result;

    }

    /**
     *
     * 好友灭火
     * @return array
     */
    function outfire($uid,$number){
        $rows = $this->update(['is_onfire' => 1,'stop_time' => t_time()],['friend_uid' => $uid,'number' => $number]);
        if(!$rows) t_error(1,'灭火失败!');
        return;
    }

    /**
     * 获取被放火信息
     * 到期火自动熄灭
     */
    function fire_status($uid){

        $this->update(['is_onfire' => 2],['friend_uid' => $uid,'is_onfire' => 0,'stop_time<=' => t_time()]);
        $this->update(['is_onfire' => 2],['uid' => $uid,'is_onfire' => 0,'stop_time<=' => t_time()]);

        $sql = "select a.number,a.start_time,a.jiasu_time,a.destroy_time,a.stop_time,b.nickname from zy_fire a,zy_user b where a.friend_uid=? AND a.uid=b.uid AND UNIX_TIMESTAMP(a.stop_time)>? ORDER BY a.id DESC ";
        $row = $this->db->query($sql,[$uid,time()])->row_array();
        return $row;
    }

    /**
     * 好友家
     * 查询好友有无被放火
     * */
    function friend_fire_status($uid, $code){

        // 根据code 获取好友uid
        $friend_uid = $this->friend_model->get_uid($uid, $code);

        //被放火信息
        $sql = "select a.number,a.start_time,a.jiasu_time,a.destroy_time,a.stop_time,b.nickname from zy_fire a,zy_user b WHERE a.friend_uid=?  and a.uid=b.uid AND UNIX_TIMESTAMP(a.stop_time)>? ORDER BY a.id DESC";
        $fire= $this->db->query($sql,[$friend_uid,time()])->row_array();
        return $fire;
    }

    /**
     * 查询好友是否烘烤
     */
    function friend_bake($uid, $code){
        // 根据code 获取好友uid
        $friend_uid = $this->friend_model->get_uid($uid, $code);
        $sql = "select count(*) as num,status from zy_bake where uid=? AND status=? ";
        $row = $this->db->query($sql,[$friend_uid,1])->row_array();
        $result['status'] = ($row['num']>0)?$row['status']:0;

        return $result;

    }

    /**
     *查询是否被放火，加速期
     */
    function jiasu($uid){

        $sql = "select status,id,jiasu_time,friend_uid from zy_fire WHERE friend_uid=? AND UNIX_TIMESTAMP(jiasu_time)>?  AND is_onfire=?  ORDER BY id DESC ";
        $row = $this->db->query($sql,[$uid,time(),0])->row_array();
        return $row;
    }

    function test($uid){
//        print_r($uid);exit;
//        $today = date("Y-m-d");
    }
}
