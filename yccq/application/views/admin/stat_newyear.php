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
        <li>浏览次数（PV）:<span class="text-blue">3783746</span></li>
        <li>独立访客（UV）:<span class="text-blue">185467</span></li>
        <li>IP:<span class="text-blue">98374</span></li>
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
      '3号口粮品吸机会': (100/450*100).toFixed(2) + '%',
      '1200乐豆口粮代金券': (30/450*100).toFixed(2) + '%',
      '1000乐豆口粮代金券': (50/450*100).toFixed(2) + '%',
      '500乐豆口粮代金券': (150/450*100).toFixed(2) + '%',
      '1300乐豆口粮代金券': (20/450*100).toFixed(2) + '%',
      '800乐豆口粮代金券': (100/450*100).toFixed(2) + '%'
    };
    const data = [{
      name: '3号口粮品吸机会',
      percent: 100/450,
      a: '1'
    }, {
      name: '1200乐豆口粮代金券',
      percent: 30/450,
      a: '1'
    }, {
      name: '1000乐豆口粮代金券',
      percent: 50/450,
      a: '1'
    }, {
      name: '500乐豆口粮代金券',
      percent: 150/450,
      a: '1'
    }, {
      name: '1300乐豆口粮代金券',
      percent: 20/450,
      a: '1'
    }, {
      name: '800乐豆口粮代金券',
      percent: 100/450,
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
        value: 113685,
        date: '02-09'
      },
      {
        type: 'pv',
        value: 100245,
        date: '02-10'
      },
      {
        type: 'pv',
        value: 98869,
        date: '02-11'
      },
      {
        type: 'pv',
        value: 95132,
        date: '02-12'
      },
      {
        type: 'pv',
        value: 93655,
        date: '02-13'
      },
      {
        type: 'pv',
        value: 94568,
        date: '02-14'
      },
      {
        type: 'pv',
        value: 90914,
        date: '02-15'
      },
      {
        type: 'pv',
        value: 89472,
        date: '02-16'
      },
      {
        type: 'pv',
        value: 92957,
        date: '02-17'
      },
      {
        type: 'pv',
        value: 88391,
        date: '02-18'
      },
      {
        type: 'pv',
        value: 86575,
        date: '02-19'
      },
      {
        type: 'pv',
        value: 85995,
        date: '02-20'
      },
      {
        type: 'pv',
        value: 87126,
        date: '02-21'
      },
      {
        type: 'pv',
        value: 84933,
        date: '02-22'
      },
      {
        type: 'pv',
        value: 81785,
        date: '02-23'
      },
      {
        type: 'pv',
        value: 80259,
        date: '02-24'
      },
      {
        type: 'pv',
        value: 78591,
        date: '02-25'
      },
      {
        type: 'pv',
        value: 79412,
        date: '02-26'
      },
      {
        type: 'pv',
        value: 76572,
        date: '02-27'
      },
      {
        type: 'pv',
        value: 82674,
        date: '02-28'
      },
      {
        type: 'uv',
        value: 10217,
        date: '02-09'
      },
      {
        type: 'uv',
        value: 9143,
        date: '02-10'
      },
      {
        type: 'uv',
        value: 8859,
        date: '02-11'
      },
      {
        type: 'uv',
        value: 8516,
        date: '02-12'
      },
      {
        type: 'uv',
        value: 8397,
        date: '02-13'
      },
      {
        type: 'uv',
        value: 8452,
        date: '02-14'
      },
      {
        type: 'uv',
        value: 8517,
        date: '02-15'
      },
      {
        type: 'uv',
        value: 8326,
        date: '02-16'
      },
      {
        type: 'uv',
        value: 8173,
        date: '02-17'
      },
      {
        type: 'uv',
        value: 8057,
        date: '02-18'
      },
      {
        type: 'uv',
        value: 8179,
        date: '02-19'
      },
      {
        type: 'uv',
        value: 7983,
        date: '02-20'
      },
      {
        type: 'uv',
        value: 7714,
        date: '02-21'
      },
      {
        type: 'uv',
        value: 7618,
        date: '02-22'
      },
      {
        type: 'uv',
        value: 7681,
        date: '02-23'
      },
      {
        type: 'uv',
        value: 7591,
        date: '02-24'
      },
      {
        type: 'uv',
        value: 7784,
        date: '02-25'
      },
      {
        type: 'uv',
        value: 7825,
        date: '02-26'
      },
      {
        type: 'uv',
        value: 7645,
        date: '02-27'
      },
      {
        type: 'uv',
        value: 8015,
        date: '02-28'
      },
      {
        type: 'ip',
        value: 3107,
        date: '02-09'
      },
      {
        type: 'ip',
        value: 3005,
        date: '02-10'
      },
      {
        type: 'ip',
        value: 2935,
        date: '02-11'
      },
      {
        type: 'ip',
        value: 2794,
        date: '02-12'
      },
      {
        type: 'ip',
        value: 2615,
        date: '02-13'
      },
      {
        type: 'ip',
        value: 2678,
        date: '02-14'
      },
      {
        type: 'ip',
        value: 2495,
        date: '02-15'
      },
      {
        type: 'ip',
        value: 2507,
        date: '02-16'
      },
      {
        type: 'ip',
        value: 2375,
        date: '02-17'
      },
      {
        type: 'ip',
        value: 2404,
        date: '02-18'
      },
      {
        type: 'ip',
        value: 2385,
        date: '02-19'
      },
      {
        type: 'ip',
        value: 2317,
        date: '02-20'
      },
      {
        type: 'ip',
        value: 2260,
        date: '02-21'
      },
      {
        type: 'ip',
        value: 2197,
        date: '02-22'
      },
      {
        type: 'ip',
        value: 2151,
        date: '02-23'
      },
      {
        type: 'ip',
        value: 2119,
        date: '02-24'
      },
      {
        type: 'ip',
        value: 2098,
        date: '02-25'
      },
      {
        type: 'ip',
        value: 2054,
        date: '02-26'
      },
      {
        type: 'ip',
        value: 2096,
        date: '02-27'
      },
      {
        type: 'ip',
        value: 2248,
        date: '02-28'
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