<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>年年有金叶奖品信息</span>
        <form action="<?= $this->baseurl.'leaf_prize' ?>" method="post" style="float: right">
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="layui-form">
                <div class="layui-input-inline w100">
<!--                    <select name="field">-->
<!--                        --><?//= getSelect($fields, $field) ?>
<!--                    </select>-->
                </div>
                <div class="layui-input-inline">
<!--                    <input type="text" name="keywords" class="layui-input " value="--><?//= $keywords ?><!--" id="thumb">-->
                </div>
<!--                <input type="submit" name="submit" class="layui-btn" value=" 搜索 ">-->
            </div>
        </form>
    </div>
    <hr>

    <table class="layui-table" >
        <thead>
        <tr>
            <th width="130">奖品类型</th>
            <th width="80">奖品数量</th>
            <th width="100">奖品抽奖次数</th>
            <th width="100">已填写地址人数</th>
            <th width="100">实发数量</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['name'] ?></td>
                <td><?= $value['number'] ?></td>
                <td><?= $value['num'] ?></td>
                <td><?= $value['address_num'] ?></td>
                <td><?= $value['address_num'] ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="margintop pages">
<!--        信息总数： --><?//= $count ?><!--条&nbsp;&nbsp;-->
<!--        --><?//= $pages ?>
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