/**
 * Created by lkl on 2017/4/6.
 */
(function(){
    var Handler = Laya.Handler;
    var Event = Laya.Event;
    var first_login = true; //第一次获取用户信息

    var self = null;
    function MyGame()
    {
        MyGame.__super.call(this);
        self = this;
        this.name = "MyGame";
        this.landArr = [];
        this.TreeArr = [];
        this.currSelectedOBJ = null;
        this.guide_step = 0;//指引步数
        this.BakingRoom = null;
        this.AgingRoom = null;
        this.Factory = null;

        this.onAssetLoaded();


        Laya.stage.on(Event.MOUSE_DOWN, this, this.mouseDown);
        Laya.stage.on('focus',this,this.onStageFocus);
    }
    Laya.class(MyGame,"MyGame",Laya.Sprite);
    var proto = MyGame.prototype;

    proto.onAssetLoaded = function()
    {
        console.log("加载完成初始化游戏");

        this.initGame();
    };
    //-----------加载页面结束--------------

    //鼠标按下拖动地图
    proto.mouseDown = function (e) {
        switch(e.target.name){
            case 'stage':

            case 'land':

            case 'building':
                if(this.currSelectedOBJ){
                    this.currSelectedOBJ.filters = null;
                    this.currSelectedOBJ = null;
                }
                Dialog.manager.closeAll();
                break;
            default:
        }
    };

    proto.initGame = function()
    {
        //地图层
        this.map = new ZY.Maps(this);
    };

    //地图加载完成回调函数
    proto.mapCompleteHandler = function () {
        //获取物品信息
        //LoadingScene.timer.clear(LoadingScene,LoadingScene.ani);
        //Laya.stage.removeChild(LoadingScene);
        if(StoryScene){
            StoryScene.destroy(true);
            StoryScene = null;
        }
        if(LoadingScene){
            LoadingScene.destroy(true);
            LoadingScene = null;
        }
        Laya.stage.addChild(this);
        this.getAllItemInfo();

        this.initUserinfo();


        this.initMyHouse();
        this.initZLShop();
        //this.initBakingRoom();
        //this.initAgingRoom();
        //this.initFactory();
        this.initPinjian();
        //this.initUpgrade();

        this.initGGL();
        this.initZhangGui();
        this.getJianDieData();
        this.initYouLeChang();
        this.initChouJiang();

        this.initNengLiangCao();
        this.initSuiPianGe();
        this.checkAddFriend();
        if(!AllowEnter){
            this.testEnd();
        }

        if(isBlack){
            this.showWarning();
        }

        this.ckeckChongzi();
        this.timer.loop(60000*3,this,this.ckeckChongzi);


        //ZhiYinManager.instance().createMask();
        //重写弹出框管理器函数
        //var dialog = new Laya.Dialog();
        //Dialog.manager.zOrder = 1000;
        Dialog.manager.closeAll = function () {
            this.removeChildren();
            this.event("close");
            hasDialog = false;
        };

        Dialog.manager.doClose = function (dialog,type){
            dialog.removeSelf();
            dialog.isModal && this._checkMask();
            dialog.closeHandler && dialog.closeHandler.runWith(type);
            dialog.onClosed(type);
            if(this.numChildren == 0){
                hasDialog = false;
            }
            //GuideManager.instance().dialogClose(dialog);
        };

        Dialog.manager.open = function(dialog,closeOther){
            (closeOther===void 0)&& (closeOther=false);
            if (closeOther)this.removeChildren();
            if (dialog.popupCenter)this._centerDialog(dialog);
            this.addChild(dialog);
            if (dialog.isModal || this._$P["hasZorder"])this.timer.callLater(this,this._checkMask);
            if (dialog.popupEffect !=null)dialog.popupEffect.runWith(dialog);
            else this.doOpen(dialog);
            this.event("open");
            hasDialog = true;

            //指引
            //var guide = new Guides(dialog);
            //GuideManager.instance().dialogOpen(dialog);
        };

        //console.log(Dialog.manager.close);

    };

    //获取物品信息
    proto.getAllItemInfo = function()
    {
        Utils.post('shop/lists_all',{uid:localStorage.GUID},this.onItemInfoDataReturn,onHttpErr);
    };

    //物品信息返回
    proto.onItemInfoDataReturn = function(res)
    {

        if(res.code == 0)
        {
            ItemInfo = res.data;
            //弹出框
            self.BZ_pop = new BoZhong();
            self.getInitData();
        }


    };

    //获取初始化数据
    proto.getInitData = function()
    {
        Utils.post('user/house_status',{uid:localStorage.GUID},this.onReturnInitData,onHttpErr);
    };

    //初始化数据返回时
    proto.onReturnInitData = function(res)
    {
        if(res.code == 0)
        {
            self.InitData = res.data;
            var now_time = Utils.strToTime(res.time);
            self.InitLand(now_time);
            self.initBakingRoom(now_time);
            self.initAgingRoom(now_time);
            self.initFactory(now_time);
            self.initPeiyushi(now_time);
            self.initDepot();
            self.initYJS();
            self.initLBT();
            //self.checkGuide();

            self.getCompensateList();

            ShiJianManager.TipsType = Number(res.data.event_land);
        }else
        {
            var tips = new CommomConfirm("获取出初始化数据失败");
            tips.popup();
        }
    };
    //初始化土地
    proto.InitLand = function(now_time){
        if(this.InitData.land){
            //土地
            var data = this.InitData.land;

            for(var i = 0; i < data.length; i++){
                var land = new myland(this.map,this);
                land.pivot(64,32);
                land.land1.landIndex = data[i].id;
                land.land1.landShopid = data[i].land_shopid;
                land.land1.skin = ItemInfo[data[i].land_shopid].thumb;
                land.zOrder = config.landPosIndex[i][0]*100+(config.landPosIndex[i][1]);
                if(Number(data[i].status))
                {
                    land.setSeed(data[i],now_time);
                }
                if(data[i].event_status == 1){
                    land.setChongZi();
                }else if(data[i].event_status == 2){
                    land.setHanZai();
                }
                this.landArr.push(land);
                this.map.addBuilding(land,config.landPosIndex[i][0],config.landPosIndex[i][1]);
            }

        }

    };

    //添加一块土地，用于购买土地解锁
    proto.addLand = function(id)
    {
        var land = new myland(this.map,this);
        land.pivot(64,32);
        land.land1.landIndex = id;
        land.land1.landShopid = '101';

        this.map.addBuilding(land,config.landPosIndex[this.landArr.length][0],config.landPosIndex[this.landArr.length][1]);
        this.landArr.push(land);
    };
    proto.autoSelectLand = function()
    {
        for(var i = 0; i < this.landArr.length; i++)
        {
            if(!this.landArr[i].land1.seed){
                this.landArr[i].selectedLand();
            }
        }
    };
    //初始化仓库
    proto.initDepot = function()
    {
        this.depot = new CKBuilding();
        this.depot.setLevel(this.InitData.other.store_lv||1);
        this.map.addBuilding(this.depot,16,26);
    };

    //初始化我的小屋
    proto.initMyHouse = function()
    {
        this.MyHouse = new MyHouse();
        this.map.addBuilding(this.MyHouse,20,26);
    };

    //初始化真龙商行
    proto.initZLShop = function()
    {
        this.ZLShop = new ZLShop();
        this.map.addBuilding(this.ZLShop,20,22);
    };

    //初始化烘烤室
    proto.initBakingRoom = function(now_time)
    {
        Utils.post('bake/lists',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                if(!self.BakingRoom){
                    self.BakingRoom = new BakingRoom();
                    self.map.addBuilding(self.BakingRoom,25,33);
                    if(self.Factory && self.AgingRoom && self.BakingRoom){
                        ZhiYinManager.instance().getGuideStep();
                    }
                }
                self.BakingRoom.initBake(res);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }

        },function(){
            var dialog = new CommomConfirm('初始化烘烤室失败!');
            dialog.popup();
        });
        /*var data = null;
        if(Number(this.InitData.other.bake_status))
        {
            data = {status:this.InitData.other.bake_status,start_time:this.InitData.other.bake_start,stop_time:this.InitData.other.bake_stop,level:this.InitData.other.bake_lv,shopid:this.InitData.other.bake_shopid,temperature:this.InitData.other.bake_temperature,bake_auto:this.InitData.other.bake_auto};
        }
        this.BakingRoom = new BakingRoom(data,now_time);
        this.map.addBuilding(this.BakingRoom,25,33);*/
    };

    //初始化醇化室
    proto.initAgingRoom = function(now_time)
    {
        Utils.post('aging/lists',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                if(!self.AgingRoom){
                    self.AgingRoom = new AgingRoom();
                    self.map.addBuilding(self.AgingRoom,26,28);
                    if(self.Factory && self.AgingRoom && self.BakingRoom){
                        ZhiYinManager.instance().getGuideStep();
                    }
                }
                self.AgingRoom.initAging(res);

            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }

        },function(){
            var dialog = new CommomConfirm('初始化醇化室失败!');
            dialog.popup();
        });
        /*var data = {status:0,level:this.InitData.other.aging_lv};
        if(Number(this.InitData.other.aging_status))
        {
            data = {status:this.InitData.other.aging_status,start_time:this.InitData.other.aging_start,stop_time:this.InitData.other.aging_stop,level:this.InitData.other.aging_lv,shopid:this.InitData.other.aging_shopid};
        }
        this.AgingRoom = new AgingRoom(data,now_time);
        this.map.addBuilding(this.AgingRoom,26,28);*/
    };

    //初始化加工厂
    proto.initFactory = function(now_time)
    {
        Utils.post('process/lists',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                data = {
                    level:self.InitData.other.zhiyan_lv,
                    data:[]
                };

                for(var i = 0; i < res.data.length; i++)
                {
                    data.data.push({status:res.data[i].status,start_time:res.data[i].start_time,stop_time:res.data[i].stop_time,shopid:res.data[i].before_shopid});
                }
                if(!self.Factory){
                    self.Factory = new Factory(data,Utils.strToTime(res.time));
                    self.map.addBuilding(self.Factory,34,33);
                    if(self.Factory && self.AgingRoom && self.BakingRoom){
                        ZhiYinManager.instance().getGuideStep();
                    }

                    //ZhiYinManager.instance().showZhiYin();
                }else {
                    self.Factory.setFactory(data,Utils.strToTime(res.time));
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },function(){
            var dialog = new CommomConfirm('初始化制烟厂失败!');
            dialog.popup();
        });

    };

    //初始品鉴行
    proto.initPinjian = function()
    {
        this.Pinjian = new Pinjian();
        this.map.addBuilding(this.Pinjian,20,17);
    };

    //初始升级香烟建筑
    proto.initUpgrade = function()
    {
        this.Upgrade = new UpdateBuilding();
        this.map.addBuilding(this.Upgrade,18,9);
    };

    //初始化种子培育中心建筑
    proto.initPeiyushi = function(now_time)
    {
        this.Peiyushi = new Peiyushi({status:0,start_time:0,stop_time:0},now_time);
        this.map.addBuilding(this.Peiyushi,25,22);
    };

    //初始化配方研究所建筑
    proto.initYJS = function()
    {
        if(!this.YJS){
            this.YJS = new YJSBuilding();
            this.map.addBuilding(this.YJS,31,22);
        }
    };

    //初始化路边摊建筑
    proto.initLBT = function()
    {
        if(!this.LBT){
            this.LBT = new LuBianTan();
            this.map.addBuilding(this.LBT,20,12);
        }
    };

    //初始化公告栏建筑
    proto.initGGL = function()
    {
        this.GGL = new GongGaoLan();
        this.map.addBuilding(this.GGL,31,26);
    };

    proto.initYouLeChang = function()
    {
        this.YouLeChang = new YouLeChang();
        this.map.addBuilding(this.YouLeChang,36,20);
    };

    proto.initChouJiang = function()
    {
        this.ChouJiangBuilding = new ChouJiangBuilding();
        this.map.addBuilding(this.ChouJiangBuilding,24,17);
    };

    proto.initNengLiangCao = function()
    {
        this.NengLiangCao = new NengLiangCao();
        this.map.addBuilding(this.NengLiangCao,16,22);
    };

    proto.initSuiPianGe = function()
    {
        this.SuiPianGe = new SuiPianGe();
        this.map.addBuilding(this.SuiPianGe,24,10);
    };

    //获取间谍模块初始化数据
    proto.getJianDieData = function()
    {
        Utils.post('jiandie/jd_status',{uid:localStorage.GUID},this.onJianDieDataReturn,onHttpErr);
    };

    proto.onJianDieDataReturn = function(res)
    {
        if(res.code == 0)
        {
            if(res.data.zu && res.data.zu.status == '0')
            {
                self.NPC.NPCEnable = false;
                self.initJianDie(res);
            }

            if(res.data.jd_placed && res.data.jd_placed.status == '0')
            {
                self.initThief(res);
            }

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    //初始化掌柜
    proto.initZhangGui = function()
    {
        this.NPC = new ZhangGui();
        this.map.addBuilding(this.NPC,21,21);
    };

    proto.initJianDie = function(data,firstTime)
    {
        if(!this.JianDie){
            this.JianDie = new JianDie(data.data.zu.start_time,data.data.zu.stop_time,data.time,firstTime);
            this.map.addBuilding(this.JianDie,22,28);
            //this.JianDie.NPCEnable = true;
            if(data.data.jd_put && data.data.jd_put.status == '0'){
                this.JianDie.setTimer(data.time,data.data.jd_put.stop_time);
            }
            if(firstTime)
            {
                this.map.mapMoveTo(18,27);
            }
        }
    };

    proto.initThief = function(data)
    {
        if(!this.Thief)
        {
            this.Thief = new Thief();
            var ponit = config.Rongshu[Math.floor(Math.random()*config.Rongshu.length)];
            this.map.addBuilding(this.Thief,ponit[0],ponit[1]);
            this.Thief.zOrder = (ponit[0]-1)*100+(ponit[1]-1);
            this.Thief.setTimer(data.time,data.data.jd_placed.stop_time);
            this.Thief.number = data.data.jd_placed.number;
        }
    };

    //发送请求更新用户信息
    proto.initUserinfo = function()
    {
        Utils.post("user/detail", {uid:localStorage.GUID}, this.onUIDataReturn,onHttpErr);
    };

    proto.onUIDataReturn = function(res)
    {
        if(res.code == 0)
        {
            //UI层
            var userInfo = res.data;
            if(self.UI == undefined)
            {
                self.UI = new UILayer(userInfo);
                self.UI.pos(0,0);
                self.addChild(self.UI);
            }
            self.UI.initUI(userInfo);
            ShiJianManager.instance();
        }
    };
    //检测是否显示指引
    proto.checkGuide = function()
    {
        Utils.post("user/detail", {uid:localStorage.GUID}, this.onCheckDataReturn,onHttpErr);

    };

    proto.onCheckDataReturn = function(res)
    {
        if(res.code == 0){
            if(res.data.is_newer_gift == "0"){
                var dialog = new welcome();
                dialog.popup();
            }else if(!GuideManager.isGetStep){
                GuideManager.instance().getGuideStep();
            }
        }
    };

    //检测添加好友
    proto.checkAddFriend = function()
    {
        if(Request.code){
            Utils.post("friend/is_my_friend",{uid:localStorage.GUID,code:Request.code},this.onFriendInfoReturn, null, this);
        }
    };

    proto.onFriendInfoReturn = function(res, self)
    {
        if(res.code ==0 && res.data.is_friend == '0')
        {
            var dialog = new Confirm1('是否同意玩家['+res.data.nickname+']请求添加您为好友');
            dialog.closeHandler = new Laya.Handler(self,self.onDialogClose);
            dialog.popup();
        }

    };
    //确认框关闭
    proto.onDialogClose = function(name)
    {

        if(name == Dialog.YES)
        {
            Utils.post("friend/add",{uid:localStorage.GUID,code:Request.code},this.onAddFriendReturn, null, this);
        }
    };

    proto.onAddFriendReturn = function(res, self)
    {    var dialog = new confirmUI();
        if(res.code ==0)
        {
            dialog.content.changeText('添加成功！');

        }else{
            dialog.content.changeText(res.msg);
        }
        dialog.popup();
    };

    proto.onStageFocus = function()
    {
        Utils.post('user/house_status',{uid:localStorage.GUID},this.onReturnFocusData)
    };

    proto.onReturnFocusData = function(res)
    {
        if(res.code == 0)
        {
            var now_time = Utils.strToTime(res.time);

            if(res.data.land){
                //土地
                var LandData = res.data.land;
                for(var i = 0; i < LandData.length; i++){
                    var land = self.getLandById(LandData[i].id);
                    if(Number(LandData[i].status))
                    {
                        land.setSeed(LandData[i],now_time);
                    }
                    if(LandData[i].event_status == 1){
                        land.setChongZi();
                    }else if(LandData[i].event_status == 2){
                        land.setHanZai();
                    }
                }
            }

            self.initBakingRoom();

            self.initAgingRoom();

            self.initFactory();
        }
    };

    proto.getLandById = function(id)
    {
        for(var i = 0; i < this.landArr.length; i++)
        {
            if(this.landArr[i].land1.landIndex == id){
                return this.landArr[i];
                break;
            }
        }
    };

    proto.testEnd = function()
    {
        Laya.loader.load('test_end/test_end.png',new Laya.Handler(this,function(){
            var dialog = new TestEnd();
            dialog.popup();
        }),null,Laya.Loader.IMAGE);

    };

    proto.showWarning = function()
    {
        Laya.loader.load('black/jinggao_2.png',new Laya.Handler(this,function(){
            var dialog = new blackUI();
            dialog.popup();
        }),null,Laya.Loader.IMAGE);
    };

    proto.getCompensateList = function()
    {
        Utils.post("Compensate/lists",{uid:localStorage.GUID},function(res){
            if(res.code == '0')
            {
                for(var i = 0; i < res.data.length; i++)
                {
                    var content = res.data[i].description+'\n';
                    if(res.data[i].money > 0) content += '银元 * '+res.data[i].money+'\n';
                    if(res.data[i].shandian > 0) content += '闪电 * '+res.data[i].shandian+'\n';
                    if(res.data[i].shopid > 0) content += ItemInfo[res.data[i].shopid].name+' * '+res.data[i].shop_num+'\n';
                    var dialog = new Compensate(content,res.data[i].id);
                    dialog.popup();
                }
            }
        },null);
    };

    proto.ckeckChongzi = function(){
        Utils.post('Chongzi/chongzi_query',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                if(res.data){
                    ChongziManager.instance().createChongzi(res.data.type,[19,37],{start_time:res.data.start_time,stop_time:res.data.stop_time,now_time:res.time,nickname:res.data.nickname,number:res.data.number});
                }
            }
        },null);

    };

})();