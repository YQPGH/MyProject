<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>请填写地址</title>
    <meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
    <meta name='apple-mobile-web-app-capable' content='yes' />
    <meta name='full-screen' content='true' />
    <meta name='screen-orientation' content='landscape' />
    <meta name='x5-fullscreen' content='true' />
    <meta name='360-fullscreen' content='true' />
    <meta http-equiv='expires' content='0' />

    <script src="<?= base_url()?>static/address/js/jquery-1.11.3.min.js"></script>
    <script src="<?= base_url()?>static/address/js/area.js"></script>
    <link rel="stylesheet"  href="<?= base_url()?>static/address/css/leaf_style.css">
</head>

<body>
<div id="msg" ></div>
<div id="pop">
    <form id="form-body" action="" method="post" enctype="multipart/form-data" onsubmit="return false">

        <div class="form-row">
            <label class="form-label" for="">姓 名</label>
            <input class="form-input" id="truename" name="truename" type="text" value="<?=$truename?>">
            <input type="hidden" id="uid" name="uid" value="<?=$uid?>">
            <input type="hidden" id="id" value="<?=$id?>">
            <input type="hidden" id="status" value="<?=$status?>">

        </div>
        <div class="form-row">
            <label class="form-label" for="">手 机</label>
            <input class="form-input"  id="phone" type="tel" name="phone" value="<?=$phone?>">
        </div>
        <div class="form-row">
            <label class="form-label" for="">省 份</label>
            <div class="form-group">
                <input id="province_txt" value="<?=$province?>">
                <select class="form-select" name="province" id="province">
                    <option value="<?=$province?>" ><?=$province?></option>
                </select>
<!--                <img style="" src="--><?//= base_url()?><!--static/address/images/leaf/arrow.png" id="arrow1" class="arrow">-->
            </div>
        </div>
        <div class="form-row">
            <label class="form-label" for="">地 区</label>
            <div class="form-group" style="width: 35%;">
                <input id="city_txt" value="<?=$city?>">
                <select class="form-select" name="city" id="city">
                    <option value="<?=$city?>"><?=$city?></option>
                </select>
<!--                <img style="" src="--><?//= base_url()?><!--static/address/images/leaf/arrow.png" id="arrow2" class="arrow">-->
            </div>
            <div class="form-group" style="width: 35%;">
                <input id="area_txt" value="<?=$area?>">
                <select class="form-select" name="area" id="county" >
                    <option value="<?=$area?>" ><?=$area?></option>
                </select>
<!--                <img style="" src="--><?//= base_url()?><!--static/address/images/leaf/arrow.png" id="arrow3" class="arrow">-->
<!--                <img style="" src="" id="arrow3" class="arrow">-->
<!--                <span id="arrow3" class="arrow"></span>-->
            </div>
        </div>
        <div class="form-row">
            <label class="form-label form-textarea-label" for="">详 细<br/>地 址</label>
            <textarea class="form-textarea" name="street" id="street" cols="29" rows="6"><?= $street?></textarea>
        </div>

        <div class="form-row submit">
            <button type="button" class="btn_submit" ></button>

        </div>
    </form>
</div>
<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script type="application/javascript">
//    var ua = navigator.userAgent.toLowerCase();
//    var isWeixin = ua.indexOf('micromessenger') != -1;
//    var isMobile = ua.indexOf('mobile') != -1;
//    var ipad = navigator.userAgent.indexOf("iPad"); //禁用ipad
//    if (!isWeixin || !isMobile || ipad != -1) {
//        document.head.innerHTML = '<title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/0.4.1/weui.css">';
//        document.body.innerHTML = '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div><div class="weui_text_area"><h4 class="weui_msg_title">请在微信手机客户端打开链接</h4></div></div>';
//    }


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
    setCity($(this).val());
    setCounty($(this).val(),0);
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
    setCounty($('#province').val(),$(this).val());
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


    var province,city,area,province_index,city_index,area_index;
    $(document).ready(function(){

        var status = document.getElementById('status').value;
        if(status==1){
            $('.btn_submit').css('display','none');
        }
        var province_obj= document.getElementById("province");
        var city_obj = document.getElementById("city");
        var area_obj = document.getElementById("county");
        setProvince(20);
        setCity(20);
        setCounty(20,0);
        var province_val = document.getElementById("province_txt").value;
        var city_val = document.getElementById("city_txt").value;
        var area_val = document.getElementById("area_txt").value;


        province_index= province_obj.selectedIndex; //序号，取当前选中选项的序号
        province = province_obj.options[province_index].text;

        city_index=city_obj.selectedIndex; //序号，取当前选中选项的序号
        city = city_obj.options[city_index].text;

        area_index=area_obj.selectedIndex; //序号，取当前选中选项的序号
        area = area_obj.options[area_index].text;
        if(province_val != ''){
            $("#province_txt").css('display','block');
            province = province_val;
        }
        if(city_val != ''){
            $("#city_txt").css('display','block');
            city = city_val;
        }
        if(area_val != ''){
            $("#area_txt").css('display','block');
            area = area_val;
        }

        $('select').change(function(){
            $("#province_txt").css('display','none');
            $("#city_txt").css('display','none');
            $("#area_txt").css('display','none');
            province_index= province_obj.selectedIndex; //序号，取当前选中选项的序号
            province = province_obj.options[province_index].text;

            city_index=city_obj.selectedIndex; //序号，取当前选中选项的序号
            city = city_obj.options[city_index].text;

            area_index=area_obj.selectedIndex; //序号，取当前选中选项的序号
            area = area_obj.options[area_index].text;
            console.log(province);
            console.log(city);
console.log(area);
        });

        $('.btn_submit').click(function(){
            var truename = $('#truename').val();
            var uid = $('#uid').val();
            var id = $('#id').val();
            var phone = $('#phone').val();
            var street = $('#street').val();

            $.post("<?= base_url()?>api/leaf/savemessage",
                {
                    uid:uid,
                    id:id,
                    truename:truename,
                    phone:phone,
                    province:province,
                    city:city,
                    area:area,
                    street:street

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
            txt.msg = "保存成功！";
        }

        pass.innerHTML = txt.msg;
        setTimeout(function(){
            $("#msg").css('display','none');
        },2000);
//        redirect('<?//= base_url()?>//api/main/index', 1);
    }

    function setProvince(selected) {

        let province1 = dsy.Items[0];
        let html = '';

        for(let i = 0; i < province1.length; i++){
                html += '<option value="'+i+'" '+((i==selected)?'selected="true"':'')+'>'+province1[i]+'</option>';
        }
        $('#province').html(html);

    }

    function setCity(p_id) {
        let citys = dsy.Items['0_'+p_id];
        let html = '';
        for(let i = 0; i < citys.length; i++){
            html += '<option value="'+i+'">'+citys[i]+'</option>';
        }
        $('#city').html(html);

    }

    function setCounty(p_id, c_id) {
        let countys = dsy.Items['0_'+p_id+'_'+c_id];

        let html = '';

        for(let i = 0; i < countys.length; i++){
            html += '<option value="'+i+'">'+countys[i]+'</option>';
        }
        $('#county').html(html);
    }

function redirect(url, time) {
    setTimeout("window.location='" + url + "'", time * 1000);
}

</script>
</body>
</html>