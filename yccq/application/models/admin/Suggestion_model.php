<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * 意见
 */
include_once 'Base_model.php';

class Suggestion_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_suggestion';
    }



}
