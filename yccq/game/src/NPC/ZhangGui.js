/**
 * Created by 41496 on 2017/9/6.
 */
(function(){
    //掌柜NPC
    function ZhangGui()
    {
        ZhangGui.__super.call(this);
        this._NPCEnable = false;
        console.log(this._NPCEnable);
        this.ani = null;
        Laya.Animation.createFrames(['tex/shanghanglaoban.png','tex/shanghanglaoban_2.png'],'zhanggui_ani');
        this.body = new Laya.Animation();
        this.body.interval = 3000;
        this.body.play(0,true,'zhanggui_ani');
        this.addChild(this.body);
        this.size(this.body.getBounds().width,this.body.getBounds().height);

        this.pivot(50,100);

        this.tanhao = new Laya.Image('tex/zhanggui_tanhao.png');
        this.tanhao.visible = this._NPCEnable;
        this.tanhao.anchorX = 0.5;
        this.tanhao.anchorY = 1;
        this.tanhao.pos(60,25);
        this.addChild(this.tanhao);

        this.createAni();

        this.scale(0.7,0.7);

        this.on(Laya.Event.CLICK,this,this.onClick);
    }
    Laya.class(ZhangGui,"ZhangGui",Laya.Sprite);
    var proto = ZhangGui.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('掌柜');
        if(this._NPCEnable){
            console.log('对话框');
            var dialog = new zhangguiUI();
            dialog.closeHandler = new Laya.Handler(this,this.onDialogClose);
            dialog.popup();
        }else {
            console.log('不可用');
        }
    };

    proto.createAni = function()
    {
        Laya.Animation.createFrames(['donghua/yanquan_1.png','donghua/yanquan_2.png','donghua/yanquan_3.png','donghua/yanquan_4.png'],'zhanggui_yanquan');
        this.ani = new Laya.Animation();
        this.ani.interval = 300;
        this.ani.play(0,false,'zhanggui_yanquan');
        var bounds = this.ani.getBounds();
        this.ani.pivot(bounds.width/2,bounds.height);
        this.ani.pos(16,44);
        this.addChild(this.ani);
        this.ani.on(Laya.Event.COMPLETE,this,function(){
            this.ani.visible = false;
            this.timer.once(2000,this,function(){
                this.ani.play(0,false,'zhanggui_yanquan');
                this.ani.visible = true;
            });
        });
    };

    proto.onDialogClose = function(name)
    {
        console.log(name);
        if(Dialog.YES == name)
        {
            var dialog = new JianDieBuy();
            dialog.popup();
        }
        else if(name == Dialog.NO)
        {
            var tips = new tipsDialog();
            tips.content.innerHTML = '等你有需要的时候可以随时到商城掌柜那去雇佣间谍喔！';
            tips.content.y = 100;
            tips.ok_btn.visible = false;
            tips.bye.visible = true;
            tips.popup();
        }
    };

    Laya.getset(0,proto,'NPCEnable',function(){
        return this._NPCEnable;
        },function(val){
        this._NPCEnable = val;
        this.tanhao.visible= this._NPCEnable;
    });
})();