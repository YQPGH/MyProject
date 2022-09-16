<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <a href="<?= $this->baseurl . 'add' ?>" class="layui-btn btn-add">+ 添加栏目</a>
    </div>
    <hr>

    <ul class="category" style="line-height: 25px;">
        <?= $tree ?>
    </ul>

</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到


    });
</script>
</body>
</html>
<!--code by tangjian-->
