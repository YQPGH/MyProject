<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 *  虫子模型
 */
include_once 'Base_model.php';

class Chongzi_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'chongzi_send';
    }

}
