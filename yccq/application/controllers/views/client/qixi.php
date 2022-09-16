<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>

<head>
    <title>乘风破浪来见你——七夕相约</title>
    <meta charset='utf-8' />
    <meta name='renderer' content='webkit' />
    <meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'
    />
    <meta name='apple-mobile-web-app-capable' content='yes' />
    <meta name='full-screen' content='true' />
    <meta name='x5-fullscreen' content='true' />
    <meta name='360-fullscreen' content='true' />
    <meta name='laya' screenorientation='landscape' />
    <meta http-equiv='expires' content='0' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
    <meta http-equiv='expires' content='0' />
    <meta http-equiv='Cache-Control' content='no-siteapp' />
</head>

<body>
    <script type="text/javascript">
        localStorage.uid = "<?=$_SESSION['uid'];?>";
		var share_data = {
			appId: '<?=$appId?>',
			timestamp: <?=$timestamp?>,
			nonceStr: '<?=$nonceStr?>',
			signature: '<?=$signature?>'
		};
        function loadLib(url) {
            var script = document.createElement("script");
            script.async = false;
            script.src = "<?='https://'.$_SERVER['HTTP_HOST'].'/yccq/qixi/'?>"+url;
            document.body.appendChild(script);
        }
    </script>
    <script type="text/javascript" src="<?='https://'.$_SERVER['HTTP_HOST'].'/yccq/qixi/'?>index.js"></script>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> 
	<script type="text/javascript" src="<?='https://'.$_SERVER['HTTP_HOST'].'/yccq/qixi/js/'?>WeixinShare.js"></script>
</body>

</html>