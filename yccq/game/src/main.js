var GameSize = {width:960,height:640};
var hasDialog = false;
var hasMove = false;
var mapMove = false;
var hasScale = false;
var Request = new UrlSearch();
var LoadingScene = null;
var StoryScene = null;
var AllowGuide = false;
var NPCShow = false;
(function()
{
    var Stage = Laya.Stage;
    var WebGL  = Laya.WebGL;
    var Canvas = Laya.WebGLCanvas;
    var Stat    = Laya.Stat;
    var Handler = Laya.Handler;


    //var MyGameScene = null;

  
    (function()
    {
        //初始化舞台，不支持WebGL时会自动切换至Canvas
        //iphoneX 2436*1125
        Laya.init(960, 640, WebGL);

        //垂直居中对齐，另一种写法：Laya.stage.alignV = Stage.ALIGN_MIDDLE
        Laya.stage.alignV = "middle";

        //水平居中对齐，另一种写法：Laya.stage.alignH = Stage.ALIGN_CENTER;
        Laya.stage.alignH = "center";
        var width = Math.max(Laya.Browser.width,Laya.Browser.height);

        if(width > 2100){
            Laya.stage.scaleMode = "showall";//适配模式(noscale,exactfit,showall,noborder,full,fixedwidth,fixedheight
        }else {
            Laya.stage.scaleMode = "exactfit";
        }

        //竖横屏设置
        Laya.stage.screenMode = "horizontal";//none,horizontal,vertical
        //Laya.stage.bgColor = "";//背景颜色
        //Laya.stage.loadImage("../laya/assets/stage_bg.jpg",0,0,Laya.stage.width,Laya.stage.height);
        Laya.stage.name = 'stage';
        //Stat.show(0, 0);

        UIConfig.closeDialogOnSide = false;//屏蔽点击Dialog之外的区域关闭弹框
        Laya.stage.width = 0;

        Laya.Font.defaultFamily = 'SimHei';

        //Laya.URL.basePath = "http://yccq.zlongwang.com/yccq/game/";
        Laya.URL.basePath = "http://gametest.gxziyun.com/yccq/game/";

        Laya.loader.load(['res/atlas/loading.atlas','ui/button_queding.png','ui/confirm_bg.png'], Handler.create(this, addLoadingScene));

        //addLoadingScene();
    })();

    function enterStory()
    {
        StoryScene = new Story();
        Laya.stage.addChild(StoryScene);
        //LoadingScene.timer.clear(LoadingScene,LoadingScene.ani);
        LoadingScene.destroy(true);
    }

    function enterMyGame()
    {

        var MyGameScene = new MyGame();
        //Laya.stage.addChild(MyGameScene);

    }

    function addLoadingScene()
    {
        console.log('EnterLoadingScene');
        Laya.stage.width = 960;
        var element = document.getElementsByTagName('body')[0];
        removeClass(element,'bg');
        addClass(element,'bg-color');

        var loadingTips = [
            '攻略里有高星级香烟生产攻略，想要获得更多礼品记得好好研究一下喔！',
            '每个模块界面上都有帮助按钮，点击就可以了解该模块具体功能喔！',
            '每日挑战通关奖励能帮我们生产更高等级香烟喔！',
            '真龙神秘商行每小时更新一次，快去碰碰运气吧！'
        ];

        LoadingScene = new loadingUI();
        LoadingScene.ani = function(){
            this.leaf.rotation += 5;
        };
        LoadingScene.tips.text = loadingTips[Math.floor(Math.random()*loadingTips.length)];
        LoadingScene.timerLoop(100,LoadingScene,LoadingScene.ani);
        Laya.stage.addChild(LoadingScene);
        if((localStorage.loadTips == 'false' || localStorage.loadTips == undefined) && !Utils.isWifi()){
            var dialog = new loadingTipsUI();
            dialog.cancel_btn.clickHandler = new Laya.Handler(this,function(){
                dialog.close();
            });
            dialog.continue_btn.clickHandler = new Laya.Handler(this,function(){
                if(dialog.BZTS_btn.selected) localStorage.loadTips = true;
                dialog.close();
                load();
            });
            dialog.popup();
        }else {
            load();
        }
    }

    function load()
    {
        Utils.post('guide/status',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                var enterScene = null;
                var resource = [
                    {url: "res/atlas/icon.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/mapbg.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/tex.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/ui.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/shop.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/userinfo.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/depot.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/agingroom.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/bakeroom.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/factory.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/dati.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/bozhong.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/peiyu.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/lubiantan.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/friend.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/sign.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/orderlist.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/pinjian.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/luckdraw.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/donghua.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/recharge.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/prize.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/guidebook.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/jiandie.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/youlechang.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/zhiyin.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/ranking.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/ani_chongzi.atlas", type: Laya.Loader.ATLAS},
                    {url: "res/atlas/draws.atlas", type: Laya.Loader.ATLAS}
                ];
                if(res.data.step1 >= 10){
                    enterScene = enterMyGame;
                }else {
                    enterScene = enterStory;
                    resource.push({url: "res/atlas/story.atlas", type: Laya.Loader.ATLAS});
                }
                Laya.loader.load(resource,Handler.create(this, enterScene), Handler.create(this, onLoading, null, false), Laya.Loader.TEXT);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    }

    function onLoading(progress)
    {
        //console.log("加载进度:"+progress);
        //LoadingScene.ProgressBar.value = Number(progress);
        LoadingScene.curr.text = (progress*100).toFixed(0)+'%';
        //LoadingScene.flower.x = LoadingScene.ProgressBar.width * progress-35;
    }

})();

//返回自家农场
function  BackHome() {
    console.log("返回我的农场");
    Laya.stage.getChildByName('FriendFarm').map.tiledMap.destroy();
    Laya.stage.getChildByName('FriendFarm').map.beforeMapDestroy();
    Laya.stage.getChildByName("FriendFarm").map.destroy();
    Laya.stage.getChildByName("FriendFarm").destroy();
    //Laya.stage.getChildByName("mapSprite").destroy();
    ChongziManager.destroy();

    Laya.stage.offAll();
    Dialog.manager.closeAll();

    var MyGameScene = new MyGame();
    //Laya.stage.addChild(MyGameScene);
}




