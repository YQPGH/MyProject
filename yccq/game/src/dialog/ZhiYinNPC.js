/**
 * Created by 41496 on 2018/1/10.
 */
(function(){
    function ZhiYinNPC(type){
        ZhiYinNPC.__super.call(this);
        this.type = type;
        this.map = Laya.stage.getChildByName('MyGame').map;
        this.tab_list = [this.getTXS, this.plant, this.bake, this.aging, this.zhiyan, this.pinjian];
        this.building_list = [this.shop_building, this.land_building, this.bake_building, this.aging_building, this.zhiyan_building, this.pinjian_building];


        this.popupEffect = new Laya.Handler(this,this.myPopupEffect);
        this.closeEffect = new Laya.Handler(this,this.myCloseEffect);
        this.init();
    }
    Laya.class(ZhiYinNPC,'ZhiYinNPC',zhiyin_npcUI);
    var proto = ZhiYinNPC.prototype;
    proto.init = function()
    {
        for(var i = 0; i < this.tab_list.length; i++)
        {
            if(this.type >= i){
                this.tab_list[i].gray = false;
            }
        }
        this.building_list[this.type].visible = true;
        this.goto_btn.clickHandler = new Laya.Handler(this, this.onGotoBtnClick);

        this.content.style.leading = 15;
        this.content.style.padding = [10,0,0,0];
        this.content.style.align = 'center';
        this.content.style.color = '#4d2202';
        this.content.style.fontSize = 24;
        this.content.innerHTML = text[this.type];

        var skip_btn = new Laya.Button("story/jump_btn.png");
        skip_btn.stateNum = 1;
        skip_btn.pos(0,-(Laya.stage.height-this.height));
        skip_btn.clickHandler = new Laya.Handler(this,this.onSkipClick);
        this.addChild(skip_btn);
    };

    proto.onSkipClick = function()
    {
        var dialog = new Confirm1("跳过游戏指引功能后,不再出现游戏指引,是否确认跳过？");
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(Dialog.YES == name)
            {
                this.close();
                ZhiYinManager.instance().setGuideStep(10,0);

            }
        });

    };

    proto.onGotoBtnClick = function()
    {
        //this.close();
        ZhiYinManager.instance().moveScreen(ZhiYinNPC.building_pos[this.type][0],ZhiYinNPC.building_pos[this.type][1],this.type);

    };

    proto.myPopupEffect = function(dialog)
    {
        dialog.scale(1,1);
        Laya.Tween.from(dialog,{x:Laya.stage.width},500,Laya.Ease.backIn,Laya.Handler.create(this,this.doOpen,[dialog]));
    };

    proto.myCloseEffect = function (dialog,type){
        Laya.Tween.to(dialog,{x:Laya.stage.width},500,Laya.Ease.strongOut,Laya.Handler.create(Dialog.manager,Dialog.manager.doClose,[dialog,type]));
    };

    var text = [
        '要生产一包香烟,首先要拥有一本<span color="#ae0626">调香书</span>,<span color="#ae0626">集齐</span>调香书记载的<span color="#ae0626">各种材料</span>才能进行加工,我们去真龙商行购买调香书吧!',
        '快到<span color="#ae0626">种植区</span>去种植吧!',
        '恭喜你收获自己的第一片烟叶，为了让烟叶的香气更突出、色泽更好，我们去<span color="#ae0626">烘烤室</span>看看!',
        '恭喜完成第一次烘烤,接下来要让烟叶的味道更醇厚,去<span color="#ae0626">醇化</span>烟叶吧!',
        '醇化完成后,就可以去制烟了!',
        '恭喜生产出了第一包香烟,快去<span color="#ae0626">品鉴所</span>鉴定下香烟的品质吧!'
    ];

    ZhiYinNPC.building_pos = [[17,19],[16,29],[22,30],[22,24],[32,31],[18,15]];
})();