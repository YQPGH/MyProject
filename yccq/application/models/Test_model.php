<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 模型 基类，其他模型需要先继承本类
 */
class Test_model extends CI_Model
{
    public $table = ''; // 数据库表名称

    function __construct()
    {
        parent::__construct();
    }

    // 初步测试API接口状态，返回JSON格式则正确
    function api()
    {
        $this->load->helper('directory');
        $files = directory_map('./application/controllers/api/');
        foreach ($files as $file) {
            include_once APPPATH . 'controllers/api/' . $file;
            $class_name = str_replace('.php', '', $file);
            $this->api_status($class_name);
        }
    }

    function api_status($class_name)
    {
        $methods = get_class_methods($class_name);
        foreach ($methods as $method) {
            if ($method == 'get_instance' || $method == '__construct') continue;
            $url = site_url("api/{$class_name}/{$method}");
            $response = t_curl($url, [], ['uid' => 'abc']);
            $result = json_decode($response['body'], true);
            if ($result && isset($result['code']))
                //echo "{$url}：正常 \n {$response['body']} \n\n";
                echo "{$url}：正常 \n";
            else
                //echo "{$url}：出问题了 \n {$response['body']} \n\n";
                echo "{$url}：出问题了 \n";

        }
    }

}
