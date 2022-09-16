/**
 * Created by 41496 on 2017/5/18.
 */
(function(){
    //研究所建筑类
    function YJSBuilding(type)
    {
        YJSBuilding.__super.call(this);
        this.initBuilding(building.Yanjiusuo,"tex/tiaoxiangyanjiu_text.png");
        this.pivot(Math.floor(this.width/2),215);
        this.unlock = false;
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
            if(Laya.stage.getChildByName('MyGame').UI.userInfo.game_lv < 9){
                this.tips = new Laya.Image('tex/jianzhushuoding.png');
                this.tips.pos(80,183);
                this.tips.scale(1.2,1.2);
                var text = new Laya.Label('9级解锁');
                text.color = '#ffccb6';
                text.fontSize = 18;
                text.pos(20,4);
                this.tips.addChild(text);
                this.addChild(this.tips);
            }
        }



    }
    Laya.class(YJSBuilding,"YJSBuilding",Building);
    var proto = YJSBuilding.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('配方研究所');
        var UI = Laya.stage.getChildByName('MyGame').UI;
        if(!this.unlock) {
            this.unlock = Boolean(Number(UI.userInfo.peifang_status));
        }
        var game_lv = UI.userInfo.game_lv;
        if(game_lv < 9)
        {
            var dialog = new CommomConfirm('9级可解锁此功能，当前您为'+game_lv+'级，\n快努力升级吧,解锁后就能使用低星级的调香书合成高星级的调香书了！');
            dialog.popup();
            return;
        }
        else if(!this.unlock)
        {
            this.showUnlock();
            return;
        }

        var dialog = new YJSDialog();
        dialog.popup();
    };

    proto.showUnlock = function()
    {
        var tips = new tipsDialog();
        tips.content.innerHTML = '使用<span color="#ae0626">250乐豆</span>或<span color="#ae0626">25000银元</span>解锁调香研究所';
        tips.content.y = 100;
        tips.use_lebi_btn.visible = true;
        tips.use_ledou_btn.visible = true;
        tips.cancel_btn.visible = true;
        tips.ok_btn.visible = false;
        tips.popup();
        tips.closeHandler = new Laya.Handler(this,this.onTipsClose);
        tips.use_lebi_btn.clickHandler = new Laya.Handler(this,function(){
            //使用乐币解锁
            Utils.post('peifang/unlock_peifang',{uid:localStorage.GUID,spend_type:'money'},function(res,caller){
                if(res.code == 0)
                {
                    caller.unlock = true;
                    caller.event('click');
                    tips.close();
                }
                else
                {
                    var dialog = new CommomConfirm(res.msg);
                    dialog.popup();
                }
                Laya.stage.getChildByName('MyGame').initUserinfo();
            },onHttpErr,this);
        });

        tips.use_ledou_btn.clickHandler = new Laya.Handler(this,function(){
            //使用乐豆解锁
            Utils.post('peifang/unlock_peifang',{uid:localStorage.GUID,spend_type:'ledou'},function(res,caller){
                if(res.code == 0)
                {
                    caller.unlock = true;
                    caller.event('click');
                    tips.close();
                }
                else
                {
                    var dialog = new CommomConfirm(res.msg);
                    dialog.popup();
                }
                Laya.stage.getChildByName('MyGame').initUserinfo();
            },onHttpErr,this);
        });
    };

    proto.onTipsClose = function(name)
    {
        if(name == 'cancel')
        {
            var tips = new tipsDialog();
            tips.content.innerHTML = '调香在制烟中可是至关重要的，可以生产用于抽奖的<span color="#ae0626">高星级香烟的调香书</span>，如果有需要记得回来解锁噢！';
            tips.content.y = 100;
            tips.ok_btn.visible = false;
            tips.bye.visible = true;
            tips.popup();
        }
    };

    proto.clearTips = function()
    {
        if(this.tips){
            this.tips.destroy(true);
        }
    }


})();