<?php $this->load->view('admin/header_css'); ?>

<body>
<form action="<?= $this->baseurl . 'save'; ?>" method="post" class="w400 layui-form" style="padding-top:10px; ">
    <input type="hidden" name="id" value="<?= $value['id'] ?>">


    <div class="layui-form-item">
        <label class="layui-form-label">* 用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="value[username]" class="layui-input" value="<?= $value['username'] ?>" readonly>
        </div>

    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="value[password]" class="layui-input" value="" autocomplete="off">
        </div>
        <div class="layui-form-mid layui-word-aux">不修改请留空</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="value[truename]" class="layui-input" value="<?= $value['truename'] ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">电话</label>
        <div class="layui-input-inline">
            <input type="text" name="value[tel]" class="layui-input" value="<?= $value['tel'] ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">电子邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="value[email]" class="layui-input" value="<?= $value['email'] ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-inline">
            <input type="text" name="value[description]" class="layui-input"
                   value="<?= $value['description'] ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" lay-submit class="layui-btn" lay-filter="formDemo">立即提交</button>
        </div>
    </div>
</form>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'element', 'layedit', 'upload'], function () {
        var layer = layui.layer, form = layui.form;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到
        var element = layui.element;

        layui.upload({
            elem: '#btn_thumb',
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

        //创建一个编辑器
        var layedit = layui.layedit;
        layedit.build('editor', {
            'height': 500,
            'uploadImage': {
                url: '<?=site_url('common/upload/image?urlType=absolute')?>', //接口url
                type: 'post' //默认post
            }
        });
    });
</script>
</body>
</html>
<!--code by tangjian-->