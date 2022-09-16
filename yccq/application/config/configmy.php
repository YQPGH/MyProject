<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 自定义 全局变量 by tangjian
$config['title'] = '香草传奇';
$config['suipian_maxnum'] = 2;


//消耗烟叶数量
$config['use_number'] = 1;
//能量转换闪电
$config['energy'] = 50;
//虫子派遣时间
$config['time'] = [
		'destroy_time' => time()+10*60*60,
		'stop_time' => time()+12*60*60
];
$config['setfire'] = [
    'fire_number' => 3, //放火次数
    'burned_num' => 3, //好友被放火次数
    'money' => 500  //银元
];
//放火时间
$config['fire_time'] = [
    'jiasu_time' => time()+2*60*60,
    'destroy_time' => time()+12*60*60,
    'stop_time' => time()+14*60*60
];
// 物品分类1
$config['shop_type1'] = [
    'tudi' => '土地',
    'lvzui' => '滤嘴',
    'zhongzi' => '种子',
    'yanye' => '烟叶',
    'yanye_kao' => '烘烤烟叶',
    'yanye_chun' => '醇化烟叶',
    'peifang' => '配方',
    'yan' => '香烟',
    'yan_pin' => '品鉴香烟',
    'chongzi' => '虫子',
    'building'=>'建筑材料',
    'building_gift' => '建筑材料礼包'
];

// 土地购买等级(每三级只能购买一块土地)
$config['land_buy_lv'] = [
    0 => ['size' => 6],
    1 => ['size' => 6],
    2 => ['size' => 6],
    3 => ['size' => 7],
    4 => ['size' => 7],
    5 => ['size' => 7],
    6 => ['size' => 8],
    7 => ['size' => 8],
    8 => ['size' => 8],
    9 => ['size' => 9],
    10 => [ 'size' => 9],
    11 => [ 'size' => 9],
    12 => [ 'size' => 10],
    13 => [ 'size' => 10],
    14 => [ 'size' => 10],
    15 => [ 'size' => 11],
    16 => [ 'size' => 11],
    17 => [ 'size' => 11],
    18 => [ 'size' => 12],
    19 => [ 'size' => 12],
    20 => [ 'size' => 12],
    21 => [ 'size' => 13],
    22 => [ 'size' => 13],
    23 => [ 'size' => 13],
    24 => [ 'size' => 14],
    25 => [ 'size' => 14],
    26 => [ 'size' => 14],
    27 => [ 'size' => 15],
    28 => [ 'size' => 15],
    29 => [ 'size' => 15],
    30 => [ 'size' => 16],
    31 => [ 'size' => 16],
    32 => [ 'size' => 16],
    33 => [ 'size' => 17],
    34 => [ 'size' => 17],
    35 => [ 'size' => 17],
	36 => [ 'size' => 18],
	37 => [ 'size' => 18],
	38 => [ 'size' => 18],
	39 => [ 'size' => 19],
	40 => [ 'size' => 19],
	41 => [ 'size' => 19],
	42 => [ 'size' => 20],
	43 => [ 'size' => 20],
	44 => [ 'size' => 20],
	45 => [ 'size' => 20],
	46 => [ 'size' => 20],
	47 => [ 'size' => 20],
	48 => [ 'size' => 20],
	49 => [ 'size' => 20],
	50 => [ 'size' => 20]
	
];


// 烟农成就等级
$config['yannong_type'] = [
    1 => ['name' => '初出茅庐', 'size' => 1, 'jian_time'=>1],
    2 => ['name' => '种植铜奖杯', 'size' => 200, 'jian_time'=>0.9],
    3 => ['name' => '种植银奖杯', 'size' => 1000, 'jian_time'=>0.85],
    4 => ['name' => '种植金奖杯', 'size' => 3000, 'jian_time'=>0.8],
];

// 制烟成就等级
$config['zhiyan_type'] = [
    1 => ['name' => '牛刀小试', 'size' => 1, 'jian_time'=>1],
    2 => ['name' => '制烟铜奖杯', 'size' => 15, 'jian_time'=>1],
    3 => ['name' => '制烟银奖杯', 'size' => 80, 'jian_time'=>0.9],
    4 => ['name' => '制烟金奖杯', 'size' => 200, 'jian_time'=>0.9],
];

