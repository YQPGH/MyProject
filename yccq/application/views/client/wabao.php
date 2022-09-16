<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<title>欢乐挖宝</title>
	<meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'
	/>
	<meta name="renderer" content="webkit">
	<meta name='apple-mobile-web-app-capable' content='yes' />
	<meta name='full-screen' content='true' />
	<meta name='x5-fullscreen' content='true' />
	<meta name='360-fullscreen' content='true' />
	<meta name="laya" screenorientation ="landscape"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta http-equiv='expires' content='0' />
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
</head>
<style>
	body{ background:url("<?=base_url('wabao/load/loading.png')?>") no-repeat center center;
		background-attachment:fixed;
		background-size:cover;
		-moz-background-size:cover;
		-webkit-background-size:cover;

	}
</style>
<script type="text/javascript">
		<?php if(isset($_SESSION["uid"])){?>
		localStorage.GUID="<?=$_SESSION['uid'];?>";
	<?php }else{?>
		//location.href="http://192.168.1.217/yccq/api/Main/index";
		location.href = "<?=site_url('api/main/index')?>";
	<?php }?>
	
</script>
<body>
	<!--核心包，封装了显示对象渲染，事件，时间管理，时间轴动画，缓动，消息交互,socket，本地存储，鼠标触摸，声音，加载，颜色滤镜，位图字体等-->
	<script type="text/javascript" src="<?= base_url('game/libs/laya.core.js'); ?>"></script>
	<!--封装了webgl渲染管线，如果使用webgl渲染，可以在初始化时调用Laya.init(1000,800,laya.webgl.WebGL);-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.webgl.js');?>"></script>
	<!--是动画模块，包含了swf动画，骨骼动画等-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.ani.js');?>"></script>
	<!--包含更多webgl滤镜，比如外发光，阴影，模糊以及更多-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.filter.js');?>"></script>
	<!--封装了html动态排版功能-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.html.js');?>"></script>
	<!--粒子类库-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.particle.js');?>"></script>
	<!--提供了制作UI的各种组件实现-->
    <script type="text/javascript" src="<?= base_url('game/libs/laya.ui.js');?>"></script>
	<!--自定义的js(src文件夹下)文件自动添加到下面jsfile模块标签里面里，js的顺序可以手动修改，修改后保留修改的顺序，新增加的js会默认依次追加到标签里-->
	<!--删除标签，ide不会自动添加js文件，请谨慎操作-->
	
	<script src="<?= base_url('wabao/src/ui/layaUI.max.all.js');?>"></script>
	<!--jsfile--startTag-->

	<script src="<?= base_url('wabao/src/NumPass.js?19');?>"></script>
	
	<script src="<?= base_url('wabao/src/CommonFun.js');?>"></script>
    <script src="<?= base_url('wabao/src/ProgressBar.js');?>"></script>
	<script src="<?= base_url('wabao/src/CarMove.js');?>"></script>
	<script src="<?= base_url('wabao/src/dialog/game_desc.js');?>"></script>
	<script src="<?= base_url('wabao/src/dialog/gameVictory.js');?>"></script>
	<script src="<?= base_url('wabao/src/Config.js?00');?>"></script>
	<script src="<?= base_url('wabao/src/Game.js?0605');?>"></script>
	<script src="<?= base_url('wabao/src/Position_config.js');?>"></script>
	
	<script src="<?= base_url('wabao/src/GameStart.js');?>"></script>
	<script src="<?= base_url('wabao/src/dialog/gameOver.js');?>"></script>
	<script src="<?= base_url('wabao/src/dialog/PassLevel.js?01');?>"></script>
	<script src="<?= base_url('wabao/src/GamePause.js');?>"></script>

	<script src="<?= base_url('wabao/src/GameInfo.js');?>"></script>
	<script src="<?= base_url('wabao/src/Goldman.js');?>"></script>
    
	
	<script src="<?= base_url('wabao/src/Background.js');?>"></script>
	
	<!--jsfile--endTag-->
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
        var share_url = 'http://xccq.th00.com.cn/yccq/client/';
        var share_img = 'http://xccq.th00.com.cn/yccq/game/loading/loading.png';
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
</html>0