<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <a href="<?= $this->baseurl.'listexcelOut' ?>" class="layui-btn"  >导出</a>
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
    <hr >

    <table class="layui-table">
        <thead>
        <tr>
            <th width="">uid/openid</th>
            <th width="">反馈内容</th>
            <th width="">回复内容</th>
            <th width="140">反馈时间</th>
            <th width="140">回复时间</th>
            <th width="50">图片</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody id="show_img" >
        <?php foreach ($list as $key => $value): ?>
            <tr >
                <td><?= $value['uid'] ?><br><?= $value['openid'] ?></td>
                <td><?= $value['content'] ?></td>
                <td><?= $value['r_content'] ?></td>
                <td><?= $value['add_time'] ?></td>
                <td><?= $value['radd_time'] ?><br><?= $value['update_time'] ?></td>
                <td>
                    <?php if(!empty($value['img'])) echo '<img id="s_img"  src='. $value['img'].'  width="50" height="50" >';?>
                </td>
                <td>
                    <a href="<?= $this->baseurl . 'edit?id=' . $value['id'] ?>">回复</a>
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
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script>

    layui.use(['layer', 'form', 'element'], function () {

        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery;//由于layer弹层依赖jQuery，所以可以直接得到

              //图片查看
              layer.photos({
                  photos: '#show_img',
                  anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）

              });


    });


</script>
</body>
</html>