// 交易成就等级
$config['jiaoyi_type'] = [
    1 => ['name' => '销售铜奖杯', 'size' => 1, 'shou_yi'=>0],
    2 => ['name' => '销售铜奖杯', 'size' => 40, 'shou_yi'=>2],
    3 => ['name' => '销售银奖杯', 'size' => 200, 'shou_yi'=>4],
    4 => ['name' => '销售金奖杯', 'size' => 500, 'shou_yi'=>6],
];

//品鉴成就等级
$config['pinjian_type'] = [
    1 => ['name' => '品鉴铜奖杯', 'size' => 1, 'jian_money'=>1],
    2 => ['name' => '品鉴铜奖杯', 'size' => 15, 'jian_money'=>0.95],
    3 => ['name' => '品鉴银奖杯', 'size' => 80, 'jian_money'=>0.9],
    4 => ['name' => '品鉴金奖杯', 'size' => 200, 'jian_money'=>0.85],
];

// 仓库升级 （虽然数组的字段为“money”,但是消耗的是乐豆）
$config['store_type'] = [
    1 => ['name' => '一级仓库', 'size' => 200,'upgrade_size' => 400, 'money' => 0],
    2 => ['name' => '二级仓库', 'size' => 250,'upgrade_size' => 450, 'money' => 100],
    3 => ['name' => '三级仓库', 'size' => 300,'upgrade_size' => 500, 'money' => 150],
    4 => ['name' => '四级仓库', 'size' => 350,'upgrade_size' => 550, 'money' => 200],
    5 => ['name' => '五级仓库', 'size' => 400,'upgrade_size' => 600, 'money' => 250],
    6 => ['name' => '六级仓库', 'size' => 450,'upgrade_size' => 650, 'money' => 300],
    7 => ['name' => '七级仓库', 'size' => 500,'upgrade_size' => 700, 'money' => 350],
    8 => ['name' => '八级仓库', 'size' => 550,'upgrade_size' => 750, 'money' => 400],
    9 => ['name' => '九级仓库', 'size' => 600,'upgrade_size' => 800, 'money' => 450],
    10 => ['name' => '十级仓库', 'size' => 650,'upgrade_size' => 850, 'money' => 500],
    11 => ['name' => '十一级仓库', 'size' => 700, 'upgrade_size' => 900,'money' => 550],
    12 => ['name' => '十二级仓库', 'size' => 750,'upgrade_size' => 950, 'money' => 600],
    13 => ['name' => '十三级仓库', 'size' => 800,'upgrade_size' => 1000, 'money' => 650],
    14 => ['name' => '十四级仓库', 'size' => 850,'upgrade_size' => 1050, 'money' => 700],
    15 => ['name' => '十五级仓库', 'size' => 900,'upgrade_size' => 1100, 'money' => 750],
    16 => ['name' => '十六级仓库', 'size' => 950,'upgrade_size' => 1150, 'money' => 800],
    17 => ['name' => '十七级仓库', 'size' => 1000,'upgrade_size' => 1200, 'money' => 850],
    18 => ['name' => '十八级仓库', 'size' => 1050,'upgrade_size' => 1250, 'money' => 900],
    19 => ['name' => '十九级仓库', 'size' => 1100,'upgrade_size' => 1300, 'money' => 950],
    20 => ['name' => '二十级仓库', 'size' => 1150,'upgrade_size' => 1350, 'money' => 1000],
    21 => ['name' => '二十一级仓库', 'size' => 1200,'upgrade_size' => 1400, 'money' => 1050],
    22 => ['name' => '二十二级仓库', 'size' => 1250,'upgrade_size' => 1450, 'money' => 1100],
    23 => ['name' => '二十三级仓库', 'size' => 1300,'upgrade_size' => 1500, 'money' => 1150],
    24 => ['name' => '二十四级仓库', 'size' => 1350,'upgrade_size' => 1550, 'money' => 1200],
    25 => ['name' => '二十五级仓库', 'size' => 1400,'upgrade_size' => 1600, 'money' => 1250],
    26 => ['name' => '二十六级仓库', 'size' => 1450,'upgrade_size' => 1650, 'money' => 1300],
    27 => ['name' => '二十七级仓库', 'size' => 1500,'upgrade_size' => 1700, 'money' => 1350],
    28 => ['name' => '二十八级仓库', 'size' => 1550,'upgrade_size' => 1750, 'money' => 1400],
    29 => ['name' => '二十九级仓库', 'size' => 1600,'upgrade_size' => 1800, 'money' => 1450],
    30 => ['name' => '三十级仓库', 'size' => 1650,'upgrade_size' => 1850, 'money' => 1500],
    31 => ['name' => '三十一级仓库', 'size' => 1700,'upgrade_size' => 1900, 'money' => 1550],
    32 => ['name' => '三十二级仓库', 'size' => 1750,'upgrade_size' => 1950, 'money' => 1600],
    33 => ['name' => '三十三级仓库', 'size' => 1800,'upgrade_size' => 2000, 'money' => 1650],
    34 => ['name' => '三十四级仓库', 'size' => 1850,'upgrade_size' => 2050, 'money' => 1700],
    35 => ['name' => '三十五级仓库', 'size' => 1900,'upgrade_size' => 2100, 'money' => 1750],
    36 => ['name' => '三十六级仓库', 'size' => 1950,'upgrade_size' => 2150, 'money' => 1800],
    37 => ['name' => '三十七级仓库', 'size' => 2000,'upgrade_size' => 2200, 'money' => 1850],
    38 => ['name' => '三十八级仓库', 'size' => 2050,'upgrade_size' => 2250, 'money' => 1900],
    39 => ['name' => '三十九级仓库', 'size' => 2100,'upgrade_size' => 2300, 'money' => 1950],
    40 => ['name' => '四十级仓库', 'size' => 2150,'upgrade_size' => 2350, 'money' => 2000],
    41 => ['name' => '四十一级仓库', 'size' => 2200,'upgrade_size' => 2400, 'money' => 2050],
    42 => ['name' => '四十二级仓库', 'size' => 2250,'upgrade_size' => 2450, 'money' => 2100],
    43 => ['name' => '四十三级仓库', 'size' => 2300,'upgrade_size' => 2500, 'money' => 2150],
];

