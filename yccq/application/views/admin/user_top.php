<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>玩家排行信息</span><br>

        <a href="<?=site_url('admin/user/top?order=game_lv')?>" style="margin-right: 20px;">按等级排行</a>
        <a href="<?=site_url('admin/user/top?order=ledou')?>" style="margin-right: 20px;">按乐豆排行</a>
        <a href="<?=site_url('admin/user/top?order=money')?>" style="margin-right: 20px;">按乐币排行</a>

        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
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
            <th width="30">序号</th>
            <th width="70">头像</th>
            <th width="80">昵称</th>
            <th width="120">uid/openid</th>
            <th width="120">游戏信息</th>
            <th width="120">游戏信息</th>
            <th>姓名/电话/地址</th>
            <th width="50">状态</th>
            <th width="150">注册/最近登录时间</th>
            <th width="50">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key => $value): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td><img src="<?= $value['local_img'] ?>" width="60"></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['uid'] ?><br><?= $value['openid'] ?></td>
                <td>
                    等级：<?= $value['game_lv'] ?><br>
                    经验值：<?= $value['game_xp'] ?><br>
                    乐币：<?= $value['money'] ?><br>
                    乐豆：<?= $value['ledou'] ?><br>
                </td>
                <td>
                    烟农成就：<?= $value['yannong_lv'] ?><br>
                    交易成就：<?= $value['jiaoyi_lv'] ?><br>
                    品鉴成就：<?= $value['pinjian_lv'] ?><br>
                    制烟成就：<?= $value['zhiyan_lv'] ?><br>
                </td>
                <td>
                    <?= $value['truename'] ?><br>
                    <?= $value['tel'] ?><br>
                    <?= $value['address'] ?>
                </td>
                <td><?= $value['status'] ?></td>
                <td><?= $value['add_time'] ?><br><?= $value['last_time'] ?></td>
                <td>
                    <a href="<?= $this->baseurl . 'edit?uid=' . $value['uid'] ?>">编辑</a>
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
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到


    });
</script>
</body>
</html>
<!--code by tangjian-->
