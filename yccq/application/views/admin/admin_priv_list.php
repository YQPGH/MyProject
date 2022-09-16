<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class="main" id="admin-body">
    <div>
        <!-- <a href="<?= $this->baseurl . 'add' ?>"  class="layui-btn btn-add">+ 添加权限</a> -->
        <a id='add_btn' class="layui-btn btn-add">+ 添加权限</a>
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
            <th width="100">权限名称</th>
            <th width="200">权限标识</th>
            <th width="150">创建</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $value['priv_name'] ?></td>
                <td><?= $value['priv_sign'] ?></td>
                <td><?= $value['add_time'] ?><br></td>
                <td>
                    <a href="javascript:;" onclick="edit(<?=$value['id']?>)">编辑</a>
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

<div id="add_priv" style="display: none;">
    <form class="layui-form layui-form-pane" action='<?=$this->baseurl."save"?>' method="post" style="margin: 50px 30px 0 30px;">
        <input type="hidden" id="priv_id" name="id" value="0">
        <div class="layui-form-item">
            <label class="layui-form-label">父目录</label>
            <div class="layui-input-block">
                <select name="pid" id="priv_pid" lay-verify="required">
                    <option value="0">顶级目录</option>
                    <?php foreach($list as $key => $val){?>
                    <option value="<?=$val['id']?>"><?=$val['priv_name']?></option>
                    <?php }?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-block">
                <input type="text" name="priv_name" id="priv_name" required lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">权限标识</label>
            <div class="layui-input-block">
                <input type="text" name="priv_sign" id="priv_sign" required lay-verify="required" placeholder="请输入权限标识" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('static/layui/layui.js')?>"></script>
<script>
layui.use(['layer', 'form', 'element'], function () {
    var layer = layui.layer, form = layui.form;
    var element = layui.element;
    var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到

    $('#add_btn').click(function(){
        layer.open({
            type:1,
            title:'添加权限',
            scrollbar:false,
            area:['500px','350px'],
            content:$('#add_priv')
        });
    });




});

function edit(id)
{
    var layer = layui.layer,form = layui.form, $ = layui.jquery;
    $.get("<?=$this->baseurl.'getPrivById'?>",{id:id},function(res){
        var data = JSON.parse(res).data;

        $('#priv_id').val(data.id);
        $("#priv_pid").val(data.pid);
        $('#priv_name').val(data.priv_name);
        $('#priv_sign').val(data.priv_sign);
        form.render();
        layer.open({
            type:1,
            title:'编辑权限',
            scrollbar:false,
            area:['500px','350px'],
            content:$('#add_priv')
        });
    });
}

</script>
</body>

</html>
<!--code by tangjian-->
