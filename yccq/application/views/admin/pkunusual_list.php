<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>异常信息</span>
        <form action="<?= site_url('admin/game_unusual') ?>" method="post" style="float: right">
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
            <th width="30">排序	</th>
            <th width="100">游戏记录ID</th>
            <th width="60">头像</th>
            <th width="100">openid</th>
            <th width="100">用户昵称</th>
            <th width="100">异常类型</th>
            <th width="100">异常描述</th>
            <th width="90">ip</th>
            <th width="120">时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value['game_id'] ?></td>
                <td><img src="<?= $value['local_img'] ?>" width="60"></td>
                <td><?= $value['openid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['error_type'] ?></td>
                <td><?= $value['description'] ?></td>
                <td><?= $value['ip'] ?></td>
                <td><?= $value['add_time'] ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="margintop pages">
        信息总数： <?= $count ?>条&nbsp;&nbsp;
        <?= $pages ?>
    </div>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
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
