/**
 * Created by 41496 on 2018/1/11.
 */
(function(){
    function ZhiYinMask()
    {
        ZhiYinMask.__super.call(this);
        this.building = null;
        this.zhiYinContent = null;
        this.size(Laya.stage.width,Laya.stage.height);
        this.map = Laya.stage.getChildByName('MyGame').map;
        this.init();
        this.zOrder = 2000;
        this.visible = false;
    }
    Laya.class(ZhiYinMask, 'ZhiYinMask', Laya.View);
    var proto = ZhiYinMask.prototype;

    proto.init = function()
    {
        this.masklayer = new Laya.Sprite();
        this.masklayer.graphics.drawRect(0,0,Laya.stage.width,Laya.stage.height,'#000000');
        this.masklayer.alpha = 0.5;
        this.masklayer.cacheAs = 'bitmap';
        this.addChild(this.masklayer);
        Laya.stage.addChild(this);
        this.masklayer.mouseEnabled = true;

        this.hitArea = new Laya.HitArea();
        this.hitArea.hit.drawRect(0, 0, Laya.stage.width, Laya.stage.height, "#000000");
        //this.hitArea.unHit.drawRect(200,200,100,50,"#000000");
        this.masklayer.hitArea = this.hitArea;

        this.viewRect = new Laya.Sprite();
        //设置叠加模式
        //this.viewRect.graphics.drawRect(200,200,100,50,"#000000");
        this.viewRect.blendMode = "destination-out";
        this.masklayer.addChild(this.viewRect);

        this.tips = new Laya.Box();//文本提示容器
        this.tips.visible = false;
        this.tips_bg = new Laya.Image('zhiyin/zhiying_qipao_1-17.png');//提示框背景
        this.tips_bg.pivot(222.5,54);
        this.tips_bg.pos(222.5,54);
        this.tips.addChild(this.tips_bg);
        /*this.tips_content = new Laya.Label();//提示文字
        this.tips_content.color = "#ffffff";
        this.tips_content.fontSize = 30;
        this.tips_content.align = "center";
        this.tips_content.valign = "middle";
        this.tips_content.anchorX = 0.5;
        this.tips_content.anchorY = 0.5;
        this.tips_content.pos(222.5,54);
        this.tips_content.size(296,96);
        this.tips_content.wordWrap = true;*/

        this.tips_content = new Laya.HTMLDivElement();
        this.tips_content.style.width = '230';
        this.tips_content.style.height = 'auto';
        //this.tips_content.style.border = '1px solid red';
        this.tips_content.style.fontSize = 24;

        this.tips_content.style.color = '#ffffff';
        this.tips_content.style.align = 'center';
        this.tips_content.pos(222,54);
        this.tips.addChild(this.tips_content);
        this.addChild(this.tips);

        var skip_btn = new Laya.Button("story/jump_btn.png");
        skip_btn.stateNum = 1;
        skip_btn.pos(0,-(Laya.stage.height-this.height-20));
        skip_btn.clickHandler = new Laya.Handler(this,this.onSkipClick);
        this.addChild(skip_btn);

        this.createNPC();
    };

    proto.onSkipClick = function()
    {
        var dialog = new Confirm1("跳过游戏指引功能后,不再出现游戏指引,是否确认跳过？");
        dialog.closeHandler = new Laya.Handler(this,function(name){
            Dialog.manager.zOrder = 1000;
            if(Dialog.YES == name)
            {
                ZhiYinManager.instance().setGuideStep(10,0);
                this.close();
            }
        });

        dialog.popup();
        Dialog.manager.zOrder = 3000;
    };

    proto.setZhiYinContent = function(content)
    {
        this.zhiYinContent = content;
    };

    proto.ZhiYinBuilding = function(building,tips)
    {
        this.zhiYinContent = tips;
        console.log(tips[ZhiYinManager.step2][0]);
        if(tips[ZhiYinManager.step2][0].dialog){
            if(Dialog.manager.getChildByName(tips[ZhiYinManager.step2][0].dialog)) return;
        }

        this.building = building;
        var building_pos = this.map.mapSprite.localToGlobal(new Laya.Point(this.building.x,this.building.y));
        if(this.building.land1 || (ZhiYinManager.step1 == 3 && ZhiYinManager.step2 ==3)){
            //this.b = new Laya.Image(this.building.land1.skin);
            this.b = new Laya.Sprite();
            this.b.on(Laya.Event.CLICK,this,null);
            this.addChild(this.b);
            this.setZhiYin(0);
        }else {
            this.b = new Laya.Image(this.building.skin);
            var building_name = this.building.getChildByName('BuildingName');
            var b_name = new Laya.Image(building_name.skin);
            b_name.pos(building_name.x,building_name.y);
            b_name.anchorX = 0.5;
            b_name.anchorY = 1;
            this.b.addChild(b_name);

            this.b.pivot(this.building.pivotX,this.building.pivotY);
            this.b.pos(building_pos.x,building_pos.y);

            this.addChild(this.b);
            this.b.on(Laya.Event.CLICK,this,function(){
                if(this.building.land1){
                    this.building.land1.event('click');
                }else {
                    this.building.event('click');
                }

                this.b.destroy(true);
                //this.close();
                //
            });

            this.JianTou = new Laya.Animation();
            this.JianTou.loadAnimation("GuideArrows.ani");
            this.JianTou.play(0,true);
            this.JianTou.pos(this.b.width/2,-50);
            this.b.addChild(this.JianTou);
        }
    };

    proto.ZhiYinDialog = function()
    {
        if(this.zhiYinContent){
            this.popup();
            this.setZhiYin(0);
        }
    };

    proto.setZhiYin = function(index)
    {
        this.tips.visible = true;
        var step2 = ZhiYinManager.step2;
        var step = this.zhiYinContent[step2][index];

        this.changeHitArea(step);

        if(step.npc){
            this.npcShow(step.npc);
            this.ok_btn.visible = true;
        }else {
            this.npcHide();
            this.ok_btn.visible = false;
        }
    };

    proto.changeHitArea = function(step)
    {
        if(step.dialog){
            var dialog = Dialog.manager.getChildByName(step.dialog);
            var view_pos = dialog.localToGlobal(new Laya.Point(step.hit.x, step.hit.y));
            var tips_pos = dialog.localToGlobal(new Laya.Point(step.tips.x, step.tips.y));
        }else {
            var view_pos = new Laya.Point(step.hit.x, step.hit.y);
            var tips_pos = new Laya.Point(step.tips.x, step.tips.y);
        }

        this.hitArea.unHit.clear();
        this.viewRect.graphics.clear();
        if(step.type == "circle"){
            this.hitArea.unHit.drawCircle(view_pos.x, view_pos.y, step.radius, "#000000");
            this.viewRect.graphics.drawCircle(0, 0, step.radius, "#000000");
        }else if(step.type == "poly"){
            this.hitArea.unHit.drawPoly(view_pos.x, view_pos.y, step.points, "#000000");
            this.viewRect.graphics.drawPoly(0, 0, step.points, "#000000");
        }else {
            this.hitArea.unHit.drawRect(view_pos.x, view_pos.y, step.hit.width, step.hit.height, "#000000");
            this.viewRect.graphics.drawRect(0, 0, step.hit.width, step.hit.height, "#000000");
        }

        this.viewRect.pos(view_pos.x, view_pos.y);
        this.tips.pos(tips_pos.x,tips_pos.y);
        this.tips_bg.skewX = step.tips.skewX;
        this.tips_bg.skewY = step.tips.skewY;

        if(step.fontSize){
            this.tips_content.style.fontSize = step.fontSize;

        }else {
            this.tips_content.style.fontSize = 24;
        }

        this.tips_content.innerHTML = step.content;
        this.tips_content.style.height = 'auto';
        this.tips_content.pivot(this.tips_content.width/2,this.tips_content.height/2);


    };

    proto.createNPC = function()
    {
        this.NPC = new Laya.Box();
        this.NPC.visible = false;
        var bg = new Laya.Image("zhiyin/zhiying_diban1.png");
        this.NPC.addChild(bg);

        this.NPCContent = new Laya.HTMLDivElement();
        this.NPCContent.style.width = 800;
        this.NPCContent.style.height = 145;
        //this.NPCContent.style.border = "1px solid red";
        this.NPCContent.style.leading = 10;
        this.NPCContent.style.padding = [10,0,0,0];
        this.NPCContent.style.align = 'center';
        this.NPCContent.style.color = '#4d2202';
        this.NPCContent.style.fontSize = 24;
        this.NPCContent.pos(40,50);
        this.NPC.addChild(this.NPCContent);

        this.ok_btn = new Laya.Button("zhiyin/zhiying_zhanbukaiqi-38.png");
        this.ok_btn.stateNum = 2;
        this.ok_btn.pos(bg.width/2-this.ok_btn.width/2,bg.height-this.ok_btn.height-15);
        this.ok_btn.visible = false;
        this.ok_btn.clickHandler = new Laya.Handler(this,this.npcHide);
        this.NPC.addChild(this.ok_btn);

        this.NPC.zOrder = 100;
        this.addChild(this.NPC);
    };

    proto.npcShow = function(content)
    {
        this.NPC.pos(-3,Laya.stage.height-this.NPC.height+20);
        this.NPCContent.innerHTML = content;
        this.NPC.visible = true;
        Laya.Tween.from(this.NPC,{x:Laya.stage.width},500,Laya.Ease.backIn);
    };

    proto.npcHide = function()
    {
        this.NPCContent.innerHTML = '';
        Laya.Tween.to(this.NPC,{x:Laya.stage.width},500,Laya.Ease.strongOut,Laya.Handler.create(this,function(){
            this.NPC.visible = false;
        }));
    };

    proto.popup = function()
    {
        NPCShow = true;
        this.visible = true;
    };

    proto.close = function()
    {
        this.hitArea.unHit.clear();
        this.viewRect.graphics.clear();
        this.tips.visible = false;
        NPCShow = false;
        this.visible = false;
    };

    proto.ZhiYinClose = function()
    {
        var step = this.zhiYinContent[this.zhiYinContent.length-1][0];
        if(step.npc){
            this.npcShow(step.npc);
            this.ok_btn.visible = true;
        }else {
            this.npcHide();
            this.ok_btn.visible = false;
        }
        this.changeHitArea(step);
    };

    ZhiYinMask.instance=function(){
        if (!ZhiYinMask._instance){
            ZhiYinMask._instance=new ZhiYinMask();
        }
        return ZhiYinMask._instance;
    };

    ZhiYinMask._instance = null;
})();