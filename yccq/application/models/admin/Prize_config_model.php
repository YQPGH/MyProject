<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  奖品配置
 */
include_once 'Base_model.php';

class Prize_config_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_prize_config';
    }

    /**
     * 获取一条记录, 同时更新查看次数
     *
     * @return array 一维数组
     */
    function row( $where=array() )
    {
        if(isset($where['id'])) {
            $this->update_visit($where['id']);
        }
        return parent::row($where);
    }




}
