<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
//?><!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--    <meta charset="utf-8">-->
<!--    <title>layui</title>-->
<!--    <meta name="renderer" content="webkit">-->
<!--    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
<!--    <link rel="stylesheet" href="--><?//= base_url()?><!--static/layui/css/layui.css"  media="all">-->
<!--    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->-->
<!--</head>-->
<!--<body>-->
<!---->
<!--<div class="site-demo-button" id="layerDemo" style="margin-bottom: 0;">-->
<!--    <button data-method="confirmTrans" class="layui-btn">配置一个透明的询问框</button>-->
<!--</div>-->
<!--<script src="--><?//= base_url('static/layui/layui.js') ?><!--"></script>-->
<!--<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->-->
<!--<script>-->
<!--    layui.use('layer', function(){ //独立版的layer无需执行这一句-->
<!--        var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句-->
<!--        //触发事件-->
<!--        var active = {-->
<!--            confirmTrans: function(){-->
<!--                //配置一个透明的询问框-->
<!--                layer.msg('大部分参数都是可以公用的<br>合理搭配，展示不一样的风格', {-->
<!--//                    time: 20000, //20s后自动关闭-->
<!--                    btn: ['明白了']-->
<!--                });-->
<!--            }-->
<!--        };-->
<!---->
<!--        $('#layerDemo .layui-btn').on('click', function(){-->
<!--            var othis = $(this), method = othis.data('method');-->
<!--            active[method] ? active[method].call(this, othis) : '';-->
<!--        });-->
<!---->
<!--    });-->
<!--</script>-->
<!---->
<!--</body>-->
<!--</html>-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>


    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
</head>
<body>

<div id="aaa" style="display: none;">
    这是隐藏内容
</div>
<button type="submit" id="but">点击显示</button>
<script type="text/javascript">
    $(document).ready(function(){
        $('#but').click(function(){
            $("#aaa").css('display','block');

            setTimeout(function(){
                $("#aaa").css('display','none');
            },2000);
        })
    })
</script>
</body>
</html>


