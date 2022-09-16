<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class="main prize">
    <div><a href="javascript:history.back();">
            <返回
        </a> &nbsp;&nbsp; 编辑信息
    </div>
    <hr>

    <form action="<?= $this->baseurl . 'save'; ?>" method="post" class="w900 layui-form">
        <input type="hidden" name="id" value="<?= $value['id'] ?>">
        <input type="hidden" id="my-shop" value="">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">* 订单名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[name]" value="<?= $value['name'] ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">类别</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[type1]" value="<?= $value['type1'] ?>" class="layui-input">
                </div>

            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">需要商品id</label>
                <div class="layui-input-inline">
                    <input id="shop_id_1"  data-method="setTop" type="text" name="value[shopid]" value="<?= $value['shopid'] ?>" class="layui-input my-btn">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">商品数量</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[shop_count]" value="<?= $value['shop_count'] ?>" class="layui-input">
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
            <div class="layui-inline">
                <label class="layui-form-label">奖励经验值</label>
                <div class="layui-input-inline">
                    <input type="text" name="value[game_xp]" value="<?= $value['game_xp'] ?>" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
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
                <textarea name="value[content]" class="layui-textarea"
                          style="width: 600px;"><?= $value['content'] ?></textarea>
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

        //触发事件
        var active = {
            setTop: function(){
                var that = this;
                //多窗口模式，层叠置顶
                layer.open({
                    type: 2 //此处以iframe举例
                    ,title: '选择商品'
                    ,area: ['900px', '320px']
                    ,shade: 0
                    ,offset: 'rt'
                    ,id: 'my-layer'
                    ,maxmin: true
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