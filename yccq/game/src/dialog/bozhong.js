/**
 * Created by lkl on 2017/4/10.
 */
(function(){
    var Event = Laya.Event;
    var self = null;
    //播种界面种子类
    function SeedItem(ItemData)
    {
        SeedItem.__super.call(this);
        this.ItemData = ItemData;
        this.skin = ItemInfo[this.ItemData.shopid].thumb;
        this.size(90,90);
        this.isSelected = false;
        this.anchorX = 0.5;
        this.anchorY = 0.5;
        this.seedType = "BZ";
        this.Status = false;

        //调式用圆圈
        /*var circle = new Laya.Sprite();
        circle.graphics.drawCircle(0,0,this.width/2,null,'#FF0000',2);
        circle.pos(this.width/2,this.height/2);
        this.addChild(circle);
        console.log(circle);*/

        //数量
        this.num = new Laya.Label(''+this.ItemData.total);
        this.num.fontSize = 16;
        this.num.size(70,20);
        this.num.align = "right";
        this.num.alignH = "middle";
        this.num.pos(25,0);
        this.addChild(this.num);

        this.on(Event.MOUSE_DOWN,this,this.onLandPress);
        //this.on(Event.CLICK,this,this.onSeedClick);

    }
    Laya.class(SeedItem,'SeedItem',Laya.Image);
    var p = SeedItem.prototype;

    p.onSeedClick = function()
    {
        var selectedOBJ = this.stage.getChildByName('MyGame').currSelectedOBJ;
        console.log(selectedOBJ);
        if(!selectedOBJ.land1.seed && Number(this.num.text)){//没有种子则播种
            selectedOBJ.BZ(this);
            this.parent.parent.close();
        }

    };

    p.onLandPress = function(e)
    {
        // 鼠标按下后，HOLD_TRIGGER_TIME毫秒后hold
        if(e.touches && e.touches.lenght == 2) return;
        this.timer.once(300, this, this.onHold);
        this.on(Laya.Event.MOUSE_UP, this, this.onLandRelease);
    };

    p.onHold = function()
    {

        this.isHold = true;
        console.log('按住');

        this.ItemInfo = new ItemInfoDialog(this,this.ItemData.shopid);
        this.ItemInfo.show();

        //this.off(Laya.Event.CLICK, this, this.onSeedClick);
    };

    /** 鼠标放开后停止hold */
    p.onLandRelease = function()
    {
        // 鼠标放开时，如果正在hold，则播放放开的效果
        if (this.isHold)
        {
            this.isHold = false;
            this.ItemInfo.close();
        }
        else // 如果未触发hold，终止触发hold
        {
            this.timer.clear(this, this.onHold);
            //this.on(Laya.Event.CLICK, this, this.onSeedClick);
        }
        this.off(Laya.Event.MOUSE_UP, this, this.onLandRelease);

    };

    p.mouseDown = function() {
        this.isSelected = true;
        this.level.visible = false;

        //console.log(this);

        this.on(Event.MOUSE_MOVE,this,this.mouseMove);
        this.on(Event.MOUSE_UP,this,this.mouseUp);
        this.on(Event.MOUSE_OUT,this,this.mouseOut);

        Laya.timer.frameLoop(1, this, this.checkHit);
    };

    p.mouseMove = function() {
        var point = new Laya.Point(Laya.stage.mouseX,Laya.stage.mouseY);
        var p = this.parent.globalToLocal(point);
        //console.log(p);
        this.x = p.x;
        this.y = p.y;
    };

    p.mouseUp = function() {
        this.isSelected = false;
        this.level.visible = true;
        this.pos(this.oldX,this.oldY);

        this.off(Event.MOUSE_MOVE,this,this.mouseMove);
        this.off(Event.MOUSE_OUT,this,this.mouseOut);
        Laya.timer.clear(this,this.checkHit);
        if(this.Status){
            this.parent.parent.close();
        }
    };

    p.mouseOut = function() {
        this.mouseUp();
    };

    p.checkHit = function() {
        var landArr = Laya.stage.getChildByName("MyGame").landArr;
        var seedPos = this.localToGlobal(this.fromParentPoint(new Laya.Point(this.x,this.y)));
        for(var i = 0; i < landArr.length; i++){
            var land = landArr[i];
            var landPos = land.localToGlobal(land.fromParentPoint(new Laya.Point(land.x,land.y)));

            var hitRadius = 30  + 32;
            var calX = Math.abs(landPos.x - seedPos.x);
            var calY = Math.abs(landPos.y - seedPos.y);
            var temp = Math.pow((calX *calX + calY * calY), 0.5);
            if (temp < hitRadius) {
                //碰撞
                if(!land.land1.seed && this.seedType == 'BZ' && this.isSelected && Number(this.num.text)){//没有种子则播种
                    land.BZ(this);

                    this.Status = true;
                }

                if(land.land1.seed && land.land1.seed.isMature && this.seedType == 'SG' && this.isSelected){//有种子并且成熟则发送收割
                    land.SG();
                    this.Status = true;
                }
                //console.log('碰撞'+land.land1.landIndex);
            }
        }
    };

    function ShouGe()
    {
        ShouGe.__super.call(this);

        this.isSelected = false;
        this.anchorX = 0.5;
        this.anchorY = 0.5;
        this.seedType = "SG";
        this.skin = ItemIcon.SG;
        this.size(64,64);
        this.Status = false;

        //等级
        this.level = new Laya.Label("收割");
        this.level.fontSize = 16;
        this.level.anchorX = 0.5;
        this.level.pos(this.width/2,this.height+5);
        this.addChild(this.level);

        //this.on(Event.MOUSE_DOWN,this,this.mouseDown);
        this.on(Event.CLICK,this,this.onSGClick);
    }
    Laya.class(ShouGe,"ShouGe",Laya.Image);
    var sg = ShouGe.prototype;
    sg.onSGClick = function()
    {
        var selectedOBJ = this.stage.getChildByName('MyGame').currSelectedOBJ;
        console.log(selectedOBJ);
        if(selectedOBJ.land1.seed && selectedOBJ.land1.seed.isMature){//有种子并且成熟则发送收割
            selectedOBJ.SG();
            this.parent.parent.close();
        }

    };



    //播种弹出框继承类
    function BoZhong()
    {
        BoZhong.__super.call(this);
        self = this;
        this.name = 'bozhong';
        this.popupEffect = null;
        this.closeEffect = null;

        this.selectedItem = null;

        this.lists = [this.list0,this.list1,this.list2,this.list3,this.list4];
        for(var i = 0; i < this.lists.length; i++)
        {
            this.lists[i].renderHandler = new Laya.Handler(this,this.updateItem);
        }

        this.upgrade_btn.clickHandler = new Laya.Handler(this,this.onUpgradeBtnClick);
        this.Plant_btn.clickHandler = new Laya.Handler(this,this.onPlantBtnClick);

        this.table.selectHandler = this.view_stack.setIndexHandler;
        this.table.selectedIndex = 0;
        this.table.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 0){
                ZhiYinMask.instance().setZhiYin(2);
            }
        });

        this.view_stack.on(Laya.Event.CLICK,this, function(){
            if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 0){
                ZhiYinMask.instance().setZhiYin(3);
            }
        });
        this.getSeedData();

    }
    Laya.class(BoZhong,"BoZhong",bozhongUI);
    var proto = BoZhong.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 0){
            ZhiYinMask.instance().setZhiYin(1);
        }
    };

    proto.getSeedData = function()
    {
        this.SeedData = {'1':[],'2':[],'3':[],'4':[],'5':[]};
        Utils.post("store/lists",{uid:localStorage.GUID,type1:"zhongzi"},this.onSeedDataReturn);
    };

    proto.onSeedDataReturn = function(res)
    {
        //console.log(res);
        if(res.code ==0)
        {
            for(var i = 0,len = res.data.length; i < len; i++)
            {
                if(Number(res.data[i].total) > 0){
                    self.SeedData[res.data[i].type2].push({shopid:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,num:res.data[i].total});
                }
            }
            //console.log(self.SeedData);
            if(self.SeedData[1].length){
                self.list0.array = self.SeedData[1];
                self.list0.getChildByName('tips').visible = false;
            }else {
                self.list0.array = null;
                self.list0.getChildByName('tips').visible = true;
            }

            if(self.SeedData[2].length){
                self.list1.array = self.SeedData[2];
                self.list1.getChildByName('tips').visible = false;
            }else {
                self.list1.array = null;
                self.list1.getChildByName('tips').visible = true;
            }

            if(self.SeedData[3].length){
                self.list2.array = self.SeedData[3];
                self.list2.getChildByName('tips').visible = false;
            }else {
                self.list2.array = null;
                self.list2.getChildByName('tips').visible = true;
            }

            if(self.SeedData[4].length){
                self.list3.array = self.SeedData[4];
                self.list3.getChildByName('tips').visible = false;
            }else {
                self.list3.array = null;
                self.list3.getChildByName('tips').visible = true;
            }

            if(self.SeedData[5].length){
                self.list4.array = self.SeedData[5];
                self.list4.getChildByName('tips').visible = false;
            }else {
                self.list4.array = null;
                self.list4.getChildByName('tips').visible = true;
            }

            /*for( i in self.SeedData)
            {
                if(self.SeedData[i].length > 0){
                    self.table.selectedIndex = i-1;
                }
            }*/

        }
    };

    proto.updateItem = function(cell, index)
    {
        //cell.offAll();
        cell.on(Laya.Event.CLICK,this,this.onItemClick,[cell,index]);
        //cell.on(Laya.Event.MOUSE_DOWN,this,this.onLandPress,[cell]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shopid,false]);
    };

    proto.onItemClick = function(Item,index)
    {
        if(this.selectedItem){
            this.selectedItem.getChildByName("select").visible = false;
        }
        this.selectedItem = Item;
        this.selectedItem.index = index;
        Item.getChildByName("select").visible = true;

    };

    proto.onPlantBtnClick = function()
    {
        var selectedOBJ = this.stage.getChildByName('MyGame').currSelectedOBJ;
        if(selectedOBJ.land1.seed){
            this.stage.getChildByName('MyGame').autoSelectLand();
            selectedOBJ = this.stage.getChildByName('MyGame').currSelectedOBJ;
        }

        if(this.selectedItem && this.selectedItem.dataSource && Number(this.selectedItem.dataSource.num) > 0){
            if(!selectedOBJ.land1.seed){//没有种子则播种
                selectedOBJ.BZ(this.selectedItem);
            }
        }
    };

    proto.showPOP = function(type) {

        if(hasMove) return;
        if(type == "BZ")
        {
            this.bg.visible = true;
            this.table.visible = true;
            this.upgrade_btn.visible = true;
            this.getSeedData();
        }
        else
        {
            this.bg.visible = false;
            this.table.visible = false;
            this.upgrade_btn.visible = false
        }
        //hasDialog = true;


        this.show();
    };

    proto.onUpgradeBtnClick = function()
    {
        var selected_land = this.stage.getChildByName("MyGame").currSelectedOBJ;
        var land_level = selected_land.land1.landShopid;
        switch(land_level)
        {
            case '101':

            case '102':
                var dialog = new Confirm1("升级到"+ItemInfo[Number(land_level)+1].name+"需要"+config.landUpgrade[land_level].ledou+'银元,种植时间缩短'+config.landUpgrade[land_level].shijian);
                dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose);
                dialog.popup();
                break;
            case '103':
                var dialog = new CommomConfirm("该土地已经是最高级了");
                dialog.popup();
                break

        }
    };

    proto.onConfirmClose = function(name)
    {
        console.log(name);
        if(name == 'yes')
        {
            var selected_land = this.stage.getChildByName("MyGame").currSelectedOBJ;
            var land_id = selected_land.land1.landIndex;
            Utils.post("land/upgrade",{uid:localStorage.GUID,land_id:land_id},this.onUpgradeReturn,onHttpErr,land_id);
        }
    };

    proto.onUpgradeReturn = function(res,land_id)
    {
        if(res.code == '0')
        {
            var land_list = self.stage.getChildByName("MyGame").landArr;
            for(var i = 0,len = land_list.length; i < len; i++)
            {
                if(land_list[i].land1.landIndex == land_id)
                {
                    var land_level = land_list[i].land1.landShopid;
                    var shop_id = Number(land_level)+1;
                    land_list[i].land1.landShopid = shop_id+'';
                    land_list[i].land1.skin = ItemInfo[shop_id].thumb;
                }

            }
            self.stage.getChildByName("MyGame").initUserinfo();
        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };





})();