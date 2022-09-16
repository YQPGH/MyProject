<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * admin 访问日志 后台日志
 */
include_once 'Base_model.php';

class Log_admin_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'log_admin';
    }

    // 保存访问记录
    public function logs($log_type,$log_result)
    {
        $data = array(
            'uid' => intval($_SESSION['admin']['id']),
            'type' => $log_type,
            'result' => $log_result,
            'url' => uri_string(),
            'user_agent' => $this->agent->platform() . '/' . $this->agent->browser() . $this->agent->version(),
            'add_time' => t_time(),
            'ip' => ip()
        );
        return $this->insert($data);
    }


}
