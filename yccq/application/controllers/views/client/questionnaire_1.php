<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
        <meta name="applicable-device" content="pc,mobile">
        <meta http-equiv="Cache-Control" content="no-transform ">
        <script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
		<script src="<?= base_url()?>static/questionnaire/js/swiper.min.js"></script>
        <title>烟草传奇公测</title>
        <link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/style.css">
		<link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/swiper.min.css">
    </head>
    <body>
        <div class="bg" style="width:100%">
            <img src="<?= base_url()?>static/questionnaire/images/bg_new_1.png" width:100%>
            <!--<div id="time"><span>时间</span></div>-->
			<div style="position: absolute;height: 20%;left:0px;top:0px;width: 100%">
                <!-- s-->
                <div class="swiper-container banner" style="width: 100%;height: 100%;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_1.png?01" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_2.png?01" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_3.png?01" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:;">
                                <img height="100%" width="100%" src="<?= base_url()?>static/questionnaire/images/banner_4.png?01" alt="">
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
				
                <div id="content"><span ></span></div>
                <div id="active">
                    <div id="icon"></div>
                    <div id="introduce">
                        <span style="height:7vw; color:#a38a23; font-weight:bold; line-height: 7vw;"></span>
                        <span style="height:25vw;">
                        </span>
                        <span style="height:30vw;">
							

                        </span>
                    </div>
                </div>
                <form action="<?= base_url()?>api/Main/saveQuestionNaire1" method="post" onsubmit="return check();">
                    <input type="hidden" name="uid" value="<?=$uid?>">
                    <div id="question">
                        <ul id="all">
                            <?php foreach($list as $key=>$value) :?>
                                <li>
                                    <p><?= $value['title']?></p>
                                    <div id="option">
                                        <?php foreach($value['option'] as $k=>$v) :?>
                                            <h5>
                                                <input class="timu_<?=$key?>" id="checkbox_<?=$key.$v['oid']?>" type="<?= $value['type1']==1?'radio':'checkbox' ?>" value="<?=$v['oid']?>"
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
                        <input id="fill_phone" type="tel" name="phone" value="" />
                        <input id="sub" type="submit" value="" >
                    </div>
                </form>
            </div>
        </div>

<script type="application/javascript">

    var ua = navigator.userAgent.toLowerCase();
    var isWeixin = ua.indexOf('micromessenger') != -1;
    var isMobile = ua.indexOf('mobile') != -1;
    if (!isWeixin || !isMobile) {
        document.head.innerHTML = '<title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/0.4.1/weui.css">';
        document.body.innerHTML = '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div><div class="weui_text_area"><h4 class="weui_msg_title">请在微信手机客户端打开链接</h4></div></div>';
    }
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

    function check()
    {
        
        var timu1 = $('.timu_1');
        var timu2 = $('.timu_2');
        var timu3 = $('.timu_3');
        var timu4 = $('.timu_4');
        var timu5 = $('.timu_5');
        var timu6 = $('.timu_6');
        var timu7 = $('.timu_7');
        var timu8 = $('.timu_8');
        var timu9 = $('.timu_9');
        var timu10 = $('.timu_10');
        var timu11 = $('.timu_11');
        if(hasSelected(timu1) && hasSelected(timu2) && hasSelected(timu3) && hasSelected(timu4) && hasSelected(timu5) && hasSelected(timu6) && hasSelected(timu7) && hasSelected(timu8) && hasSelected(timu9) && hasSelected(timu10) && hasSelected(timu11)){

            var tel = $('#fill_phone').val();
            if(checkMobile(tel)){
                return true;
            }else{
                alert('请填写正确的手机号码');
            }
            
        }else{
            alert('请答完所有问题哦!');
        }

        return false;
    }

    function hasSelected(obj)
    {
        var flag = false;
        if(obj){
            obj.each(function(){
                if($(this).attr('checked') == 'checked'){
                    flag = true;
                }
            });
        }
        return flag;
    }

    function checkMobile(tel){ 
        var sMobile = tel;
        if(!(/^1[3|4|5|6|7|8|9][0-9]\d{4,8}$/.test(sMobile))){ 
            return false; 
        }
        return true;
    } 

</script>
<?php 
     function getSignPackage() {
        $key = 'ka2J9PC326T44D439H6tvcPBY';
        $sign = strtoupper(md5($key));
        //测试环境
        //$get_jsSDK_url = 'http://zl.haiyunzy.com/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;
        //生产环境
        $get_jsSDK_url = 'http://wx.thewm.cn/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;       
        $signPackage = json_decode( curlGetData($get_jsSDK_url), true);

        $appid = $signPackage['appid'];
        $jsapiTicket = $signPackage['JsapiTicket']; //$signPackage['ticket'] ;

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    
    function curlGetData($url) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1' );
        $data = curl_exec ( $ch );
        $status = curl_getinfo ( $ch );
        $errno = curl_errno ( $ch );
        curl_close ( $ch );
        return $data;
    }   
    $share_data['signPackage'] = getSignPackage();  
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> 
<script>
var add_frined_code = '<?=isset($_GET['code']) ? $_GET['code'] : '';?>';   
var url_code = '';

 wx.config({
            //debug: true,
            appId: '<?php echo $share_data['signPackage']["appId"];?>',
            timestamp: <?php echo $share_data['signPackage']["timestamp"];?>,
            nonceStr: '<?php echo $share_data['signPackage']["nonceStr"];?>',
            signature: '<?php echo $share_data['signPackage']["signature"];?>',
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'onMenuShareQZone',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem'

            ]
        });


    wx.ready(function () {
        wx.hideMenuItems({

            menuList: ['menuItem:copyUrl']

        });
        share();
        /*wx.checkJsApi({

         jsApiList: ['scanQRCode'], // 需要检测的JS接口列表，所有JS接口列表见附录2,

         success: function(res) {
         if(res.checkResult.scanQRCode == 'no'){
         window.location.href = 'http://www.baidi.com';//跳转到指定页面
         }

         // 以键值对的形式返回，可用的api值true，不可用为false

         // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}

         }

         });*/

    });


    function share(title, content, imgUrl) {
        var share_t = '真龙《烟草传奇》游戏公测邀请';
        var share_c = '年度最好玩的游戏，赶快来报名公测！';
        var share_url = 'http://yccq.zlongwang.com/yccq/api/Main/' + url_code;
        var share_img = 'http://yccq.zlongwang.com/yccq/game/loading/share_icon.png';
        if (title != '' && typeof(title) != "undefined") share_t = title;
        if (content != '' && typeof(content) != "undefined") share_c = content;
        if (imgUrl != '' && typeof(imgUrl) != "undefined") share_img = imgUrl;

        share_app(share_t, share_c, share_url, share_img);
        share_timeline(share_c, share_url, share_img);
    }

    function share_app(title, content, link, imgUrl) {

        // 在这里调用 API
        wx.onMenuShareAppMessage({
            title: title, // 分享标题
            desc: content, // 分享描述
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function (res) {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            },
            trigger: function (res) {
            }, complete: function () {
                share();
				closeShare();
            }
        });
    }

    function share_timeline(content, link, imgUrl) {
        wx.onMenuShareTimeline({
            title: content, // 分享标题
            link: link,
            imgUrl: imgUrl, // 分享图标
            success: function () {
            },
            cancel: function () {
            },
            trigger: function (res) {
            }, complete: function () {
                share();
				closeShare();
            }
        });
    }

</script>

    </body>
</html>