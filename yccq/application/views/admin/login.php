<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>香草传奇-后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="" media="all">
    <link rel="stylesheet" href="<?= base_url('static/layui/css/layui.css?t=0510') ?>" media="all">
    <link rel="stylesheet" href="<?= base_url('static/admin/css/global.css?t=0510') ?>" media="all">
    <script src="<?= base_url()?>static/admin/js/jquery-1.8.3.min.js"></script>
</head>

<body style="background-color:#1f358b; background-image:url(<?= base_url('static/admin/images/bg.jpg') ?>); background-repeat:no-repeat; background-position:center top; background-size:cover; overflow:hidden;">

<form action="<?= site_url('admin/common/check_login') ?>" method="post">

<div class="loginbox">
    <ul>
        <li><span>用户名</span><input name="username" type="text" class="inputext"/></li>
        <li><span>密码</span><input name="password" type="password" class="inputext" autocomplete="off"/></li>
        <li><input type="submit" class="layui-btn  layui-btn-normal layui-btn-small" value=" 登 录 " style="width: 70px;"></li>
        <?php if(!empty($_SESSION['error'])&&$_SESSION['error']>2){?>
        <li style="margin: 10px 0 0 0;">
            <span>验证码</span><input class="inputext" style=" float:left;" type="text" name="captcha">
            <img  id="captcha"  style="margin-left: 20px; width:90px; height:30px; float:left; border-radius: 4px; " src="<?= get_captcha()?>"/ >
        </li>
        <?php }?>
    </ul>
</div>
</form>
<div class="login_footer">
    <div>
        ©2017 广西中烟工业集团 &nbsp;&nbsp;请使用IE8及以上浏览器<br>技术支持：广西紫云科技
    </div>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery; //由于layer弹层依赖jQuery，所以可以直接得到


    })
</script>

<script type="text/javascript">
    //验证码点击事件
    $('#captcha').click(function(){
        var src=$(this).attr('src');
//        alert(src);
        //获取路径
        $.ajax({
            url: '<?=site_url('admin/common/get_captcha')?>',   //后台处理程序
            type: "post",         //数据发送方式
            async:false,//取消异步请求
            /*dataType:"json",   */ //接受数据格式
            data:{captcha:src},  //要传递的数据
            success:function(data){
                //alert(data);
                $("#captcha").attr('src',data);
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("失败！");
            }
        });

    });
</script>


</body>
</html>