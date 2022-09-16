<!DOCTYPE html>
<html >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
<meta name="applicable-device" content="pc,mobile">
<meta http-equiv="Cache-Control" content="no-transform ">
<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<title>意见反馈</title>
<link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/suggestion.css?16">


</head>
<body>
	<div class="bg">
		<a id="close"  href="<?= base_url()?>api/Main/index"></a>
		<form action="<?= base_url()?>api/Suggestion/saveSuggestion" method="post" enctype="multipart/form-data" >
            <input type="hidden" id="uid" name="uid" value="<?=$uid?>">
            <div id="upload_bg">
                <input id="upload_img" type="file" name="file" >
                <input type="text" name="thumb"  value="" id="thumb" readonly="readonly">
            </div>

            <div id="container">

                <textarea name="content" id="content"></textarea>
            </div>

                <input id="check" name="button" type="button" value="" onclick=window.open("<?= base_url()?>api/Suggestion/suggestionList")>
                <input id="sub" name="submit" type="submit" value="" onclick="return Check();">

        </form>
	</div>
 <script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script type="application/javascript">
    function Check(){
        var c =  $('#content').val();
        if(c === '' || c=== null){
            alert('内容不能为空');
            return false;
        }
    }
  $(document).ready(function () {
        $('.bg').css({'width':$(window).width(),'height':$(window).height()});

        /*$('#sub').click(function(){
            var content = $('#content').val();
            var uid = $('#uid').val();
            $.post("<?= base_url()?>api/Main/saveSuggestion",
                {
                    uid:uid,
                    content:content
                },
                function(data){
                    var dataObj=eval("("+data+")");//转换为json对象
                    if(dataObj.code == 0){
                        alert("您的宝贵意见已提交，感谢您的支持！");
                    }else{
                        alert("意见保存失败!");
                    }
                });
        });*/

    });
  layui.use('upload', function() {
      var $ = layui.jquery,
          upload = layui.upload;
      layui.upload({
          elem: '#upload_img',
          url: '<?=site_url('common/upload/image')?>',
          type: 'images', // images video  file audio

          before: function (input) {
              load_index = layer.load(1);
          },
          success: function (data) {
              console.log(data); //上传成功返回值，必须为json格式
              $("#thumb").val(data.data.src);
              layer.close(load_index);
              layer.msg("上传完成");
          }
      });

  });

</script>

</body>
</html>