<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?= $this->name; ?></span>
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
            <th width="100">用户ID/ opneid</th>
            <th width="120">用户昵称</th>
            <th width="80">奖励一</th>
            <th width="80">奖励二</th>
            <th width="120">奖励三 /数量</th>
            <th width="150">奖励时间</th>
            <th width="60">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td>
                    <?= $value['uid'] ?><br>
                    <?= $value['openid'] ?>
                </td>
                <td><?= $value['nickname'] ?></td>
                <td>闪电：<?= $value['shandian'] ?></td>
                <td>银元：<?= $value['yinyuan'] ?></td>
                <td>
                    商品1：<?= $value['shop1'] ?>*<?= $value['shop1_total'] ?><br>
                    商品2：<?= $value['shop2'] ?>*<?= $value['shop2_total'] ?><br>
                </td>
                <td><?= $value['add_time'] ?></td>
                <td>
                    <a href="<?= $this->baseurl . 'table_delete?id=' . $value['id'] ?>"
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