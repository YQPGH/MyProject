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
            <th width="80">日期</th>
            <th width="80">活跃用户</th>
            <th width="80">新用户</th>
            <th width="80">乐币交易</th>
            <th width="80">乐币交易</th>
            <th width="80">种植数量</th>
            <th width="80">加工数量</th>
            <th width="80">品鉴数量</th>
            <th width="80">小游戏数量</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i=1;$i<=20; $i++): ?>
            <tr>
                <th width="50">2017-06-09</th>
                <th width="80">78563</th>
                <th width="80">236</th>
                <th width="80">123456</th>
                <th width="80">123456</th>
                <th width="80">123456</th>
                <th width="80">123456</th>
                <th width="80">123456</th>
                <th width="80">123456</th>
                </td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <div class="margintop pages">
        信息总数： <?= $count ?>条&nbsp;&nbsp;
        <?= $pages ?>
    </div>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
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