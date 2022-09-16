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
            <th width="80">编号</th>
            <th width="100">建筑名称</th>
            <th width="80">类型</th>
            <th width="100">参数</th>
            <th width="80">等级开启</th>
            <th width="150">发布时间</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['order'] ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['type'] ?></td>
                <td>
                    瓦石：<?= $value['shopid_num1'] ?><br>
                    木材：<?= $value['shopid_num2'] ?><br>
                    油漆：<?= $value['shopid_num3'] ?>
                </td>
                <td><?= $value['lv'] ?></td>
                <td><?= $value['addtime'] ?><br>
                    <?= $value['updatetime'] ?></td>
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
