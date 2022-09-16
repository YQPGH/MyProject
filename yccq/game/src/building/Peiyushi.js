/**
 * Created by 41496 on 2017/5/15.
 */
(function(){
    //培育室建筑类
    function Peiyushi(data,now_time,type)
    {
        Peiyushi.__super.call(this);
        this.initBuilding(building.Peiyushi,"tex/zhongzhipeiyu_text.png");
        this.pivot(135,210);
        this.unlock = false;
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
            this.collectiblePos = [this.width/2,this.height/2];
            this.setStatus(data,now_time);

            if(Laya.stage.getChildByName('MyGame').UI.userInfo.game_lv < 8){
                this.tips = new Laya.Image('tex/jianzhushuoding.png');
                this.tips.pos(70,190);
                this.tips.scale(1.2,1.2);
                var text = new Laya.Label('8级解锁');
                text.color = '#ffccb6';
                text.fontSize = 18;
                text.pos(25,4);
                this.tips.addChild(text);
                this.addChild(this.tips);
            }
        }
    }
    Laya.class(Peiyushi,"Peiyushi",Building);
    var proto = Peiyushi.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('种子培育中心');
        var UI = Laya.stage.getChildByName('MyGame').UI;
        if(!this.unlock){
            this.unlock = Boolean(Number(UI.userInfo.peiyu_status));
        }
        var game_lv = UI.userInfo.game_lv;
        if(game_lv < 8)
        {
            var dialog = new CommomConfirm('8级可解锁此功能，当前您为'+game_lv+'级，\n快努力升级吧,解锁后可以使用烟叶培育更高级的种子！');
            dialog.popup();
            return;
        }
        else if(!this.unlock)
        {
            this.showUnlock();
            return;
        }

        var dialog = new PeiyushiDialog();
        dialog.popup();
    };

    proto.showUnlock = function()
    {
        var tips = new tipsDialog();
        tips.content.innerHTML = '使用<span color="#ae0626">200乐豆</span>或<span color="#ae0626">20000银元</span>解锁种子培育中心';
        tips.content.y = 100;
        tips.use_lebi_btn.visible = true;
        tips.use_ledou_btn.visible = true;
        tips.cancel_btn.visible = true;
        tips.ok_btn.visible = false;
        tips.popup();
        tips.closeHandler = new Laya.Handler(this,this.onTipsClose);
        tips.use_lebi_btn.clickHandler = new Laya.Handler(this,function(){
            //使用银元解锁
            Utils.post('peiyu/unlock_peiyu',{uid:localStorage.GUID,spend_type:'money'},function(res,caller){
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
            Utils.post('peiyu/unlock_peiyu',{uid:localStorage.GUID,spend_type:'ledou'},function(res,caller){
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
            tips.content.innerHTML = '真是遗憾，有了种子培育中心就可以<span color="#ae0626">随时培育</span>高星级种子了！如果需要可以来这里解锁！';
            tips.content.y = 100;
            tips.ok_btn.visible = false;
            tips.bye.visible = true;
            tips.popup();
        }
    };

    proto.clearTips = function()
    {
        console.log(this.tips);
        if(this.tips){
            this.tips.destroy(true);
        }
    }
})();