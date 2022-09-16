<?php $this->load->view('admin/header'); ?>

<style>
    .prize .layui-form-item .layui-form-label {
        width: 100px;
    }

    .prize .layui-form-item .layui-input-inline {
        width: 220px;
    }
</style>

<!--右侧布局-->
<div class="main prize">
    <div><a href="javascript:history.back();">
            <返回
        </a> &nbsp;&nbsp; 编辑信息
    </div>
    <hr>

    <form action="<?= $this->baseurl . 'save'; ?>" method="post" class="w900 layui-form">
        <input type="hidden" name="id" value="<?= $value['id'] ?>">

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">* 奖品名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[name]" value="<?= $value['name'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">* 关卡数</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[pass]" value="<?= $value['pass'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励乐币</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[money]" value="<?= $value['money'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品等级</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop1]" value="<?= $value['shop1'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop1_total]" value="<?= $value['shop1_total'] ?>"
                           class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品2等级</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop2]" value="<?= $value['shop2'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品2数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop2_total]" value="<?= $value['shop2_total'] ?>"
                           class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">获取几率</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[rate]" value="<?= $value['rate'] ?>" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">比如中奖率20%，填20</div>
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
<!--<script src="<?/*= base_url('static/admin/js/admin.js') */?>"></script>-->
<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'element', 'layedit', 'upload','laydate'], function () {
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