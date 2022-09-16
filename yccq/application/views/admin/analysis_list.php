<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span>统计概况</span>
        <form action="<?= $this->baseurl ?>" method="post" style="float: right">
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="layui-form">
                <div class="layui-input-inline w100">
                    <select name="year">
                        <?= getSelect(config_item('years'), $year) ?>
                    </select>
                </div>
                <input type="submit" name="submit" class="layui-btn" value=" 搜索 ">
            </div>
        </form>
    </div>
    <hr>

    <div id="container" style="min-width:400px;height:400px"></div>

</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script src="https://img.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
<script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
<script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到
    });

    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '游戏统计概况'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: <?=$dates?>
            },
            yAxis: {
                title: {
                    text: '人数'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true          // 开启数据标签
                    },
                    enableMouseTracking: false // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                }
            },
            series: [{
                name: '总用户',
                data: [<?=$users?>]
            },{
                name: '活跃用户',
                data: [<?=$active?>]
            }, {
                name: '游戏次数',
                data: [<?=$logins?>]
            }]
        });
    });


</script>
</body>
</html>
<!--code by tangjian-->