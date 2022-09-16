/**
 * Created by 41496 on 2018/1/19.
 */
(function(){
    function Story()
    {
        Story.__super.call(this);
        this.currPage = 0;

        this.onMouseDownX = 0;
        this.onMouseDownY = 0;
        this.isMove = false;

        this.init();
    }
    Laya.class(Story,'Story',StoryUI);
    var proto = Story.prototype;

    proto.init = function()
    {
        //this.next_btn.clickHandler = new Laya.Handler(this,this.nextBtnClick);
        //this.pre_btn.clickHandler = new Laya.Handler(this,this.preBtnClick);
        this.jump_btn.clickHandler = new Laya.Handler(this,this.jumpBtnClick);
        this.chuanyue_btn.clickHandler = new Laya.Handler(this,this.aniPlay);

        this.jump_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnMouseDown,[this.jump_btn]);
        this.jump_btn.on(Laya.Event.MOUSE_UP,this,this.onBtnMouseUp,[this.jump_btn]);

        this.chuanyue_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnMouseDown,[this.chuanyue_btn]);
        this.chuanyue_btn.on(Laya.Event.MOUSE_UP,this,this.onBtnMouseUp,[this.chuanyue_btn]);

        //Laya.Tween.from(this.che,{x:400},10000);
        //Laya.Tween.from(this.chuan,{x:960},2000);
        this.timer.loop(10000,this,this.nextPage);
        this.chuan.play(0,false);
        this.timeline = new Laya.TimeLine();
        this.timeline.addLabel("finger",0).to(this.finger,{x:46},1000,null,0);
        this.timeline.play(0,true);

        this.on(Laya.Event.MOUSE_DOWN,this,this.onMouseDown);
        this.on(Laya.Event.MOUSE_UP,this,this.onMouseUp);

    };

    proto.onMouseDown = function(e)
    {
        this.on(Laya.Event.MOUSE_MOVE,this,this.onMouseMove);
        this.onMouseDownX = e.target.mouseX;
        this.onMouseDownY = e.target.mouseY;
    };

    proto.onMouseUp = function(e)
    {
        this.off(Laya.Event.MOUSE_MOVE ,this, this.onMouseMove);

        if(this.isMove)
        {
            var moveLen = this.onMouseDownX - e.target.mouseX;
            if( moveLen >50)
            {
                this.nextPage();
            }else if (moveLen < -50)
            {
                this.prePage();
            }

        }
        this.onMouseDownX = 0;
        this.onMouseDownY = 0;
        this.isMove = false;
    };

    proto.onMouseMove = function()
    {
        this.isMove = true ;
    };

    proto.onBtnMouseDown = function(obj)
    {
        Laya.Tween.to(obj,{scaleX:1.2,scaleY:1.2},100);
    };

    proto.onBtnMouseUp = function(obj)
    {
        Laya.Tween.to(obj,{scaleX:1,scaleY:1},100);
    };

    proto.nextBtnClick = function()
    {
        this.nextPage();
    };

    proto.preBtnClick = function()
    {
        this.prePage();
    };

    proto.jumpBtnClick = function()
    {
        this.jumpToGameScene();
    };

    proto.nextPage = function()
    {
        if(this.currPage == 3) return;

        var curr_item = this['item'+this.currPage];
        var next_item = this['item'+(this.currPage+1)];
        this.currPage++;
        if(this.currPage == 3) {
            this.finger.visible = false;
            this.jiantou.visible = false;
            this.jiantou1.visible = false;
        }
        next_item.x = Laya.stage.width;
        next_item.visible = true;
        Laya.Tween.to(curr_item,{x:-curr_item.width},300,null,Laya.Handler.create(this,function(){
            curr_item.visible = false;
        }));
        Laya.Tween.to(next_item,{x:0},300,null);
        this.timer.loop(10000,this,this.nextPage);
    };

    proto.prePage = function()
    {
        if(this.currPage == 0) return;
        this.finger.visible = true;
        this.jiantou.visible = true;
        this.jiantou1.visible = true;
        var curr_item = this['item'+this.currPage];
        var pre_item = this['item'+(this.currPage-1)];
        this.currPage--;
        pre_item.x = -pre_item.width;
        pre_item.visible = true;
        Laya.Tween.to(curr_item,{x:curr_item.width},300,null,Laya.Handler.create(this,function(){
            curr_item.visible = false;
        }));
        Laya.Tween.to(pre_item,{x:0},300,null);
        this.timer.loop(10000,this,this.nextPage);
    };

    proto.aniPlay = function()
    {
        this.chuanyue_ani.visible = true;
        this.chuanyue_ani.play(0,false);
        this.chuanyue_ani.on(Laya.Event.COMPLETE,this,this.jumpToGameScene);
    };

    proto.jumpToGameScene = function(){
        this.timeline.destroy();
        var MyGameScene = new MyGame();
        //Laya.stage.addChild(MyGameScene);
    }
})();