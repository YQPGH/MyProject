<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'/>
    <title><?= $title ?></title>
    <meta name='viewport'
          content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
    <meta name='apple-mobile-web-app-capable' content='yes'/>
    <meta name='full-screen' content='true'/>
    <meta name='screen-orientation' content='landscape'/>
    <meta name='x5-fullscreen' content='true'/>
    <meta name='360-fullscreen' content='true'/>
    <meta http-equiv='expires' content='0'/>
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' https://res.wx.qq.com https://yccq.zlongwang.com 'unsafe-eval' https://yccq.zlongwang.com">
</head>
<style>
    .bg {
        background: url('<?=base_url('game/loading/loading.png');?>') no-repeat center center;
        background-attachment: fixed;
        /* background-repeat:no-repeat;*/
        background-size: cover;
        -moz-background-size: cover;
        -webkit-background-size: cover;

    }
	
	.bg-color{
		background-color: #000;
	}
</style>
<script type="text/javascript">
    <?php if(isset($_SESSION["uid"])){?>
    localStorage.GUID = "<?=$_SESSION['uid'];?>";
    <?php }else{?>
    location.href = "<?=site_url('api/main/index')?>";
    <?php }?>
	var AllowEnter = 1;
	var isBlack = Number(<?=$_SESSION['is_black'];?>);
</script>
<body class="bg">

<!--核心包，封装了显示对象渲染，事件，时间管理，时间轴动画，缓动，消息交互,socket，本地存储，鼠标触摸，声音，加载，颜色滤镜，位图字体等-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.core.js'); ?>"></script>
<!--封装了webgl渲染管线，如果使用webgl渲染，可以在初始化时调用Laya.init(1000,800,laya.webgl.WebGL);-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.webgl.js'); ?>"></script>
<!--是动画模块，包含了swf动画，骨骼动画等-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.ani.js'); ?>"></script>
<!--包含更多webgl滤镜，比如外发光，阴影，模糊以及更多-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.filter.js'); ?>"></script>
<!--封装了html动态排版功能-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.html.js');?>"></script>
<!--粒子类库-->
<!--<script type="text/javascript" src="libs/laya.particle.js"></script>-->
<!--提供tileMap解析支持-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.tiledmap.js'); ?>"></script>
<!--提供了制作UI的各种组件实现-->
<script type="text/javascript" src="<?= base_url('game/libs/laya.ui.js'); ?>"></script>

<!--自定义的js(src文件夹下)文件自动添加到下面jsfile模块标签里面里，js的顺序可以手动修改，修改后保留修改的顺序，新增加的js会默认依次追加到标签里-->
<!--删除标签，ide不会自动添加js文件，请谨慎操作-->

