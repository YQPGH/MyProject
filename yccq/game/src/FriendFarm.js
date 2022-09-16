/**
 * Created by 41496 on 2017/6/26.
 */
(function(){
    var self = null;
    function Farm(friendData)
    {
        Farm.__super.call(this);
        self = this;
        this.name = 'FriendFarm';

        this.landArr = [];

        localStorage.FUID = friendData.code;



        this.initGame();
    }
    Laya.class(Farm,"Farm",Laya.Sprite);
    var proto = Farm.prototype;

    proto.initGame = function()
    {
        //地图层
        this.map = new ZY.Maps(this);



        //获取物品信息
        //this.getAllItemInfo();
    };

    //地图加载完成回调函数
    proto.mapCompleteHandler = function () {
        this.initUserinfo();

        this.getFriendLand();

        this.initDepot();
        this.initMyHouse();
        this.initZLShop();
        this.initPinjian();
        //this.initUpgrade();
        this.initYJS();
        this.initLBT();

        //this.InitLand();
        this.initBakingRoom();
        this.initAgingRoom();
        this.initFactory();
        this.initPeiyushi();
        this.initGGL();
        this.initYouLeChang();
        this.initChouJiang();
        this.initNengLiangCao();
        this.initSuiPianGe();

        this.addVisit();

        this.ckeckChongzi();

    };

    proto.getFriendLand = function()
    {
        Utils.post('friend/land',{uid:localStorage.GUID,code:localStorage.FUID},this.InitLand);
    };

    //初始化土地
    proto.InitLand = function(res){
        var now_time = Utils.strToTime(res.time);
        if(res.code ==0){
            //土地
            var data = res.data;

            for(var i = 0; i < data.length; i++){
                var land = new myland(self.map,self,'FriendFarm');
                land.pivot(64,32);
                land.land1.landIndex = data[i].id;
                land.land1.landShopid = data[i].land_shopid;
                land.land1.skin = ItemInfo[data[i].land_shopid].thumb;
                land.zOrder = config.landPosIndex[i][0]*100+(config.landPosIndex[i][1]);
                if(Number(data[i].status))
                {
                    land.setSeed(data[i],now_time);
                }
                self.landArr.push(land);
                self.map.addBuilding(land,config.landPosIndex[i][0],config.landPosIndex[i][1]);
            }

        }
    };

    //初始化仓库
    proto.initDepot = function()
    {
        this.depot = new CKBuilding('FriendFarm');
        this.map.addBuilding(this.depot,16,26);
    };

    //初始化我的小屋
    proto.initMyHouse = function()
    {
        this.MyHouse = new MyHouse('FriendFarm');
        this.map.addBuilding(this.MyHouse,20,26);
    };

    //初始化真龙商行
    proto.initZLShop = function()
    {
        this.ZLShop = new ZLShop('FriendFarm');
        this.map.addBuilding(this.ZLShop,20,22);
    };

    //初始化烘烤室
    proto.initBakingRoom = function()
    {

        this.BakingRoom = new BakingRoom(null,0,'FriendFarm');
        this.map.addBuilding(this.BakingRoom,25,33);
    };

    //初始化醇化室
    proto.initAgingRoom = function()
    {

        this.AgingRoom = new AgingRoom(null,0,'FriendFarm');
        this.map.addBuilding(this.AgingRoom,26,28);
    };

    //初始化加工厂
    proto.initFactory = function()
    {

        this.Factory = new Factory(null,0,'FriendFarm');
        this.map.addBuilding(this.Factory,34,33);
    };

    //初始品鉴行
    proto.initPinjian = function()
    {
        this.Pinjian = new Pinjian('FriendFarm');
        this.map.addBuilding(this.Pinjian,20,17);
    };

    //初始升级香烟建筑
    proto.initUpgrade = function()
    {
        this.Upgrade = new UpdateBuilding('FriendFarm');
        this.map.addBuilding(this.Upgrade,18,9);
    };

    //初始化种子培育中心建筑
    proto.initPeiyushi = function()
    {
        this.Peiyushi = new Peiyushi({status:0,start_time:0,stop_time:0},0,'FriendFarm');
        this.map.addBuilding(this.Peiyushi,25,22);
    };

    //初始化配方研究所建筑
    proto.initYJS = function()
    {
        this.YJS = new YJSBuilding('FriendFarm');
        this.map.addBuilding(this.YJS,31,22);
    };

    //初始化路边摊建筑
    proto.initLBT = function()
    {
        this.LBT = new LuBianTan('FriendFarm');
        this.map.addBuilding(this.LBT,20,12);
    };

    //初始化公告栏建筑
    proto.initGGL = function()
    {
        this.GGL = new GongGaoLan('FriendFarm');
        this.map.addBuilding(this.GGL,31,26);
    };

    proto.initYouLeChang = function()
    {
        this.YouLeChang = new YouLeChang('FriendFarm');
        this.map.addBuilding(this.YouLeChang,36,20);
    };

    proto.initChouJiang = function()
    {
        this.ChouJiangBuilding = new ChouJiangBuilding('FriendFarm');
        this.map.addBuilding(this.ChouJiangBuilding,24,17);
    };

    proto.initNengLiangCao = function()
    {
        this.NengLiangCao = new NengLiangCao('FriendFarm');
        this.map.addBuilding(this.NengLiangCao,16,22);
    };

    proto.initSuiPianGe = function()
    {
        this.SuiPianGe = new SuiPianGe('FriendFarm');
        this.map.addBuilding(this.SuiPianGe,24,10);
    };

    //发送请求更新用户信息
    proto.initUserinfo = function()
    {
        Utils.post("friend/user", {uid:localStorage.GUID,code:localStorage.FUID}, this.onUIDataReturn);

    };

    proto.onUIDataReturn = function(res)
    {
        console.log(res);
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
            self.UI.friendUI(userInfo);

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.show();
            dialog.closeHandler = new Laya.Handler(this,BackHome);

        }
    };

    proto.addVisit = function()
    {
        Utils.post("friend/add_visit",{uid:localStorage.GUID,code:localStorage.FUID},function(){});
    };

    proto.ckeckChongzi = function(){
        Utils.post('Chongzi/friend_chongzi_placed',{uid:localStorage.GUID,code:localStorage.FUID},function(res){
            if(res.code == '0'){
                if(res.data){
                    ChongziManager.instance('FriendFarm').createChongzi(res.data.type,[19,37],{start_time:res.data.start_time,stop_time:res.data.stop_time,now_time:res.time,nickname:res.data.nickname,number:res.data.number});
                }
            }
        },null);

    };

})();