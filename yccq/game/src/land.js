/**
 * Created by lkl on 2017/1/13.
 */
(function() {
    // 土地继承类
    var GlowFilter = Laya.GlowFilter;
    var HttpRequest = Laya.HttpRequest;
    var Event = Laya.Event;
    var Text = Laya.Text;
    var hr = null;

    function myland(maps,gameUI,type) {

        myland.__super.call(this);
        this.mouseThrough = true;
        this.land1.name = 'land';
        this.land1.seed = null;//种子
        this.Status = false;
        this.dialog = null;
        this.Level = 0;
        this.maps = maps;
        this.gameUI = gameUI;
        if(type != 'FriendFarm'){
            this.land1.on(Laya.Event.CLICK, this, this.onClickLand,[this.land1]);
            //this.land1.on(Event.MOUSE_DOWN, this, this.onLandPress);
        }

    }

    Laya.class(myland, "myland",landUI);
    var _proto = myland.prototype;

    _proto.onClickLand = function(land) {
        //var gameUI = this.parent.parent;
        if(hasScale || hasMove) return;
        this.selectedLand();

        var p = this.getPosition();


        this.gameUI.BZ_pop.pos(p.x,p.y);
        if(!this.land1.seed){
            this.gameUI.BZ_pop.showPOP('BZ');
            //this.gameUI.BZ_pop.show();
        }else if(land.seed.isMature){
            var dialog = new ShouGeDialog();
            dialog.popupCenter = false;
            dialog.pos(p.x,p.y);
            dialog.show();


        }else {
            this.dialog = new PlantDialog(land.seed);
            this.dialog.popupCenter = false;
            this.dialog.pos(p.x,p.y);
            this.dialog.show();
        }
        ShiJianManager.instance().tips();
        this.Status = false;

    };

    _proto.selectedLand = function()
    {
        if(this.gameUI.currSelectedOBJ){
            this.gameUI.currSelectedOBJ.filters = [];
        }
        this.gameUI.currSelectedOBJ = this;
        console.log(hasMove);
        if(hasMove) {
            this.gameUI.BZ_pop.close();
            return;
        }

        //this.land1.graphics.drawCircle(64,32,30,null,'#ff0000',1);
        //创建一个发光滤镜
        var glowFilter = new GlowFilter("#FFFFFF", 10, 0, 0);
        //设置滤镜集合为发光滤镜
        if(!this.filters){
            this.filters = [glowFilter,glowFilter];
        }
    };

    _proto.getPosition = function()
    {
        var p = new Laya.Point(Laya.stage.mouseX,Laya.stage.mouseY);
        this.maps.mapSprite.globalToLocal(p);
        p = this.maps.getIndexByPos(p.x,p.y);

        p = this.maps.getPosByindex(p.x,p.y);
        this.maps.mapLayer.localToGlobal(p);
        return p;
    };



    _proto.BZ = function(seed) {
        if(!this.Status){
            this.Status = true;

            hr = new HttpRequest();
            hr.once(Event.PROGRESS, this, this.onHttpRequestProgress);
            hr.once(Event.COMPLETE, this, this.onBZComplete,[seed]);
            hr.once(Event.ERROR, this, onHttpErr);
            hr.send(config.BaseURL+'land/seed', 'uid='+localStorage.GUID+'&land_id='+this.land1.landIndex+'&seed_shopid='+seed.dataSource.shopid, 'post', 'text');
        }
    };

    _proto.SG = function() {
        if(!this.Status){
            this.Status = true;
            hr = new HttpRequest();
            hr.once(Event.PROGRESS, this, this.onHttpRequestProgress);
            hr.once(Event.COMPLETE, this, this.onSGComplete);
            hr.once(Event.ERROR, this, onHttpErr);
            hr.send(config.BaseURL+'land/gather', 'uid='+localStorage.GUID+'&land_id='+this.land1.landIndex, 'post', 'text');
        }

    };

    _proto.onHttpRequestProgress = function(e) {

    };

    _proto.onBZComplete = function (S,data)
    {
        var res = JSON.parse(data);
     
        if(res.code == 0 && !this.land1.seed){
            console.log(S.dataSource);
            S.dataSource.num = Number(S.dataSource.num) - 1;
            if(S.dataSource.num <= 0)
            {
                //S.getChildByName("select").visible = false;

                S.parent.parent.deleteItem(S.index);
            }
            //S.parent.parent.initItems();
            S.parent.parent.refresh();
            var work_time = Utils.strToTime(res.data.stop_time) - Utils.strToTime(res.data.start_time);
            //console.log(work_time);
            var seeDtata = {name:ItemInfo[S.dataSource.shopid].name,shop_id:S.dataSource.shopid,work_time:work_time};
            var seed = new Seed(seeDtata);
            seed.pos(64,32);
            //seed.plant_record_id = res.plant_record_id;
            this.land1.seed = seed;

            this.land1.addChild(seed);

            console.log(S.dataSource.shopid);

            this.Status = false;
            this.stage.getChildByName("MyGame").initUserinfo();

            if(res.data.event_land == 1){
                this.timer.once(5000,this,this.setChongZi);
            }else if(res.data.event_land == 2){
                this.timer.once(5000,this,this.setHanZai);
            }

            //ShiJianManager.instance().init();

            if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 0)
            {
                ZhiYinManager.instance().setGuideStep(4,1);
                var BZDialog = Dialog.manager.getChildByName('bozhong');
                if(BZDialog){
                    BZDialog.close();
                }
            }

        }else {
            //alert(res.msg);
            console.log(res.msg);
            var tips = new CommomConfirm(res.msg);
            tips.show();
        }
    };

    _proto.onSGComplete = function(data) {
        var res = JSON.parse(data);

        if(res.code == 0){
            console.log(res);
            //Laya.timer.clear(this,this.updateTime);
            for(var i = 0; i < res.data.success.length; i++){
                getItem(res.data.success[i].yanye_shopid);
            }
            if(res.data.suipian.length){
                var suipian_tips = new FragmentGetTips(res.data.suipian);
                suipian_tips.popup()
            }
            var failed = [];
            for(var j = 0; j < res.data.false.length; j++){
                failed.push(res.data.false[j].yanye_shopid);
            }
            if(failed.length){
                var yanye_names = [];
                for(var f = 0; f < failed.length; f++){
                    yanye_names.push(ItemInfo[failed[f]].name);
                }
                var text = yanye_names.join(',');
                text += '已被虫子毁坏';
                var dialog = new CommomConfirm(text);
                dialog.popup();
            }
            //getItem(ItemInfo[this.land1.seed.seedData.shop_id].mubiao);
            this.land1.destroyChildren();
            this.land1.seed = null;
            this.Status = false;
            this.clearHanZai();
            //this.gameUI.depot.addItem(seed.seed_id,1);
            this.stage.getChildByName("MyGame").initUserinfo();
        }else{
            //alert('操作失败');
            console.log(res.msg);
            var tips = new CommomConfirm(res.msg);
            tips.show();
        }
        ShiJianManager.instance().init();

        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 2)
        {
            ZhiYinManager.instance().setGuideStep(5,0);
        }
    };

    //用于初始化已种植土地
    _proto.setSeed = function(data,now_time)
    {
        var startTime = Utils.strToTime(data.start_time);
        var stopTime = Utils.strToTime(data.stop_time);

        var workTime = Number(stopTime) - Number(startTime);
        var seedData = {shop_id:data.seed_shopid,work_time:workTime};
        if(!this.land1.seed){
            var seed = new Seed(seedData);
            seed.pos(64,32);
            //seed.plant_record_id = data.plant_record_id;
            this.land1.seed = seed;

            this.land1.addChild(seed);
        }else {
            this.land1.seed.setSeed(seedData);
        }

        var lifetime = workTime - (now_time - startTime);
        if(now_time >= stopTime)
        {
            this.land1.seed.setMatureLocal();
        }else if(lifetime <= this.land1.seed.growingTime){
            this.land1.seed.setGrowing();
            this.land1.seed.lifeTime = lifetime;
        }else {
            this.land1.seed.lifeTime = lifetime;
        }

    };

    _proto.onLandPress = function(e)
    {
        // 鼠标按下后，HOLD_TRIGGER_TIME毫秒后hold
        if(e.touches && e.touches.lenght == 2) return;
        this.timer.once(500, this, this.onHold);
        this.land1.on(Laya.Event.MOUSE_UP, this, this.onLandRelease);
    };

    _proto.onHold = function()
    {

        this.isHold = true;
        console.log('按住');
        this.selectedLand();
        var p = this.getPosition();
        if(!this.land1.seed)
        {

            var dialog = new LandUpgrade();
            dialog.popupCenter = false;
            dialog.pos(p.x,p.y);
            dialog.show();
        }else if(!this.land1.seed.isMature)
        {
            console.log('清除植物');
            var dialog = new LandClear();
            dialog.popupCenter = false;
            dialog.pos(p.x,p.y);
            dialog.show();
        }
        this.land1.off(Laya.Event.CLICK, this, this.onClickLand);
    };

    /** 鼠标放开后停止hold */
    _proto.onLandRelease = function()
    {
        // 鼠标放开时，如果正在hold，则播放放开的效果
        if (this.isHold)
        {
            this.isHold = false;
        }
        else // 如果未触发hold，终止触发hold
        {
            this.timer.clear(this, this.onHold);
            this.land1.on(Laya.Event.CLICK, this, this.onClickLand,[this.land1]);
        }
        this.land1.off(Laya.Event.MOUSE_UP, this, this.onLandRelease);

    };

    //触发虫灾
    _proto.setChongZi = function()
    {
        if(this.land1.seed){
            var wrap = new Laya.Sprite();
            wrap.name = 'chongzi';
            var chongzi = new Laya.Image('shijiang/chongzi.png');
            chongzi.pos(60,0);
            wrap.addChild(chongzi);
            var chongzi1 = new Laya.Image('shijiang/chongzi.png');
            chongzi1.pos(20,10);
            wrap.addChild(chongzi1);
            this.land1.addChild(wrap);
            ShiJianManager.instance().init();
        }
    };

    //清除虫子
    _proto.clearChongZi = function()
    {
        if(this.land1.seed){
            this.land1.removeChildByName('chongzi');
        }
    };

    //触发旱灾
    _proto.setHanZai = function()
    {
        this.land1.skin = 'tex/land_gan.png';
        ShiJianManager.instance().init();
    };

    //清除旱灾
    _proto.clearHanZai = function()
    {
        this.land1.skin = ItemInfo[this.land1.landShopid].thumb;
    };
})();