<?php $this->load->view('admin/header_css'); ?>

<!--右侧布局-->
<div id="admin-body">
    <form class="layui-form layui-form-pane" action='<?=$this->baseurl."fenpeiSave"?>' method="post">
        <input type="hidden" id="shenji_id" name="id" value="<?=$id?>">
        <table class="layui-table" id="table_list">
            <thead>
            <tr>
                <th>权限名称</th>
                <th>读</th>
                <th>写</th>
                <th>删除</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($priv_list as $key => $value): ?>
                <tr>
                    <td><?= $value['priv_name'] ?></td>
                    <td>
                        <input type="checkbox" name="read[]" value="<?=$value['id']?>" <?=(in_array($value['id'],$group['read']))?'checked=""':''?> lay-skin="primary">
                    </td>
                    <td>
                        <input type="checkbox" name="write[]" value="<?=$value['id']?>" <?=(in_array($value['id'],$group['write']))?'checked=""':''?> lay-skin="primary">
                    </td>
                    <td>
                        <input type="checkbox" name="del[]" value="<?=$value['id']?>" <?=(in_array($value['id'],$group['del']))?'checked=""':''?> lay-skin="primary">
                    </td>
                   
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

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

    /*form.on('submit(formDemo)', function(data){
        console.log(data);
        $.post('<?=$this->baseurl.'fenpeiSave'?>',data.field);
        return false;
    });*/

}); 

</script>
</body>

</html>
<!--code by tangjian-->