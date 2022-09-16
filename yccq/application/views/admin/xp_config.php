<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class="main prize">
    <div><a href="javascript:history.back();">
            <返回
        </a> &nbsp;&nbsp; <?= $this->name; ?>
    </div>
    <hr>

    <form action="<?= $this->baseurl . 'save'; ?>" method="post" class="w900 layui-form">
        <?php foreach ($list as $value): ?>
            <div class="layui-form-item">
                <label class="layui-form-label" style="width:150px;"><?= $value['title'] ?></label>
                <div class="layui-input-block" style="margin-left: 200px;">
                    <input type="text" name="value[<?= $value['mkey'] ?>]" value="<?= $value['mvalue'] ?>" class="layui-input">
                </div>
            </div>
        <?php endforeach; ?>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width:150px;"></label>
            <div class="layui-input-block" style="margin-left: 200px;">
                <button type="submit" class="layui-btn" lay-filter="formDemo">立即提交</button>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
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