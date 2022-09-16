<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?= $this->name; ?></span>
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
            <th width="100">用户ID</th>
            <th width="100">用户昵称</th>
            <th width="60">仓库等级</th>
            <th width="200">烘烤室</th>
            <th width="200">醇化室</th>
            <th width="">加工槽1</th>
            <th width="">加工槽2</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['store_lv'] ?></td>
                <td>
                    <?= $value['bake_status'] ?><br>
                    <?= $value['bake_start'] ?><br>
                    <?= $value['bake_stop'] ?><br>
                    <?= $value['bake_lv'] ?><br>
                </td>
                <td>
                    <?= $value['aging_status'] ?><br>
                    <?= $value['aging_start'] ?><br>
                    <?= $value['aging_stop'] ?><br>
                    <?= $value['aging_lv'] ?><br>
                </td>
                <td>
                    <?= $value['process_status'] ?><br>
                    <?= $value['process_start'] ?><br>
                    <?= $value['process_stop'] ?><br>
                    <?= $value['process_lv'] ?><br>
                </td>
                <td>
                    <?= $value['process_status_2'] ?><br>
                    <?= $value['process_start_2'] ?><br>
                    <?= $value['process_stop_2'] ?><br>
                    <?= $value['process_lv'] ?><br>
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