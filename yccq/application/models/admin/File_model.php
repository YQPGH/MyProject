<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 文件
 */
include_once 'base_model.php';

class file_model extends base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'fly_file';
    }


    // 图片上传和压缩处理
    function upload_image() {
        include_once APPPATH.'./libraries/UploadFile.class.php';
        $upload = new UploadFile();
        $data = $upload->upload('imgFile', 5, 'image');

        // 正确
        if ($data ['error'] == 0) {
            thumb2($data ['url']); // 生成多张图片，缩略图
            // 写入数据库
            $insert_data = array(
                'addtime' => time(),
                'catid' => 1,
                'url' => $data ['url']
            );
            $this->insert($insert_data);
        }

        return $data;
    }

    // 图片上传和压缩处理
    function upload_audio() {
        include_once APPPATH.'./libraries/UploadFile.class.php';
        $upload = new UploadFile();
        $data = $upload->upload('audioFile', 5, 'media');

        // 正确
        if ($data ['error'] == 0) {

            // 写入数据库
            $insert_data = array(
                'addtime' => time(),
                'catid' => 2,
                'url' => $data ['url']
            );
            $this->insert($insert_data);
        }

        return $data;
    }

    // 图片上传和压缩处理
    function upload_video() {
        include_once APPPATH.'./libraries/UploadFile.class.php';
        $upload = new UploadFile();
        $data = $upload->upload('videoFile', 500, 'media');

        // 正确
        if ($data ['error'] == 0) {

            // 写入数据库
            $insert_data = array(
                'addtime' => time(),
                'catid' => 2,
                'url' => $data ['url']
            );
            $this->insert($insert_data);
        }

        return $data;
    }


}