// 升级奖励
$config['game_lv_prize'] = [
    1 => ['shopid'=>202, 'num'=>1, 'type'=>'zhongzi'],
    2 => ['shopid'=>607, 'num'=>1, 'type'=>'peifang'],
    3 => ['shopid'=>1007, 'num'=>2, 'type'=>'lvzui'],
    4 => ['shopid'=>606, 'num'=>1, 'type'=>'peifang'],
    5 => ['shopid'=>0, 'num'=>10, 'type'=>'shandian'],
    6 => ['shopid'=>203, 'num'=>1, 'type'=>'zhongzi'],
    7 => ['shopid'=>1006, 'num'=>1, 'type'=>'lvzui'],
    8 => ['shopid'=>605, 'num'=>1, 'type'=>'peifang'],
    9 => ['shopid'=>1005, 'num'=>1, 'type'=>'lvzui'],
    10 => ['shopid'=>0, 'num'=>15, 'type'=>'shandian'],
    11 => ['shopid'=>204, 'num'=>1, 'type'=>'zhongzi'],
    12 => ['shopid'=>604, 'num'=>1, 'type'=>'peifang'],
    13 => ['shopid'=>1004, 'num'=>1, 'type'=>'lvzui'],
    14 => ['shopid'=>603, 'num'=>1, 'type'=>'peifang'],
    15 => ['shopid'=>0, 'num'=>20, 'type'=>'shandian'],
    16 => ['shopid'=>205, 'num'=>1, 'type'=>'zhongzi'],
    17 => ['shopid'=>1003, 'num'=>1, 'type'=>'lvzui'],
    18 => ['shopid'=>602, 'num'=>1, 'type'=>'peifang'],
    19 => ['shopid'=>1002, 'num'=>1, 'type'=>'lvzui'],
    20 => ['shopid'=>0, 'num'=>25, 'type'=>'shandian'],
    21 => ['shopid'=>201, 'num'=>1, 'type'=>'zhongzi'],
    22 => ['shopid'=>601, 'num'=>1, 'type'=>'peifang'],
    23 => ['shopid'=>1001, 'num'=>1, 'type'=>'lvzui'],
    24 => ['shopid'=>212, 'num'=>1, 'type'=>'zhongzi'],
    25 => ['shopid'=>0, 'num'=>30, 'type'=>'shandian'],
    26 => ['shopid'=>617, 'num'=>1, 'type'=>'peifang'],
    27 => ['shopid'=>1007, 'num'=>2, 'type'=>'lvzui'],
    28 => ['shopid'=>616, 'num'=>1, 'type'=>'peifang'],
    29 => ['shopid'=>213, 'num'=>1, 'type'=>'zhongzi'],
    30 => ['shopid'=>0, 'num'=>40, 'type'=>'shandian'],
    31 => ['shopid'=>1006, 'num'=>2, 'type'=>'lvzui'],
    32 => ['shopid'=>615, 'num'=>1, 'type'=>'peifang'],
    33 => ['shopid'=>1005, 'num'=>2, 'type'=>'lvzui'],
    34 => ['shopid'=>214, 'num'=>1, 'type'=>'zhongzi'],
    35 => ['shopid'=>0, 'num'=>50, 'type'=>'shandian'],
    36 => ['shopid'=>614, 'num'=>1, 'type'=>'peifang'],
    37 => ['shopid'=>1004, 'num'=>2, 'type'=>'lvzui'],
    38 => ['shopid'=>613, 'num'=>1, 'type'=>'peifang'],
    39 => ['shopid'=>215, 'num'=>1, 'type'=>'zhongzi'],
    40=> ['shopid'=>0, 'num'=>60, 'type'=>'shandian'],
    41=> ['shopid'=>1003, 'num'=>2, 'type'=>'lvzui'],
    42=> ['shopid'=>612, 'num'=>1, 'type'=>'peifang'],
    43=> ['shopid'=>1002, 'num'=>2, 'type'=>'lvzui'],
    44=> ['shopid'=>211, 'num'=>1, 'type'=>'zhongzi'],
    45=> ['shopid'=>0, 'num'=>70, 'type'=>'shandian'],
    46=> ['shopid'=>611, 'num'=>1, 'type'=>'peifang'],
    47=> ['shopid'=>1001, 'num'=>2, 'type'=>'lvzui'],
    48=> ['shopid'=>221, 'num'=>1, 'type'=>'zhongzi'],
    49=> ['shopid'=>621, 'num'=>1, 'type'=>'peifang'],
    50=> ['shopid'=>0, 'num'=>80, 'type'=>'shandian'],
];

