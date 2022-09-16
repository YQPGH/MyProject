/**
 * Created by 41496 on 2017/6/1.
 */
//加工厂弹出框
(function(){
    var self = null;
    function JGCDialog()
    {
        JGCDialog.__super.call(this);
        self = this;
        this.name ='jgcdialog';
        this.Building = this.stage.getChildByName("MyGame").Factory;
        this.tab.selectHandler = this.view_stack.setIndexHandler;
        this.MachineList = [this.jiqi_1,this.jiqi_2,this.jiqi_3];
        this.zulin_box = [this.zulin_box0,this.zulin_box1,this.zulin_box2];
        this.selectItem = null;

        this.Status = [false,false];

        this.tips = [this.tips1,this.tips2];

        this.caoList = [this.cao0,this.cao1,this.cao2];

        this.select_zulin_box = null;


        //租赁绑定点击事件
        for(var i = 0; i < this.zulin_box.length; i++)
        {
            this.zulin_box[i].on(Laya.Event.CLICK,this,this.onZulinBoxClick,[this.zulin_box[i]]);
            this.zulin_box[i].number = i+1;
            this.zulin_box[i].zulin_status = false;
            this.zulin_box[i].zulin_disabled = false;
            this.zulin_box[i].select_box = function(){
                this.getChildByName('select').visible = true;
            };
            this.zulin_box[i].unselect_box = function(){
                this.getChildByName('select').visible = false;
            };
            this.zulin_box[i].countdown = function(){
                this.sy_time --;
                this.getChildByName('countdown').changeText(Utils.formatSeconds(this.sy_time));
                if(this.sy_time <= 0){
                    this.zulin_status = false;
                    self.initFactory();
                    this.timer.clear(this,this.countdown);
                    this.getChildByName('countdown').changeText('');
                }
            };
            this.zulin_box[i].setZulin = function(now,start,stop){
                var now_time = Utils.strToTime(now);
                var start_time = Utils.strToTime(start);
                var stop_time = Utils.strToTime(stop);

                this.sy_time = (stop_time-start_time)-(now_time-start_time);
                if(this.sy_time > 0){
                    this.zulin_status = true;
                    this.getChildByName('countdown').changeText(Utils.formatSeconds(this.sy_time));
                    this.timer.loop(1000,this,this.countdown);
                }
            };
            this.zulin_box[i].setZulinDisabled = function(flag){
                if(!flag){
                    this.disabled = false;
                    this.zulin_disabled = false;
                    this.getChildByName('suo').visible = false;
                    self.tips[this.number-2].visible = false;
                }else {
                    this.disabled = true;
                    this.zulin_disabled = true;
                    this.getChildByName('suo').visible = true;
                    self.tips[this.number-2].visible = true;
                }
            }
        }

        //租赁按钮点击事件
        this.zulin_ok_btn.clickHandler = new Laya.Handler(this,this.onZulinBtnClick);

        //绑定槽点击事件
        for(var i = 0; i < this.caoList.length; i++)
        {
            this.caoList[i].on(Laya.Event.CLICK, this, this.onCaoClick,[i]);
            this.caoList[i].caoIndex = i;
            this.caoList[i].status = false;//加工状态
        }
        this.btn_srart.clickHandler = new Laya.Handler(this,this.onStartBtnClick);
        this.btn_lingqu.clickHandler = new Laya.Handler(this,this.onLingquBtnClick);
        this.btn_speedup.clickHandler = new Laya.Handler(this,this.onSpeedUpBtnClick);
        this.closeHandler = new Laya.Handler(this,this.onDialogClose);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(5);
            dialog.popup();
        });

        this.tab.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 0 && this.tab.selectedIndex == 0){
                ZhiYinMask.instance().setZhiYin(1);
            }else if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1 && this.tab.selectedIndex == 1){
                ZhiYinMask.instance().ZhiYinDialog();
            }
        });
        this.timerLoop(500,this,this.updateTime);
        this.getMachineStatus();
        this.ck_btn.clickHandler = new Laya.Handler(this, this.goto_CK);
    }
    Laya.class(JGCDialog,"JGCDialog",JGCDialogUI);
    var proto = JGCDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 7)
        {
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 8) {
            ZhiYinManager.instance().showZhiYin();
            ZhiYinMask.instance().close();
        }
    };

    proto.getFactoryStatus = function()
    {
        Utils.post('process/lists',{uid:localStorage.GUID},this.onFactoryStatusReturn,onHttpErr);
    };

    proto.onFactoryStatusReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i = 0; i < res.data.length; i++)
            {
                switch(Number(res.data[i].status))
                {
                    case 0:
                        break;
                    case 1:
                        self.setPeifang(Number(res.data[i].process_index),{shopid:res.data[i].before_shopid,icon:ItemInfo[res.data[i].before_shopid].thumb,name:ItemInfo[res.data[i].before_shopid].name});
                        self.startWork(Number(res.data[i].process_index));
                        break;
                    case 2:
                        self.setPeifang(Number(res.data[i].process_index),{shopid:res.data[i].before_shopid,icon:ItemInfo[res.data[i].before_shopid].thumb,name:ItemInfo[res.data[i].before_shopid].name});
                        self.caoList[Number(res.data[i].process_index)].status = true;
                        //self.endWork(Number(res.data[i].index)-1);
                        break;
                }
            }
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.getMachineStatus = function()
    {
        Utils.post('zulin/lists',{uid:localStorage.GUID},this.onMachineStatusReturn,onHttpErr);
    };

    proto.onMachineStatusReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            if(res.data.list.length){
                for(var i = 0; i < res.data.list.length; i++)
                {
                    self.zulin_box[Number(res.data.list[i].number)-1].setZulin(res.time,res.data.list[i].start_time,res.data.list[i].stop_time);
                }
            }
            self.Building.Level = res.data.zhiyan_lv;
            self.getFactoryStatus();
            self.initFactory();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onZulinBoxClick = function(box)
    {
        for(var i = 0; i < this.zulin_box.length; i++)
        {
            this.zulin_box[i].unselect_box();
        }
        box.select_box();
        if(box.zulin_status){
            this.zulin_ok_btn.disabled = true;
        }else {
            this.zulin_ok_btn.disabled = false;
        }
        this.select_zulin_box = box;

        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 0)
        {
            ZhiYinMask.instance().setZhiYin(2);
            this.zulin_ok_btn.disabled = false;
        }
    };

    proto.onZulinBtnClick = function()
    {
        if(this.select_zulin_box)
        {
            console.log(this.select_zulin_box.number);
            Utils.post('zulin/zu',{uid:localStorage.GUID,number:this.select_zulin_box.number},this.onZulinReturn,onHttpErr,this.select_zulin_box);
        }
    };

    proto.onZulinReturn = function(res,box)
    {
        console.log(res);
        if(res.code == 0)
        {
            //box.zulin_status = true;
            box.setZulin(res.time,res.data.start_time,res.data.stop_time);
            self.zulin_ok_btn.disabled = true;
            self.initFactory();
            self.stage.getChildByName("MyGame").initUserinfo();
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 0)
            {
                ZhiYinMask.instance().setZhiYin(3);
                ZhiYinManager.instance().setGuideStep(7,1,true);
            }
        }else{
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.initFactory = function()
    {
        this.zulin = 0;
        for(var i = 0; i < this.zulin_box.length; i++)
        {
            if(this.zulin_box[i].zulin_status){
                this.zulin = this.zulin_box[i].number;
            }
        }
        switch(Number(this.Building.Level))
        {
            case 0:
                break;
            case 1:

                break;
            case 2:
                this.zulin_box[1].setZulinDisabled(false);
                break;
            case 3:
                this.zulin_box[1].setZulinDisabled(false);
                break;
            case 4:
                this.zulin_box[1].setZulinDisabled(false);
                this.zulin_box[2].setZulinDisabled(false);
                break;
        }
        this.setMachineLevel(this.zulin);

    };

    proto.setMachineLevel = function(level)
    {
        var name = ['','初级制烟机器','中级制烟机器','高级制烟机器'];
        for(var i = 0,len = this.MachineList.length; i < len; i++)
        {
            this.MachineList[i].visible = false;
        }
        if(level){
            this.MachineList[level-1].visible = true;
            if(level >= 2) this.caoList[1].visible = true;
            if(level >= 3) this.caoList[2].visible = true;
        }
        this.jiqi_name.changeText(name[level]);

    };

    proto.onSpeedUpBtnClick = function()
    {
        if(this.selectItem && this.selectItem.status){
            //var item_lv = ItemInfo[this.selectItem.peifangData.shopid].type2;
            var shandian_num = Number(this.need_ledou.text);
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 2)
            {
                shandian_num = 0;
            }
            var dialog = new Confirm1("加速需要消耗"+shandian_num+'闪电');
            var index = this.selectItem.caoIndex;
            dialog.closeHandler = new Laya.Handler(this,function(name) {
                console.log(name);
                if (name == Dialog.YES) {
                    if (this.stage.getChildByName("MyGame").UI.subShandian(shandian_num)) {
                        console.log("加速"+index);
                        Utils.post("process/process_jiasu", {uid: localStorage.GUID,process_index:index}, this.onSpeedUpReturn, onHttpErr,index);
                    }
                    else {
                        var dialog = new RechargeDialog('shandian');
                        dialog.popup();
                    }
                }
            });
            dialog.popup();
        }


    };

    proto.onSpeedUpReturn = function(res,index)
    {
        console.log(res);
        if(res.code == 0)
        {
            //self.endWork(index);
            self.Building.FactoryEnding(index);
            self.stage.getChildByName("MyGame").initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onStartBtnClick = function()
    {
        if(this.selectItem){
            if(!this.selectItem.status){
                if(this.selectItem.peifangData){
                    var peifang_id = this.selectItem.peifangData.shopid;
                    var caoIndex = this.selectItem.caoIndex;
                    console.log('开始生产'+caoIndex+'槽位,配方id:'+peifang_id);
                    Utils.post("process/process_start",{uid:localStorage.GUID,shopid:peifang_id,process_index:caoIndex},this.onStartDataReturn,onHttpErr,caoIndex);
                }else {
                    var dialog = new CommomConfirm('请选择一张调香书');
                    dialog.popup();
                }
            }
        }else {
            var dialog = new CommomConfirm('请选择一个生产位');
            dialog.popup();
        }

    };

    proto.onStartDataReturn = function(res,index)
    {
        console.log(res);
        if(res.code == 0)
        {
            console.log(res.data);
            for(var i = 0; i < res.data.length; i++)
            {
                var data = {shopid:res.data[i].before_shopid,time:Utils.strToTime(res.data[i].stop_time) - Utils.strToTime(res.data[i].start_time),work_time:Utils.strToTime(res.data[i].stop_time) - Utils.strToTime(res.data[i].start_time),start_time:Utils.strToTime(res.data[i].start_time),stop_time:Utils.strToTime(res.data[i].stop_time),now_time:Utils.strToTime(res.time)};
                self.Building.FactoryWorking(Number(res.data[i].process_index),data);
                self.startWork(Number(res.data[i].process_index));
            }
        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onCaoClick = function(index)
    {
        this.onSelectItem(index);
        if(this.caoList[index].status) return;
        switch(this.zulin){
            case 0:
                var dialog = new CommomConfirm("请先租机器");
                dialog.popup();
                if(AllowGuide){
                    GuideManager.instance().factory(this,0);
                }
                break;
            case 1:
                if(index == 1){
                    var dialog = new CommomConfirm("请租中级以上机器解锁");
                    dialog.popup();
                    break;
                }else if(index == 2){
                    var dialog = new CommomConfirm("请租高级以上机器解锁");
                    dialog.popup();
                    break;
                }
                var dialog = new JGCPeifang(this,index);
                dialog.popup();
                break;
            case 2:
                /*var isWorking = false;
                for(var i = 0; i < this.caoList.length; i++)
                {
                    if(this.caoList[i].status){
                        //isWorking = true;
                    }
                }*/
                if(index == 2){
                    var dialog = new CommomConfirm("请租高级机器解锁");
                    dialog.popup();
                    break;
                }

                var dialog = new JGCPeifang(this,index);
                dialog.popup();

                break;
            case 3:
                var dialog = new JGCPeifang(this,index);
                dialog.popup();
                break;
        }

    };

    proto.onLingquBtnClick = function()
    {
        if(this.selectItem){
            if(this.Building.FactoryTimeNum[this.selectItem.caoIndex] <= 0){
                this.Gather(this.selectItem.caoIndex);
            }
        }
        //
    };

    proto.setPeifang = function(index,peifang)
    {
        if(this.caoList[index].status) return;
        var image = this.caoList[index].getChildByName('icon');
        image.skin = peifang.icon;
        this.caoList[index].peifangData = peifang;
        this.caoList[index].getChildByName('jia').visible = false;
        this.onSelectItem(index);
    };

    proto.onSelectItem = function(index)
    {
        this.selectItem = this.caoList[index];
        for(var i = 0; i < this.caoList.length; i++)
        {
            this.caoList[i].getChildByName('selected').visible = false;
        }
        this.selectItem.getChildByName('selected').visible = true;
        if(this.selectItem.peifangData){
            var yan = ItemInfo[ItemInfo[this.selectItem.peifangData.shopid].mubiao];
            this.yan_name.changeText(yan.name);
            this.yan_icon.skin = yan.thumb;
        }else {
            this.yan_name.changeText('');
            this.yan_icon.skin = '';
        }

    };

    proto.updateTime = function()
    {
        var cao = this.selectItem;
        if(cao && cao.status){
            var curr_time = this.Building.FactoryTimeNum[cao.caoIndex];
            var all_time = this.Building.FactoryData[cao.caoIndex].work_time;
            if(curr_time > 0){
                this.time_progress.value = curr_time/all_time;
                this.time_countdown.text = Utils.formatSeconds(curr_time);
                this.btn_srart.selected = true;
                this.btn_srart.visible = true;
                this.btn_lingqu.visible = false;
            }else {
                this.btn_srart.selected = false;
                this.btn_srart.visible = false;
                this.btn_lingqu.visible = true;
                this.time_progress.value = 0;
                this.time_countdown.text = '';
            }
        }else {
            this.btn_srart.selected = false;
            this.btn_srart.visible = true;
            this.btn_lingqu.visible = false;
            this.time_progress.value = 0;
            this.time_countdown.text = '';
        }
        this.setNeedLeDou();
    };

    proto.startWork = function(index)
    {

        this.caoList[index].status = true;
        //this.StartBTNList[index].gray = true;
        this.time_countdown.visible = true;

        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
        {
            ZhiYinManager.instance().setGuideStep(7,2,true);
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.endWork = function(index)
    {
        this.caoList[index].status = false;
        this.caoList[index].peifangData = null;
        this.caoList[index].getChildByName('icon').skin = "";
        this.caoList[index].getChildByName('jia').visible = true;
        this.yan_name.changeText('');
        this.yan_icon.skin = '';
        this.btn_srart.selected = false;
        this.btn_srart.visible = true;
        this.btn_lingqu.visible = false;
        this.time_progress.value = 0;
        this.time_countdown.text = '';

    };

    //收取成品
    proto.Gather = function(index)
    {
        //this.status = 0;
        Utils.post('process/process_gather',{uid:localStorage.GUID,process_index:index},this.onGatherReturn,onHttpErr,index);
    };

    proto.onGatherReturn = function(res,index)
    {
        if(res.code == undefined)
        {
            var dialog = new CommomConfirm("数据连接失败");
            dialog.popup();
            return;
        }
        if(res.code == 0)
        {
            var ids = self.Building.FactoryData[index].shopid.split(",");
            for(var i = 0; i < ids.length; i++)
            {
                ids[i] = ItemInfo[ids[i]].mubiao;
            }
            getItem(ids);
			//获得抽奖机会
			/*
            if(Number(res.data.draws_times) == 1) {
                var draw = new Draws();
                draw.init(1610);
                draw.popup();
            }*/

            if(res.data.suipian.length){
                var suipian_tips = new FragmentGetTips(res.data.suipian);
                suipian_tips.popup()
            }

            self.endWork(index);
            //self.Building.removeCollectible();
            self.Building.status = 0;
            self.Building.FactoryData[index] = [];



            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 3)
            {
                ZhiYinManager.instance().setGuideStep(8,0,true);
                ZhiYinMask.instance().ZhiYinClose();
            }else {
                var dialog = new CommomConfirm("恭喜香烟生产完成，可到仓库查看");
                dialog.popup();
            }
        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.setNeedLeDou = function()
    {
        if(!this.selectItem) return;

        var time = this.Building.FactoryTimeNum[this.selectItem.caoIndex];
        var num = count_shandian(time);
        if(ZhiYinManager.step1 == 7){
            num = 0;
        }
        this.need_ledou.changeText(num);
    };

    proto.onDialogClose = function()
    {

        this.timer.clear(this,this.updateTime);

        for(var o = 0; o < this.zulin_box.length; o++)
        {
            this.timer.clear(this.zulin_box[o],this.zulin_box[o].countdown);
        }
    }

 
  proto.goto_CK = function(){
      this.close();
    //   Laya.stage.getChildByName('MyGame').map.mapMoveTo(16,26);
      var dialog = new CKDialog();
      dialog.popup();
  }

})();