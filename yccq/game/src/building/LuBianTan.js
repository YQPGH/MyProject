/**
 * Created by 41496 on 2017/6/13.
 */
(function(){
    function LuBianTan(type)
    {
        LuBianTan.__super.call(this);
        this.initBuilding(building.LuBianTan,"tex/lubianxiaotan_text.png");
        this.pivot(Math.floor(this.width/2),100);

        this.on(Laya.Event.CLICK,this,this.onClick);

        this.FarmType = type;

        this.unlock = false;
        if(!this.FarmType){
            if(Laya.stage.getChildByName('MyGame').UI.userInfo.game_lv < 12){
                this.tips = new Laya.Image('tex/jianzhushuoding.png');
                this.tips.pos(55,65);
                this.tips.scale(1.2,1.2);
                var text = new Laya.Label('12级解锁');
                text.color = '#ffccb6';
                text.fontSize = 18;
                text.pos(20,4);
                this.tips.addChild(text);
                this.addChild(this.tips);
            }
        }
    }
    Laya.class(LuBianTan,"LuBianTan",Building);
    var proto = LuBianTan.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('路边小摊');

        if(!this.FarmType){
            if(!this.unlock){
                this.unlock = Boolean(Number(Laya.stage.getChildByName('MyGame').UI.userInfo.market_status));
            }
            var game_lv = Laya.stage.getChildByName('MyGame').UI.userInfo.game_lv;
            if(game_lv < 12)
            {
                var dialog = new CommomConfirm('12级可解锁此功能，当前您为'+game_lv+'级，\n快努力升级吧,解锁后可以与其他玩家相互交易物品！');
                dialog.popup();
                return;
            }
            else if(!this.unlock)
            {
                this.showUnlock();
                return;
            }
        }

        var dialog = new LBTDialog(this.FarmType);
        dialog.popup();
    };

    proto.showUnlock = function()
    {
        var tips = new tipsDialog();
        tips.content.innerHTML = '是否使用<span color="#ae0626">500乐豆</span>解锁路边小摊';
        tips.content.y = 100;
        tips.yes_btn.visible = true;
        tips.cancel_btn.pos(tips.use_ledou_btn.x,tips.use_ledou_btn.y);
        tips.cancel_btn.visible = true;
        tips.ok_btn.visible = false;
        tips.popup();
        tips.closeHandler = new Laya.Handler(this,this.onTipsClose);
    };

    proto.onTipsClose = function(name)
    {
        if(name == 'yes')
        {
            Utils.post('market/unlock_market',{uid:localStorage.GUID,spend_type:'ledou'},function(res,caller){
                if(res.code == 0)
                {
                    caller.unlock = true;
                    caller.event('click');
                }
                else
                {
                    var dialog = new CommomConfirm(res.msg);
                    dialog.popup();
                }
                Laya.stage.getChildByName('MyGame').initUserinfo();
            },onHttpErr,this);

        }
        else
        {
            var tips = new tipsDialog();
            tips.content.innerHTML = '你确定不需要解锁吗？这里是可以最快速<span color="#ae0626">获得生产原料</span>的途径喔！没关系，你再考虑一下，想要解锁再来这里找我。';
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