// 种子培育槽扩展
$config['peiyu_type'] = [
    4 => [ 'money' => 10000,'pic'=>'','game_lv'=>15],//升级到4个，需要10000银元
    5 => [ 'money' => 20000, 'pic'=>'','game_lv'=>25],//升级到5个，需要20000个银元
    6 => [ 'money' => 50000,'pic'=>'','game_lv'=>35],//升级到6个，需要50000个银元
];

// 醇化室升级
$config['chun_type'] = [
    1 => [ 'money' => 0,'work_time'=>9], //work_time醇化时间，9分钟
    2 => [ 'money' => 20000, 'work_time'=>8],//升级到中级，需要20个乐豆
    3 => [ 'money' => 50000, 'work_time'=>6],//升级到高级，需要60个乐豆
];

//升级实体烟花费乐豆
$config['st_yan_type'] = [
    1 => [ 'money' => 170],         //需要170乐豆
    2 => [ 'money' => 100],         //需要100乐豆
    3 => [ 'money' => 72],          //需要72乐豆
    4 => [ 'money' => 64],          //需要64乐豆
    5 => [ 'money' => 50],          //需要50乐豆
    6 => [ 'money' => 40],          //需要40乐豆
    7 => [ 'money' => 20],          //需要20乐豆
];

//烘烤需要的时间（只跟烟叶的等级有关，与产地无关）
$config['bake_time'] = [
    1 => 90,                        //一星需要90秒
    2 => 180,                       //二星需要180秒
    3 => 300,                       //三星需要300秒
    4 => 450,                       //四星需要450秒
    5 => 720                        //五星需要720秒
];

