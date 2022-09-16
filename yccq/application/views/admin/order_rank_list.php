<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <span>订单排行榜</span>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">

            <div class="layui-form">
                <!-- <div class="layui-input-inline w100">
                    <select name="jifen">
                        <option value="0">周数</option>
                        <?= getSelect($arr, $jifen) ?>
                    </select>
                </div> -->
                <div class="layui-input-inline w100">
                    <select name="field"  >
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
            <th width="120">用户id</th>
            <th width="100">昵称</th>
            <th width="80">排名</th>
            <th width="80">银元</th>
            <th width="80">闪电</th>
            <th width="80">商品</th>
            <th width="150">奖励时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['ranking'] ?></td>
                <td><?= $value['yinyuan'] ?></td>
                <td><?= $value['shandian'] ?></td>
                <td>
                    商品1：<?= $value['shop1'] ?>*<?= $value['shop1_total'] ?><br>
                    商品2：<?= $value['shop2'] ?>*<?= $value['shop2_total'] ?><br>
                </td>
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
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到


    });


</script>
</body>
</html>
<!--code by tangjian-->
