<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * 种植集能量 模型
 */
include_once 'Base_model.php';

class Energytrees_model extends Base_model
{

    function __construct()
    {
        parent::__construct();

        $this->table = 'zy_trees_player';
    }



}