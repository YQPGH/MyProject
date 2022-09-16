<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>劳动光荣 勤劳兴“叶”名单列表</span>
        <form action="<?= $this->baseurl.'name_list' ?>" method="post" style="float: right">
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
            <th width="80">用户昵称</th>
            <!--            <th width="80">用户姓名</th>-->
            <th width="100">奖品/名称类型</th>
            <!--            <th width="150">邮寄地址</th>-->
            <th width="150">获得时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['uid'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <!--                <td>--><?//= $value['truename'] ?><!--</td>-->
                <td><?= $value['money'] ?><br>
                    <?= $value['shandian'] ?><br>
                    <?= $value['shop_name'] ?><br>
                    <?= $value['shop_num'] ?>
                </td>
                <!--                <td>--><?//= $value['address'];?><!--</td>-->
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