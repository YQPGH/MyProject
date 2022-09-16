<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <!--<a href="<?/*= $this->baseurl . 'add' */?>" class="layui-btn btn-add">+ 添加信息</a>-->
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
            <th width="100">用户ID</th>
            <th width="100">用户昵称</th>
            <th width="80">订单ID</th>
            <th width="80">订单名称</th>
            <th width="80">订单星级</th>
            <th width="150">订单商品</th>
            <th width="80">商品数量</th>
            <th width="80">奖励经验</th>
            <th width="80">奖励乐币</th>
            <th width="">记录时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['order_id'] ?></td>
                <td><?= $value['order_name'] ?></td>
                <td><?= $value['type1'] ?></td>
                <td><?= $value['shop_name'] ?><br><?= $value['shop_name']?$value['shopid']:''?></td>
                <td><?= $value['shop_count'] ?></td>
                <td><?= $value['game_xp'] ?></td>
                <td><?= $value['money'] ?></td>
                <td><?= $value['add_time'] ?></td>
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
        var layer = layui.layer, form = layui.form();
        var element = layui.element();
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到

        // 点击更改状态
        $(".update-status").click(function () {
            var tid = $(this).attr("name");
            var mystatus = '';
            if ($(this).text() == "正常") {
                mystatus = 1;
                $(this).text("锁定");
                $(this).addClass("red");
            } else {
                mystatus = 0;
                $(this).text("正常");
                $(this).removeClass("red");
            }
            $.get("<?=$this->baseurl . 'update_status'?>", {id: tid, status: mystatus}, function (data) {
                console.log(data);
            });
        });
    });

</script>
</body>
</html>
<!--code by tangjian-->