//醇化需要的时间（只跟烟叶的等级有关，与产地无关）
$config['aging_time'] = [
    1 => 150,                       //一星需要150秒
    2 => 300,                       //二星需要300秒
    3 => 480,                       //三星需要480秒
    4 => 600,                       //四星需要600秒
    5 => 1080                       //五星需要1080秒
];

//加工需要的时间（只跟烟叶的等级有关，与产地无关）
$config['process_time'] = [
    1 => 3600,                       //一星需要3600秒
    2 => 6000,                       //二星需要6000秒
    3 => 9600,                       //三星需要9600秒
    4 => 14400,                      //四星需要14400秒
    5 => 21600                       //五星需要21600秒
];

//醇化加速
$config['chun_jiasu_type'] = [
    1 => [ 'money' => 30],          //需要30个闪电
    2 => [ 'money' => 20],          //需要20个闪电
    3 => [ 'money' => 10],          //需要10个闪电
];

//制烟加速
$config['process_jiasu_type'] = [
    1 => [ 'money' => 15],          //需要15个闪电
    2 => [ 'money' => 45],          //需要45个闪电
    3 => [ 'money' => 105],         //需要105个闪电
    4 => [ 'money' => 180],         //需要180个闪电
    5 => [ 'money' => 300],         //需要300个闪电
];

//种植加速
$config['seed_jiasu_type'] = [
    1 => [ 'money' => 20],
    2 => [ 'money' => 40],
    3 => [ 'money' => 60],
    4 => [ 'money' => 80],
    5 => [ 'money' => 100],
];

//制烟机器分类
$config['zulin_type'] = [
    1 => ['name' => '普通制烟机', 'money' => 20000,'pic'=>''],
    2 => ['name' => '中级制烟机', 'money' => 30000,'pic'=>''],
    3 => ['name' => '高级制烟机', 'money' => 40000,'pic'=>''],
];

// 种子培育中心解锁条件
$config['unlock_peiyu_term'] = ['grade_lv'=>8, 'ledou'=>200, 'money'=>20000];

// 调香研究所解锁条件
$config['unlock_peifang_term'] = ['grade_lv'=>10, 'ledou'=>250, 'money'=>25000];

// 路边小摊心解锁条件
$config['unlock_market_term'] = ['grade_lv'=>15, 'ledou'=>500, 'money'=>50000];

// 间谍系统解锁条件
$config['unlock_jiandie_term'] = ['grade_lv'=>13, 'ledou'=>0, 'money'=>0];

// 神秘商行商品解锁条件
$config['shenmi_shop_type2'] = [
    0 => ['start_lv'=>0,'end_lv'=>15,'type2'=>'2,3'],
    1 => ['start_lv'=>16,'end_lv'=>30,'type2'=>'2,3,4'],
    2 => ['start_lv'=>31,'end_lv'=>1000,'type2'=>'2,3,4,5']
];


//节日配置
$config['holiday_gift'] = [
    'money'=>10000,
    'shandian'=>100,
    'shopid'=> 645,
    'shop_num'=>10
];


//龙币兑换乐豆，奖励道具配置
$config['prop_config'] = [
    1=>[
        'money'=>800,
        'shandian'=>0,
        'shopid'=>602,
        'shop_num'=>1],
    2=>[
        'money'=>1600,
        'shandian'=>0,
        'shopid'=>605,
        'shop_num'=>2
    ],
    3=>[
        'money'=>3500,
        'shandian'=>0,
        'shopid'=>613,
        'shop_num'=>2
    ],
    4=>[
        'money'=>6000,
        'shandian'=>0,
        'shopid'=>614,
        'shop_num'=>3
    ],
    5=>[
        'money'=>12000,
        'shandian'=>0,
        'shopid'=>621,
        'shop_num'=>1
    ]



];

