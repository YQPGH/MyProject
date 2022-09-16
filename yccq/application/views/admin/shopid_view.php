<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>烟草传奇-管理后台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?= base_url('static/layui/css/layui.css?t=0510') ?>" media="all">
    <link rel="stylesheet" href="<?= base_url('static/admin/css/global.css?t=0705') ?>" media="all">
</head>
<body>
<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title">
        <?php foreach($arr as $key=>$value){ ?>
            <li class="<?php if($key==0){echo "layui-this";} ?>"><?=$arr_name[$key]?></li>
        <?php }?>
    </ul>
    <div class="layui-tab-content" style="">
        <?php foreach($list as $key=>$value){ ?>
            <div class="layui-tab-item  <?php if($key==0){echo "layui-show";} ?>">
                <?php foreach($value as $k=>$val){ ?>
                    <div style="margin-bottom: 10px;">
                        <?php foreach($val as $r=>$t){ ?>
                            <button class="layui-btn layui-btn-warm layui-btn-small" data-shopid="<?=$t['shopid']?>"><?=$t['name']?></button>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        <?php }?>
    </div>
</div>
<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到
        
        $('.layui-btn').on('click', function(){
            $(".is-selected").removeClass('layui-btn-disabled');
            $(".is-selected").addClass('layui-btn-warm');
            $(".is-selected").removeClass('layui-btn-disabled');
            $(this).removeClass('layui-btn-warm');
            $(this).addClass('layui-btn-disabled is-selected');
            var shopid = $(this).attr('data-shopid');
            var id = window.parent.document.getElementById("my-shop").value;
            window.parent.document.getElementById(id).value = shopid;

        });

    });

</script>
</body>
</html>
<!--code by tangjian-->