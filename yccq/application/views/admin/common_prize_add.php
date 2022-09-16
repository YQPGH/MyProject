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

    <form action="<?= $this->baseurl . 'common_prize_save'; ?>" method="post" class="w900 layui-form">
        <input type="hidden" name="id" value="<?= $value['id'] ?>">
        <input type="hidden" name="value[type2]" value="<?= $type2 ?>">
        <input type="hidden" id="my-shop" value="">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">* 奖品名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[name]" value="<?= $value['name'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">* 获取说明</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[title]" value="<?= $value['title'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">分类1</label>
                <div class="layui-input-inline">
                    <select name="value[type1]">
                        <?= getSelect(config_item('prize_type'), $value['type1']) ?>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励银元</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[money]" value="<?= $value['money'] ?>" class="layui-input">
                </div>
            </div>


        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励闪电</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shandian]" value="<?= $value['shandian'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励乐豆</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[ledou]" value="<?= $value['ledou'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品1-ID</label>
                <div class="layui-input-inline">
                    <input id="shop_id_1"  data-method="setTop"  type="text" name="value[shop1]" value="<?= $value['shop1'] ?>" class="layui-input my-btn">
                    <!--<button data-method="setTop" class="layui-btn">选 择</button>-->
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品1数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop1_total]" value="<?= $value['shop1_total'] ?>"
                           class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品2-ID</label>
                <div class="layui-input-inline">
                    <input id="shop_id_2" data-method="setTop" type="text" name="value[shop2]" value="<?= $value['shop2'] ?>" class="layui-input my-btn">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品2-数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop2_total]" value="<?= $value['shop2_total'] ?>"
                           class="layui-input">
                </div>
            </div>
        </div>

        <!--<div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品3-ID</label>
                <div class="layui-input-inline">
                    <input id="shop_id_3" data-method="setTop" type="text" name="value[shop3]" value="<?/*= $value['shop3'] */?>" class="layui-input my-btn">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">奖励商品3-数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop3_total]" value="<?/*= $value['shop3_total'] */?>"
                           class="layui-input">
                </div>
            </div>
        </div>-->

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-filter="formDemo">立即提交</button>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
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

        //触发事件
        var active = {
            setTop: function(){
                var that = this;
                //多窗口模式，层叠置顶
                layer.open({
                    type: 2 //此处以iframe举例
                    ,title: '选择商品'
                    ,area: ['900px', '350px']
                    ,shade: 0
                    ,id: 'my-layer'
                    ,content: '<?=site_url('admin/prize/get_shopid')?>'
                    ,yes: function(layero, index){

                    }
                    ,zIndex: layer.zIndex //重点1
                    ,success: function(layero,index){
                        var body = layer.getChildFrame('body', index);
                        var iframeWin = window[layero.find('iframe')[0]['name']]; //得到iframe页的窗口对象，执行iframe页的方法：
                        //iframeWin.method();
                        //console.log(body.html()) //得到iframe页的body内容
                        //body.find('button').val('Hi，我是从父页来的')
                    }

                });
            }

        };

        $('.my-btn').on('click', function(){
            var id = $(this).attr('id');
            $("#my-shop").val(id);
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });


    });
</script>
</body>
</html>
<!--code by tangjian-->