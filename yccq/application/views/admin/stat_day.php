<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?= $this->name; ?></span>
<!--        <a href="javascript:" class="layui-btn" onclick="edit();" >导出</a>-->
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
            <th width="90">日期</th>
            <th width="90">活跃用户</th>
            <th width="90">新增用户</th>
            <th width="90">游戏次数</th>
            <th width="90">银元交易额</th>
            <th width="90">乐豆交易额</th>
            <th width="90">制烟次数</th>
            <th width="90">品吸劵数</th>
            <th width="90">关卡游戏数</th>
<!--            <th width="90">在线时长人数（0~10）分</th>-->
<!--            <th width="90">在线时长人数（10~30）分</th>-->
<!--            <th width="90">在线时长人数（30~60）分</th>-->
<!--            <th width="90">在线时长人数（60~）分</th>-->
            <th width="">刷新时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $value): ?>
            <tr>
                <td><?= $value['stat_day'] ?></td>
                <td><?= $value['active'] ?></td>
                <td><?= $value['new_user'] ?></td>
                <td><?= $value['logins'] ?></td>
                <td><?= $value['money'] ?></td>
                <td><?= $value['ledou'] ?></td>
                <td><?= $value['zhiyan'] ?></td>
                <td><?= $value['ticket'] ?></td>
                <td><?= $value['guanka'] ?></td>
<!--                <td>--><?//= $value['online_minutes1'] ?><!--</td>-->
<!--                <td>--><?//= $value['online_minutes2'] ?><!--</td>-->
<!--                <td>--><?//= $value['online_minutes3'] ?><!--</td>-->
<!--                <td>--><?//= $value['online_minutes4'] ?><!--</td>-->
                <td><?= $value['update_time'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="margintop pages">
        信息总数： <?= $count ?>条&nbsp;&nbsp;<?= $pages ?>
    </div>
</div>
<div id="add_priv" style="display: none;">
    <form class="layui-form layui-form-pane" action="<?= base_url().'admin/stat/listexcelOut' ?>" method="post" >
        <div class="layui-form">
        <div class="layui-form-item">
            <div class="layui-inline ">
               <br>
                <div class="layui-inline" id="time">
                    <div class="layui-input-inline">
                        <input type="text" name="starttime" lay-verify="date" autocomplete="off" id="time-startDate-1" class="layui-input" placeholder="开始日期">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" name="endtime" lay-verify="date" autocomplete="off" id="time-endDate-1" class="layui-input" placeholder="结束日期">
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">

                <button type="submit" class="layui-btn" lay-submit lay-filter="formDemo" >导出</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>


<script src="<?= base_url('static/layui/layui.js')?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>

<script>

    layui.use(["layer", "form", "element","laydate"], function () {
        var laydate = layui.laydate;
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery; //由于layer弹层依赖jQuery，所以可以直接得到

        //日期范围
        laydate.render({
            elem: '#time'
            //设置开始日期、日期日期的 input 选择器
            //数组格式为 2.6.6 开始新增，之前版本直接配置 true 或任意分割字符即可
            ,range: ['#time-startDate-1', '#time-endDate-1']
            ,trigger: 'click'
            ,done:function(value, date)
            {
                document.getElementById('time').value = value;
            }
        });

    });
    function edit()
    {
        var layer = layui.layer, $ = layui.jquery, form = layui.form;
        LayIndex= layer.open({
            type:1,
            title:'日期导出选择',
            scrollbar:false,
            area:['500px','350px'],
            content:$('#add_priv')
        });

    }



</script>
</body>
</html>
<!--code by tangjian-->