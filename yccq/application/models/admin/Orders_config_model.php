<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  订单配置
 */
include_once 'Base_model.php';

class Orders_config_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_orders_config';
    }

    /**
     * 获取一条记录, 同时更新查看次数
     *
     * @return array 一维数组
     */
    function row($where = array())
    {
        if (isset($where['id'])) {
            $this->update_visit($where['id']);
        }
        return parent::row($where);
    }

}
