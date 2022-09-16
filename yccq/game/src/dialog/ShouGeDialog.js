/**
 * Created by 41496 on 2017/7/19.
 */
(function(){
    //收获界面
    var self = null;
    function ShouGeDialog()
    {
        ShouGeDialog.__super.call(this);
        self = this;
        this.name = 'shouge';
        this.shouge_btn.clickHandler = new Laya.Handler(this,this.onShouGeBtnClick);
        this.yijian_btn.clickHandler = new Laya.Handler(this,this.onYiJianBtnClick);

    }
    Laya.class(ShouGeDialog,'ShouGeDialog',ShouGeUI);
    var proto = ShouGeDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 2){
            ZhiYinMask.instance().setZhiYin(1);
        }
    };

    proto.onShouGeBtnClick = function()
    {
        var selected_obj = this.stage.getChildByName("MyGame").currSelectedOBJ;
        if(selected_obj.land1.seed && selected_obj.land1.seed.isMature){//有种子并且成熟则发送收割
            selected_obj.SG();
            this.close();
        }
    };

    proto.onYiJianBtnClick = function()
    {
        console.log('一件收割');
        Utils.post('land/yi_jian_gather',{uid:localStorage.GUID},this.onYiJianGatherReturn,onHttpErr);
    };

    proto.onYiJianGatherReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var yanye_ids = [];
            var land_list = self.stage.getChildByName("MyGame").landArr;
            for(var j = 0; j < res.data.success.length; j++)
            {
                for(var i = 0,len = land_list.length; i < len; i++)
                {
                    if(Number(land_list[i].land1.landIndex) == Number(res.data.success[j].id))
                    {
                        land_list[i].land1.destroyChildren();
                        land_list[i].land1.seed = null;
                        land_list[i].Status = false;
                        land_list[i].clearHanZai();
                    }
                }
                if(res.data.success[j].yanye_shopid != 0){
                    yanye_ids.push(res.data.success[j].yanye_shopid);
                }
            }
            if(res.data.suipian.length){
                var suipian_tips = new FragmentGetTips(res.data.suipian);
                suipian_tips.popup()
            }
            var failed = [];
            for(var k = 0; k < res.data.false.length; k++)
            {
                for(var h = 0,len = land_list.length; h < len; h++)
                {
                    if(Number(land_list[h].land1.landIndex) == Number(res.data.false[k].id))
                    {
                        land_list[h].land1.destroyChildren();
                        land_list[h].land1.seed = null;
                        land_list[h].Status = false;
                        land_list[h].clearHanZai();
                    }
                }
                if(res.data.false[k].yanye_shopid != 0){
                    failed.push(res.data.false[k].yanye_shopid);
                }
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
            getItem(yanye_ids);
            self.stage.getChildByName("MyGame").initUserinfo();
            self.close();
            if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 2)
            {
                ZhiYinManager.instance().setGuideStep(5,0);
            }
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        ShiJianManager.instance().init();
    }
})();