// 乐豆购买闪电赠送
$config['present_shandain'] = [
    100 => 10,
    300 => 42,
    680 => 128,
    1280 => 300,
    3000 => 888
];

// 乐豆购买银元赠送
$config['present_money'] = [
    100 => 1000,
    300 => 4200,
    680 => 12800,
    1280 => 30000,
    3000 => 88888
];

// 订单选取的初始概率
$config['order_ranking_init'] = [
    1 => 64,
    2 => 28,
    3 => 5,
    4 => 2,
    5 => 1
];

// 订单选取随等级变化的概率
$config['order_ranking_change'] = [
    1 => -0.008,
    2 => -0.002,
    3 => 0.6,
    4 => 0.24,
    5 => 0.16
];

//不同等级烟叶收割获得经验
$config['gather_xp'] = [
    1 => 5,
    2 => 10,
    3 => 20,
    4 => 35,
    5 => 60
];

//不同等级烟叶烘烤收取获得经验
$config['bake_xp'] = [
    1 => 6,
    2 => 12,
    3 => 22,
    4 => 38,
    5 => 62
];

//不同等级烟叶醇化收取获得经验
$config['aging_xp'] = [
    1 => 8,
    2 => 18,
    3 => 32,
    4 => 50,
    5 => 72
];

//不同等级香烟加工收取获得经验
$config['process_xp'] = [
    1 => 100,
    2 => 180,
    3 => 320,
    4 => 540,
    5 => 800
];

// 物品分类2 等级
$config['shop_type2'] = [
    0 => '其他',
    1 => '一星/戊级',
    2 => '二星/丁级',
    3 => '三星/丙级',
    4 => '四星/乙级',
    5 => '五星/甲级',
];

// 土地等级
$config['land_type'] = [
    1 => '初级',
    2 => '中级',
    3 => '高级',
];

// 数据表的 状态
$config['shop_status'] = [
    0 => '普通商行',
    1 => '神秘商行',
    2 => '不可购买',
];

// 虫子等级
$config['chongzi_type'] = [
    1 => '小虫',
    2 => '大虫',
    3 => '巨无霸虫',
];

//消耗类型
$config['spend_type'] = [
    1 => '购买商品',
    2 => '使用乐豆兑换银元',
    3 => '使用乐豆购买闪电',
    4 => '仓库升级',
    5 => '',
    6 => '租赁制烟机',
    7 => '扩展培育中心的培育槽数量',
    8 => '品鉴花费',
    9 => '升级实体烟',
    10 => '个人刷新订单',
    11 => '个人熟悉神秘商店',
    12 => '每日抽奖',
    13 => '每日签到',
    14 => '种子种植加速',
    15 => '烘烤室加速',
    16 => '醇化加速',
    17 => '制烟工厂加速',
    18 => '土地升级',
    19 => '出售商品给商行',
    20 => '',
    21 => '租赁间谍',
    22 => '',
    23 => '',
    24 => '',
    25 => '',
    26 => '',
    27 => '',
    28 => '',
    29 => '解锁间谍、解锁调香书合成中心、解锁培育中心、解锁路边摊、',
    30 => '购买虫子',
    31 => '领取龙币兑换乐豆道具',
    32 => '好运临门'

];

//种植收获烟叶时候，积分奖励配置
$config['yanye_jifen'] = [
    1 => 2,      //收获一星烟叶获得2个积分
    2 => 6,      //收获二星烟叶获得6个积分
    3 => 10,     //收获三星烟叶获得10个积分
    4 => 18,     //收获四星烟叶获得18个积分
    5 => 28      //收获五星烟叶获得28个积分
];

//制烟收获香烟时候，积分奖励配置
$config['yan_jifen'] = [
    1 => 10,      //收获一星香烟获得2个积分
    2 => 25,      //收获二星香烟获得6个积分
    3 => 55,      //收获三星香烟获得10个积分
    4 => 130,     //收获四星香烟获得18个积分
    5 => 300      //收获五星香烟获得28个积分
];

