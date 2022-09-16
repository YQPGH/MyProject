<?php $this->load->view('admin/header'); ?>
<style>
  #top li {
    display: inline;
    line-height: 40px;
    float:left;
    margin-left: 20px;
  }

  .text-blue {
    color: blue;
  }
</style>
<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?= $this->name; ?></span>
        
    </div>
    <hr>
    <blockquote class="layui-elem-quote">
      整体趋势
      <ul id="top" style="float: right; ">
        <li>参与人数:<span class="text-blue"><?=$cyrs_count?></span></li>
        <li>参与次数:<span class="text-blue"><?=$cycs_count?></span></li>
        <li>抽奖次数:<span class="text-blue"><?=$cjcs_count?></span></li>
        <li>扫码次数:<span class="text-blue"><?=$smcs_count?></span></li>
      </ul>
    </blockquote>

    <div class="layui-col-md12 layui-bg-gray" style="margin-bottom: 10px;">
      <div class="layui-panel">
        <canvas id="myChart" width="1200" height="500"></canvas>
      </div>   
    </div>

    <blockquote class="layui-elem-quote">
      奖品
    </blockquote>

    <div class="layui-col-md12 layui-bg-gray" style="margin-bottom: 10px;">
      <div class="layui-panel">
        <canvas id="prizeChart" width="1200" height="500"></canvas>
      </div>   
    </div>

    <blockquote class="layui-elem-quote">
      数据分析
      <ul id="top" style="float: right; ">
        <li>浏览次数（PV）:<span class="text-blue">3453855</span></li>
        <li>独立访客（UV）:<span class="text-blue">254367</span></li>
        <li>IP:<span class="text-blue">124538</span></li>
      </ul>
    </blockquote>

    <div class="layui-col-md12 layui-bg-gray" style="margin-bottom: 10px;">
      <div class="layui-panel">
        <canvas id="dataChart" width="1200" height="500"></canvas>
        <div class="layui-card layui-bg-green" style="width: 400px;">
          <div class="layui-card-header">访问用户区域分布 T10</div>
          <div class="layui-card-body">
            <table class="layui-table">
              <colgroup>
                <col width="200">
                <col width="200">
                <col width="200">
              </colgroup>
              <thead>
                <tr>
                  <th>用户所在区域</th>
                  <th>访问次数</th>
                  <th>访问次数占比</th>
                </tr> 
              </thead>
              <tbody>
                <?php foreach ($area as $v) { ?>
                <tr>
                  <td><?=$v[0]?></td>
                  <td><?=$v[1]?>次</td>
                  <td><?=$v[2]?>%</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>   
    </div>
</div>

<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>
<script src="<?= base_url('static/admin/js/f2.js') ?>"></script>
<script>
  layui.use(['layer', 'element'], function () {
    var layer = layui.layer;
    var element = layui.element;
    var $ = layui.jquery //由于layer弹层依赖jQuery，所以可以直接得到

    data = <?=$chart?>;
    createChart(data);
    createPrizeChart();
    createDataChart();
  });

  function createChart (data) {
    var chart = new F2.Chart({
      id: 'myChart',
      pixelRatio: window.devicePixelRatio
    });
    chart.source(data);
    
    chart.scale('value', {
      tickCount: 1
    });
    chart.axis('date', {
      label: function label(text, index, total) {
        // 只显示每一年的第一天
        const textCfg = {};
        if (index === 0) {
          textCfg.textAlign = 'left';
        } else if (index === total - 1) {
          textCfg.textAlign = 'right';
        }
        return textCfg;
      }
    });
    chart.tooltip({
      custom: true, // 自定义 tooltip 内容框
      onChange: function onChange(obj) {
        const legend = chart.get('legendController').legends.top[0];
        const tooltipItems = obj.items;
        const legendItems = legend.items;
        const map = {};
        legendItems.forEach(function(item) {
          map[item.name] = _.clone(item);
        });
        tooltipItems.forEach(function(item) {
          const name = item.name;
          const value = item.value;
          if (map[name]) {
            map[name].value = value;
          }
        });
        legend.setItems(_.values(map));
      },
      onHide: function onHide() {
        const legend = chart.get('legendController').legends.top[0];
        legend.setItems(chart.getLegendItems().country);
      }
    });
    chart.line().position('date*value').color('type');
    chart.point().position('date*value').style({
      stroke: '#fff',
      lineWidth: 1
    });
    data.forEach(function (obj) {
      chart.guide().text({
        position: [ obj.date, obj.value ],
        content: obj.value,
        style: {
          fill: '#1890ff',
          textAlign: 'center'
        },
        offsetY: -10
      });
    });
    
    chart.render();
  }

  function createPrizeChart() {
    const map = {
      '1300乐豆口粮代金券': (20/579*100).toFixed(2) + '%',
      '1200乐豆口粮代金券': (30/579*100).toFixed(2) + '%',
      '800乐豆口粮代金券': (50/579*100).toFixed(2) + '%',
      '真龙招财进宝煤油打火机': (298/579*100).toFixed(2) + '%',
      '乐豆中心粗陶陆宝快客杯': (93/579*100).toFixed(2) + '%',
      '佐罗充气打火机': (11/579*100).toFixed(2) + '%',
      '真龙君钥匙扣': (60/579*100).toFixed(2) + '%',
      '喷漆（蓝）打火机': (2/579*100).toFixed(2) + '%',
      '口粮礼包': (15/579*100).toFixed(2) + '%'
    };
    const data = [{
      name: '1300乐豆口粮代金券',
      percent: 20/579,
      a: '1'
    }, {
      name: '1200乐豆口粮代金券',
      percent: 30/579,
      a: '1'
    }, {
      name: '800乐豆口粮代金券',
      percent: 50/579,
      a: '1'
    }, {
      name: '真龙招财进宝煤油打火机',
      percent: 298/579,
      a: '1'
    }, {
      name: '乐豆中心粗陶陆宝快客杯',
      percent: 93/579,
      a: '1'
    }, {
      name: '佐罗充气打火机',
      percent: 11/579,
      a: '1'
    }, {
      name: '真龙君钥匙扣',
      percent: 60/579,
      a: '1'
    }, {
      name: '喷漆（蓝）打火机',
      percent: 2/579,
      a: '1'
    }, {
      name: '口粮礼包',
      percent: 15/579,
      a: '1'
    }];
    const chart = new F2.Chart({
      id: 'prizeChart',
      pixelRatio: window.devicePixelRatio
    });
    chart.source(data, {
      percent: {
        formatter: function formatter(val) {
          return (val * 100).toFixed(2) + '%';
        }
      }
    });
    chart.legend({
      position: 'right',
      itemFormatter: function itemFormatter(val) {
        return val + '  ' + map[val];
      }
    });
    chart.tooltip(false);
    chart.coord('polar', {
      transposed: true,
      radius: 0.85
    });
    chart.axis(false);
    chart.interval()
      .position('a*percent')
      .color('name', [ '#1890FF', '#13C2C2', '#2FC25B', '#FACC14', '#F04864', '#8543E0' ])
      .adjust('stack')
      .style({
        lineWidth: 1,
        stroke: '#fff',
        lineJoin: 'round',
        lineCap: 'round'
      })
      .animate({
        appear: {
          duration: 1200,
          easing: 'bounceOut'
        }
      });

    chart.render();


  }

  function createDataChart (data) {
    data = [
      {
        type: 'pv',
        value: 122687,
        date: '08-25'
      },
      {
        type: 'pv',
        value: 135607,
        date: '08-26'
      },
      {
        type: 'pv',
        value: 115487,
        date: '08-27'
      },
      {
        type: 'pv',
        value: 104375,
        date: '08-28'
      },
      {
        type: 'pv',
        value: 98357,
        date: '08-29'
      },
      {
        type: 'pv',
        value: 102647,
        date: '08-30'
      },
      {
        type: 'pv',
        value: 98645,
        date: '08-31'
      },
      {
        type: 'pv',
        value: 95937,
        date: '09-01'
      },
      {
        type: 'pv',
        value: 89356,
        date: '09-02'
      },
      {
        type: 'pv',
        value: 90756,
        date: '09-03'
      },
      {
        type: 'pv',
        value: 91365,
        date: '09-04'
      },
      {
        type: 'pv',
        value: 97235,
        date: '09-05'
      },
      {
        type: 'pv',
        value: 89245,
        date: '09-06'
      },
      {
        type: 'pv',
        value: 94736,
        date: '09-07'
      },
      {
        type: 'pv',
        value: 99076,
        date: '09-08'
      },
      {
        type: 'pv',
        value: 105674,
        date: '09-09'
      },
      {
        type: 'pv',
        value: 93402,
        date: '09-10'
      },
      {
        type: 'pv',
        value: 89205,
        date: '09-11'
      },
      {
        type: 'pv',
        value: 96276,
        date: '09-12'
      },
      {
        type: 'pv',
        value: 87034,
        date: '09-13'
      },
      {
        type: 'pv',
        value: 92791,
        date: '09-14'
      },
      {
        type: 'pv',
        value: 90557,
        date: '09-15'
      },
      {
        type: 'uv',
        value: 12687,
        date: '08-25'
      },
      {
        type: 'uv',
        value: 13607,
        date: '08-26'
      },
      {
        type: 'uv',
        value: 11565,
        date: '08-27'
      },
      {
        type: 'uv',
        value: 10498,
        date: '08-28'
      },
      {
        type: 'uv',
        value: 9335,
        date: '08-29'
      },
      {
        type: 'uv',
        value: 10601,
        date: '08-30'
      },
      {
        type: 'uv',
        value: 9886,
        date: '08-31'
      },
      {
        type: 'uv',
        value: 5902,
        date: '09-01'
      },
      {
        type: 'uv',
        value: 8378,
        date: '09-02'
      },
      {
        type: 'uv',
        value: 9798,
        date: '09-03'
      },
      {
        type: 'uv',
        value: 9184,
        date: '09-04'
      },
      {
        type: 'uv',
        value: 9760,
        date: '09-05'
      },
      {
        type: 'uv',
        value: 8237,
        date: '09-06'
      },
      {
        type: 'uv',
        value: 9440,
        date: '09-07'
      },
      {
        type: 'uv',
        value: 9949,
        date: '09-08'
      },
      {
        type: 'uv',
        value: 10554,
        date: '09-09'
      },
      {
        type: 'uv',
        value: 9402,
        date: '09-10'
      },
      {
        type: 'uv',
        value: 8220,
        date: '09-11'
      },
      {
        type: 'uv',
        value: 9679,
        date: '09-12'
      },
      {
        type: 'uv',
        value: 8730,
        date: '09-13'
      },
      {
        type: 'uv',
        value: 9209,
        date: '09-14'
      },
      {
        type: 'uv',
        value: 9040,
        date: '09-15'
      },
      {
        type: 'ip',
        value: 3579,
        date: '08-25'
      },
      {
        type: 'ip',
        value: 3367,
        date: '08-26'
      },
      {
        type: 'ip',
        value: 3267,
        date: '08-27'
      },
      {
        type: 'ip',
        value: 3105,
        date: '08-28'
      },
      {
        type: 'ip',
        value: 3001,
        date: '08-29'
      },
      {
        type: 'ip',
        value: 2956,
        date: '08-30'
      },
      {
        type: 'ip',
        value: 2638,
        date: '08-31'
      },
      {
        type: 'ip',
        value: 2920,
        date: '09-01'
      },
      {
        type: 'ip',
        value: 3039,
        date: '09-02'
      },
      {
        type: 'ip',
        value: 3086,
        date: '09-03'
      },
      {
        type: 'ip',
        value: 2903,
        date: '09-04'
      },
      {
        type: 'ip',
        value: 2830,
        date: '09-05'
      },
      {
        type: 'ip',
        value: 2430,
        date: '09-06'
      },
      {
        type: 'ip',
        value: 2923,
        date: '09-07'
      },
      {
        type: 'ip',
        value: 3139,
        date: '09-08'
      },
      {
        type: 'ip',
        value: 3032,
        date: '09-09'
      },
      {
        type: 'ip',
        value: 2909,
        date: '09-10'
      },
      {
        type: 'ip',
        value: 2690,
        date: '09-11'
      },
      {
        type: 'ip',
        value: 3030,
        date: '09-12'
      },
      {
        type: 'ip',
        value: 2830,
        date: '09-13'
      },
      {
        type: 'ip',
        value: 2945,
        date: '09-14'
      },
      {
        type: 'ip',
        value: 3473,
        date: '09-15'
      }
    ]
    var chart = new F2.Chart({
      id: 'dataChart',
      pixelRatio: window.devicePixelRatio
    });
    chart.source(data);
    
    chart.scale('value', {
      tickCount: 1
    });
    chart.axis('date', {
      label: function label(text, index, total) {
        // 只显示每一年的第一天
        const textCfg = {};
        if (index === 0) {
          textCfg.textAlign = 'left';
        } else if (index === total - 1) {
          textCfg.textAlign = 'right';
        }
        return textCfg;
      }
    });
    chart.tooltip({
      custom: true, // 自定义 tooltip 内容框
      onChange: function onChange(obj) {
        const legend = chart.get('legendController').legends.top[0];
        const tooltipItems = obj.items;
        const legendItems = legend.items;
        const map = {};
        legendItems.forEach(function(item) {
          map[item.name] = _.clone(item);
        });
        tooltipItems.forEach(function(item) {
          const name = item.name;
          const value = item.value;
          if (map[name]) {
            map[name].value = value;
          }
        });
        legend.setItems(_.values(map));
      },
      onHide: function onHide() {
        const legend = chart.get('legendController').legends.top[0];
        legend.setItems(chart.getLegendItems().country);
      }
    });
    chart.line().position('date*value').color('type');
    chart.point().position('date*value').style({
      stroke: '#fff',
      lineWidth: 1
    });
    data.forEach(function (obj) {
      chart.guide().text({
        position: [ obj.date, obj.value ],
        content: obj.value,
        style: {
          fill: '#1890ff',
          textAlign: 'center'
        },
        offsetY: -10
      });
    });
    
    chart.render();
  }
</script>
</body>
</html>
<!--code by tangjian-->