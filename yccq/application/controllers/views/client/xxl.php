<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta id="viewport" name="viewport"
          content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>种子消消乐</title>
    <script type="text/javascript">
        <?php if(isset($_SESSION["uid"])){?>
        uid = "<?=$_SESSION['uid'];?>";
        <?php }else{?>
        //location.href="http://192.168.1.217/yccq/api/Main/index";
        location.href = "<?=site_url('api/main/index')?>";
        <?php }?>
    </script>
</head>

<body style="margin: 0; padding: 0;">
<div id="topContainer" style="position: absolute; left: 0px; top: 0px;width:100%; height:100%; z-index: 100;
      ">
    <img src="<?= base_url('xxl/res/bg_html.jpg');?>" style="width: 100%; height: 100%;">

    <div class="load"
         style="position: absolute; width:200px;left:50%;top: 40%; text-align: center; margin-left:-100px;
transform:rotate(90deg);
           ">
        游戏加载中...
    </div>
</div>

<script type="text/javascript" src="<?= base_url('game/libs/min/laya.core.min.js');?>"></script>
<script type="text/javascript" src="<?= base_url('game/libs/min/laya.webgl.min.js');?>"></script>
<script type="text/javascript" src="<?= base_url('game/libs/min/laya.ui.min.js');?>"></script>
<script type="text/javascript" src="<?= base_url('game/libs/min/laya.filter.min.js');?>"></script>

<script type="text/javascript" src="<?= base_url('xxl/js/Config.js?22');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/libs/Utils.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/libs/Server.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/libs/Xiao.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/Common.js?20');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/ComDialog.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/GuizeDialog.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/OverDialog.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/SuccessDialog.js?28');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/LoadUI.js?0');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/GameView.js?20');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/GameUI.js?04');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/ui/MainUI.js');?>"></script>
<script type="text/javascript" src="<?= base_url('xxl/js/Index.js');?>"></script>

<script type="text/javascript" src="<?= base_url('xxl/js/ui/ConfirmDialog.js');?>"></script>
<!--<script type="text/javascript" src="js/all.min.js"></script>-->
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

 
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>

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
        var share_t = '烟草传奇';
        var share_c = '烟草传奇';
        var share_url = 'http://yccq.zlongwang.com/yccq/client/';
        var share_img = 'http://yccq.zlongwang.com/yccq/game/loading/loading.png';
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
            }
        });
    }

</script>
</body>
</html>

