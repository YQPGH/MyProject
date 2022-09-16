<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  好友
 */
include_once 'Base_model.php';

class Friend_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_friend';
        $this->load->model('api/user_model');
    }

    /**
     * 我的好友列表
     * @return array
     */
    function list_my($uid)
    {
        $list = $this->lists('id,friend_uid uid, code,status is_forbidden', ['uid' => $uid], 'id', 100);
        $this->load->model('api/energytrees_model');

        $list = $this->user_model->append_list_friend($list);
        foreach ($list as &$row) {
            $fid = $this->db->query("select fid from zy_user WHERE uid=?",[$row['uid']])->row_array();
            $trees = $this->energytrees_model->querylist($row['uid']);
            $row['trees'] = $trees;
            $row['fid'] = $fid['fid'];
            unset($row['uid']);
        }
        $data['list'] = $list;

        return $data;
    }

    /**
     * 最近访问列表
     * @return array
     */
    function lists_visit($uid)
    {
        $list = $this->db->query("SELECT a.id,a.friend_uid uid ,a.code,b.status is_forbidden
 FROM zy_friend_visit a,zy_friend b
 WHERE a.uid='{$uid}' AND a.friend_uid=b.friend_uid and a.uid=b.uid
 ORDER BY a.id DESC LIMIT 0,10")->result_array();
        $this->load->model('api/energytrees_model');
//        $list = $this->column_order_sql("id,friend_uid uid ,code",['uid'=>$uid],"zy_friend_visit","id","ASC",10,0,1);
        $list = $this->user_model->append_list_friend($list);

        foreach($list as &$row)
        {
            $fid = $this->db->query("select fid from zy_user WHERE uid=?",[$row['uid']])->row_array();
            $trees = $this->energytrees_model->querylist($row['uid']);
            $row['trees'] = $trees;
            $row['fid'] = $fid['fid'];

        }

        return $list;
    }

    /**
     * 生成添加好友URL
     * @return array
     */
    function mark_url($uid)
    {
        $code = t_rand_str();
        $url = '?code=' . $code . '&time=' . time() . '&add=1';

        // 写入好友表
        $this->table_insert('zy_friend_invite', [
            'uid' => $uid,
            'code' => $code,
            'add_time' => t_time(),
        ]);

        return ['url' => $url];
    }

    /**
     * 添加好友， 好友上线 100
     * @return array
     */
    function add_friend($uid, $code)
    {
        $invite = $this->table_row('zy_friend_invite', ['code' => $code]);
        if (!$invite) t_error(1, '无效的code');

        $row = $this->row(['uid' => $uid, 'friend_uid' => $invite['uid']]);
        if ($row) t_error(3, '你们已经是好友,操作无效');

        if ($uid == $invite['uid']) t_error(3, '不能添加自己为好友');

        $count = $this->count(['uid' => $uid]);
        if ($count >= 100) t_error(2, '超过好友上限100人了，请先删除几个');
        //判断是否已经添加过
        $is_friend = $this->count(['uid' => $uid,'friend_uid'=>$invite['uid']]);
        if(!$is_friend){
            $time = t_time();
            $new_code = t_rand_str();
            // 插入两条数据
            $this->db->trans_start();
            $this->insert([
                'uid' => $uid,
                'friend_uid' => $invite['uid'],
                'code' => $new_code,
                'add_time' => $time,
            ]);

            $this->insert([
                'uid' => $invite['uid'],
                'friend_uid' => $uid,
                'code' => $new_code,
                'add_time' => $time,
            ]);
        }
        //$this->table_delete('zy_friend_invite', ['code' => $code]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '操作失败，请稍后再来');

        return 1;
    }

    /**
     * 删除好友
     * @return array
     */
    function delete_friend($uid, $id)
    {
        $row = $this->row($id);
        if (!$row || $row['uid'] != $uid) t_error(1, '没有删除权限');

        $this->db->trans_start();
        $this->delete($id); // 删除自己的好友
        // 同时删除好友那边的记录
        $this->delete([
            'uid' => $row['friend_uid'],
            'friend_uid' => $uid,
        ]);
        //保存删除记录
        $this->table_insert('zy_delete_friend_record', [
            'uid' => $uid,
            'friend_uid' => $row['friend_uid'],
            'add_time' => t_time()
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) t_error(100, '操作失败，请稍后再来');

        return 1;
    }

    // 根据code 获取好友uid
    function get_uid($uid, $code)
    {
        $row = $this->row(['uid' => $uid, 'code' => $code]);

        if (!$row || !$row['friend_uid']) t_error(1, 'code信息错误');

        return $row['friend_uid'];
    }

    /**
     * 添加最近访问记录,
     * @return array
     */
    function add_visit($uid, $code)
    {
        $friend_uid = $this->get_uid($uid, $code);
        $row = $this->table_row('zy_friend_visit', ['uid'=>$friend_uid, 'friend_uid'=> $uid]);
        if($row) {
            $this->table_update('zy_friend_visit', [
                'code' => $code,
                'add_time' => t_time(),
            ], ['uid'=>$friend_uid, 'friend_uid'=> $uid]);
        } else {
            $insert_id = $this->table_insert('zy_friend_visit', [
                'uid' => $friend_uid,
                'friend_uid' => $uid,
                'code' => $code,
                'add_time' => t_time(),
            ]);
        }

        return 0;
    }

    //随机获取10个好友申请列表
    public function randFriendList($uid){
        $time = time() - 30*86400;  //筛选最近一个月登陆的玩家
        $list = $this->db->query("SELECT nickname,fid,head_img,game_lv,last_time FROM zy_user WHERE game_lv>0 AND UNIX_TIMESTAMP(last_time)>$time AND uid != '$uid'
AND uid NOT IN (SELECT friend_uid FROM zy_friend WHERE uid='$uid') AND uid NOT IN (SELECT friend_uid FROM zy_friend_apply WHERE uid='$uid')")->result_array();
        if(!empty($list) && count($list)>10){
            $rand_arr = array_rand($list,10);
            foreach($rand_arr as $val){
                $data['list'][] = $list[$val];
            }
        }else{
            $data['list'] = $list;
        }
        //查询是否有未查看过的好友申请记录
        $is_apply = $this->table_count('zy_friend_apply',['friend_uid'=>$uid,'is_check'=>0]);
        $data['is_apply'] = $is_apply ? 1 : 0;
        return $data;
    }

    //添加申请
    public function addApply($uid,$fid){
        $this->db->trans_start();
        $fid_arr = explode(',', $fid);
        if (count($fid_arr) > 10) t_error(1, '每次最多添加10个好友');
        foreach($fid_arr as $key=>$fid){
            //校验fid是否合法
            $friend_uid = $this->column_sql('uid',['fid'=>$fid],'zy_user',0);
            if(!empty($friend_uid)){
                //申请信息添加到zy_friend_apply表
                $row = $this->column_sql('id,status',['uid'=>$uid,'friend_uid'=>$friend_uid['uid']],'zy_friend_apply',0);
                if($row){
                    $update['is_check'] = 0;
                    $update['add_time'] = t_time();
                    $this->table_update('zy_friend_apply',$update,['id'=>$row['id']]);
                }else{
                    $insert['uid'] = $uid;
                    $insert['friend_uid'] = $friend_uid['uid'];
                    $insert['status'] = 0;
                    $insert['is_check'] = 0;
                    $insert['add_time'] = t_time();
                    $this->table_insert('zy_friend_apply',$insert);
                }
            }
        }
        $this->db->trans_complete();
    }

    //申请添加为好友列表
    public function applyList($uid){
        $res = $this->db->query("SELECT b.nickname,b.head_img,b.fid,b.game_lv,b.last_time FROM zy_friend_apply a, zy_user b WHERE friend_uid='$uid' AND b.uid=a.uid AND a.status=0")->result_array();
        //查看过申请列表之后，更新zy_friend_apply的is_check字段为1
        $update['is_check'] = 0;
        $update['add_time'] = t_time();
        if(!empty($res)){
            $update['is_check'] = 1;
            $update['update_time'] = t_time();
            $this->table_update('zy_friend_apply',$update,['friend_uid'=>$uid,'is_check'=>0]);
        }
        return $res;
    }

    //好友同意申请
    public function agreeApply($uid,$fid){
        $this->db->trans_start();
        $fid_arr = explode(',', $fid);
        if (count($fid_arr) > 10) t_error(1, '每次最多添加10个好友');
        foreach($fid_arr as $key=>$fid){
            $apply_uid = $this->column_sql('uid',['fid'=>$fid],'zy_user',0);
            $row = $this->row(['uid' => $uid, 'friend_uid' => $apply_uid['uid']]);
            if ($row) t_error(3, '你们已经是好友,操作无效');
            if ($uid == $apply_uid['uid']) t_error(3, '自己不能添加自己为好友');
            if(!empty($apply_uid)){
                //查询是否有此条申请记录
                $count = $this->table_count('zy_friend_apply',['uid'=>$apply_uid['uid'],'friend_uid'=>$uid,'status'=>0]);
                if($count){
                    $update['status'] = 1;
                    $update['update_time'] = t_time();
                    $this->table_update('zy_friend_apply',$update,['uid'=>$apply_uid['uid'],'friend_uid'=>$uid,'status'=>0]);
                    //同意之后，添加到zy_friend表
                    $code = t_rand_str();
                    $this->insert([
                        'uid' => $apply_uid['uid'],
                        'friend_uid' => $uid,
                        'code' => $code,
                        'add_time' => t_time(),
                    ]);
                    $this->insert([
                        'uid' => $uid,
                        'friend_uid' => $apply_uid['uid'],
                        'code' => $code,
                        'add_time' => t_time(),
                    ]);
                }
            }
        }
        $this->db->trans_complete();
    }

    //好友拒绝
    public function refuseApply($uid,$fid){
        $this->db->trans_start();
        $fid_arr = explode(',', $fid);
        if (count($fid_arr) > 10) t_error(1, '每次最多拒绝10个好友');
        foreach($fid_arr as $key=>$fid){
            $apply_uid = $this->column_sql('uid',['fid'=>$fid],'zy_user',0);
            if(!empty($apply_uid)){
                //查询是否有此条申请记录
                $row = $this->table_row('zy_friend_apply',['uid'=>$apply_uid['uid'],'friend_uid'=>$uid,'status'=>0]);
                if($row){
                    $this->table_delete('zy_friend_apply',$row['id']);
                }
            }
        }
        $this->db->trans_complete();
    }



    //=============访问好友场景用到的接口===========

    // 好友土地列表
    public function land($uid, $code)
    {
        // 根据code 获取好友uid
        $friend_uid = $this->get_uid($uid, $code);

        $land_list = model('land_model')->lists_status($friend_uid);

        return $land_list;
    }

    // 好友土地列表
    public function market($uid, $code)
    {
        // 根据code 获取好友uid
        $friend_uid = $this->get_uid($uid, $code);

        $land_list = model('market_model')->list_my($friend_uid);

        return $land_list;
    }

    //
    public function user($uid, $code)
    {
        // 根据code 获取好友uid
        $friend_uid = $this->get_uid($uid, $code);

        $user = $this->user_model->row_sql("SELECT nickname,head_img as thumb,game_lv,game_xp,game_xp_all,header_frame
                            FROM zy_user
                            WHERE uid='{$friend_uid}'
                            LIMIT 1");

        $user['thumb'] = $user['thumb'];

        return $user;
    }

    function is_my_friend($uid, $code){
        $row['is_friend'] = $this->count(['uid'=>$uid,'code'=>$code]);
        // 根据code 获取好友uid
        $query = $this->db->query("select a.nickname from zy_user a, zy_friend_invite b WHERE code='$code' AND a.uid=b.uid")->row_array();
        $row['nickname'] =  $query['nickname'];

        return $row;
    }


}
