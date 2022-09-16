<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 意见
include_once 'Base.php';

class Suggestion extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('api/user_model');
    }

    //建议
    public function suggestion(){
//        $data['uid'] = 'abcc';
        $data['uid'] = $_SESSION["uid"];
        $this->load->view("client/suggestion",$data);
    }

    //保存建议
    public function saveSuggestion(){

        $uid = $this->input->post('uid');
        $content = trim($this->input->post('content'));
        $img = $this->input->post('thumb');
        $this->load->model('filteredtext/filterwords_model');
        if(!empty($uid && $content)) {
            $insert['uid'] = $uid;
            $insert['img'] = $img;
            $insert['add_time'] = t_time();
            $insert['content'] = $this->filterwords_model->getMain($content);
            $insert['content'] = filter_keyword($insert['content']);
            $insert_id = $this->user_model->table_insert('zy_suggestion', $insert);
        }
//        if($insert_id){

            $this->load->view("client/msg");
//        }else{
//            t_error();
//        }

    }
    //显示玩家留言建议
    public function suggestionList(){
//        $data['uid'] = 'abcc';
        $data['uid'] = $_SESSION["uid"];
        $list = [];
        //查询玩家建议
        $res = $this->db->query("select * from zy_suggestion where uid='$data[uid]'")->result_array();

        if(!empty($res)){
            foreach($res as $key=> $value) {
                if (!empty($value['content'])) {
                    $list[$value['id']] = $value;
                    $list[$value['id']]['reply_content'] = $this->db->query("select r_content from zy_reply  where rid=$value[id]")->result_array();

                }
            }
        }
        $data['list'] = $list;

        $this->load->view("client/suggestionList", $data);
    }
}