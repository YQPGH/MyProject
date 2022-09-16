<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>填写地址</title>
    <meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
    <meta name='apple-mobile-web-app-capable' content='yes' />
    <meta name='full-screen' content='true' />
    <meta name='screen-orientation' content='landscape' />
    <meta name='x5-fullscreen' content='true' />
    <meta name='360-fullscreen' content='true' />
    <meta http-equiv='expires' content='0' />

    <script src="<?= base_url()?>static/address/js/jquery-1.11.3.min.js"></script>
    <script src="<?= base_url()?>static/address/js/area.js"></script>
    <link rel="stylesheet"  href="<?= base_url()?>static/address/css/trees_style.css">
</head>

<body>
<div id="clickSub" >
    <p id="content"></p>
    <button type="button"  class="click_submit" ></button>
    <button type="button"  class="btn_close" ></button>
</div>

<div id="msg" ></div>
<div id="pop">
    <form id="form-body" action="" method="post" enctype="multipart/form-data" onsubmit="return false">
        <div class="form-row">
            <div id="text_img">
                <p>填写地址</p>
            </div>
        </div>
        <div id="bg-body">

            <div class="form-row">
                <label class="form-label" for="">姓 名</label>
                <input class="form-input" id="truename" name="truename" type="text" value="<?=$truename?>"  autocomplete="off">
                <input type="hidden" id="uid" name="uid" value="<?=$uid?>">
                <input type="hidden" id="id" value="<?=$id?>">
                <input type="hidden" id="status" value="<?=$status?>">
                <input type="hidden" id="type" value="<?=$type?>">

            </div>
            <div class="form-row">
                <label class="form-label" for="">手 机</label>
                <input class="form-input"  id="phone" type="tel" name="phone" value="<?=$phone?>"  autocomplete="off">
            </div>
            <div class="form-row">
                <label class="form-label" for="">省 份</label>
                <div class="form-group">
                    <select class="form-select" name="province" id="province" >
                        <?php foreach($province_name as $v):?>
                            <option value="<?=$v['pro_code']?>" ><?=$v['pro_name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="arrow1" class="arrow"></span>
                </div>
            </div>
            <div class="form-row">
                <label class="form-label" for="">地 区</label>
                <div class="form-group" style="width: 34%;">
                    <select class="form-select" name="city" id="city">
                        <?php foreach($city_name as $v):?>
                            <option value="<?=$v['city_code']?>" ><?=$v['city_name']?></option>
                        <?php endforeach; ?>

                    </select>
                    <span id="arrow2" class="arrow"></span>
                </div>
                <div class="form-group" style="width: 34%;">
                    <select class="form-select" name="area" id="county" >
                        <?php foreach($area_name as $v):?>
                            <option value="<?=$v['area_code']?>" ><?=$v['area_name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="arrow3" class="arrow"></span>
                </div>
            </div>
            <div class="form-row textarea">
                <label class="form-label form-textarea-label" for="">详 细<br/>地 址</label>
                <textarea class="form-textarea" name="street" id="street" cols="29" rows="5"><?= $street?></textarea>
            </div>
            <div class="form-row wtips">
                <span class="tips">温馨提示：请填写真实地址详情，填写后点击提交按钮即为填写成功，</span>
                <span style="color:  #ffb432;">提交后不可修改。</span>
            </div>
        </div>


        <div class="form-row submit">
            <button type="button" data-method="confirmTrans" class="btn_submit" ></button>

        </div>
    </form>
</div>
<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script type="application/javascript">

         var ua = navigator.userAgent.toLowerCase();
         var isWeixin = ua.indexOf('micromessenger') != -1;
         var isMobile = ua.indexOf('mobile') != -1;
         if (!isWeixin || !isMobile) {
         document.head.innerHTML = '<title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/0.4.1/weui.css">';
         document.body.innerHTML = '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div><div class="weui_text_area"><h4 class="weui_msg_title">请在微信手机客户端打开链接</h4></div></div>';
         }


    $("#province").focus(function(){

    $("#arrow1").css({
        transform:"rotate(180deg)"
    });
}).blur(function(){
    $("#arrow1").css({
        transform:"rotate(0deg)"
    });
}).on("change",function(){
    $("#province").blur();
    $("#arrow1").css({
        transform:"rotate(0deg)"
    });

});

$("#city").focus(function(){
    $("#arrow2").css({
        transform:"rotate(180deg)"
    });
}).blur(function(){
    $("#arrow2").css({
        transform:"rotate(0deg)"
    });
}).on("change",function(){
    $("#city").blur();
    $("#arrow2").css({
        transform:"rotate(0deg)"
    });
});

$("#county").focus(function(){
    $("#arrow3").css({
        transform:"rotate(180deg)"
    });
}).blur(function(){
    $("#arrow3").css({
        transform:"rotate(0deg)"
    });
}).on("change",function(){
    $("#county").blur();
    $("#arrow3").css({
        transform:"rotate(0deg)"
    });
});



    var province, city, area;
    var province_code,city_code,area_code;
    var type;
    $(document).ready(function(){

        $(".btn_submit").removeAttr('disabled');
        type = $('#type').val();
        var status = document.getElementById('status').value;
        if(status==1){
            $('.btn_submit').css('display','none');
            $('.submit').css('display','none');
        }

        province_code = document.getElementById("province").options[0].value;
        city_code = document.getElementById("city").options[0].value;
        area_code = document.getElementById("county").options[0].value;
        $('select').change(function(){

            $("#province_txt").css('display','none');
            $("#city_txt").css('display','none');
            $("#area_txt").css('display','none');
             province_code = $('#province option:selected') .val();//选中的值
             city_code = $('#city option:selected') .val();
             area_code = $('#county option:selected') .val();
            var name=$(this).attr('name');

            $.post("<?=base_url()?>api/address/get_address",
                {
                    name:name,
                    province_code:province_code,
                    city_code:city_code,
                    area_code:area_code,
                    type:type
                },
                function(data){
                    province = JSON.parse(data).data.province;
                    city = JSON.parse(data).data.city;
                    area = JSON.parse(data).data.area;

                    if (province){
                        var str='';
                        str+=' <select class="form-select" name="'+province+'" id="province" >';
                        for(var i in province){
                            str+='<option value="'+province[i].pro_code+'">'+province[i].pro_name+'</option>';
                        }
                        str+='</select>';
                        $("#province").html(str);
                        province_code = document.getElementById("province").options[0].value;

                    }
                    if (city){
                        var str='';
                        str+=' <select class="form-select" name="'+city+'" id="city" >';
                        for(var i in city){
                            str+='<option value="'+city[i].city_code+'">'+city[i].city_name+'</option>';
                        }
                        str+='</select>';
                        $("#city").html(str);
                        city_code = document.getElementById("city").options[0].value;
                    }
                    if (area){
                        var str='';
                        str+=' <select class="form-select" name="'+area+'" id="county" >';
                        for(var i in area){
                            str+='<option value="'+area[i].area_code+'">'+area[i].area_name+'</option>';
                        }
                        str+='</select>';
                        $("#county").html(str);
                        area_code = document.getElementById("county").options[0].value;

                    }
                });

        });
    });
      $('.btn_submit').on('click', function(){
                var truename = $('#truename').val();
                var uid = $('#uid').val();
                var id = $('#id').val();
                var phone = $('#phone').val();
                var street = $('#street').val();
                province = document.getElementById("province").options[0].text;
                city = document.getElementById("city").options[0].text;
                area = document.getElementById("county").options[0].text;

                if(truename=='' || phone=='' ||  street=='')
                {
                    $("#msg").css('display','block');
                    var pass = document.getElementById('msg');
                    if(phone=='') pass.innerHTML = escapeHTML('请输入手机号');
                    if(truename=='') pass.innerHTML = escapeHTML('请输入姓名');
                    if(street=='') pass.innerHTML = escapeHTML('请输入详细地址');
                    setTimeout(function(){
                        $("#msg").css('display','none');
                    },2000);
                }
                else
                {
                    $("#clickSub").css('display','block');
                    $(".btn_submit").attr('disabled','disabled');
                    document.getElementById('content').innerHTML = escapeHTML('姓名：'+truename+
                    '<br>手机：'+phone+
                    '<br>省份：'+province+
                    '<br>地区：'+city+area+
                    '<br>详细地址：'+street);
                    $('.click_submit').on('click',function()
                    {
                        $.post("<?= base_url()?>api/address/savemessage",
                            {
                                uid:uid,
                                type:type,
                                id:id,
                                truename:truename,
                                phone:phone,
                                address:province+','+city+','+area+','+street,
                                code:province_code+','+city_code+','+area_code
                            },
                            function(data){

                                var dataObj=eval("("+data+")");//转换为json对象
                                alertfun(dataObj);
                            });
                    });

             }

         });
       $('.btn_close').on('click', function() {
            $("#clickSub").css('display', 'none');
           $(".btn_submit").removeAttr('disabled');
      });


    function alertfun(txt) {

        $("#msg").css('display','block');
        var pass = document.getElementById('msg');
        if(txt.code == 0){
            txt.msg = "提交成功！";
            redirect('<?= base_url()?>api/main/index', 1);
        }

        pass.innerHTML = txt.msg;
        setTimeout(function(){
            $("#msg").css('display','none');
        },2000);
    }


function redirect(url, time) {
    setTimeout("window.location='" + url + "'", time * 1000);
}
         function escapeHTML(unsafe_str) {
             return unsafe_str
                 .replace(/&/g, '&')
                 .replace(/</g, '<')
                 .replace(/>/g, '>')
                 .replace(/\"/g, '"')
                 .replace(/\'/g, '')
                 .replace(/\//g, '')

         }


</script>
</body>
</html>
