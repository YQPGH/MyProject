/**
 * Created by 41496 on 2018/1/10.
 */
//新版指引管理器
(function(){
    function ZhiYinManager(){
        this.loadSource();
        this.MyGame = Laya.stage.getChildByName('MyGame');
        this.map = this.MyGame.map;
        this.b_list = [this.MyGame.ZLShop,this.MyGame.landArr[4],this.MyGame.BakingRoom,this.MyGame.AgingRoom,this.MyGame.Factory,this.MyGame.Pinjian];
        console.log(this.b_list);
    }
    Laya.class(ZhiYinManager,'ZhiYinManager');
    var proto = ZhiYinManager.prototype;

    proto.loadSource = function()
    {
        Laya.loader.load('res/atlas/zhiyin.atlas',null);
    };

    proto.showZhiYin = function()
    {
        Laya.loader.load('res/atlas/zhiyin.atlas',Laya.Handler.create(this,function(){
            //ZhiYinMask.instance().close();
            switch(Number(ZhiYinManager.step1)){
                case 0:
                    this.showSelectRole();

                    break;
                case 1:
                    this.showWelcome();
                    break;
                case 2:
                    this.showGift();
                    break;
                case 3:
                    if(ZhiYinManager.step2 == 0)
                    {
                        this.showNpc(0);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 3)
                        {
                            ZhiYinMask.instance().ZhiYinBuilding(this.b_list[0],ZhiYinContent[0]);
                            ZhiYinMask.instance().popup();
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[0][0],ZhiYinNPC.building_pos[0][1],0);
                    }
                    break;
                case 4:
                    if(ZhiYinManager.step2 == 0)
                    {
                        this.showNpc(1);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 1 && this.b_list[1].land1.seed.isMature){
                            this.setGuideStep(4,2);
                            return;
                        }
                        if(ZhiYinManager.step2 == 2 && !this.b_list[1].land1.seed){
                            this.setGuideStep(5,0);
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[1][0],ZhiYinNPC.building_pos[1][1],1);
                    }
                    break;
                case 5:
                    this.b_list[2] = this.MyGame.BakingRoom;
                    if(ZhiYinManager.step2 == 0)
                    {
                        ZhiYinMask.instance().close();
                        this.showNpc(2);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 1 && this.b_list[2].ItemList[0].status == 2){
                            this.setGuideStep(5,2);
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[2][0],ZhiYinNPC.building_pos[2][1],2);
                    }
                    break;
                case 6:
                    this.b_list[3] = this.MyGame.AgingRoom;
                    if(ZhiYinManager.step2 == 0)
                    {
                        ZhiYinMask.instance().close();
                        this.showNpc(3);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 1 && this.b_list[3].ItemList[0].status == 2){
                            this.setGuideStep(6,2);
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[3][0],ZhiYinNPC.building_pos[3][1],3);
                    }
                    break;
                case 7:
                    this.b_list[4] = this.MyGame.Factory;
                    if(ZhiYinManager.step2 == 0)
                    {
                        ZhiYinMask.instance().close();
                        this.showNpc(4);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 2 && this.b_list[4].FactoryData[0].time <= 0){
                            this.setGuideStep(7,3);
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[4][0],ZhiYinNPC.building_pos[4][1],4);
                    }
                    break;
                case 8:
                    if(ZhiYinManager.step2 == 0)
                    {
                        ZhiYinMask.instance().close();
                        this.showNpc(5);
                    }else if(ZhiYinManager.step2 >= 1)
                    {
                        if(ZhiYinManager.step2 == 1)
                        {
                            this.setGuideStep(9,0);
                            return;
                        }
                        this.moveScreen(ZhiYinNPC.building_pos[5][0],ZhiYinNPC.building_pos[5][1],5);
                    }
                    break;
                case 9:
                    this.showDone();
                    break;
            }
        }));
    };

    //step 大步骤，index小步骤
    proto.setGuideStep = function(step,index,update)
    {
        ZhiYinManager.step1 = step;
        ZhiYinManager.step2 = index;
        Utils.post('guide/update',{uid:localStorage.GUID,step1:step,step2:index},this.onDataReturn,onHttpErr,update);
    };

    proto.onDataReturn = function(res,update) {
        if(res.code == 0){
            if(!update){
                ZhiYinManager.instance().showZhiYin();
            }
            if(ZhiYinManager.step1 >= 10){
                ZhiYinManager.instance().getGuideStep();
            }
        }
    };

    proto.getGuideStep = function()
    {
        Utils.post('guide/status',{uid:localStorage.GUID},this.onGuideStepReturn,onHttpErr);
    };

    proto.onGuideStepReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            if(res.data.step1 != 10)
            {
                ZhiYinManager.step1 = res.data.step1;
                ZhiYinManager.step2 = res.data.step2;
                ZhiYinManager.instance().showZhiYin();
            }
            if(res.data.step1 >= 10){
                ZhiYinManager.instance().showHolidays();
                ZhiYinManager.instance().showHuoDong();
                ZhiYinManager.instance().showDouble11();
                ZhiYinManager.instance().showDouble12();
                ZhiYinManager.instance().showKuma();
                ZhiYinManager.instance().showNewYearsDay();
                ZhiYinManager.instance().showScanNewer();
                //ZhiYinManager.instance().showNewYearsLogin();
            }
            ZhiYinManager.choujiang = res.data.choujiang;
            ZhiYinManager.dingdan = res.data.dingdan;
            ZhiYinManager.gonglue = res.data.gonglue;
            ZhiYinManager.jiandie = res.data.jiandie;
            ZhiYinManager.peiyu = res.data.peiyu;
            ZhiYinManager.tiaoxiang = res.data.tiaoxiang;
            ZhiYinManager.xiaotan = res.data.xiaotan;
        }
    };

    proto.showSelectRole = function()
    {
        var select_role = new SelectRole();
        select_role.popup();
    };

    proto.showWelcome = function()
    {
        var dialog = new welcome('intro');
        dialog.popup();
    };

    proto.showGift = function()
    {
        var dialog = new welcome('gift');
        dialog.popup();
    };

    proto.showDone = function()
    {
        var dialog = new welcome('done');
        dialog.popup();
    };

    proto.showNpc = function(type)
    {
        var ZY_NPC = new ZhiYinNPC(type);
        ZY_NPC.popupCenter = false;
        ZY_NPC.pos(0,Laya.stage.height-ZY_NPC.height+20);
        ZY_NPC.popup();
    };

    proto.moveScreen = function(col,row,type)
    {
        var building = this.b_list[type];
        this.map.mapMoveTo(col,row,this,function(){
            ZhiYinMask.instance().ZhiYinBuilding(building,ZhiYinContent[type]);
            ZhiYinMask.instance().popup();
        });
    };

    proto.showHuoDong = function(){
        Laya.loader.load([{url:'huodong/youxihuodongdatingdiban.png',type:Laya.Loader.IMAGE},{url:'res/atlas/huodong.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
            var dialog = new HuoDong();
            dialog.popup();
        }),null,Laya.Loader.TEXT);

    };

    proto.showHolidays = function(){
        Utils.post('User/queryHolidayGift',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                if(res.data.is_pop == 1){
                    Laya.loader.load([{url:'Holidays/yuandan_bg.png',type:Laya.Loader.IMAGE},{url:'Holidays/zhongqiu_btn.png',typr:Laya.Loader.IMAGE}],new Laya.Handler(this,function(){
                        var dialog = new Holidays(res.data.list);
                        dialog.popup();
                    }),null,Laya.Loader.TEXT);
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });
    };

    proto.showDouble11 = function()
    {
        Utils.post('gift/queryLdChangeGift',{uid:localStorage.GUID,activity_type:1},function(res){
            if(res.code == 0){

                Laya.loader.load([{url:'double11/bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/double11.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
                    if(res.data.choujiang && res.data.choujiang.length){
                        var dialog = new Doubel11(2,res.data.choujiang);
                        dialog.popup();
                    }

                    if(res.data.daoju && res.data.daoju.length){
                        var dialog = new Doubel11(1,res.data.daoju);
                        dialog.popup();
                    }

                }),null,Laya.Loader.TEXT);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });

    };

    proto.showDouble12 = function()
    {
        Utils.post('gift/queryLdChangeGift',{uid:localStorage.GUID,activity_type:2},function(res){
            if(res.code == 0){

                Laya.loader.load([{url:'double11/1212bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/double11.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
                    if(res.data.choujiang && res.data.choujiang.length){
                        var dialog = new Doubel12(2,res.data.choujiang);
                        dialog.popup();
                    }

                    if(res.data.daoju && res.data.daoju.length){
                        var dialog = new Doubel12(1,res.data.daoju);
                        dialog.popup();
                    }

                }),null,Laya.Loader.TEXT);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });

    };

    proto.showNewYearsDay = function()
    {
        Utils.post('gift/queryLdChangeGift',{uid:localStorage.GUID,activity_type:3},function(res){
            if(res.code == 0){

                Laya.loader.load([{url:'double11/newyearsday_bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/double11.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
                    if(res.data.choujiang && res.data.choujiang.length){
                        var dialog = new NewYearsDay(2,res.data.choujiang);
                        dialog.popup();
                    }

                    if(res.data.daoju && res.data.daoju.length){
                        var dialog = new NewYearsDay(1,res.data.daoju);
                        dialog.popup();
                    }

                }),null,Laya.Loader.TEXT);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });

    };

    proto.showNewYearsLogin = function()
    {
        Utils.post('LoginPrize/login_activity',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                if(res.data.is_pop == 1 && res.data.login_today == 0) {
                    Laya.loader.load([{url:'2019newyearlogin/denglu_bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/2019newyearlogin.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
                        var dialog = new NewYearLogin(res.data);
                        dialog.popup();
                    }),null,Laya.Loader.TEXT);
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });

    };

    proto.showKuma = function()
    {
        Utils.post('kuma/lists',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                if(res.data.length != 0) {
                    var dialog = new Kuma(res.data);
                    dialog.popup();
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });

    };

    proto.showScanNewer = function()
    {
        Utils.post('Fragment/newer_scan',{uid:localStorage.GUID},function (res) {
            if(res.code == 0){
                var data = [];
                data.push({item_icon:ItemIcon.MoneyIcon,item_name:'银元*'+res.data.money});
                data.push({item_icon:ItemIcon.ShandianIcon,item_name:'闪电*'+res.data.shandian});
                data.push({item_icon:ItemInfo[res.data.shop1].thumb,item_name:ItemInfo[res.data.shop1].name+'*'+res.data.shop1_total});
                data.push({item_icon:ItemInfo[res.data.shop2].thumb,item_name:ItemInfo[res.data.shop2].name+'*'+res.data.shop2_total});
                data.push({item_icon:ItemInfo[res.data.shop3].thumb,item_name:ItemInfo[res.data.shop3].name+'*'+res.data.shop3_total});
                data.push({item_icon:ItemInfo[res.data.suipian_shop].thumb,item_name:ItemInfo[res.data.suipian_shop].name+'*'+res.data.suipian_total});
                var dialog = new FragmentNewer(data);
                dialog.popup();
                dialog.closeHandler = new Laya.Handler(this,function(){
                    getMoney(res.data.money);
                    getShandian(res.data.shandian);
                    getItem([{shopid:res.data.shop1,num:res.data.shop1_total},{shopid:res.data.shop2,num:res.data.shop2_total},{shopid:res.data.shop3,num:res.data.shop3_total},{shopid:res.data.suipian_shop,num:res.data.suipian_total}]);
                });
            }
        },null);
    };


    ZhiYinManager.instance=function(){
        if (!ZhiYinManager._instance){
            ZhiYinManager._instance=new ZhiYinManager();
        }
        return ZhiYinManager._instance;
    };
    ZhiYinManager.mask = null;
    ZhiYinManager._instance=null;
    ZhiYinManager.step1 = 0;
    ZhiYinManager.step2 = 0;
    ZhiYinManager.choujiang = 0;
    ZhiYinManager.dingdan = 0;
    ZhiYinManager.gonglue = 0;
    ZhiYinManager.jiandie = 0;
    ZhiYinManager.peiyu = 0;
    ZhiYinManager.tiaoxiang = 0;
    ZhiYinManager.xiaotan = 0;
    var ZhiYinContent = [
        [
            [//step2 == 0
                {
                    dialog:'zldialog',
                    hit:{width:106,height:40,x:112,y:148},
                    tips:{x:205,y:40,skewX:0,skewY:0},

                    content:'点击调香书按钮'
                },
                {
                    dialog:'zldialog',
                    hit:{width:154,height:157,x:440,y:355},
                    tips:{x:19,y:239,skewX:0,skewY:180},
                    content:'选择一星基础调香书'
                },
                {
                    dialog:'zlbuy',
                    hit:{width:365,height:130,x:60,y:100},
                    tips:{x:-250,y:-20,skewX:0,skewY:180},
                    content:'此处可查看<br/>生产所需材料',
                    npc:'部分需要的烟叶已经在礼包中赠送,现在还<span color="#ae0626">缺少1份一星吕宋烟叶·醇</span>和<span color="#ae0626">10个一点红嘴棒</span>,我们先购买缺少烟叶的种子和一点红嘴棒吧!'
                },
                {
                    dialog:'zlbuy',
                    hit:{width:145,height:45,x:170,y:375},
                    tips:{x:300,y:270,skewX:0,skewY:0},
                    content:'先购买一份调香书'
                }
            ],
            [//step2 == 1
                {
                    dialog:'zldialog',
                    hit:{width:106,height:40,x:220,y:148},
                    tips:{x:300,y:40,skewX:0,skewY:0},
                    content:'点击种子按钮'
                },
                {
                    dialog:'zldialog',
                    hit:{width:154,height:157,x:600,y:189},
                    tips:{x:180,y:339,skewX:180,skewY:180},
                    content:'选择需要购买的种子'
                },
                {
                    dialog:'zlbuy',
                    hit:{width:145,height:45,x:170,y:375},
                    tips:{x:300,y:270,skewX:0,skewY:0},
                    content:'点击购买完成交易'
                }
            ],
            [//step2 == 2
                {
                    dialog:'zldialog',
                    hit:{width:106,height:40,x:328,y:148},
                    tips:{x:420,y:40,skewX:0,skewY:0},
                    content:'点击嘴棒按钮'
                },
                {
                    dialog:'zldialog',
                    hit:{width:154,height:157,x:440,y:355},
                    tips:{x:19,y:239,skewX:0,skewY:180},
                    content:'选择需要购买的嘴棒'
                },
                {
                    dialog:'zlbuy',
                    type:'circle',
                    radius:30,
                    hit:{x:314,y:253},
                    tips:{x:320,y:120,skewX:0,skewY:0},
                    content:'把数量调整到10'
                },
                {
                    dialog:'zlbuy',
                    hit:{width:145,height:45,x:170,y:375},
                    tips:{x:300,y:270,skewX:0,skewY:0},
                    content:'点击购买完成交易'
                }
            ],
            [
                {
                    dialog:null,
                    hit:{width:130,height:130,x:0,y:240},
                    tips:{x:100,y:200,skewX:0,skewY:0},
                    content:'点击进入'
                },
                {
                    dialog:'chaxun',
                    hit:{width:380,height:50,x:150,y:80},
                    tips:{x:235,y:130,skewX:180,skewY:0},
                    content:'二星及以上的调香书可到<span color="#ae0626">每日挑战</span>参与<span color="#ae0626">欢乐挖宝游戏</span>获得,也可以在<span color="#ae0626">调香书研究所</span>合成喔!',
                    fontSize:20
                },
                {
                    dialog:'chaxun',
                    hit:{width:90,height:50,x:55,y:80},
                    tips:{x:125,y:130,skewX:180,skewY:0},
                    content:'一星调香书与<br/>嘴棒原料均可到<br/><span color="#ae0626">真龙商行</span>购买'
                },
                {
                    dialog:'chaxun',
                    hit:{width:110,height:110,x:50,y:130},
                    tips:{x:135,y:80,skewX:0,skewY:0},
                    content:'先查看已拥有的一星基础调香书上记载的材料缺哪些吧。',
                    fontSize:24
                },
                {
                    dialog:'chaxun',
                    hit:{width:620,height:170,x:180,y:300},
                    tips:{x:385,y:180,skewX:0,skewY:0},
                    content:'<span color="#ae0626">红字部分</span>就是缺少的材料和数量哦~'
                },
                {
                    dialog:'chaxun',
                    type:'circle',
                    radius:35,
                    hit:{x:920,y:38},
                    tips:{x:430,y:60,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ],
            [//关闭
                {
                    dialog:'zldialog',
                    type:'circle',
                    radius:35,
                    hit:{x:847,y:30},
                    tips:{x:370,y:60,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ]
        ],
        [
            [
                {
                    dialog:null,
                    type:'poly',
                    points:[41,-5,107,-40,172,-8,105,28],
                    hit:{x:55,y:485},
                    tips:{x:200,y:350,skewX:0,skewY:0},
                    content:'选择一片<span color="#ae0626">空闲</span>土地<br/>进行种植'
                },
                {
                    dialog:'bozhong',
                    hit:{width:100,height:50,x:18,y:13},
                    tips:{x:105,y:-100,skewX:0,skewY:0},
                    content:'选择种子等级'
                },
                {
                    dialog:'bozhong',
                    hit:{width:100,height:100,x:18,y:65},
                    tips:{x:105,y:-50,skewX:0,skewY:0},
                    content:'选择需要种植的种子'
                },
                {
                    dialog:'bozhong',
                    hit:{width:160,height:70,x:530,y:92},
                    tips:{x:105,y:-20,skewX:0,skewY:180},
                    content:'点击<span color="#ae0626">种植</span>按钮<br/>完成种植'
                }
            ],
            [
                {
                    dialog:null,
                    type:'poly',
                    points:[41,-5,107,-40,172,-8,105,28],
                    hit:{x:55,y:485},
                    tips:{x:200,y:350,skewX:0,skewY:0},
                    content:'种植期间可<span color="#ae0626">花时间等候</span>或使用<span color="#ae0626">闪电加速</span>，<span color="#ae0626">本次加速免费</span>'
                },
                {
                    dialog:'plant',
                    hit:{width:120,height:50,x:70,y:112},
                    tips:{x:165,y:170,skewX:180,skewY:0},
                    content:'点击<span color="#ae0626">加速</span>按钮'
                },
                {
                    dialog:'confirm1',
                    hit:{width:130,height:50,x:60,y:140},
                    tips:{x:165,y:200,skewX:180,skewY:0},
                    content:'点击确定按钮'
                }
            ],
            [
                {
                    dialog:null,
                    type:'poly',
                    points:[41,-5,107,-40,172,-8,105,28],
                    hit:{x:55,y:485},
                    tips:{x:200,y:350,skewX:0,skewY:0},
                    content:'可以收获烟叶了!点击<span color="#ae0626">已经成熟</span>的烟叶'
                },
                {
                    dialog:'shouge',
                    hit:{width:350,height:65,x:20,y:125},
                    tips:{x:325,y:2,skewX:0,skewY:0},
                    content:'点击<span color="#ae0626">收获</span>或<span color="#ae0626">全部收获</span>按钮'
                }
            ]
        ],
        [
            [
                {
                    dialog:'hkdialog',
                    hit:{width:80,height:50,x:470,y:40},
                    tips:{x:60,y:80,skewX:180,skewY:180},
                    content:'选择<span color="#ae0626">一星</span>按钮'
                },
                {
                    dialog:'hkdialog',
                    hit:{width:100,height:135,x:485,y:100},
                    tips:{x:60,y:170,skewX:180,skewY:180},
                    content:'选择需要烘烤的烟叶'
                },
                {
                    dialog:'hkdialog',
                    hit:{width:140,height:60,x:665,y:425},
                    tips:{x:250,y:320,skewX:0,skewY:180},
                    content:'点击<span color="#ae0626">烘烤</span>后<br/>开始烘烤烟叶'
                }
            ],
            [
                {
                    dialog:'hkdialog',
                    hit:{width:140,height:60,x:512,y:423},
                    tips:{x:80,y:320,skewX:0,skewY:180},
                    content:'期间可<span color="#ae0626">花时间等候</span>或使用<span color="#ae0626">闪电加速</span>,<br/><span color="#ae0626">本次加速免费</span>'
                },
                {
                    dialog:'confirm1',
                    hit:{width:130,height:50,x:60,y:140},
                    tips:{x:165,y:200,skewX:180,skewY:0},
                    content:'点击确定按钮'
                }
            ],
            [
                {
                    dialog:'hkdialog',
                    hit:{width:140,height:60,x:665,y:425},
                    tips:{x:250,y:320,skewX:0,skewY:180},
                    content:'烘烤完成了,快收获烘烤好的烟叶!'
                }
            ],
            [//关闭
                {
                    dialog:'hkdialog',
                    type:'circle',
                    radius:35,
                    hit:{x:897,y:27},
                    tips:{x:420,y:60,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ]
        ],
        [
            [
                {
                    dialog:'chdialog',
                    hit:{width:80,height:50,x:502,y:55},
                    tips:{x:70,y:110,skewX:180,skewY:180},
                    content:'选择<span color="#ae0626">一星</span>按钮'
                },
                {
                    dialog:'chdialog',
                    hit:{width:100,height:135,x:528,y:120},
                    tips:{x:100,y:180,skewX:180,skewY:180},
                    content:'选择需要醇化的烟叶'
                },
                {
                    dialog:'chdialog',
                    hit:{width:140,height:60,x:710,y:440},
                    tips:{x:280,y:320,skewX:0,skewY:180},
                    content:'点击<span color="#ae0626">醇化</span>后<br>开始醇化烟叶'
                }
            ],
            [
                {
                    dialog:'chdialog',
                    hit:{width:140,height:60,x:550,y:438},
                    tips:{x:130,y:320,skewX:0,skewY:180},
                    content:'期间可<span color="#ae0626">花时间等候</span>或使用<span color="#ae0626">闪电加速</span>,<br/><span color="#ae0626">本次加速免费</span>'
                },
                {
                    dialog:'confirm1',
                    hit:{width:130,height:50,x:60,y:140},
                    tips:{x:165,y:200,skewX:180,skewY:0},
                    content:'点击确定按钮' 
                }
            ],
            [
                {
                    dialog:'chdialog',
                    hit:{width:140,height:60,x:710,y:440},
                    tips:{x:280,y:320,skewX:0,skewY:180},
                    content:'醇化完成了,快收获醇化好的烟叶吧!'
                }
            ],
            [//关闭
                {
                    dialog:'chdialog',
                    type:'circle',
                    radius:35,
                    hit:{x:930,y:35},
                    tips:{x:470,y:70,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ]
        ],
        [
            [
                {
                    dialog:'jgcdialog',
                    hit:{width:80,height:60,x:880,y:132},
                    tips:{x:430,y:170,skewX:180,skewY:180},
                    content:'终于来到生产工厂了,但是还没有机器!我们先去<span color="#ae0626">租赁</span>吧!'
                },
                {
                    dialog:'jgcdialog',
                    hit:{width:240,height:300,x:130,y:100},
                    tips:{x:350,y:200,skewX:180,skewY:0},
                    content:'选择<span color="#ae0626">初级制烟机器</span>'
                },
                {
                    dialog:'jgcdialog',
                    hit:{width:140,height:70,x:430,y:440},
                    tips:{x:530,y:320,skewX:0,skewY:0},
                    content:'点击确定即可租赁'
                },
                {
                    dialog:'jgcdialog',
                    hit:{width:80,height:60,x:880,y:212},
                    tips:{x:430,y:260,skewX:180,skewY:180},
                    content:'已经有机器了,我们快去<span color="#ae0626">生产</span>香烟吧!'
                }
            ],
            [
                {
                    dialog:'jgcdialog',
                    hit:{width:105,height:105,x:516,y:120},
                    tips:{x:160,y:240,skewX:180,skewY:180},
                    content:'点击加号添加原料'
                },
                {
                    dialog:'jgcpeifang',
                    hit:{width:95,height:50,x:30,y:10},
                    tips:{x:120,y:60,skewX:180,skewY:0},
                    content:'选择<span color="#ae0626">一星</span>按钮'
                },
                {
                    dialog:'jgcpeifang',
                    hit:{width:110,height:150,x:30,y:60},
                    tips:{x:140,y:200,skewX:180,skewY:0},
                    content:'选择<span color="#ae0626">一本调香书</span>'
                },
                {
                    dialog:'jgcpeifang',
                    hit:{width:780,height:190,x:100,y:250},
                    tips:{x:560,y:450,skewX:180,skewY:0},
                    content:'<span color="#00ff00">绿色</span>为已有材料<br/><span color="#ae0626">红色</span>为缺少材料'
                },
                {
                    dialog:'jgcpeifang',
                    hit:{width:140,height:60,x:380,y:460},
                    tips:{x:500,y:350,skewX:0,skewY:0},
                    content:'材料都收集好了<br/>可以<span color="#ae0626">开始</span>加工了'
                },
                {
                    dialog:'jgcdialog',
                    hit:{width:140,height:60,x:690,y:440},
                    tips:{x:270,y:340,skewX:0,skewY:180},
                    content:'点击<span color="#ae0626">开始生产</span>'
                }
            ],
            [
                {
                    dialog:'jgcdialog',
                    hit:{width:120,height:60,x:545,y:440},
                    tips:{x:120,y:340,skewX:0,skewY:180},
                    content:'期间可<span color="#ae0626">花时间等候</span><br/>或使用<span color="#ae0626">闪电加速</span>,<br/><span color="#ae0626">本次加速免费</span>'
                },
                {
                    dialog:'confirm1',
                    hit:{width:130,height:50,x:60,y:140},
                    tips:{x:165,y:200,skewX:180,skewY:0},
                    content:'点击确定按钮'
                }
            ],
            [
                {
                    dialog:'jgcdialog',
                    hit:{width:140,height:60,x:690,y:440},
                    tips:{x:270,y:340,skewX:0,skewY:180},
                    content:'香烟已经生产好了,快点击<span color="#ae0626">收获</span>吧!'
                }
            ],
            [//关闭
                {
                    dialog:'jgcdialog',
                    type:'circle',
                    radius:35,
                    hit:{x:917,y:52},
                    tips:{x:450,y:70,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ]
        ],
        [
            [
                {
                    dialog:'pjdialog',
                    hit:{width:80,height:95,x:482,y:235},
                    tips:{x:550,y:330,skewX:180,skewY:0},
                    content:'选择一包香烟进行品鉴'
                },
                {
                    dialog:'pjdialog',
                    hit:{width:110,height:40,x:230,y:440},
                    tips:{x:320,y:330,skewX:0,skewY:0},
                    content:'点击<span color="#ae0626">品鉴</span>按钮'
                },
                {
                    dialog:'confirm1',
                    hit:{width:130,height:50,x:60,y:140},
                    tips:{x:165,y:190,skewX:180,skewY:0},
                    content:'点击确定按钮'
                }
            ],
            [
                {
                    dialog:'pinjian_result',
                    type:'circle',
                    radius:35,
                    hit:{x:558,y:25},
                    tips:{x:100,y:70,skewX:180,skewY:180},
                    content:'点击关闭按钮',
                    npc:'恭喜品鉴成功,完成品鉴的香烟可以去参与<span color="#ae0626">幸运抽奖</span>或者去<span color="#ae0626">完成订单</span>!参与抽奖需使用相应的星级香烟,有机会获得<span color="#ae0626">品吸机会代金券</span>等多重好礼。而完成订单可获得<span color="#ae0626">银元</span>与<span color="#ae0626">经验</span>奖励!',
                    btn:true
                }
            ],
            [//关闭
                {
                    dialog:'pjdialog',
                    type:'circle',
                    radius:35,
                    hit:{x:930,y:132},
                    tips:{x:450,y:150,skewX:180,skewY:180},
                    content:'点击关闭按钮'
                }
            ]
        ]
    ];
})();