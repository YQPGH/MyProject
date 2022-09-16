<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?=$this->name;?></span>
        <form action="<?= $this->baseurl.'gather_list' ?>" method="post" style="float: right">
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
    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>

            <th width="">用户ID</th>
            <th width="">能量球编号</th>
            <th width="">能量值</th>
            <th width="">能量收取状态</th>
            <th width="">创建时间<br>编辑时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td>
                    <?= $value['uid'] ?>
                </td>
                <td><?= $value['index'] ?> 号能量球</td>
                <td><?= $value['total'] ?></td>
                <td><?= $value['type'] ?></td>
                <td><?= $value['addtime'] ?>
                    <br>
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


</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery ;//由于layer弹层依赖jQuery，所以可以直接得到

    });

</script>
</body>
</html>
<!--code by tangjian-->