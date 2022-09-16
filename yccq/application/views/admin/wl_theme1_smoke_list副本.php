<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    
    <div class="top">
        <span>游戏公测活动方案样品烟B邮寄名单</span>
        <form action="<?= $this->baseurl.'theme_list_1' ?>" method="post" style="float: right">
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
            <th width="50">序号</th>
            <th width="100">OPENID</th>
            <th width="80">用户昵称</th>
            <th width="60">用户姓名</th>
            <th width="70">手机号</th>
            <th width="150">邮寄地址</th>
            <th width="80">奖品类型</th>
            <th width="20">数量</th>
            <th width="100">领取时间</th>
            <th width="50">发货状态</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td><?= $value['openid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['truename'] ?></td>
                <td><?= $value['phone'] ?></td>
                <td><?= $value['address'];?></td>
                <td><?= $value['prize'] ?></td>
                <td><?= $value['num'] ?></td>
                <td><?= $value['add_time'] ?></td>
                <td><?= $value['status'] ?></td>
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