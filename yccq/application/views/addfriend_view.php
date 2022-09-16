<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'/>
<title>添加好友</title>
<meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
<meta name='apple-mobile-web-app-capable' content='yes' />
<meta name='full-screen' content='true' />
<meta name='screen-orientation' content='portrait' />
<meta name='x5-fullscreen' content='true' />
<meta name='360-fullscreen' content='true' />
<meta http-equiv='expires' content='0' />
<meta name='laya' screenorientation='portrait'>
</head>
<body>
<style>

</style>

<?php 
var_dump($_SESSION);
if (isset($_SESSION['code']) && !empty($_SESSION['code'])) {
	?>
			<script>alert('<?=$_SESSION['code']?>');</script>
			
			<?php }?>
 <script src="http://static.gxtianhai.cn/mntvdb/gamecenter/static/system/js/jquery-1.7.1.min.js"></script>
<script src="http://static.gxtianhai.cn/mntvdb/gamecenter/static/system/js/jweixin-1.0.0.js"></script> 
<script>


var add_frined_code = '';

 var wx_info = {
     uid:'<?=$uid?>',
     nickname:'<?=$nickname?>',   
     allowMusic:'<?=$allowMusic?>',
     first_time:'<?=$first_time?>',
	 isSaveIMG:'<?=$isSaveIMG?>',
	 total_num : '<?=$total_num?>',
	 version :'<?=$version?>',
     open_chest_time:'<?=$open_chest_time?>',
     open_box_status:'<?=$open_box_status?>',
     game_view_first:'<?=$game_view_first?>',
     myball_view_first:'<?=$myball_view_first?>',
     from_banding:'<?=$from_banding?>'
 };


        wx.config({
            appId: '<?php echo $_SESSION['signPackage']["appId"];?>',
            timestamp: <?php echo $_SESSION['signPackage']["timestamp"];?>,
            nonceStr: '<?php echo $_SESSION['signPackage']["nonceStr"];?>',
            signature: '<?php echo $_SESSION['signPackage']["signature"];?>',
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
            share();
        });

        function share(title,content,imgUrl){
            var share_t = '烟草传奇';
            var share_c =  '烟草传奇';
            var share_url = 'http://xccq.th00.com.cn/server/api/AddFriend/' + add_frined_code;
            var share_img = 'http://xccq.th00.com.cn/client/ui/loading.png';
            if(title !='' && typeof(title) !="undefined" ) share_t = title;
            if(content !='' && typeof(content) !="undefined" ) share_c = content;           
            if(imgUrl != '' && typeof(imgUrl) !="undefined") share_img = imgUrl;			
          
            share_app(share_t, share_c, share_url, share_img);
            share_timeline( share_c, share_url, share_img);
        }

		function share_app(title,content,link,imgUrl){

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
                trigger : function (res){

                },complete:function(){
                   share();
                }
            });
		}

    function  share_timeline(content,link,imgUrl){
        wx.onMenuShareTimeline({
            title: content, // 分享标题
            link: link,
            imgUrl: imgUrl, // 分享图标
            success: function () {
               
            },
            cancel: function () {
				
            },
            trigger : function (res){
				
            },complete:function(){
               share();
            }
        });
    }
	
	
	
	function addFriend(){
		$.ajax({
			 type: 'POST',	
			 url: '../friend/mark_url' ,	
			 data: {uid:wx_info.uid} ,
			 dataType: 'json',
			 success: function(data) {
				  if(data.code == "0"){
					  console.log(data.data.url)
					  add_frined_code =  data.data.url;
					  var share_title = '[' + wx_info.nickname+ ']想添加你为好友一起玩烟草传奇！'
					  share(share_title, share_title);
				  }else{
				  }
			  }

		});
		
	}
		
    </script>
<a href="#" onClick="addFriend()" >添加好友</a>
<div>
</div>
</body>
</html>