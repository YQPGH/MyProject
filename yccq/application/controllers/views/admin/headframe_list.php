<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>头像框信息记录</span>

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
            <th width="100">昵称</th>
            <th width="120">用户id</th>
            <th width="120">获得头像框</th>
            <th width="60">数量</th>
            <th width="150">获得时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $key => $value):?>
            <tr>
                <td><?= $value['nickname']?></td>
                <td><?= $value['uid']?></td>
                <td><?= $value['frame'] ?></td>
                <td><?= $value['num']?></td>
                <td><?= $value['add_time']?></td>
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
        var layer = layui.layer, form = layui.form();
        var element = layui.element();
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到


    });
</script>
</body>
</html>
<!--code by tangjian-->