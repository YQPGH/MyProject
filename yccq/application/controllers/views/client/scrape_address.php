<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>请填写地址</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="<?= base_url()?>static/scrape_address/css/style.css" />
		<script src="<?= base_url()?>static/scrape_address/js/jquery-1.11.3.min.js"></script>
    	<script src="<?= base_url()?>static/scrape_address/js/area.js"></script>
		<script type="text/javascript"> 
        !function(J){function H(){var d=E.getBoundingClientRect().width;var e=(d/7.5>500*B?500*B:(d/7.5<42?42:d/7.5));E.style.fontSize=e+"px",J.rem=e}var G,F=J.document,E=F.documentElement,D=F.querySelector('meta[name="viewport"]'),B=0,A=0;if(D){var y=D.getAttribute("content").match(/initial\-scale=([\d\.]+)/);y&&(A=parseFloat(y[1]),B=parseInt(1/A))}if(!B&&!A){var u=(J.navigator.appVersion.match(/android/gi),J.navigator.appVersion.match(/iphone/gi)),t=J.devicePixelRatio;B=u?t>=3&&(!B||B>=3)?3:t>=2&&(!B||B>=2)?2:1:1,A=1/B}if(E.setAttribute("data-dpr",B),!D){if(D=F.createElement("meta"),D.setAttribute("name","viewport"),D.setAttribute("content","initial-scale="+A+", maximum-scale="+A+", minimum-scale="+A+", user-scalable=no"),E.firstElementChild){E.firstElementChild.appendChild(D)}else{var s=F.createElement("div");s.appendChild(D),F.write(s.innerHTML)}}J.addEventListener("resize",function(){clearTimeout(G),G=setTimeout(H,300)},!1),J.addEventListener("pageshow",function(b){b.persisted&&(clearTimeout(G),G=setTimeout(H,300))},!1),H()}(window);
        if (typeof(M) == 'undefined' || !M){
            window.M = {};
        }
     </script>   	
	</head>
	<body>
		<div id="msg" ></div>
		<div class="banner">
			<img src="<?= base_url('static/scrape_address/images/bg_top.png') ?>" />
		</div>
		 <form id="form-body" action="" method="post" enctype="multipart/form-data" onsubmit="return false">
			<div class="banner-bottom">
				<div class="form-body">
					<ul class="information">
						<li>
							<label>姓&nbsp;名</label>
							<input class="infor-input" id="truename" name="truename" placeholder="" type="text"  maxlength="100" value="<?=$truename?>"/>
						</li>
						<li>
							<label>手&nbsp;机</label>
							<input class="infor-input" id="phone" type="tel" name="phone" placeholder="" maxlength="11" value="<?=$phone?>" onkeyup="this.value=this.value.replace(/[^\d]/g,'') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'') " onblur="return checkPhone(this)"  />
						</li>
						<li>
							<label>省&nbsp;份</label> 
							<div class=" drop-down">
								<select class="infor-input infor-select" name="province" id="province">
				                    <?php foreach($province_name as $v):?>
				                        <option value="<?=$v['pro_code']?>" ><?=$v['pro_name']?></option>
				                    <?php endforeach; ?>
				                </select>
								<span class="drop-down-img" id="arrow1"></span>
							</div>
						</li>
						<li>
							<label>地&nbsp;区</label>
							<div class=" drop-down">
								<select class="infor-input infor-select-small" name="city" id="city">
				                    <?php foreach($city_name as $v):?>
				                        <option value="<?=$v['city_code']?>" ><?=$v['city_name']?></option>
				                    <?php endforeach; ?>

				                </select>
				                <span class="drop-down-img" id="arrow2" style="right: 2.5rem;"></span>

							</div>
							
							<div class=" drop-down">
								<select class="infor-input infor-select-small" style="margin-left: .1rem;" name="area" id="county" >
				                    <?php foreach($area_name as $v):?>
				                        <option value="<?=$v['area_code']?>" ><?=$v['area_name']?></option>
				                    <?php endforeach; ?>
				                </select>
				                <span class="drop-down-img" id="arrow3" style="right: .1rem;"></span>
							</div>
			                
						</li>
						<li>
							<label style="line-height: .4rem;">详&nbsp;细<br/>地&nbsp;址</label>
							<textarea class="infor-text" maxlength="300" name="street" id="street" placeholder=""><?= $street?></textarea>
						</li>
					</ul>
					
					<div class="ture tips-label">
						温馨提示：请填写真实地址详情，填写后点击提交按钮即为填写成功，提交后24小时内可进行修改
					</div>
					<button id="submit_btn" class="submit-btn"></button>

				</div>

			</div>
			<input type="hidden" id="uid" name="uid" value="<?=$uid?>">
            <input type="hidden" id="id" value="<?=$id?>">
            <input type="hidden" id="save_status" value="<?=$save_status?>">
            <input type="hidden" id="activ" value="<?=$activ?>">
		</form>

		<script src="<?= base_url('static/layui/layui.js') ?>"></script>
		<script type="application/javascript">
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
		    $(document).ready(function(){

		        var save_status = document.getElementById('save_status').value;
		        if(save_status==3){
		            $('.submit-btn').css('display','none');
		            // $('.submit').css('display','none');
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

		            $.post("<?= base_url()?>api/address/get_address",
		                {
		                    name:name,
		                    province_code:province_code,
		                    city_code:city_code,
		                    area_code:area_code
		                },
		                function(data){
		                	console.log(data);
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

		        $('.submit-btn').click(function(){

		            var truename = $('#truename').val();
		            var uid = $('#uid').val();
		            var id = $('#id').val();
		            var phone = $('#phone').val();
		            var street = $('#street').val();
	                province = document.getElementById("province").options[0].text;
	                city = document.getElementById("city").options[0].text;
	                area = document.getElementById("county").options[0].text;
		           
	                if (truename == '' || phone == '' || street == '') {
	                	$("#msg").css('display','block');
				        var pass = document.getElementById('msg');
				        pass.innerHTML = "请填写完整信息";
				        setTimeout(function(){
				            $("#msg").css('display','none');
				        },2000);
				        return false;
	                }

	                if(!(/^1[3456789]\d{9}$/.test(phone))){ 
		                $("#msg").css('display','block');
				        var pass = document.getElementById('msg');
				        pass.innerHTML = "手机号码有误";
				        setTimeout(function(){
				            $("#msg").css('display','none');
				        },2000);
		                return false; 
		            }

		            $.ajax({
		                url:"<?= base_url()?>api/scrape/savemessage",
		                type:'post',
		                data:{
		                    save_status:save_status,
		                    uid:uid,
		                    id:id,
		                    truename:truename,
		                    phone:phone,
		                    province:province,
		                    city:city,
		                    area:area,
		                    street:street,
		                    province_code:province_code,
		                    city_code:city_code,
		                    area_code:area_code,
		                    activ:activ
		                },
		                beforeSend:function(){
		                    $('#submit_btn').attr('disabled',"true");
		                    $("#msg").css('display','block');
					        var pass = document.getElementById('msg');
					        pass.innerHTML = "提交中...";
					        setTimeout(function(){
					            $("#msg").css('display','none');
					        },2000);
		                },
		                success:function (data) {
		                    var dataObj=eval("("+data+")");//转换为json对象
		                    alertfun(dataObj);
		                },
		                complete: function () {
					        //$('#submit_btn').removeAttr("disabled"); 
		                    $("#msg").css('display','block');
					        var pass = document.getElementById('msg');
					        pass.innerHTML = "提交成功";
					        setTimeout(function(){
					            $("#msg").css('display','none');
					        },2000);
					    },
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
		       redirect('<?= base_url()?>/api/main/index', 1);
		    }


			function redirect(url, time) {
			    setTimeout("window.location='" + url + "'", time * 1000);
			}

			//验证电话号码
		    function checkPhone(obj){ 
		        var id = obj.id;
		        var phone = document.getElementById(id).value;
		        if (phone) {
		            if(!(/^1[3456789]\d{9}$/.test(phone))){ 
		                $("#msg").css('display','block');
				        var pass = document.getElementById('msg');
				        pass.innerHTML = "手机号码有误";
				        setTimeout(function(){
				            $("#msg").css('display','none');
				        },2000);
		                return false; 
		            }
		        }
		        else{
		            $("#msg").css('display','block');
			        var pass = document.getElementById('msg');
			        pass.innerHTML = "请填写手机号码";
			        setTimeout(function(){
			            $("#msg").css('display','none');
			        },2000);
	                return false; 
		        }
		        
		    }



		</script>
		
		
	</body>
</html>
