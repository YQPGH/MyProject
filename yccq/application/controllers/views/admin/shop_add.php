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
            <div class="layui-inline">
                <label class="layui-form-label">* 物品名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[name]" value="<?= $value['name'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">* 编号</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shopid]" value="<?= $value['shopid'] ?>" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"> 数字格式</div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">分类1</label>
                <div class="layui-input-inline">
                    <select name="value[type1]">
                        <?= getSelect(config_item('shop_type1'), $value['type1']) ?>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">分类2</label>
                <div class="layui-input-inline">
                    <select name="value[type2]" id="gender">
                        <?= getSelect(config_item('shop_type2'), $value['type2']) ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">商行售卖</label>
                <div class="layui-input-inline">
                    <select name="value[status]" id="gender">
                        <?= getSelect(config_item('shop_status'), $value['status']) ?>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">经验值</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[xp]" value="<?= $value['xp'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">出售乐币</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[money]" value="<?= $value['money'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">出售乐豆</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[ledou]" value="<?= $value['ledou'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">回收乐币</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[back_money]" value="<?= $value['back_money'] ?>" class="layui-input">
                </div>
            </div>

        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">库存</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[total]" value="<?= $value['total'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">工作时长</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[work_time]" value="<?= $value['work_time'] ?>"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">秒</div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">开放等级</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[open_lv]" value="<?= $value['open_lv'] ?>"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">图片</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[thumb]" value="<?= $value['thumb'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">简介</label>
            <div class="layui-input-block">
                <textarea name="value[description]" class="layui-textarea"
                          style="width: 600px;"><?= $value['description'] ?></textarea>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">附属字段<br>JSON格式</label>
            <div class="layui-input-block">
                <textarea name="value[json_data]" class="layui-textarea"
                          style="width: 600px;"><?= $value['json_data'] ?></textarea>
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
        var layer = layui.layer, form = layui.form();
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到
        var element = layui.element();

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