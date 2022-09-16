<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">


    <div class="layui-btn-container" >
        <button type="button" class="layui-btn  layui-btn-normal layui-btn-sm" id="excelfile">选择文件</button>
        <button type="button" class="layui-btn layui-btn-sm" id="excelIn">资产导入</button>

    </div>
    <div id="file-name">

    </div>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script>
    //一般直接写在一个js文件中
    var UPLOAD_FILES;
    layui.use(['table', 'layer','upload','element','form'], function(){
        var layer = layui.layer;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element,upload = layui.upload;
        var $ = layui.jquery ;//由于layer弹层依赖jQuery，所以可以直接得到

        upload.render({
            elem: '#excelfile'
            ,url: "<?= $this->baseurl.'excelIn'?>" //上传接口
            ,accept: 'file' //普通文件
            ,multiple: true
            ,auto: false  //选完文件后不自动上传
            ,bindAction: '#excelIn'
            , choose: function (obj) {
                UPLOAD_FILES = obj.pushFile();
//                clearFile();    //将所有文件先删除再说
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#file-name').append('<span >'+file.name+'</span>');
                });
            }
            ,done: function(res){
                layer.msg('上传成功');
                $('#file-name').empty();
                clearFile();

            }
        });


    });
</script>
</body>
</html>
<!--code by tangjian-->