// 星期
$config['week'] = [
    1 => '星期一',
    2 => '星期二',
    3 => '星期三',
    4 => '星期四',
    5 => '星期五',
    6 => '星期六',
    7 => '星期日',
];


// 性别
$config['sex'] = [
    0 => '未知',
    1 => '男',
    2 => '女'
];

// 数据表的 状态
$config['status'] = [
    0 => '正常',
    1 => '锁定',
];


// 管理员分类
$config['admin_type'] = [
    1 => '超级管理',
    2 => '普通管理员'
];

// 新闻分类
$config['news_type'] = [
    1 => '国内新闻',
    2 => '国际资讯'
];

// 会员分类
$config['user_type'] = [
    1 => '普通会员',
    2 => '高级会员'
];

// 会员分类
$config['istop'] = [
    0 => '是否推荐',
    1 => '首页推荐',
];
//活动时间
$config['activity_time'] = [
    'start_time'=>"2019-02-05 00:00:00",
    'end_time'=>"2019-02-11 23:59:59",
    'turntable_starttime'=>"2019-01-28 00:00:00",
    'turntable_endtime'=> '2019-02-10 23:59:59',
    'national_starttime'=>"2019-09-25 23:30:00",
    'national_endtime'=>"2019-10-26 23:59:59"
];

//碎片活动时间
$config['suipian_time'] = [
    'start_time'=>'2019-05-06 00:00:00',
    'end_time'=>'2019-07-31 23:59:59'
];

//碎片 概率
$config['fragment_rate'] = [
	'task' => ['name'=>'每日任务','rate'=>-1],
	'orders' => ['name'=>'订单栏','rate'=>50],
	'plant' => ['name'=>'种植','rate'=>5],
	'bake' => ['name'=>'烘烤','rate'=>25],
	'aging' => ['name'=>'醇化','rate'=>25],
	'zhiyan' => ['name'=>'制烟','rate'=>80],
	'wb' => ['name'=>'欢乐挖宝','rate'=>60],
	'xxl' => ['name'=>'消消乐','rate'=>60],

];

$config['suipian_type'] = [
	1=>['id'=>1,'name'=>'碎片A','rate'=>16.7],
	2=>['id'=>2,'name'=>'碎片B','rate'=>16.7],
	3=>['id'=>3,'name'=>'碎片C','rate'=>16.7],
	4=>['id'=>4,'name'=>'碎片D','rate'=>16.7],
	5=>['id'=>5,'name'=>'碎片E','rate'=>16.6],
	6=>['id'=>6,'name'=>'碎片F','rate'=>16.6],

];
//新用户扫码礼包
$config['newer_scan_gift'] = [
	'money'=> 80000,
	'shandian' => 300,
	'shop1' => 622,
	'shop1_total' => 3,
	'shop2' => 225,
	'shop2_total' => 8,
    'shop3' => 523,
    'shop3_total' => 8,
];

$config['prize_task'] = [
    'task_1'=>['game_lv'=>3,'prize_quan'=>50],//被邀请玩家达到3级以上,老玩家奖励50奖券
    'task_2'=>['game_lv'=>5,'prize_quan'=>15],//被邀请玩家达到10级以上,老玩家奖励15奖券
    'task_3'=>['game_lv'=>10,'prize_quan'=>20], //被邀请玩家达到20级以上,老玩家奖励20奖券
    'task_4'=>['game_lv'=>15,'prize_quan'=>25], //被邀请玩家达到20级以上,老玩家奖励25奖券
    'task_5'=>['game_lv'=>18,'prize_quan'=>30], //被邀请玩家达到20级以上,老玩家奖励30奖券
    'task_6'=>['game_lv'=>20,'prize_quan'=>50] //被邀请玩家达到20级以上,老玩家奖励50奖券

];

//召集制烟师活动时间
$config['laxin_time'] = [
    'start_time' => '2019-09-09 00:00:00',
    'end_time' => '2019-11-30 23:59:59'
];

