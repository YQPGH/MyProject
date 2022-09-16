<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>乐豆中心水晶烟灰缸获奖明细</span>
        <form action="<?= $this->baseurl.'ashtray_list' ?>" method="post" style="float: right">
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
            <th width="100">OPENID</th>
            <th width="80">用户昵称</th>
            <th width="60">用户姓名</th>
            <th width="80">奖品类型</th>
            <th width="70">手机号</th>
            <th width="150">邮寄地址</th>
            <th width="20">数量</th>
            <th width="50">快递</th>
            <th width="100">快递单号</th>
            <th width="100">获得时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['openid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['truename'] ?></td>
                <td><?= $value['prize'] ?></td>
                <td><?= $value['phone'] ?></td>
                <td><?= $value['address'];?></td>
                <td><?= $value['num'] ?></td>
                <td><?= $value['express'] ?></td>
                <td><?= $value['odd_numbers'] ?></td>
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


</script>
</body>
</html>
<!--code by tangjian-->