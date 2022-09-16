<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div>
        <a href="<?= $this->baseurl . 'add' ?>" class="layui-btn btn-add">+ 添加角色</a>
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

    <table class="layui-table">
        <thead>
        <tr>
            <th>角色名称</th>
            <!-- <th width="">管理栏目</th> -->
            <th>创建/编辑时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['title'] ?></td>
                <!-- <td><?= $value['menu_names'] ?></td> -->
                <td><?= $value['add_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <a href="javascript:;" class="fenpei-btn" data-id="<?=$value['id']?>">分配权限</a>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">编辑</a>
                    <a href="<?= $this->baseurl . 'delete?id=' . $value['id'] ?>"
                       onClick="return confirm('确定要删除吗？');">删除</a>
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
        var layer = layui.layer, form = layui.form();
        var element = layui.element();
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到

        // 点击更改状态
        $(".fenpei-btn").click(function () {
            var group_id = $(this).attr("data-id");
            layer.open({
                type:2,
                title:'权限分配',
                scrollbar:false,
                area:['600px','500px'],
                content:"<?=$this->baseurl.'priv_fenpei'?>?group_id="+group_id
            });
        });
    });

</script>
</body>
</html>
<!--code by tangjian-->