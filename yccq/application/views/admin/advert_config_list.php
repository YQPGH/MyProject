<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <a href="<?= $this->baseurl.'add'?>" class="layui-btn btn-add"> + 添加信息</a>
        <form action="<?= $this->baseurl.'config_list' ?>" method="post" style="float: right">
            <input type="hidden" name="catid" value="<?= $catid ?>">
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
            <th width="80">活动排序</th>
            <th width="80">活动名称</th>
            <th width="50">广告状态</th>
            <th width="70">广告地址</th>
            <th width="50">类型</th>
            <th width="70">活动地址</th>
            <th width="160">活动简介</th>
            <th width="130">发布/编辑时间</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['arcrank'] ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['status'] ?></td>
                <td><?= $value['img'] ?></td>
                <td><?= $value['type'] ?></td>
                <td><?= $value['action'] ?></td>
                <td><?= $value['intro'] ?></td>
                <td>
                    <?= $value['add_time'] ?><br>
                    <?= $value['update_time'] ?>
                </td>
                <td>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
                    <a href="<?= $this->baseurl . 'delete?id=' . $value['id'] ?>"
                       onClick="return confirm('确定要删除吗？');">删除</a>
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
<!--code by y2020-->
