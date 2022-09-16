<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div><a href="javascript:history.back();">
            <返回
        </a> &nbsp;&nbsp; 编辑信息
    </div>
    <hr>
    <form action="<?= $this->baseurl . 'save'; ?>" method="post" class="w900 layui-form">
        <input type="hidden" name="id" value="<?= $value['id'] ?>">

        <div class="layui-form-item">
            <label class="layui-form-label">分值范围</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['score_min'] ?>">
            </div>
            <div class="layui-form-mid layui-word-aux"> -</div>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['score_max'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">奖励乐币</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['money'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品1</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shopid1'] ?>">
            </div>
            <label class="layui-form-label">获取概率值</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shop1_rate'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品2</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shopid2'] ?>">
            </div>
            <label class="layui-form-label">获取概率值</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shop2_rate'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品3</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shopid3'] ?>">
            </div>
            <label class="layui-form-label">获取概率值</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shop3_rate'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品4</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shopid4'] ?>">
            </div>
            <label class="layui-form-label">获取概率值</label>
            <div class="layui-input-inline">
                <input type="text" name="value[title]" required lay-verify="required" autocomplete="off"
                       
                       class="layui-input" value="<?= $value['shop4_rate'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-filter="formDemo">立即提交</button>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'element', 'layedit', 'upload'], function () {
        var layer = layui.layer, form = layui.form;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到
        var element = layui.element;

        layui.upload({
            elem: '#btn_thumb',
            url: '<?=site_url('common/upload/image')?>',
            type: 'images', // images video  file audio
            before: function (input) {
                load_index = layer.load(1);
            },
            success: function (data) {
                console.log(data); //上传成功返回值，必须为json格式
                $("#thumb").val(data.data.src);
                layer.close(load_index);
                layer.msg("上传完成");
            }
        });

        //创建一个编辑器
        var layedit = layui.layedit;
        layedit.build('editor', {
            'height': 500,
            'uploadImage': {
                url: '<?=site_url('common/upload/image?urlType=absolute')?>', //接口url
                type: 'post' //默认post
            }
        });
    });
</script>
</body>
</html>
<!--code by tangjian-->