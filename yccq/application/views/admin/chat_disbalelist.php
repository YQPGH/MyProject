<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?=$this->name;?></span>
        <form action="<?= $this->baseurl.'disbale_list' ?>" method="post" style="float: right">
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

            <th width="">openID
<!--                <br>用户ID-->
            </th>
            <th width="">被禁好友openID
<!--                <br>被禁好友ID-->
            </th>
            <th width="">禁言状态</th>
            <th width="">禁言时间</th>
            <th width="">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td>
                    <?= $value['openid'] ?>
<!--                    <br>-->
<!--                    --><?//= $value['uid'] ?>

                </td>
                <td>
                    <?= $value['friend_openid'] ?>
<!--                    <br>-->
<!--                    --><?//= $value['friend_uid'] ?>
                </td>
                <td><?= $value['status'] ?></td>
                <td><?= $value['update_time'] ?></td>
                <td>
                    <input type="button"  id="update-status" class="layui-btn layui-btn-xs " name="<?=$value['id']?>"  value=" 解除禁言">
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
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery ;//由于layer弹层依赖jQuery，所以可以直接得到

        // 点击更改状态
        $("#update-status").click(function () {
            var tid = $(this).attr("name");
            $.post("<?=$this->baseurl . 'update_status'?>", {id: tid}, function (res) {
                var data = JSON.parse(res);

                if(data.code=="0")
                {
                    $("#update-status").addClass("layui-btn-disabled");
                }
                else
                {
                    alert(data.msg)
                }

            });
        });
    });

</script>
</body>
</html>
<!--code by tangjian-->