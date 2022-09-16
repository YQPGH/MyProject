<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><a href="<?= $this->baseurl.'add'?>" class="layui-btn btn-add">+添加信息</a></span>
        <form action="<?= $this->baseurl.'prize_list' ?>" method="post" style="float: right">
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="layui-form">
<!--                <div class="layui-input-inline w100">-->
<!--                    <select name="field">-->
<!--                        --><?//= getSelect($fields, $field) ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="layui-input-inline">-->
<!--                    <input type="text" name="keywords" class="layui-input " value="--><?//= $keywords ?><!--" id="thumb">-->
<!--                </div>-->
<!--                <input type="submit" name="submit" class="layui-btn" value=" 搜索 ">-->
            </div>
        </form>
    </div>
    <hr>

    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th width="120">任务</th>
            <th width="120">参数</th>
            <th width="100">添加时间/更新时间</th>
            <th width="60">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['name'] ?></td>
                <td>
                    闪电：<?= $value['shandian'] ?><br>
                    银元：<?= $value['money'] ?><br>
                    商品/编号：<?= $value['shop_name'] ?>(<?= $value['shopid']?>)<br>
                    商品数量：<?= $value['shop_num'] ?>
                </td>
                <td>
                    <?= $value['add_time'] ?><br>
                    <?= $value['update_time']?>
                </td>
                <td>
                    <a href="<?= $this->baseurl.'edit?id='.$value['prizeid']?>">编辑</a>
                    <a href="<?= $this->baseurl.'delete?id='.$value['prizeid']?>" onclick="return confim('是否删除？')">删除</a>
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