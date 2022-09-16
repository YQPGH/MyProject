/**
 * Created by 41496 on 2017/5/23.
 */
(function(){
    //种植倒计时
    var self = this;
    function PlantDialog(seed)
    {
        PlantDialog.__super.call(this);
        self = this;
        this.name = 'plant';
        this.seed = seed;
        //this.Huafei_list = [this.huafei1,this.huafei2,this.huafei3];

        /*for(var i = 0; i < this.Huafei_list.length; i++)
        {
            this.Huafei_list[i].on(Laya.Event.CLICK,this,this.onHuafeiClick,[i]);
        }*/


        //this.getHuafei();

        this.SpeedUp_btn.clickHandler = new Laya.Handler(this,this.onSpeedUpBtnClick);

        this.clear_btn.clickHandler = new Laya.Handler(this,this.onClearBtnClick);

        this.plant_name.changeText(seed.seedName);

        this.timerLoop(100,this,this.UpdateTime);
    }
    Laya.class(PlantDialog,"PlantDialog",PlantUI);
    var proto = PlantDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 1){
            ZhiYinMask.instance().setZhiYin(1);
        }
    };

    proto.UpdateTime = function()
    {

        this.countdown.changeText(Utils.formatSeconds(this.seed.lifeTime));
        this.plant_progress.value = this.seed.lifeTime/this.seed.seedData.work_time;
    };

    proto.onSpeedUpBtnClick = function()
    {
        var shandian_num = count_shandian(this.seed.lifeTime);
        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 1){
            shandian_num = 0;
        }
        var dialog = new Confirm1("加速需要消耗"+shandian_num+"闪电");
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Dialog.YES){
                if (this.stage.getChildByName("MyGame").UI.subShandian(shandian_num)) {
                    Utils.post("land/seed_jiasu",{uid:localStorage.GUID,land_id:this.seed.parent.landIndex},this.onSpeedUpReturn,onHttpErr);
                }
                else {
                    var dialog = new RechargeDialog('shandian');
                    dialog.popup();
                }

            }

        });
        dialog.show();
    };

    proto.onSpeedUpReturn = function(res)
    {
        console.log(res);
        if(res.code ==0)
        {
            self.seed.setMature();
            Laya.stage.getChildByName('MyGame').initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onClearBtnClick = function()
    {
        var dialog = new Confirm1("确定要清除吗？");
        dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose);
        dialog.popup();

    };

    proto.onConfirmClose = function(name)
    {
        console.log(name);
        if(name == 'yes')
        {
            var selected_land = this.stage.getChildByName("MyGame").currSelectedOBJ;
            var land_id = selected_land.land1.landIndex;
            Utils.post("land/my_delete",{uid:localStorage.GUID,land_id:land_id},this.onClearReturn,onHttpErr,land_id);
        }
    };

    proto.onClearReturn = function(res,land_id)
    {
        if(res.code == '0')
        {
            var land_list = self.stage.getChildByName("MyGame").landArr;
            for(var i = 0,len = land_list.length; i < len; i++)
            {
                if(land_list[i].land1.landIndex == land_id)
                {
                    console.log(land_list[i].land1.landIndex);
                    land_list[i].land1.destroyChildren();
                    land_list[i].land1.seed = null;
                    land_list[i].Status = false;
                }

            }
            //self.stage.getChildByName("MyGame").initUserinfo();
        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        self.close();
    }

})();