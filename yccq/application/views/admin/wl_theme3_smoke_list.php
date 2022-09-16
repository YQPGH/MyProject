<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="layui-tab">
        <ul class="layui-tab-title" id="tab_list">
            <?php foreach ($tab_list as $key => $value): ?>
            <li class=""  value="<?= $value ?>"><?= $value ?></li>
            <?php endforeach; ?>
        </ul>
        <br>
        <div class="top">
           <span>《烟草传奇》游戏公测活动方案真龙(<?= $tab_type ?>)样品烟B</span>
            <form action="<?= $this->baseurl.'theme_list_3' ?>" method="post" style="float: right">
                <input type="hidden" name="catid" value="<?= $catid ?>">
                <input id="tab_type" type="hidden" name="tab_type" value="<?= $tab_type ?>">
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
<!--            <hr>-->
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <table class="layui-table" lay-skin="line">
                    <thead>
                    <tr>
                        <th width="30">序号</th>
                        <th width="90">OPENID</th>
                        <th width="80">用户昵称</th>
                        <th width="60">用户姓名</th>
                        <th width="100">手机号</th>
                        <th width="130">邮寄地址</th>
                        <th width="60">奖品类型</th>
                        <th width="50">数量（包）</th>
                        <th width="100">领取时间</th>
                        <th width="50">发货状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list as $key => $value): ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $value['openid'] ?></td>
                            <td><?= $value['nickname'] ?></td>
                            <td><?= $value['truename'] ?></td>
                            <td><?= $value['phone'] ?></td>
                            <td><?= $value['address'];?></td>
                            <td><?= $value['prize'] ?></td>
                            <td><?= $value['num'] ?></td>
                            <td><?= $value['add_time'] ?></td>
                            <td><?= $value['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
            <div class="margintop pages">
                信息总数： <?= $count ?>条&nbsp;&nbsp;
                <?= $pages ?>
            </div>

        </div>
    </div>

</div>

<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>


<script type="application/javascript">



    $(document).ready(function () {

        $("#tab_list li").on("click", function (event) {
            var target = $(event.target).html();

            window.location.href = "<?=base_url()?>/admin/wldetail/theme_list_3?tab_type="+target;

        });
        var ul=document.getElementById("tab_list").getElementsByTagName("li");
        var type = $("#tab_type").val();
        for(var i=0;i<ul.length;i++){
            var cityid=ul[i].getAttribute("value");
            if(cityid == type){
                ul[i].classList.add('layui-this');
            }

        }

    });

</script>
</body>
</html>
<!--code by tangjian-->