<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><a href="<?= $this->baseurl . 'add' ?>"
                 class="layui-btn btn-add <?= permission('news_edit') ?>">+ 添加信息</a></span>
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
            <th width="30">#</th>
            <th width="150">分值范围</th>
            <th width="80">奖励乐币</th>
            <th width="80">商品1/概率</th>
            <th width="80">商品2/概率</th>
            <th width="80">商品3/概率</th>
            <th width="80">商品4/概率</th>
            <th width="">发布/编辑时间</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['id'] ?></td>
                <td><?= $value['score_min'] . '-' . $value['score_max'] ?>分</td>
                <td><?= $value['money'] ?></td>
                <td><?= $value['shopid1'] ?><br><?= $value['shop1_rate'] ?></td>
                <td><?= $value['shopid2'] ?><br><?= $value['shop2_rate'] ?></td>
                <td><?= $value['shopid3'] ?><br><?= $value['shop3_rate'] ?></td>
                <td><?= $value['shopid4'] ?><br><?= $value['shop4_rate'] ?></td>
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <div class="<?= permission('news_edit') ?>">
                        <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
                    </div>
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
