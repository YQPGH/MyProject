<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 排行 模型
 */
include_once 'Base_model.php';

class Rank_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_ranking_prize_record';
    }



}
