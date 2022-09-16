<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>种子培育记录</span>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="layui-form">
                <div class="layui-input-inline w100" >
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
            <th width="100">用户ID</th>
            <th width="100">用户昵称</th>
            <th width="100">培育巢编号</th>
            <th width="100">烟叶1</th>
            <th width="100">烟叶2</th>
            <th width="100">种子</th>
            <th width="50">状态</th>
            <th width="150">开始时间</th>
            <th width="">结束时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['number'] ?></td>
                <td><?= $value['yanye1_name'] ?><br><?= $value['yanye1_name']?$value['yanye1']:''?></td>
                <td><?= $value['yanye2_name'] ?><br><?= $value['yanye2_name']?$value['yanye2']:''?></td>
                <td><?= $value['seed_name'] ?><br><?= $value['seed_name']?$value['seed']:''?></td>
                <td><?= $value['status'] ?></td>
                <td><?= $value['start_time'] ?></td>
                <td><?= $value['stop_time'] ?></td>
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
