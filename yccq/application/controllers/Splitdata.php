<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Splitdata extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }



    function dataSheet()
    {

        $this->db->trans_start();

        $count = $this->db->query("select COUNT(*) num from chat_detail_1 ")->row_array();
        $id = $this->db->query("select  id from  chat_detail_1 ORDER by id desc")->row_array();

        $id = $id?$id['id']:0;
        $time = time()-60*60*24*7;

        $max = 5000000;
        $table1 = $count['num']<$max?'chat_detail_1':'chat_detail_2';

        $table2 = 'chat_detail';
        $this->db->query("INSERT  IGNORE INTO $table1($table1.id,$table1.uid,$table1.friend_uid, $table1.content, $table1.addtime,
                          $table1.updatetime,$table1.is_read, $table1.ip, $table1.user_agent)
                          SELECT $table2.id,$table2.uid,$table2.friend_uid, $table2.content, $table2.addtime,
                           $table2.updatetime,$table2.is_read, $table2.ip, $table2.user_agent  FROM $table2
                          where  $table2.is_read=? and UNIX_TIMESTAMP($table2.addtime)<=? AND  $table2.id>? limit 5000",[1,$time,$id]);
        if($id)
        {
            $this->db->query("delete from $table2  WHERE id<? AND is_read=? AND UNIX_TIMESTAMP($table2.addtime)<=? ",[$id,1,$time]);

        }
        $this->db->trans_complete();
    }
	
	




}
