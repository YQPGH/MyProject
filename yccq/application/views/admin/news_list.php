<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><a href="<?= $this->baseurl . 'add' ?>" class="layui-btn btn-add show">+ 添加信息</a></span>
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
            <th width="50">缩略图</th>
            <th>标题</th>
            <th width="60">阅读</th>
            <th width="150">发布人</th>
            <th width="150">发布/编辑时间</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><img src="<?= $value['thumb'] ?>" width="50" height="30"></td>
                <td><?= $value['title'] ?></td>
                <td><?= $value['visit'] ?></td>
                <td><?= $value['auther'] ?></td>
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <div class="<?=permission('news_edit')?>">
                    <a href="javascript:" title="点击更改状态" class="update-status <?php if ($value['status'] == 1) {
                        echo 'red';
                    } ?>" name="<?= $value['id'] ?>"><?= config('status', $value['status']) ?></a>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
                    <a href="<?= $this->baseurl . 'delete?id=' . $value['id'] ?>"
                       onClick="return confirm('确定要删除吗？');">删除</a>
                        </div>
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
            $.ajax({
                url:"<?=$this->baseurl . 'update_status'?>",
                type:'post',
                dataType:"json",
                data:{
                    id: tid,
                    status: mystatus,
                },
                success:function (data){
                    console.log(data);
                }
            })
        });
    });

</script>
</body>
</html>
<!--code by tangjian-->
