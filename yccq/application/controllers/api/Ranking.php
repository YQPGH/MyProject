<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 我的物品
include_once 'Base.php';

class Ranking extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ranking_model');
    }

    //一周乐豆消耗排行榜
    public function consumeLDRanking(){
        $reslut = $this->ranking_model->consumeLDRanking($this->uid);

        t_json($reslut);
    }

    //一周银元消耗排行榜
    public function consumeMoneyRanking(){
        $reslut = $this->ranking_model->consumeMoneyRanking($this->uid);

        t_json($reslut);
    }

    //上周个人乐豆排名
    public function preMyConsumeLDRanking(){
        $reslut = $this->ranking_model->preMyConsumeLDRanking($this->uid);

        t_json($reslut);
    }

    //上周个人银元排名
    public function preMyConsumeMoneyRanking(){
        $reslut = $this->ranking_model->preMyConsumeMoneyRanking($this->uid);

        t_json($reslut);
    }

    //获取上周氪金排行奖励
    public function getRankingLDPrize(){
        $reslut = $this->ranking_model->getRankingLDPrize($this->uid);
        t_json($reslut);
    }

    //获取上周壕一个字排行奖励
    public function getRankingMoneyPrize(){
        $reslut = $this->ranking_model->getRankingMoneyPrize($this->uid);
        t_json($reslut);
    }

    //一周种植积分排行榜
    public function zZJFRanking(){
        $reslut = $this->ranking_model->zZJFRanking($this->uid);

        t_json($reslut);
    }

    //一周种植积分排行榜
    public function zZJFRanking_old(){
        $reslut = $this->ranking_model->zZJFRanking_old($this->uid);

        t_json($reslut);
    }

    //领取上周种植排行奖励
    public function getRankingZZPrize(){
        $reslut = $this->ranking_model->getRankingZZPrize($this->uid);
        t_json($reslut);
    }

    //一周制烟积分排行榜
    public function zYJFRanking(){
        $reslut = $this->ranking_model->zYJFRanking($this->uid);

        t_json($reslut);
    }

    //领取上周制烟排行奖励
    public function getRankingZYPrize(){
        $reslut = $this->ranking_model->getRankingZYPrize($this->uid);
        t_json($reslut);
    }


    //查询本周所有排名的奖励
    public function queryRankingPrize(){
        $type = $this->input->post('type');
        $reslut['prize_list'] = $this->ranking_model->queryRankingPrize($type);

        t_json($reslut);
    }

    //获取本周显示种植榜还是制烟榜
    public function show_type_ranking(){
        $reslut = $this->ranking_model->show_type_ranking();
        t_json($reslut);
    }

    public function test(){
        $reslut = $this->ranking_model->queryRankingJFPrize();
        t_json($reslut);
    }

}