//游戏等级奖励
$config['newer_game_lv_prize'] = [

    1 => ['shandian' => 6,'money'=>600,'ticket_num'=>11],
    2 => ['shandian' => 8,'money'=>700,'ticket_num'=>12],
    3 => ['shandian' => 10,'money'=>800,'ticket_num'=>13],
    4 => ['shandian' => 12,'money'=>900,'ticket_num'=>14],
    5 => ['shandian' => 14,'money'=>1000,'ticket_num'=>15],
    6 => ['shandian' => 16,'money'=>1100,'ticket_num'=>16],
    7 => ['shandian' => 18,'money'=>1200,'ticket_num'=>17],
    8 => ['shandian' => 20,'money'=>1300,'ticket_num'=>18],
    9 => ['shandian' => 22,'money'=>1400,'ticket_num'=>19],
    10 => ['shandian' => 24,'money'=>1600,'ticket_num'=>20],
    11 => ['shandian' => 26,'money'=>1800,'ticket_num'=>21],
    12 => [ 'shandian' => 28,'money'=>2000,'ticket_num'=>22],
    13 => [ 'shandian' => 30,'money'=>2200,'ticket_num'=>23],
    14 => ['shandian' => 32,'money'=>2400,'ticket_num'=>24],
    15 => ['shandian' => 34,'money'=>2600,'ticket_num'=>25],
    16 => ['shandian' => 36,'money'=>2800,'ticket_num'=>26],
    17 => ['shandian' => 38,'money'=>3000,'ticket_num'=>27],
    18 => [ 'shandian' => 40,'money'=>3200,'ticket_num'=>28],
    19 => ['shandian' => 45,'money'=>3400,'ticket_num'=>29],
    20 => ['shandian' => 50,'money'=>3600,'ticket_num'=>30],

];

//拉新活动奖品限购次数
$config['limit_times'] = [
    394=>1,
    395=>1,
    396=>1,
    397=>1,
    398=>1,
    399=>1,
    400=>1,
    401=>1,
    402=>1,
    403=>1,
    404=>1,
    405=>1,
    406=>1,
    407=>1,
    408=>1,
    409=>3,
    410=>1,
    411=>1,
    412=>1
];

//国庆活动
$config['national_day'] = [
    ['money'=>7777,'shopid'=>'','shop_num'=>'','shandian'=>''],
    ['money'=>'','shopid'=>241,'shop_num'=>7,'shandian'=>''],
    ['money'=>'','shopid'=>244,'shop_num'=>7,'shandian'=>''],
    ['money'=>'','shopid'=>242,'shop_num'=>7,'shandian'=>''],
    ['money'=>'','shopid'=>243,'shop_num'=>7,'shandian'=>''],
    ['money'=>'','shopid'=>622,'shop_num'=>7,'shandian'=>''],
    ['money'=>'','shopid'=>'','shop_num'=>'','shandian'=>77]
];


//春节叠叶子活动时间
$config['diemouse_time'] = [
	'start_time' => '2020-04-15 23:00:00',
	'end_time' => '2020-05-31 23:59:59'
];



$config['sunshine_value'] = [
	1=> ['id'=>1,'num'=>2000],
	2=> ['id'=>2,'num'=>3000],
	3=> ['id'=>3,'num'=>4000],
	4=> ['id'=>4,'num'=>5000],
	5=> ['id'=>5,'num'=>6000],
	6=> ['id'=>6,'num'=>7000],
	7=> ['id'=>7,'num'=>8000]
];

$config['building_jiasu'] = [
    'aging' => 0.8,
    'zhiyan' => 0.8,
    'peiyu' => 0.8,
    'bake' => 0.8
];

$config['building_upgrade'] = [
    0=>'未升级',
    1=>'升级中',
    2=>'已升级'
];

//建筑升级时间
$config['building_time'] =   21600; //120;  //6小时


//烟叶能量等级
$config['trees'] = [
    1=>['type'=>1,'number' => 10],
    2=>['type'=>2,'number' => 15],
    3=>['type'=>3,'number' => 20],
    4=>['type'=>4,'number' => 30],
    5=>['type'=>5,'number' => 40]
];

$config['trees_config'] =
    [
        'trees_time'=>3600*8,//能量球时间
        'trees_max' => 200,//能量球最大值
        'trees_min' => 120//能量球剩余最小值
    ];