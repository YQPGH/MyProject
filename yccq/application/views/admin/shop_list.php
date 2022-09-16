<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <a href="<?= $this->baseurl . 'add' ?>" class="layui-btn btn-add">+ 添加信息</a>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
            <div class="layui-form">
                <div class="layui-input-inline w100">
                    <select name="type1">
                        <option value="0">分类</option>
                        <?= getSelect(config_item('shop_type1'), $type1) ?>
                    </select>
                </div>
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
            <th width="100">名称/编号</th>
            <th width="110">参数</th>
            <th width="110">参数</th>
            <th width="110">参数</th>
            <th width="150">参数</th>
            <th>附属字段</th>
            <th width="150">发布/编辑时间</th>
            <th width="60">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['name'] ?><br>(<?= $value['shopid'] ?>)</td>
                <td>分类1：<?= $value['type1'] ?><br>
                    分类2：<?= $value['type2'] ?><br>
                    商行 ：<?= $value['status'] ?>
                </td>
                <td>
                    乐币：<?= $value['money'] ?><br>
                    乐豆：<?= $value['ledou'] ?><br>
                    回购：<?= $value['back_money']?>
                </td>
                <td>
                    库存：<?= $value['total'] ?><br>
                    交易：<?= $value['sales'] ?><br>
                    耗时：<?= $value['work_time'] ?>秒<br>
                    经验：<?= $value['xp'] ?><br>
                </td>
                <td>
                    开放等级：<?= $value['open_lv'] ?><br>
                    简介：<?= $value['description'] ?><br>
                    图片：<?= $value['thumb'] ?>
                </td>
                <td><?= $value['json_data'] ?></td>
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
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
