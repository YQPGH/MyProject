<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  仓库表
 */
include_once 'Base_model.php';

class St_message_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_st_message';
        $this->load->model('api/user_model');
    }

    function add($uid,$shopid){
        $insert['uid'] = $uid;
        $insert['shopid'] = $shopid;
        $insert['add_time'] = t_time();
        $this->insert($insert);
    }

    function lists_st($uid,$page=0){
        //获取所有实体物品
        $per_page = 10;
        $offset = $page*$per_page;
        $result['list'] = $this->db->query("select a.id,a.shopid,a.status,b.end_time from zy_st_message a,zy_prize b WHERE a.uid=? AND b.shop1=a.shopid ORDER BY id DESC limit ?,?",[$uid,$offset,$per_page])->result_array();
        $query = $this->db->query("select count(*) as num from zy_st_message WHERE uid='$uid'")->row_array();
        $result['page']['curr_page'] = $page+1;
        $result['page']['total_page'] = ceil($query['num']/$per_page)==0 ? 1 : ceil($query['num']/$per_page);
        return $result;
    }



}
