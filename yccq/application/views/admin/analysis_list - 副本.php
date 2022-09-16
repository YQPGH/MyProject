
<?php $this->load->view('admin/header'); ?>

<!--右侧布局-->
<div class=" main">
    <div class="top">
        <span><?= $this->name?></span>
        <form action="" method="post" style="float: right">

            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="layui-form">
                <div class="layui-input-inline w100">
                    <select id="select" name="name"  >
<!--                        <option value="0">活动</option>-->
                        <?= getSelect($title, $name) ?>
                    </select>
                </div>
                <input  type="button" name="submit" class="layui-btn btn_submit" value=" 搜索 ">
            </div>
        </form>
    </div>
    <hr>
<!DOCTYPE html>
<html>
<head>

<!--    <link type="text/css" href="--><?//= base_url('static/admin/analysis/css/public.css') ?><!--" rel="stylesheet">-->
    <link type="text/css" href="<?= base_url('static/admin/analysis/css/icon.css') ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('static/admin/analysis/css/index.css') ?>" rel="stylesheet">
    <script type="text/javascript">

//        document.documentElement.style.fontSize = document.documentElement.clientWidth /768*100 + 'px';
    </script>
    <script src="<?= base_url('static/admin/analysis/js/echarts.min.js') ?>"></script>
</head>

<div class="bg">
    <div class="leftMain">
    	<div class="leftMain_top">
        	<div class="leftMain_topIn">
            	<ul>
                	<li>
                        <div class="liIn">
                            <h3>这里是标题1</h3>
                            <p class="shu"><span class="shu1">6890.69</span><i>元</i></p>
                            <div class="zi"><span class="span1">小标题：文字</span><span>小标题：文字</span></div>
                            <span class="border_bg_leftTop"></span>
                            <span class="border_bg_rightTop"></span>
                            <span class="border_bg_leftBottom"></span>
                            <span class="border_bg_rightBottom"></span>
                        </div>
                    </li>
                	<li>
                        <div class="liIn">
                            <h3>这里是标题2</h3>
                            <p class="shu"><span class="shu2">6090.99</span><i>元</i></p>
                            <div class="zi"><span class="span1">小标题：文字</span><span>小标题：文字</span></div>
                            <span class="border_bg_leftTop"></span>
                            <span class="border_bg_rightTop"></span>
                            <span class="border_bg_leftBottom"></span>
                            <span class="border_bg_rightBottom"></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="leftMain_middle">
        	<div class="leftMain_middle_left">
            	<div class="leftMain_middle_leftIn">
                	<h3><?= $this->title?></h3>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div class="biaoge" style="width:100%; height:25vh" id="chartmain"></div>
                    <script type="text/javascript">
					//window.onload = function () {
								//指定图表的配置项和数据
								var dataAxis = ['1日', '2日', '3日', '4日', '5日', '6日', '7日', '8日', '9日', '10日', '11日', '12日', '13日', '14日', '15日'];
								var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 200];
								var yMax = 500;
								var dataShadow = [];

								for (var i = 0; i < data.length; i++) {
									dataShadow.push(yMax);
								}

								option = {
									title: {
										text: '',
										subtext: ''
									},
									grid:{
										x:40,
										y:40,
										x2:20,
										y2:20,

									},
									xAxis: {
										data: dataAxis,
										axisLabel: {
											/*inside: true,*/
											interval:0,
											textStyle: {
												color: '#000',
												fontSize: 12

											}
										},
										axisTick: {
											show: false,
										},
										axisLine: {
											show: true,
											symbol:['none', 'arrow'],
											symbolOffset: 12,
											lineStyle:{
												color: '#000',
											}
										},
										z: 10
									},
									yAxis: {
										type: 'value',
										name: '单位：元',
										axisLine: {
											show: true,
											symbol: ['none', 'arrow'],
											symbolOffset: 12,
											lineStyle:{
												color: '#000',
											}
										},
										axisTick: {
											show: false
										},
										axisLabel: {
											textStyle: {
												color: '#000',
												fontSize: 12
											}
										}
									},

									dataZoom: [
										{
											type: 'inside'
										}
									],
									series: [
										{ // For shadow
											type: 'bar',
											itemStyle: {
												color: 'rgba(0,0,0,0.05)'
											},
											barGap: '-100%',
											barCategoryGap: '40%',
											data: dataShadow,
											animation: false
										},
										{
											type: 'bar',
											itemStyle: {
												color: new echarts.graphic.LinearGradient(
													0, 0, 0, 1,
													[
														{offset: 0, color: '#e2e2e2'},
														{offset: 0.5, color: '#188df0'},
														{offset: 1, color: '#188df0'}
													]
												)
											},
											emphasis: {
												itemStyle: {
													color: new echarts.graphic.LinearGradient(
														0, 0, 0, 1,
														[
															{offset: 0, color: '#2378f7'},
															{offset: 0.7, color: '#2378f7'},
															{offset: 1, color: '#e2e2e2'}
														]
													)
												}
											},
											data: data
										}
									]
								};

								// Enable data zoom when user click bar.
								/*var zoomSize = 6;
								myChart.on('click', function (params) {
									console.log(dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)]);
									myChart.dispatchAction({
										type: 'dataZoom',
										startValue: dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)],
										endValue: dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)]
									});
								});*/

								//获取dom容器
								var myChart = echarts.init(document.getElementById('chartmain'));
								// 使用刚指定的配置项和数据显示图表。
								myChart.setOption(option);
						//};
					</script>
                    <span class="border_bg_leftTop"></span>
                    <span class="border_bg_rightTop"></span>
                    <span class="border_bg_leftBottom"></span>
                    <span class="border_bg_rightBottom"></span>
                </div>
            </div>

        </div>
