<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
        <meta name="applicable-device" content="pc,mobile">
        <meta http-equiv="Cache-Control" content="no-transform ">
        <script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
		<script src="<?= base_url()?>static/questionnaire/js/swiper.min.js"></script>
        <title>烟草传奇公测网页</title>
        <link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/style.css">
		<link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/swiper.min.css">
    </head>
    <body>
    <div class="bg">
        <img src="<?= base_url()?>static/questionnaire/images/bg_new_1.png"  >
        <div style="position: absolute;height: 20%;left:0px;top:0px;width: 100%">
                <!-- s-->
                <div class="swiper-container banner" style="width: 100%;height: 100%;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_1.png" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_2.png" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_3.png" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_4.png" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination">

                    </div>
                    <!-- Add Arrows -->
                    <!--<div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>-->
                </div>
                <!-- end-->
            </div>
        <div id="container">
            <div id="content"><span></span></div>
            <div id="active">
                <div id="introduce">
                    <span style="height:6vw; color:#a38a23; font-weight:bold; line-height: 6vw;">
<!--                        2018年5月21日至5月27日-->
                    </span>
                    <span style="height:25vw;">
<!--						填写问卷及手机号码后，点击“提交”即可完成报名，获得参与的用户，将收到“真龙”微信发送的参与通知，用户可点击通知进入游戏参与测试。-->
					</span>
                    <span style="height:30vw; ">
<!--						参与公测的粉丝，游戏等级每上升一级，都将获得游戏道具赠送，等级达到15级时粉丝将获得（起源）品吸机会一次，等级达到20级时粉丝将获得300乐豆奖励，等级达到25级时粉丝将获得500乐豆奖励。还有机会获得品吸代金券，兑奖更多品吸机会。-->
					</span>
                </div>
            </div>
            <form action="<?= base_url()?>api/Main/saveQuestionNaire2" method="post">
                <input type="hidden" name="uid" value="<?=$uid?>">
                <div id="question">
                    <ul id="all">
                        <?php foreach($list as $key=>$value) :?>
                        <li>
                            <p><?= $value['title']?></p>
                            <div id="option">
                            <?php foreach($value['option'] as $k=>$v) :?>
                                <h5>
                                    <input id="checkbox_<?=$key.$v['oid']?>" type="<?= $value['type1']==1?'radio':'checkbox' ?>" value="<?=$v['oid']?>"
                                    name="<?= $value['type1']==1?"value[$key]":"value[$key][$v[oid]]"?>">
                                    <label for="checkbox_<?=$key.$v['oid']?>"></label>
                                    <span><?=$v['option_name']?></span>
                                </h5>
                            <?php endforeach ?>
                            </div>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div id="bottom">
                    <input id="fill_phone" type="text" name="phone" value="" />
                    <input id="sub" type="submit" value="" >
                </div>
            </form>
        </div>
    </div>

    <script type="application/javascript">
        $('#fill_phone').focus(function(){
            $('body').height($('body')[0].clientHeight);
            var h1 = $('#all li').eq(0).height();
            var h2 = $('#all p').eq(0).height();
            var h3 = $('#option').height();
            $('#all li').css('height',h1);
            $('#all p').css('height', h2);
            $('#option').css('height',h3);
 
			$('#fill_phone').css('padding',"0 0 0 1.8vw");        
		});
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			//nextButton: '.swiper-button-next',
			//prevButton: '.swiper-button-prev',
			slidesPerView: 1,
			paginationClickable: true,
			spaceBetween: 0,
			loop: true,
			autoplay: 5000,
			speed:500,
			//effect:'fade'
		});
    </script>

    </body>
</html>