/**
 * Created by lkl on 2017/2/10.
 */
var config = {
    MaxLevel:50,//角色最高等级
    BaseURL:"http://gametest.gxziyun.com/yccq/api/",//外网
    WabaoURL:"http://gametest.gxziyun.com/yccq/client/wabao",
    XiaoxiaoleURL:"http://gametest.gxziyun.com/yccq/client/xxl",
    //XiaoxiaoleURL:"http://192.168.1.149/client/xxl",
    //BaseURL:"http://192.168.1.149/yccq/api/",//小杨
    //BaseURL:"http://yccq.a.com/api/",//本机
    //WabaoURL:"http://192.168.1.149/client/wabao",
	BeanGoods:"http://ld.thewm.cn/zlbean/frontpage/beanGoods/goodsList",
	BeanMyList:"http://ld.thewm.cn/zlbean/frontpage/beanOrder/myOrderList",
    ShopMaxBuyNum:99,//商店一次性最多购买数量
    MaxOrderNum:9,//最多订单持有数量
    Dengji:{1:'甲级',2:'乙级',3:'丙级',4:'丁级',5:'戊级'},//种子叶子等级
    Xingji:{1:'一星',2:'二星',3:'三星',4:'四星',5:'五星'},//调香书、香烟星级
    AgingRoomLevel:['普通醇化室','中级醇化室','高级醇化室'],//醇化室等级
    AgingRoomMaxItem:[4,5,6],//单次最多可醇化片数
    AgingRoomNeedTime:[9,6,3],//醇化所需时间（分钟）
    AgingRoomLossFactor:[0.1,0.07,0.03],//烟叶损耗率
    AgingRoomUpgrade:[0,20000,50000],//升级花费（银元）
    AgingRoomSpeedUp:[30,20,10],//加速花费（乐豆）
    BakingRoomMaxItem:[3,4,5],
    BakingRoomUpgrade:[0,20,60],//升级花费（乐豆）
    FactoryNeedTime:[0.5,40,60,80,120],
    FactorySpeedUp:[60,140,240,340,440],
    landPosIndex : [[16,35],[17,35],[18,35],[19,35],[16,34],[17,34],[18,34],[19,34],[16,33],[17,33],[18,33],[19,33],[16,32],[17,32],[18,32],[19,32],[16,31],[17,31],[18,31],[19,31],[16,30],[17,30],[18,30],[19,30],[16,29],[17,29],[18,29],[19,29]],//土地在地图上的格子坐标数组
    landUpgrade:{'101':{ledou:10000,shijian:'10%'},'102':{ledou:20000,shijian:'20%'}},//升级土地费用
    LandSpeedUp:[20,40,60,80,100],
    StoreUpdate:[
        {name:'一级仓库',size:200, money :0},
        {name:'二级仓库',size:250, money :100},
        {name:'三级仓库',size:300, money :150},
        {name:'四级仓库',size:350, money :200},
        {name:'五级仓库',size:400, money :250},
        {name:'六级仓库',size:450, money :300},
        {name:'七级仓库',size:500, money :350},
        {name:'八级仓库',size:550, money :400},
        {name:'九级仓库',size:600, money :450},
        {name:'十级仓库',size:650, money :500},
        {name:'十一级仓库',size:700, money :550},
        {name:'十二级仓库',size:750, money :600},
        {name:'十三级仓库',size:800, money :650},
        {name:'十四级仓库',size:850, money :700},
        {name:'十五级仓库',size:900, money :750},
        {name:'十六级仓库',size:950, money :800},
        {name:'十七级仓库',size:1000, money :850},
    ],
    PeiYuUpate:{'4':10000,'5':20000,'6':50000},
    PeiYuLevel:{'4':15,'5':25,'6':35},
    Recharge:{money:[
                    {icon:'recharge/jinbi_1.png',num:10000,song:1000,bean:100},
                    {icon:'recharge/jinbi_2.png',num:30000,song:4200,bean:300},
                    {icon:'recharge/jinbi_3.png',num:68000,song:12800,bean:680},
                    {icon:'recharge/jinbi_4.png',num:128000,song:30000,bean:1280},
                    {icon:'recharge/jinbi_5.png',num:300000,song:88888,bean:3000}
                ],
                shandian:[
                    {icon:'recharge/shandianlibao_1.png',num:100,song:10,bean:100},
                    {icon:'recharge/shandianlibao_2.png',num:300,song:42,bean:300},
                    {icon:'recharge/shandianlibao_3.png',num:680,song:128,bean:680},
                    {icon:'recharge/shandianlibao_4.png',num:1280,song:300,bean:1280},
                    {icon:'recharge/shandianlibao_5.png',num:3000,song:888,bean:3000}
                ]},
    Achievement:{
        'Yannong':{name:['初出茅庐奖章','种瓜得瓜一星奖章','兢兢业业二星奖章','种植能手三星奖章'],icon:['userinfo/Plant0.png','userinfo/Plant1.png','userinfo/Plant2.png','userinfo/Plant3.png'],needNum:[1,200,1000,3000],text:['无','种植时间缩短10%','种植时间缩短15%','种植时间缩短20%']},
        'Zhiyan':{name:['牛刀小试奖章','熟能生巧一星奖章','游刃有余二星奖章','制烟专家三星奖章'],icon:['userinfo/Zhiyan0.png','userinfo/Zhiyan1.png','userinfo/Zhiyan2.png','userinfo/Zhiyan3.png'],needNum:[1,15,80,200],text:['无','解锁中级制烟机器','加工时间缩短10%','解锁高级制烟机器，加工时间缩短10%']},
        'Jiaoyi':{name:['第一桶金奖章','生财有道一星奖章','财源滚滚二星奖章','销售精英三星奖章'],icon:['userinfo/Jiaoyi0.png','userinfo/Jiaoyi1.png','userinfo/Jiaoyi2.png','userinfo/Jiaoyi3.png'],needNum:[1,40,200,500],text:['无','订单收益加成2%','订单收益加成4%','订单收益加成6%']},
        'Pinjian':{name:['初露头角奖章','小有成就一星奖章','名声鹊起二星奖章','品鉴大师三星奖章'],icon:['userinfo/Pinjian0.png','userinfo/Pinjian1.png','userinfo/Pinjian2.png','userinfo/Pinjian3.png'],needNum:[1,15,80,200],text:['无','品鉴花费降低5%','品鉴花费降低10%','品鉴花费降低15%'],jiangli:[1,0.95,0.9,0.85]}
    },//成就
    PinJian:[500,500,500,500,500],
    Rongshu : [[28,40],[34,40],[35,39],[37,29],[24,29],[8,23],[8,19],[11,21],[14,23],[14,17],[8,27],[10,31],[14,34]],

    Duihuan : {'3':800,'2':300,'1':150},

    contentArr : [
        '闪电:用于缩短种植、烘烤、醇化、制烟等环节的等待时间',
        '银元:用于购买种子、调香书、嘴棒等物品或升级、解锁建筑物等',
        '乐豆:可通过购买香烟扫描二维码获得乐豆，用于购买闪电、银元或升级、解锁建筑物等',
        '经验:可用于人物等级提升'
    ]

};


