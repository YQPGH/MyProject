<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <a href="<?= $this->baseurl . 'add' ?>" class="layui-btn btn-add">+ 添加信息</a>
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
            <th width="80">档次(龙币)</th>
            <th width="80">原价(乐豆)</th>
            <th width="110">参数</th>
            <th width="110">参数</th>
            <th width="150">发布/编辑时间</th>
            <th width="60">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['level'] ?></td>
                <td><?= $value['ledou'] ?></td>
                <td>道具1：<?= $value['name'] ?><br>
                    数量：<?= $value['num'] ?><br>

                </td>
                <td>道具2：银元<br>
                    数量：<?= $value['money'] ?><br>

                </td>
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
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
<!--code by tangjian-->
