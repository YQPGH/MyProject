<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  资讯
 */
include_once 'Base_model.php';

class News_model extends Base_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'zy_news';
        $this->load->model('api/news_model');
    }


}
