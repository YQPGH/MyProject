/**
 * Created by 41496 on 2017/11/20.
 */
(function(){
    var self = null;
    function welcome(type)
    {
        welcome.__super.call(this);
        self = this;
        if(type == 'intro'){
            this.text = '在这里不仅能体验到<span color="#ae0626">烟草种植、</span><br/><span color="#ae0626">加工到生产的流程工艺</span><br/>还有机会赢取<span color="#ae0626">品吸机会代金券</span>等惊喜好礼,<br/>现在开始你的制烟之旅吧!';
            this.content.style.leading = 20;
            this.content.style.padding = [15,0,0,0];
            this.start_btn.visible = true;
            this.start_btn.clickHandler = new Laya.Handler(this,this.onStartBtnClick);
        }else if(type == 'gift'){
            this.text = '为了欢迎你的加入,<br/>我们为你准备了丰厚的<span color="#ae0626">新手礼包</span>!';

            this.content.style.leading = 10;
            this.content.style.padding = [10,0,0,0];
            this.gift_btn.visible = true;
            this.gift_btn.clickHandler = new Laya.Handler(this,this.receiveGift);
        }else if(type == 'done'){
            this.text = '到了这里就是香烟生产的全部流程了,<br/>想要生产高星级香烟可以查看攻略内容,<br/>只有<span color="#ae0626">使用四、五星香烟</span>抽奖才有可能得到<span color="#ae0626">品吸机会代金券</span>喔!';

            this.content.style.leading = 10;
            this.content.style.padding = [10,0,0,0];
            this.xiayibu_btn.visible = true;
            this.xiayibu_btn.clickHandler = new Laya.Handler(this,this.next);
        }
        this.content.style.align = 'center';
        this.content.style.color = '#4d2202';
        this.content.style.fontSize = 24;
        this.content.innerHTML = this.text;
        this.ani.play(0,false);

        //this.gift.on(Laya.Event.CLICK, this, this.receiveGift);
        //Laya.timer.loop(500, this, this.showReceiveBtn);
    }
    Laya.class(welcome,'welcome',welcomeUI);
    var proto = welcome.prototype;

    proto.onStartBtnClick = function()
    {
        this.close();
        ZhiYinManager.instance().setGuideStep(2,0);

    };

    //领取新手礼包
    proto.receiveGift = function(){
        this.close();
        //领取礼包
        Utils.post("user/newer_gift",{uid:localStorage.GUID}, this.emptyFun, onHttpErr);
    };

    proto.emptyFun = function(res){
        if(res.code == 0)
        {
            Laya.stage.getChildByName("MyGame").initUserinfo();
            var dialog = new GuideStep01UI();
            dialog.on(Laya.Event.CLICK,this,function(){
                dialog.close();
                ZhiYinManager.instance().setGuideStep(3,0);
            });
            dialog.popup();
            //
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.closeHandler = new Laya.Handler(this,function(){
                ZhiYinManager.instance().setGuideStep(3,0);
            });
            dialog.popup();
        }
    };

    proto.next = function()
    {
        var text = '恭喜您完成新手指引任务,我们准备了:<br/><span color="#ae0626">一星基础调香书*3</span><br/><span color="#ae0626">一星巴西种子*10</span><br/><span color="#ae0626">一星云贵种子*5</span><br/><span color="#ae0626">一星吕宋种子*5</span>、<span color="#ae0626">一点红嘴棒*5</span><br/>快根据指引的步骤来生产一包香烟吧!';
        this.xiayibu_btn.visible = false;
        this.lingqu_btn.visible = true;
        this.content.innerHTML = text;
        this.lingqu_btn.clickHandler = new Laya.Handler(this,this.lingqu);
    };

    proto.lingqu = function()
    {
        this.close();
        ZhiYinManager.instance().setGuideStep(10,0);
    }

})();