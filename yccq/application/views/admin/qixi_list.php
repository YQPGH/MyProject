<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>七夕活动列表</span>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
            <div class="layui-form">
                <div class="layui-input-inline w100">
                    <select name="field">
                        <?= getSelect($fields, $field) ?>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="keywords" class="layui-input " value="<?= $keywords ?>" id="thumb">
                </div>
                <input type="submit" name="submit" class="layui-btn" value=" 搜索 ">
            </div>
        </form>
    </div>
    <hr>

    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th width="">用户ID</th>
            <th width="">用户昵称</th>
            <th width="">角色</th>
            <th width="">骰子数量</th>
            <th width="">当前停留格子数</th>
            <th width="">相会状态</th>
            <th width="">创建时间/编辑时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['role'] ?></td>
                <td><?= $value['num'] ?></td>
                <td> <?= $value['position'] ?></td>
                <td>
                    <?= $value['status'] ?>
                </td>
                <td><?= $value['addtime'] ?><br>
                    <?= $value['updatetime'] ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="margintop pages">
        信息总数： <?= $count ?>条&nbsp;&nbsp;
        <?= $pages ?>
    </div>
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