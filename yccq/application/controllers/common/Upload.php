<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * 上传文件
 */
class Upload extends CI_Controller
{
    function __construct()
    {
        parent::__construct();        
    }

    // 详细页
    public function image()
    {
        set_time_limit(1000);

        $config['upload_path'] = 'uploads/image/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
        $config['max_size'] = 3 * 1024; //3M
        $config['encrypt_name'] = true;
        $config['file_ext_tolower'] = true;
        if (!file_exists($config['upload_path'])) { // 创建文件夹
            mkdir($config['upload_path']);
        }
        $config['upload_path'] .= date("Ymd") . "/";
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            echo t_error(1, $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $result = array(
                'src'=>$config['upload_path'].$data['file_name'],
                'title'=> $data['client_name']
            );
            // 返回绝对地址
            if($_GET['urlType'] == 'absolute') $result['src'] = base_url($result['src']);
            echo t_json($result);
        }
    }

    // 详细页
    public function video()
    {
        set_time_limit(1000);
        
        $config['upload_path'] = 'uploads/video/';
        $config['allowed_types'] = 'mp4|flv';
        $config['max_size'] = 200 * 1024; //200M
        $config['encrypt_name'] = true;
        $config['file_ext_tolower'] = true;
        if (!file_exists($config['upload_path'])) { // 创建文件夹
            mkdir($config['upload_path']);
        }
        $config['upload_path'] .= date("Ymd") . "/";
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('video')) {
            echo t_error(1, $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $result = array(
                'src'=>$config['upload_path'].$data['file_name'],
                'title'=> $data['client_name']
            );
            // 返回绝对地址
            if($_GET['urlType'] == 'absolute') $result['src'] = base_url($result['src']);
            echo t_json($result);
        }
    }

    // 详细页
    public function file()
    {
        set_time_limit(1000);

        $config['upload_path'] = 'uploads/file/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 200 * 1024; //200M
        $config['encrypt_name'] = true;
        $config['file_ext_tolower'] = true;
        if (!file_exists($config['upload_path'])) { // 创建文件夹
            mkdir($config['upload_path']);
        }
        $config['upload_path'] .= date("Ymd") . "/";
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            echo t_error(1, $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $result = array(
                'src'=>$config['upload_path'].$data['file_name'],
                'title'=> $data['client_name']
            );
            // 返回绝对地址
            if($_GET['urlType'] == 'absolute') $result['src'] = base_url($result['src']);
            echo t_json($result);
        }
    }


}
