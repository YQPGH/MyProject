
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>excel导入</title>

</head>
<body>
<ul id="message">

</ul>

<div>
<form id="form" name="form" action="<?= site_url('admin/excel/excel')?>" method="post" enctype="multipart/form-data">


    <h3 id="count"></h3>
    <h4 id="target" ></h4>
    <h4 id="show_msg"></h4>
    <input id="file" type="file" name="file"/>
    <input  type="submit" value="导入" />
</form>
</div>
<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<script>

//    $(document).ready(function(){
//        add_up();
//    });
//    function add_up()
//    {
//        var obj = document.getElementById("File1");
//        if(obj.value=="")
//        {
//            alert("请选择一个文件");
//            return false;
//        }
//        else
//        {
//            form1.submit()
//        }
//    }
</script>
</body>
</html>

