/**
 * Created by 41496 on 2017/10/11.
 */
(function(){
    function GuideView(){
        GuideView.__super.call(this);
        this.StepBtn = [this.BuySeed,this.Plant,this.Baking,this.Aging,this.ZhiYan,this.PinJian,this.ChouJiang,this.Order,this.yanjiusuo,this.peiyu,this.youleyuan];
        this.GuideContent = Content;
        this.currIndex = 0;
        this.Step = 0;
        this.currContentArr = [];
        this.btn_next.clickHandler = new Laya.Handler(this,this.onNextStepBtnClick);
        this.pos(0,Laya.stage.height);
        this.initBtn();
        //this.guideShow();
        this.initGuidePanel();
        this.onStepBtnClick(0);
        this.nav.hScrollBar.hide = true;
    }
    Laya.class(GuideView,'GuideView',guide_npc_dialogUI);
    var proto = GuideView.prototype;
    proto.initBtn = function()
    {
        this.show_guide_btn = new Laya.Button('ui/tuozhan_shou.png');
        this.show_guide_btn.stateNum = 2;
        this.show_guide_btn.toggle = true;
        this.show_guide_btn.rotation = -90;
        this.show_guide_btn.pos(Laya.stage.width/2,35);
        this.addChild(this.show_guide_btn);
        this.show_guide_btn.clickHandler = Laya.Handler.create(this,this.onShowGuideBtnCLick,null,false);
    };

    proto.onShowGuideBtnCLick = function()
    {
        if(this.show_guide_btn.selected)
        {
            this.guideShow();
        }else {
            this.guideHiden();
        }
    };

    proto.guideShow = function()
    {
        this.show_guide_btn.y = 40;
        this.show_guide_btn.selected = true;
        Laya.Tween.to(this,{y:Laya.stage.height-this.height},500);
    };

    proto.guideHiden = function()
    {
        this.show_guide_btn.selected = false;
        Laya.Tween.to(this,{y:Laya.stage.height},500,null,new Laya.Handler(this,this.onGuideHiden));
    };

    proto.onGuideHiden = function()
    {
        this.show_guide_btn.y = 35;
    };

    proto.initGuidePanel = function()
    {
        for(var i = 0; i < this.StepBtn.length; i++)
        {
            this.StepBtn[i].on(Laya.Event.CLICK,this,this.onStepBtnClick,[i]);
        }
    };

    proto.onStepBtnClick = function(index)
    {

        if(index > this.StepBtn.length-1)return;
        this.currIndex = index;
        this.Step = 0;
        for(var i = 0; i < this.StepBtn.length; i++)
        {
            this.StepBtn[i].gray = true;
        }
        this.StepBtn[index].gray = false;
        this.currContentArr = this.getContentByIndex(index);
        this.Content_text.text = this.currContentArr[0];
    };

    proto.getContentByIndex = function(index)
    {
        var arr = [];
        for(var i = 0; i < this.GuideContent.length; i++)
        {
            if(this.GuideContent[i].index == index)
            {
                for(var j = 0; j < this.GuideContent[i].text.length; j++)
                {
                    arr.push(this.GuideContent[i].text[j]);
                }

            }
        }
        return arr;
    };

    proto.onNextStepBtnClick = function()
    {

        if((this.currIndex == this.StepBtn.length-1) && (this.Step == this.currContentArr.length-1))return;
        if(this.Step == this.currContentArr.length-1)
        {
            this.Step = 0;
            this.onStepBtnClick(this.currIndex+1);
        }else {
            this.Step++;
            this.Content.text = this.currContentArr[this.Step];
        }
    };
    var Content = [
            {
                index:0,
                text:['要生产一包香烟，首先要拥有一本调香书，集齐调香书上记载的各种材料才能进行加工，调香书可以在真龙商行购买，高级的调香书需要在调香研究所合成。']
            },
            {
                index:1,
                text:['点击空土地后选择种子进行种植，等待一段时间后点击成熟作物即可收获烟叶。']
            },
            {
                index:2,
                text:['在烘烤房中选择需要烘烤的烟叶，调节烘烤温度与时间并烘烤后稍等一段时间即可收获烟叶·烤，也可选择自动烘烤，跳过温度与时间调节并立即完成烘烤。']
            },
            {
                index:3,
                text:['在醇化室选择需要醇化的烟叶·烤，点击醇化后稍等一段时间即可收获烟叶·醇。']
            },
            {
                index:4,
                text:['进入制烟坊，在租赁界面租赁一台机器，拥有任意机器使用权后进入生产界面，添加调香书后即可开始生产香烟，注意要集齐材料哦~等待一段时间后，香烟就制作好了。']
            },
            {
                index:5,
                text:['进入品鉴所，选择一包未品鉴的香烟后点击品鉴即可完成品鉴。只有品鉴后的香烟才能用于完成订单或升级香烟券哦！']
            },
            {
                index:6,
                text:['香烟经过品鉴后，可兑换积分或直接用于抽奖，抽奖可获得银元、种子等物品，还有机会获得香烟等实体奖励哦！']
            },
            {
                index:7,
                text:['进入订单栏，选择有绿色标记的订单后点击达成，通过完成订单可以将不需要的材料售出，可以赚取银元和经验值哦~']
            },
            {
                index:8,
                text:['调香研究所可以合成高星级的调香书，选择三本调香书进行合成，有几率获得更高星级的调香书。']
            },
            {
                index:9,
                text:['种子培育中心可以培育出高星级的种子，选择两片烟叶进行培育，有几率培育出更高星级的种子。']
            },
            {
                index:10,
                text:['可以进行多种有趣的小游戏，通关后有几率获得高级种子、调香书等奖励。']
            }
        ];
})();