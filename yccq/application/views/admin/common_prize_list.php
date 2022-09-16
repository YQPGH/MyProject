<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>

        <a href="<?= $this->baseurl . 'common_prize_add?type2='.$type2 ?>" class="layui-btn btn-add">+ 添加信息</a>
        <form action="<?= $url ?>" method="post" style="float: right">
            <div class="layui-form">
                <!--<div class="layui-input-inline w150">
                    <select name="type1">
                        <?/*= getSelect(config_item('prize_type'), $type1) */?>
                    </select>
                </div>-->
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
            <th width="150">名称/编号</th>
            <th width="250">参数</th>
            <th width="150">奖励</th>
            <th width="150">奖励</th>
            <th width="150">添加/编辑时间</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['name'] ?><br>
                </td>
                <td>分类1：<?= $value['type1'] ?><br>
                    编号：<?= $value['id'] ?><br>
                    说明 ：<?= $value['title'] ?>
                </td>
                <td>
                    银元：<?= $value['money'] ?><br>
                    乐豆：<?= $value['ledou'] ?><br>
                    闪电：<?= $value['shandian'] ?>
                </td>
                <td>
                    商品1：<?= $value['shop1'] ?>*<?= $value['shop1_total'] ?><br>
                    商品2：<?= $value['shop2'] ?>*<?= $value['shop2_total'] ?><br>
                    经验：<?= $value['xp'] ?>
                </td>
                <!--<td>
                    剩余库存：<?/*= $value['total'] */?><br>
                    获取几率：<?/*= $value['get_rate'] */?><br>
                </td>
                <td>
                    开放等级：<?/*= $value['open_lv'] */?><br>
                    简介：<?/*= $value['description'] */?><br>
                    图片：<?/*= $value['thumb'] */?>
                </td>-->
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <a href="<?= $this->baseurl . 'common_prize_edit?id=' . $value['id'] ?>">编辑</a>
                    <a href="<?= $this->baseurl . 'common_prize_delete?id=' . $value['id'] ?>" onClick="return confirm('确定要删除吗？');">删除</a>
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
<!--<script src="<?/*= base_url('static/admin/js/admin.js') */?>"></script>-->
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
