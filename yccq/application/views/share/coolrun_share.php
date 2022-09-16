<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="applicable-device" content="pc,mobile">
    <meta http-equiv="Cache-Control" content="no-transform ">
    <script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
    <title></title>
    <link rel="stylesheet" style="text/css" href="<?= base_url()?>static/invite/css/run_style.css">
    <link rel="stylesheet" style="text/css" href="<?= base_url()?>static/invite/css/flexible.css">

</head>
<body>
<img id="manager" src="<?= base_url()?>static/invite/images/run/manager.png">
<div class="bg">
    <div id="msg" ></div>
    <form action="" method="post" enctype="multipart/form-data"   onsubmit="return false">
        <input type="hidden" id="incode" name="incode" value="<?=$incode?>">
        <input type="hidden" id="uid" name="uid" value="<?=$uid?>">

        <div id="word">
            <span style="color:#f2d79c;"><?=$nickname?></span><br>
            <?=$content?>
        </div>
        <div class="form-row submit">
            <button id="sub" type="button" class="btn_submit" ></button>
        </div>
    </form>
</div>


<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="application/javascript">


     var ua = navigator.userAgent.toLowerCase();
     var isWeixin = ua.indexOf('micromessenger') != -1;
     var isMobile = ua.indexOf('mobile') != -1;
     if (!isWeixin || !isMobile) {
     document.head.innerHTML = '<title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/0.4.1/weui.css">';
     document.body.innerHTML = '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div><div class="weui_text_area"><h4 class="weui_msg_title">请在微信手机客户端打开链接</h4></div></div>';
     }

    $(document).ready(function () {

        var height = window.screen.availHeight;
        $('.submit').css({'top':height*0.73});

        $('.bg').css({'width':$(window).width(),'height':$(window).height()});

        $('.submit').click(function(){
         var content = $('#content').val();
         var uid = $('#uid').val();
         var incode = $('#incode').val();

             $.post("<?= base_url()?>api/coolrun/invite_accept",
             {
             uid:uid,
             content:content,
             incode:incode
             },
             function(data){
                 var dataObj=eval("("+data+")");//转换为json对象
                 alertfun(dataObj);
             });
         });

    });

function alertfun(txt) {

    $("#msg").css('display','block');
    var pass = document.getElementById('msg');
    if(txt.code == 0){
        txt.msg = "成功接受邀请！";
    }

    pass.innerHTML = txt.msg;
    setTimeout(function(){
        $("#msg").css('display','none');
    },2000);
    redirect('<?= base_url()?>api/main/index', 1);
}
    function redirect(url, time) {
        setTimeout("window.location='" + url + "'", time * 1000);
    }

</script>

</body>
</html>