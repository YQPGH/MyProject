<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 会员 模型
 */
include_once 'Base_model.php';

class User_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_user';
    }

    /**
     *  用户详情
     *
     * @return int
     */
    function detail($uid)
    {
        $user = $this->row(['uid' => $uid]);
        if (!$user) t_error(1, '获取用户信息失败，请稍后再试');
        $user['thumb'] = base_url($user['thumb']);

        return $user;
    }

    function list_all($where, $limit, $offset)
    {
        $sql = "SELECT * FROM zy_user
                    WHERE $where
                    ORDER BY id DESC 
                    LIMIT $offset,$limit";
        $list = $this->lists_sql($sql);

        return $list;
    }

    // 更新登录次数和时间
    function update_logins($uid)
    {
        if (empty($uid)) return 0;

        $this->db->set('logins', 'logins+1', FALSE);
        $this->db->set('lastlogtime', time());
        $this->db->where('id', $uid);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    // 会员 登录验证
    public function app_check_login()
    {
        $email = trim($this->input->post('email'));
        $password = trim($this->input->post('password'));

        if (empty ($email) || empty ($password)) {
            show_json(1, '邮箱和密码不能为空');
        }

        $user = $this->model->detail(array(
            'email' => $email
        ));

        if (empty ($user)) {
            show_json('2', '您输入邮箱不存在');
        }

        if ($user ['password'] != $password) {
            show_json('3', '密码错误');
        }
        if ($user ['status'] == 0) {
            show_json('4', '账号已被锁定，请联系管理员');
        }

        // 登录成功，返回会员信息
        $this->update_logins($user['id']);
        $value = $this->app_detail($user['id']);
        show_json(0, 'ok', $value);
    }

    // 获取一条会员全部信息
    function app_detail($uid)
    {
        $value = $this->row($uid);
        unset ($value ['password']);
        unset ($value ['status']);
        if ($value ['thumb']) {
            $value ['thumb'] = base_url() . new_filename($value ['thumb'], '_small');
        }

        return $value;
    }

    // 获取一条会员全部信息
    function register($data)
    {
        $data['password'] = get_password($data['password']);
        unset($data['password2']);
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['login_time'] = time();

        $id = $this->insert($data);
        if (empty($id))
            return '注册失败，请稍后再试';
        else
            return $id;
    }

    /**
     * 为列表附加上信息
     *
     * @return array 一维数组
     */
    function append_list($list)
    {
        if (empty($list)) return $list;

        $uid_arr = [];
        foreach ($list as $row) {
            $uid_arr[] = $row['uid'];
        }
        $query = $this->db->select('uid,nickname,head_img,game_lv')
            ->where_in('uid', $uid_arr)
            ->limit(count($uid_arr))
            ->get($this->table);
        $user_list = $query->result_array();
        $user_list2 = [];
        foreach ($user_list as $user) {
            $user['thumb'] = $user['head_img'];
            $user_list2[$user['uid']] = $user;
        }

        foreach ($list as &$value) {
            $value['nickname'] = $user_list2[$value['uid']]['nickname'];
            $value['user_thumb'] = $user_list2[$value['uid']]['thumb'];
            $value['game_lv'] = $user_list2[$value['uid']]['game_lv'];
            // $value['thumb'] = base_url($shop_list[$value['shopid']]['thumb']);
        }

        return $list;
    }


}
