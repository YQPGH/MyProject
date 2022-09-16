<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <a href="<?= $this->baseurl.'add'?>" class="layui-btn btn-add"> + 添加信息</a>
        <form action="<?= $this->baseurl.'laxin_config' ?>" method="post" style="float: right">
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
            <th width="50">#</th>
            <th width="50">活动类型</th>
            <th width="80">奖品名称</th>
            <th width="100">主题</th>
            <th width="60">奖品id</th>
            <th width="60">奖品数量</th>
            <th width="60">奖品-道具1</th>
            <th width="60">奖品-道具2</th>
            <th width="150">发布/编辑时间</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td><?= $value['type2'] ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['title'] ?></td>
                <td><?= $value['shop1'] ?></td>
                <td><?= $value['shop1_total'] ?></td>
                <td><?= $value['money'] ?></td>
                <td><?= $value['shandian'] ?></td>
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
                    <a href="<?= $this->baseurl . 'table_delete?id=' . $value['id'] ?>"
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
<!--code by tangjian-->