var ItemIcon = {
    BXseed:"icon/icon_64_64.png",//巴西种子
    BMseed:"icon/zhongzi_1_1.png",
    BMyan:"icon/icon_64_64.png",
    BMleaf:"icon/yanye_1_1.png",
    BMleaf_baked:"icon/kaoyanye_1_1.png",
    BMleaf_aging:"icon/chunyanye_1_1.png",
    SG:"bozhong/shou.png",
    BoZhong_diwen:"bozhong/diwen_2.png",
    BRLeaf_normal:"bakeroom/ye_1.png",
    BRLeaf_baked:"bakeroom/ye_2.png",
    Peiyu_bg1:"peiyu/peiyangmin_2.png",
    Peiyu_bg2:"peiyu/peiyangmin_1.png",
    MoneyIcon:'userinfo/lebi.png',
    BeanIcon:'userinfo/ledou.png',
    ShandianIcon:'userinfo/sandian.png',
    Xingxing:'tex/xingxing.png'
};

var building = {
    CangKu:"tex/cang3.png",
    MyHouse:"tex/xiaowu1.png",
    ZLShop:"tex/zlshop.png",
    AgingRoom:"tex/agingroom.png",
    BakingRoom:"tex/hongkaofang.png",
    Factory:"tex/zhiyanfang.png",
    Pinjian:"tex/pinjian.png",
    UpdateBuilding:"tex/80050.png",
    Peiyushi:"tex/peiyushi1.png",
    Yanjiusuo:"tex/yanjiusuo1.png",
    Tree_baishu:"tex/baishu.png",
    Tree_songshu:"tex/shongshu.png",
    Tree_rongshu:"tex/rongshu.png",
    Tree_liushu:"tex/liushu.png",
    Tree_zhiwu1:"tex/zhiwu1.png",
    Tree_zhiwu2:"tex/zhiwu2.png",
    Tree_zhiwu3:"tex/zhiwu3.png",
    Tree_zhiwu4:"tex/zhiwu4.png",
    Grass:"tex/zhiwu5.png",
    Liba_da:"tex/da_liba.png",
    Liba_xiao:"tex/xiao_liba.png",
    Shijie:"tex/shijie.png",
    ShijieSmall:"tex/xiao_shijie.png",
    Shitou:"tex/dashitou.png",
    ShitouSmall:"tex/xiaoshitou.png",
    LuBianTan:"tex/xiaotan.png",
    GongGaoLan:"tex/gonggaolan.png",
    YouLeChang:"tex/daditu_fang_11.png",
    ChouJiang:"tex/daditu_fang_12.png",
    NengLiangCao:"tex/nengliangcao.png",
	SuiPianGe:"tex/daditu_fragment.png",
};

