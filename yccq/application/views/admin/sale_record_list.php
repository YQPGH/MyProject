<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>商店出售记录</span>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
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
            <th width="120">用户ID</th>
            <th width="120">用户昵称</th>
            <!--<th width="120">花费类型</th>-->
            <th width="120">商品名称</th>
            <th width="120">用户花费乐币</th>
            <th width="120">用户花费乐豆</th>
            <th width="120">商店赚取乐币</th>
            <th width="120">商店赚取乐豆</th>
            <!--<th width="120">花费闪电</th>-->
            <th width="150">出售时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <!--<td><?/*= $value['spend_type_name'] */?></td>-->
                <td><?= $value['shop_name'] ?></td>
                <td><?= $value['money'] ?></td>
                <td><?= $value['ledou'] ?></td>
                <td><?= abs($value['money']) ?></td>
                <td><?= abs($value['ledou']) ?></td>
                <!--<td><?/*= $value['shandian'] */?></td>-->
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
