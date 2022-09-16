/**
 * Created by 41496 on 2017/5/17.
 */
(function(){
    var self = null;
    function UserInfoDialog() {
        UserInfoDialog.__super.call(this);
        self = this;
        this.info_btn.clickHandler = new Laya.Handler(this,this.onInfoBtnClick);
        this.yijian_btn.clickHandler = Laya.Handler.create(this,this.onYiJianBtnClick,null,false);
        this.header_btn.clickHandler = Laya.Handler.create(this,this.onHeaderBtnClick,null,false);
        this.getUserData()
    }
    Laya.class(UserInfoDialog,"UserInfoDialog",UserInfoUI);
    var proto = UserInfoDialog.prototype;

    proto.getUserData = function()
    {
        Utils.post('user/detail',{uid:localStorage.GUID},this.onUserDataReturn);
    };

    proto.onUserDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.nickname.changeText(res.data.nickname);
            self.level.changeText(res.data.game_lv+"级");
            self.progress_exp.value = res.data.game_xp/res.data.game_xp_all;
            self.progress_text.changeText(res.data.game_xp+"/"+res.data.game_xp_all);
            self.bean.changeText(res.data.ledou);
            self.money.changeText(res.data.money);
            self.medal.changeText(Number(res.data.shandian));
            if(Number(res.data.role) > 0) self.role.skin = "userinfo/role_"+res.data.role+".png";


            if(Number(res.data.yannong_lv)) {
                self.Plant.skin = config.Achievement.Yannong.icon[Number(res.data.yannong_lv)-1];
                self.PlantName.changeText(config.Achievement.Yannong.name[Number(res.data.yannong_lv)-1]);
            }
            if(Number(res.data.zhiyan_lv)) {
                self.Zhiyan.skin = config.Achievement.Zhiyan.icon[Number(res.data.zhiyan_lv)-1];
                self.ZhiyanName.changeText(config.Achievement.Zhiyan.name[Number(res.data.zhiyan_lv)-1]);
            }
            if(Number(res.data.jiaoyi_lv)) {
                self.Sale.skin = config.Achievement.Jiaoyi.icon[Number(res.data.jiaoyi_lv)-1];
                self.SaleName.changeText(config.Achievement.Jiaoyi.name[Number(res.data.jiaoyi_lv)-1]);
            }
            if(Number(res.data.pinjian_lv)) {
                self.Pinjian.skin = config.Achievement.Pinjian.icon[Number(res.data.pinjian_lv)-1];
                self.PinjianName.changeText(config.Achievement.Pinjian.name[Number(res.data.pinjian_lv)-1]);
            }
            self.Plant.on(Laya.Event.CLICK,self,self.popChengjiu);
            self.Zhiyan.on(Laya.Event.CLICK,self,self.popChengjiu);
            self.Sale.on(Laya.Event.CLICK,self,self.popChengjiu);
            self.Pinjian.on(Laya.Event.CLICK,self,self.popChengjiu);
        }
    };

    proto.onInfoBtnClick = function()
    {
        var dialog = new MyPrize();
        dialog.popup();
    };

    proto.popChengjiu = function()
    {
        var dialog = new Chengjiu();
        dialog.popup();
    };

    proto.onYiJianBtnClick = function()
    {
        window.location.href = config.BaseURL+'Main/suggestion';
    };

    proto.onHeaderBtnClick = function()
    {

        var dialog = new ChangeHeader();
        dialog.popup();

    }
})();