<script src="<?= base_url('game/src/ui/layaUI.max.all.js?=v1.6'); ?>"></script>
<!--jsfile--startTag-->
<script src="<?= base_url('game/src/building/ShenShu.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ShenShuDialog.js');?>"></script>
<script src="<?= base_url('game/src/dialog/dialog0303.js');?>"></script>
<script src="<?= base_url('game/src/dialog/zhongqiu_gf_result.js');?>"></script>
<script src="<?= base_url('game/src/dialog/zhongqiu_gf.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Zhongqiu.js');?>"></script>
<script src="<?= base_url('game/src/NPC/ChangE.js');?>"></script>
<script src="<?= base_url('game/src/dialog/GuaJiang.js');?>"></script>
<script src="<?= base_url('game/src/dialog/BuildingUpgrade.js');?>"></script>
<script src="<?= base_url('game/src/dialog/BuildingChangeSkin.js');?>"></script>
<script src="<?= base_url('game/src/dialog/BuildingUpgradeConfirm.js');?>"></script>
<script src="<?= base_url('game/src/dialog/NationalDay.js');?>"></script>
<script src="<?= base_url('game/src/dialog/LaxinZhaoji.js');?>"></script>
<script src="<?= base_url('game/src/dialog/LaxinRule.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Laxin.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FragmentNewer.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FragmentConfirm.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FragmentGetTips.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FragmentAskGiving.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FragmentBoxOpenTips.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Fragment.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Kuma.js');?>"></script>
<!--<script src="<?= base_url('game/src/dialog/Draws.js');?>"></script>
<script src="<?= base_url('game/src/dialog/NewYearLogin.js');?>"></script>
<script src="<?= base_url('game/src/dialog/DaZhuanPan.js');?>"></script>-->
<script src="<?= base_url('game/src/dialog/NewYearsDay.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Doubel12.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Doubel11.js');?>"></script>
<script src="<?= base_url('game/src/dialog/FriendFireDialog.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ChongziFriendList.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ChongziDialog.js?v=1.5');?>"></script>
<script src="<?= base_url('game/src/ani/ChongziAni.js');?>"></script>
<script src="<?= base_url('game/src/ChongziManager.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Holidays.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ChangeHeader.js?v=1.4');?>"></script>
<script src="<?= base_url('game/src/dialog/Compensate.js');?>"></script>
<script src="<?= base_url('game/src/dialog/HuoDong.js');?>"></script>
<script src="<?= base_url('game/src/dialog/TestEnd.js');?>"></script>
<script src="<?= base_url('game/src/dialog/Ranking.js?v=1.0');?>"></script>
<script src="<?= base_url('game/src/dialog/TuiJian.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ledouTips.js');?>"></script>
<script src="<?= base_url('game/src/Story.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ChaXun.js');?>"></script>
<script src="<?= base_url('game/src/dialog/tipsDialog.js');?>"></script>
<script src="<?= base_url('game/src/ZhiYinManager.js?=v1.6');?>"></script>
<script src="<?= base_url('game/src/dialog/ZhiYinMask.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ZhiYinNPC.js');?>"></script>
<script src="<?= base_url('game/src/dialog/SelectRole.js');?>"></script>
<script src="<?= base_url('game/src/dialog/ChuLi.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ShiJianList.js'); ?>"></script>
<script src="<?= base_url('game/src/ShiJianManager.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/welcome.js?v=1.4'); ?>"></script>
<script src="<?= base_url('game/src/dialog/SelectYan.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JiFenDuiHuan.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JiangChi.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Choujiang.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ChouJiangZhong.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ChouJiangGao.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/ani/BoyAni.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/GirlAni.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/YanAni.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Gongren.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Gharry.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/HaianAni.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Hailang.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Pond.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/BirdAni.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Ship.js'); ?>"></script>
<script src="<?= base_url('game/src/ani/Haio.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/YouLeChang.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Gonglue.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JianDieBuy.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JianDieInfo.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/QuanDialog.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/chengjiu.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/MyPrize.js?v=1.0'); ?>"></script>
<script src="<?= base_url('game/src/dialog/GuideBook.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/RechargeDialog.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ShouGeDialog.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ItemInfo.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/LuckDraw.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ShengjiSuccess.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/HechengSuccess.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/PinjianSuccess.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/FriendInfo.js'); ?>"></script>
<script src="<?= base_url('game/src/FriendFarm.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/SignIn.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/OrderList.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/FriendDialog.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/LBT_XJ.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/LBT_ALL.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/LBT_SJ.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/LuBianTan.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/Tree.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeBaishu.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeSongshu.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeRongshu.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeLiushu.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeZhiwu1.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeZhiwu2.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeZhiwu3.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeZhiwu4.js'); ?>"></script>
<script src="<?= base_url('game/src/building/TreeGrass.js'); ?>"></script>
<script src="<?= base_url('game/src/building/Liba.js'); ?>"></script>
<script src="<?= base_url('game/src/building/LibaSmall.js'); ?>"></script>
<script src="<?= base_url('game/src/building/Shijie.js'); ?>"></script>
<script src="<?= base_url('game/src/building/ShijieSmall.js'); ?>"></script>
<script src="<?= base_url('game/src/building/Shitou.js'); ?>"></script>
<script src="<?= base_url('game/src/building/ShitouSmall.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Dati_jiangpin.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Dati.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JGCPeifang.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/JGCDialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/PlantDialog.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Confirm1.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/YJSDialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/userinfoDialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/BuyConfirm.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/pydialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/pjdialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ardialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/brdialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/confirm.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/zldialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/Building.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/NPC/ZhangGui.js'); ?>"></script>
<script src="<?= base_url('game/src/NPC/JianDie.js'); ?>"></script>
<script src="<?= base_url('game/src/NPC/Thief.js'); ?>"></script>
<script src="<?= base_url('game/src/NPC/DiaoYuWeng.js'); ?>"></script>
<script src="<?= base_url('game/src/dialog/Thief.js'); ?>"></script>
<script src="<?= base_url('game/src/building/SuiPianGe.js'); ?>"></script>
<script src="<?= base_url('game/src/building/NengLiangCao.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/ChouJiang.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/YouLeChang.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/ZLShop.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/AgingRoom.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/BakingRoom.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/MyHouse.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/Factory.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/Pinjian.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/Peiyushi.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/YJSBuilding.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/LuBianTan.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/GongGaoLan.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/ckdialog.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/building/CKBuilding.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/dialog/bozhong.js'); ?>"></script>
<script src="<?= base_url('game/src/seed.js'); ?>"></script>
<script src="<?= base_url('game/src/land.js'); ?>"></script>
<script src="<?= base_url('game/src/utils.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/map.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/config.js?v=1.6'); ?>"></script>
<script src="<?= base_url('game/src/myGame.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/UILayer.js?v=1.5'); ?>"></script>
<script src="<?= base_url('game/src/main.js?v=1.6'); ?>"></script>
<!--jsfile--endTag-->
<?php 
     function getSignPackage() {
        $key = 'ka2J9PC326T44D439H6tvcPBY';
        $sign = strtoupper(md5($key));
        //测试环境
        //$get_jsSDK_url = 'http://zl.haiyunzy.com/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;
        //生产环境
        //$get_jsSDK_url = 'http://wx.thewm.cn/thirdInterface/thirdInterface!getJsapiTicket.action?sign='.$sign;       
		$get_jsSDK_url = 'http://yccq.zlongwang.com/yccq/api/WeiXin/getJsapiTicket?sign='.$sign;
        $res = curlGetData($get_jsSDK_url);
        $signPackage = json_decode( $res, true);
        $appid = $signPackage['appid'];
        $jsapiTicket = $signPackage['JsapiTicket']; //$signPackage['ticket'] ;

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";
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
   /*var ua = navigator.userAgent.toLowerCase();
    var isWeixin = ua.indexOf('micromessenger') != -1;
    var isMobile = ua.indexOf('mobile') != -1;
    if (!isWeixin || !isMobile) {
        document.head.innerHTML = '<title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/0.4.1/weui.css">';
        document.body.innerHTML = '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div><div class="weui_text_area"><h4 class="weui_msg_title">请在微信手机客户端打开链接</h4></div></div>';
    }*/
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
        var share_t = '【香草传奇】种植集能量，劳动最闪亮';//'整个朋友圈都在玩<香草传奇>，你还不快来！';
        var share_c = '致敬劳动者'; //'好玩的停不下来，我是种植王者！';
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