var plant = {
    201:{young:{time:0,texture:"tex/bama_1.png"},growing:{time:60,texture:"tex/bama_2.png"},mature:{time:120,texture:"tex/bama_3_1.png"}},
    202:{young:{time:0,texture:"tex/baxi_1.png"},growing:{time:60,texture:"tex/baxi_2.png"},mature:{time:120,texture:"tex/baxi_3_1.png"}},
    203:{young:{time:0,texture:"tex/yungui_1.png"},growing:{time:60,texture:"tex/yungui_2.png"},mature:{time:120,texture:"tex/yungui_3_1.png"}},
    204:{young:{time:0,texture:"tex/lvsong_1.png"},growing:{time:60,texture:"tex/lvsong_2.png"},mature:{time:10,texture:"tex/lvsong_3_1.png"}},
    205:{young:{time:0,texture:"tex/jinbabuwei_1.png"},growing:{time:60,texture:"tex/jinbabuwei_2.png"},mature:{time:120,texture:"tex/jinbabuwei_3_1.png"}},
    211:{young:{time:0,texture:"tex/bama_1.png"},growing:{time:150,texture:"tex/bama_2.png"},mature:{time:300,texture:"tex/bama_3_2.png"}},
    212:{young:{time:0,texture:"tex/baxi_1.png"},growing:{time:150,texture:"tex/baxi_2.png"},mature:{time:300,texture:"tex/baxi_3_2.png"}},
    213:{young:{time:0,texture:"tex/yungui_1.png"},growing:{time:150,texture:"tex/yungui_2.png"},mature:{time:300,texture:"tex/yungui_3_2.png"}},
    214:{young:{time:0,texture:"tex/lvsong_1.png"},growing:{time:150,texture:"tex/lvsong_2.png"},mature:{time:300,texture:"tex/lvsong_3_2.png"}},
    215:{young:{time:0,texture:"tex/jinbabuwei_1.png"},growing:{time:150,texture:"tex/jinbabuwei_2.png"},mature:{time:300,texture:"tex/jinbabuwei_3_2.png"}},
    221:{young:{time:0,texture:"tex/bama_1.png"},growing:{time:240,texture:"tex/bama_2.png"},mature:{time:480,texture:"tex/bama_3_3.png"}},
    222:{young:{time:0,texture:"tex/baxi_1.png"},growing:{time:240,texture:"tex/baxi_2.png"},mature:{time:480,texture:"tex/baxi_3_3.png"}},
    223:{young:{time:0,texture:"tex/yungui_1.png"},growing:{time:240,texture:"tex/yungui_2.png"},mature:{time:480,texture:"tex/yungui_3_3.png"}},
    224:{young:{time:0,texture:"tex/lvsong_1.png"},growing:{time:240,texture:"tex/lvsong_2.png"},mature:{time:480,texture:"tex/lvsong_3_3.png"}},
    225:{young:{time:0,texture:"tex/jinbabuwei_1.png"},growing:{time:240,texture:"tex/jinbabuwei_2.png"},mature:{time:480,texture:"tex/jinbabuwei_3_3.png"}},
    231:{young:{time:0,texture:"tex/bama_1.png"},growing:{time:390,texture:"tex/bama_2.png"},mature:{time:780,texture:"tex/bama_3_4.png"}},
    232:{young:{time:0,texture:"tex/baxi_1.png"},growing:{time:390,texture:"tex/baxi_2.png"},mature:{time:780,texture:"tex/baxi_3_4.png"}},
    233:{young:{time:0,texture:"tex/yungui_1.png"},growing:{time:390,texture:"tex/yungui_2.png"},mature:{time:780,texture:"tex/yungui_3_4.png"}},
    234:{young:{time:0,texture:"tex/lvsong_1.png"},growing:{time:390,texture:"tex/lvsong_2.png"},mature:{time:780,texture:"tex/lvsong_3_4.png"}},
    235:{young:{time:0,texture:"tex/jinbabuwei_1.png"},growing:{time:390,texture:"tex/jinbabuwei_2.png"},mature:{time:780,texture:"tex/jinbabuwei_3_4.png"}},
    241:{young:{time:0,texture:"tex/bama_1.png"},growing:{time:600,texture:"tex/bama_2.png"},mature:{time:1200,texture:"tex/bama_3_5.png"}},
    242:{young:{time:0,texture:"tex/baxi_1.png"},growing:{time:600,texture:"tex/baxi_2.png"},mature:{time:1200,texture:"tex/baxi_3_5.png"}},
    243:{young:{time:0,texture:"tex/yungui_1.png"},growing:{time:600,texture:"tex/yungui_2.png"},mature:{time:1200,texture:"tex/yungui_3_5.png"}},
    244:{young:{time:0,texture:"tex/lvsong_1.png"},growing:{time:600,texture:"tex/lvsong_2.png"},mature:{time:1200,texture:"tex/lvsong_3_5.png"}},
    245:{young:{time:0,texture:"tex/jinbabuwei_1.png"},growing:{time:600,texture:"tex/jinbabuwei_2.png"},mature:{time:1200,texture:"tex/jinbabuwei_3_5.png"}},
};

var ItemInfo = {

};
var game_level = null;