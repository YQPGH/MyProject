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
        <li>浏览次数（PV）:<span class="text-blue">4583728</span></li>
        <li>独立访客（UV）:<span class="text-blue">205831</span></li>
        <li>IP:<span class="text-blue">139864</span></li>
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
      '乐豆中心秋帝双层饭盒': (100/850*100).toFixed(2) + '%',
      '乐豆中心啤酒开瓶器': (300/850*100).toFixed(2) + '%',
      '800乐豆口粮代金券': (200/850*100).toFixed(2) + '%',
      '1200乐豆口粮代金券': (100/850*100).toFixed(2) + '%',
      '1300乐豆口粮代金券': (50/850*100).toFixed(2) + '%',
      '真龙君招财进宝煤油打火机': (100/850*100).toFixed(2) + '%'
    };
    const data = [{
      name: '乐豆中心秋帝双层饭盒',
      percent: 100/850,
      a: '1'
    }, {
      name: '乐豆中心啤酒开瓶器',
      percent: 300/850,
      a: '1'
    }, {
      name: '800乐豆口粮代金券',
      percent: 200/850,
      a: '1'
    }, {
      name: '1200乐豆口粮代金券',
      percent: 100/850,
      a: '1'
    }, {
      name: '1300乐豆口粮代金券',
      percent: 50/850,
      a: '1'
    }, {
      name: '真龙君招财进宝煤油打火机',
      percent: 100/850,
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
        value: 143451,
        date: '09-16'
      },
      {
        type: 'pv',
        value: 125901,
        date: '09-17'
      },
      {
        type: 'pv',
        value: 117583,
        date: '09-18'
      },
      {
        type: 'pv',
        value: 108473,
        date: '09-19'
      },
      {
        type: 'pv',
        value: 103724,
        date: '09-20'
      },
      {
        type: 'pv',
        value: 102647,
        date: '09-21'
      },
      {
        type: 'pv',
        value: 91671,
        date: '09-22'
      },
      {
        type: 'pv',
        value: 85779,
        date: '09-23'
      },
      {
        type: 'pv',
        value: 89356,
        date: '09-24'
      },
      {
        type: 'pv',
        value: 99523,
        date: '09-25'
      },
      {
        type: 'pv',
        value: 86575,
        date: '09-26'
      },
      {
        type: 'pv',
        value: 88334,
        date: '09-27'
      },
      {
        type: 'pv',
        value: 85552,
        date: '09-28'
      },
      {
        type: 'pv',
        value: 83647,
        date: '09-29'
      },
      {
        type: 'pv',
        value: 98722,
        date: '09-30'
      },
      {
        type: 'pv',
        value: 93357,
        date: '09-31'
      },
      {
        type: 'pv',
        value: 99695,
        date: '10-01'
      },
      {
        type: 'pv',
        value: 98577,
        date: '10-02'
      },
      {
        type: 'pv',
        value: 96276,
        date: '10-03'
      },
      {
        type: 'pv',
        value: 94521,
        date: '10-04'
      },
      {
        type: 'pv',
        value: 92791,
        date: '10-05'
      },
      {
        type: 'pv',
        value: 90557,
        date: '10-06'
      },
      {
        type: 'pv',
        value: 86138,
        date: '10-07'
      },
      {
        type: 'pv',
        value: 84849,
        date: '10-08'
      },
      {
        type: 'pv',
        value: 82587,
        date: '10-09'
      },
      {
        type: 'pv',
        value: 80144,
        date: '10-10'
      },
      {
        type: 'uv',
        value: 12805,
        date: '09-16'
      },
      {
        type: 'uv',
        value: 11340,
        date: '09-17'
      },
      {
        type: 'uv',
        value: 11015,
        date: '09-18'
      },
      {
        type: 'uv',
        value: 10817,
        date: '09-19'
      },
      {
        type: 'uv',
        value: 9823,
        date: '09-20'
      },
      {
        type: 'uv',
        value: 9548,
        date: '09-21'
      },
      {
        type: 'uv',
        value: 9549,
        date: '09-22'
      },
      {
        type: 'uv',
        value: 9561,
        date: '09-23'
      },
      {
        type: 'uv',
        value: 9689,
        date: '09-24'
      },
      {
        type: 'uv',
        value: 9238,
        date: '09-25'
      },
      {
        type: 'uv',
        value: 9521,
        date: '09-26'
      },
      {
        type: 'uv',
        value: 9344,
        date: '09-27'
      },
      {
        type: 'uv',
        value: 9215,
        date: '09-28'
      },
      {
        type: 'uv',
        value: 9253,
        date: '09-29'
      },
      {
        type: 'uv',
        value: 9517,
        date: '09-30'
      },
      {
        type: 'uv',
        value: 9382,
        date: '09-31'
      },
      {
        type: 'uv',
        value: 9134,
        date: '10-01'
      },
      {
        type: 'uv',
        value: 9320,
        date: '10-02'
      },
      {
        type: 'uv',
        value: 9350,
        date: '10-03'
      },
      {
        type: 'uv',
        value: 9140,
        date: '10-04'
      },
      {
        type: 'uv',
        value: 9183,
        date: '10-05'
      },
      {
        type: 'uv',
        value: 9204,
        date: '10-06'
      },
      {
        type: 'uv',
        value: 9584,
        date: '10-07'
      },
      {
        type: 'uv',
        value: 9522,
        date: '10-08'
      },
      {
        type: 'uv',
        value: 9627,
        date: '10-09'
      },
      {
        type: 'uv',
        value: 9608,
        date: '10-10'
      },
      {
        type: 'ip',
        value: 3177,
        date: '09-16'
      },
      {
        type: 'ip',
        value: 3293,
        date: '09-17'
      },
      {
        type: 'ip',
        value: 3594,
        date: '09-18'
      },
      {
        type: 'ip',
        value: 3513,
        date: '09-19'
      },
      {
        type: 'ip',
        value: 3864,
        date: '09-20'
      },
      {
        type: 'ip',
        value: 4176,
        date: '09-21'
      },
      {
        type: 'ip',
        value: 4154,
        date: '09-22'
      },
      {
        type: 'ip',
        value: 4034,
        date: '09-23'
      },
      {
        type: 'ip',
        value: 2808,
        date: '09-24'
      },
      {
        type: 'ip',
        value: 3906,
        date: '09-25'
      },
      {
        type: 'ip',
        value: 3467,
        date: '09-26'
      },
      {
        type: 'ip',
        value: 3761,
        date: '09-27'
      },
      {
        type: 'ip',
        value: 3220,
        date: '09-28'
      },
      {
        type: 'ip',
        value: 3757,
        date: '09-29'
      },
      {
        type: 'ip',
        value: 3873,
        date: '09-30'
      },
      {
        type: 'ip',
        value: 3241,
        date: '09-31'
      },
      {
        type: 'ip',
        value: 3503,
        date: '10-01'
      },
      {
        type: 'ip',
        value: 4128,
        date: '10-02'
      },
      {
        type: 'ip',
        value: 3912,
        date: '10-03'
      },
      {
        type: 'ip',
        value: 3923,
        date: '10-04'
      },
      {
        type: 'ip',
        value: 4177,
        date: '10-05'
      },
      {
        type: 'ip',
        value: 3131,
        date: '10-06'
      },
      {
        type: 'ip',
        value: 2959,
        date: '10-07'
      },
      {
        type: 'ip',
        value: 3062,
        date: '10-08'
      },
      {
        type: 'ip',
        value: 3213,
        date: '10-09'
      },
      {
        type: 'ip',
        value: 2996,
        date: '10-10'
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