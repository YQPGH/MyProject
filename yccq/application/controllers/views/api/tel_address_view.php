<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="<?= base_url('static/assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('static/assets/css/style.css') ?>">
</head>
<body>
<div class="register-container container">
    <div>
        <div class="register span4">
            <form action="" method="post">
                <input type="hidden" id="my_id" name="id" value="<?= $value['id']?>">
                <h4>奖品名称： <span class="red"><strong><?= $value['name']?></strong></span></h4>
                <label for="firstname">真实姓名</label>
                <input type="text" id="firstname" name="firstname" placeholder="请输入姓名..." value="<?= $value['truename'] ?>">
                <label for="adreess">地址</label>
                <input type="text" id="adreess" name="adreess" placeholder="请输入地址..." value="<?= $value['address'] ?>">
                <label for="tel">电话</label>
                <input type="text" id="tel" name="tel" placeholder="请输入电话..." value="<?= $value['tel'] ?>" >
                <button type="button" id="my_button">提交</button>
            </form>
        </div>
    </div>
</div>
<!-- Javascript -->
<script src="<?= base_url('static/assets/js/jquery-1.8.2.min.js') ?>"></script>
<script src="<?= base_url('static/assets/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $("#my_button").on("click",function(){
            var id = $("#my_id").val();
            var truename = $("#firstname").val();
            var tel = $("#tel").val();
            var address = $("#adreess").val();
            $.ajax({
                url:"http://yccq.zlongwang.com/server/api/Redirect/save",
                //url:"http://192.168.1.217/yccq/api/Redirect/save",
                type: "post",         //数据发送方式
                dataType:"json",    //接受数据格式
                data:{id:id,truename:truename,tel:tel,address:address},  //要传递的数据
                success:function(res){
                    if(res.code==0){
                        alert("保存成功");
                        window.location.href = "http://yccq.zlongwang.com/server/api/Main/index";
                        //window.location.href = "http://192.168.1.178/YCCQ_NEW/bin/index.html";
                        //console.log(res.data);
                    }else{
                        alert("失败！");
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                    //alert(errorThrown);
                }
            });
        });
    })
</script>
</html>