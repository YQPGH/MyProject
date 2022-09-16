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
<script src="<?= base_url('static/admin/js/jquery-1.8.3.min.js') ?>"></script>

<script src="<?= base_url('static/admin/js/hcharts/highcharts.js') ?>"></script>
<script src="<?= base_url('static/admin/js/hcharts/modules/exporting.js') ?>"></script>
<script src="<?= base_url('static/admin/js/hcharts/modules/export-data.js') ?>"></script>
<script src="<?= base_url('static/admin/js/hcharts/modules/accessibility.js') ?>"></script>


<script>
    layui.use(['layer', 'form', 'element'], function () {
        var layer = layui.layer, form = layui.form;
        var element = layui.element;
        var $ = layui.jquery; //由于layer弹层依赖jQuery，所以可以直接得到

    });

    Highcharts.setOptions({
        lang:{
            contextButtonTitle:"图表导出菜单",
            decimalPoint:".",
            downloadJPEG:"下载JPEG图片",
            downloadPDF:"下载PDF文件",
            downloadPNG:"下载PNG文件",
            downloadSVG:"下载SVG文件",
            downloadCSV:"下载CSV文件",
            downloadXLS:"下载XLS文件",

            drillUpText:"返回 {series.name}",
            loading:"加载中",
            months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
            noData:"没有数据",
            numericSymbols: [ "千" , "兆" , "G" , "T" , "P" , "E"],
            printChart:"打印图表",
            resetZoom:"恢复缩放",
            resetZoomTitle:"恢复图表",
            shortMonths: [ "Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"],
            thousandsSep:",",
            weekdays: ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六","星期天"]
        }
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
                categories: [<?=$dates?>]
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