<!--        <div class="leftMain_middle">-->
<!--        	<div class="leftMain_middle_left">-->
<!--            	<div class="leftMain_middle_leftIn">-->
<!--                	<h3>这里是标题</h3>-->
<!--                    <div class="biaoge" style="width:100%; height:25vh" id="chartmain_zhe"></div>-->
<!--                    <script type="text/javascript">-->
<!--					//window.onload = function (){-->
<!--								//指定图表的配置项和数据-->
<!--								-->
<!--					option = {-->
<!--						title: {-->
<!--							text: ''-->
<!--						},-->
<!--						tooltip: {-->
<!--							trigger: 'axis'-->
<!--						},-->
<!--						legend: {-->
<!--							textStyle: {-->
<!--								color: '#000',-->
<!--								fontSize: 12,-->
<!--							},-->
<!--							right:'10%',-->
<!--							data: ['折线一', '折线二']-->
<!--						},-->
<!--						grid:{-->
<!--								x:40,-->
<!--								y:40,-->
<!--								x2:20,-->
<!--								y2:20,-->
<!--							},-->
<!--						toolbox: {-->
<!--							feature: {-->
<!--								//saveAsImage: {}-->
<!--							}-->
<!--						},-->
<!--						xAxis: {-->
<!--							type: 'category',-->
<!--							boundaryGap: false,-->
<!--							axisLabel: {-->
<!--											/*inside: true,*/-->
<!--											interval:0,-->
<!--											textStyle: {-->
<!--												color: '#000',-->
<!--												fontSize: 12-->
<!--												-->
<!--											}-->
<!--										},-->
<!--										axisTick: {-->
<!--											show: false,-->
<!--										},-->
<!--										axisLine: {-->
<!--											show: true,-->
<!--											symbol:['none', 'arrow'],-->
<!--											symbolOffset: 12,-->
<!--											lineStyle:{-->
<!--												color: '#000',-->
<!--											}-->
<!--										},-->
<!--							data: ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00']-->
<!--						},-->
<!--						yAxis: {-->
<!--							type: 'value',-->
<!--							-->
<!--							axisLine: {-->
<!--								show: true,-->
<!--								symbol: ['none', 'arrow'],-->
<!--								symbolOffset: 12,-->
<!--								lineStyle:{-->
<!--									color: '#000',-->
<!--								}-->
<!--							},-->
<!--							axisTick: {-->
<!--								show: false-->
<!--							},-->
<!--							axisLabel: {-->
<!--								textStyle: {-->
<!--									color: '#000',-->
<!--									fontSize: 12-->
<!--								}-->
<!--							}-->
<!--						},-->
<!--						series: [-->
<!--							{-->
<!--								name: '折线一',-->
<!--								type: 'line',-->
<!--								stack: '总量',-->
<!--								data: [280, 102, 191, 134, 390, 230, 210],-->
<!--								itemStyle: {-->
<!--									 normal: {-->
<!--									   color: "#000",//折线点的颜色-->
<!--									   lineStyle: {-->
<!--									   color: "#000",//折线的颜色-->
<!--									   width:2,-->
<!--									  }-->
<!--									},-->
<!--								}-->
<!--							},-->
<!--							{-->
<!--								name: '折线二',-->
<!--								type: 'line',-->
<!--								stack: '总量',-->
<!--								data: [100, 132, 131, 234, 290, 330, 110]-->
<!--							},-->
<!--						]-->
<!--					};		-->
<!--								//获取dom容器-->
<!--								var myChart = echarts.init(document.getElementById('chartmain_zhe'));-->
<!--								// 使用刚指定的配置项和数据显示图表。-->
<!--								myChart.setOption(option);-->
<!--						//};-->
<!--					</script>-->
<!--                    <span class="border_bg_leftTop"></span>-->
<!--                    <span class="border_bg_rightTop"></span>-->
<!--                    <span class="border_bg_leftBottom"></span>-->
<!--                    <span class="border_bg_rightBottom"></span>-->
<!--                </div>-->
<!--            </div>-->
<!--        	<div class="leftMain_middle_right">-->
<!--            	<div class="leftMain_middle_rightIn">-->
<!--                	<h3>这里是标题</h3>-->
<!--                    <div class="biaoge biaoge_bi" style="width:100%; height:25vh">-->
<!--                    	<ul>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu1">23</p>-->
<!--                                    <p class="zi">今日收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu2">107</p>-->
<!--                                    <p class="zi">本月收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu3">107</p>-->
<!--                                    <p class="zi">历史收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu4">23</p>-->
<!--                                    <p class="zi">今日收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu5">23</p>-->
<!--                                    <p class="zi">本月收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        	<li>-->
<!--                            	<div class="liIn">-->
<!--                                	<p class="shu shu6">23</p>-->
<!--                                    <p class="zi">历史收益比例</p>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    -->
<!--                    </div>-->
<!--                    <span class="border_bg_leftTop"></span>-->
<!--                    <span class="border_bg_rightTop"></span>-->
<!--                    <span class="border_bg_leftBottom"></span>-->
<!--                    <span class="border_bg_rightBottom"></span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
    <div class="rightMain">
        <div class="rightMain_top">
            <div class="rightMain_topIn">
                <h3><?= $this->title?></h3>
                <div class="biaoge" style="width:100%; height:30vh" id="chartmain_bing"></div>
                <script type="text/javascript">
					option = {
						title: {
							text: '数据情况统计',
							subtext: '',
							left: 'right',
							textStyle: {
								color: '#000',
								fontSize: 12
							}
						},
						tooltip: {
							trigger: 'item',
							formatter: '{a} <br/>{b} : {c} ({d}%)'
						},
						legend: {
							// orient: 'vertical',
							// top: 'middle',
							type: 'scroll',
							orient: 'vertical',
							right: 10,
							top: 40,
							bottom: 20,
							left: 'right',
							data: ['西凉', '益州', '兖州', '荆州', '幽州'],
							textStyle: {
								color: '#000',
								fontSize: 12
							}

						},
						grid:{
							x:'-10%',
							y:40,
							x2:20,
							y2:20,
						},
						color : [ '#09d0fb', '#f88cfb', '#95f8fe', '#f9f390',  '#ecfeb7' ],
						series: [
							{
								type: 'pie',
								radius: '65%',
								center: ['50%', '50%'],
								selectedMode: 'single',
								data: [
									{value: 1548, name: '幽州',

							},
									{value: 535, name: '荆州'},
									{value: 510, name: '兖州'},
									{value: 634, name: '益州'},
									{value: 735, name: '西凉'}
								],
								emphasis: {
									itemStyle: {
										shadowBlur: 10,
										shadowOffsetX: 0,
										shadowColor: 'rgba(0, 0, 0, 0.5)'
									}
								}
							}
						]
					};
                //获取dom容器
								var myChart = echarts.init(document.getElementById('chartmain_bing'));
								// 使用刚指定的配置项和数据显示图表。
								myChart.setOption(option);

                </script>
                <span class="border_bg_leftTop"></span>
                <span class="border_bg_rightTop"></span>
                <span class="border_bg_leftBottom"></span>
                <span class="border_bg_rightBottom"></span>
            </div>
         </div>

    </div>
    <div style="clear:both;"></div>
</div>
    </div>
</div>
<!--数字增长累加动画-->
<script src="<?= base_url('static/admin/analysis/js/jquery-1.11.0.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('static/admin/analysis/js/jquery.numscroll.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('static/layui/layui.js') ?>"></script>
<script src="<?= base_url('static/admin/js/admin.js') ?>"></script>

<script type="text/javascript">

   $(document).ready(function(){
       $('.btn_submit').click(function(){

           var myselect = $('#select option:selected') .val();//选中的值
           if(myselect!='')
           {
               location.href = (myselect);

           }

        });

   })
</script>

<script type="text/javascript">
	$(".shu1").numScroll();
	$(".shu2").numScroll();
	$(".shu3").numScroll();
	$(".shu4").numScroll();
	$(".shu5").numScroll();
	$(".shu6").numScroll();

	/*$(".num2").numScroll({
		time:5000
	});*/
</script>

</body>
</html>
