// 程序入口  2017-08-16 tangjian
(function () {

    function GameIndex() {
        this.uid = uid
        this.stageWidth = Config.stageWidth;
        //this.stageHeight = (Laya.Browser.height / Laya.Browser.width) * this.stageWidth;
        if(Laya.Browser.width < Laya.Browser.height) {
            this.stageHeight = (Laya.Browser.width / Laya.Browser.height) * this.stageWidth;
        } else {
            this.stageHeight = (Laya.Browser.height / Laya.Browser.width) * this.stageWidth;
        }
        this.mLoadUI;
        this.mMainUI;
        this.mGameUI;
        this.mOverDialog;

        console.log(this.stageWidth, this.stageHeight);
        Laya.init(this.stageWidth, this.stageHeight, Laya.WebGL);
        Laya.stage.scaleMode = "fixedheight"; // fixedwidth fixedheight
        Laya.stage.screenMode = "horizontal"; // horizontal vertical
        Laya.stage.bgColor = "#232628";
        //Laya.stage.frameRate = "slow";

        //Laya.Stat.show();
        Laya.URL.basePath = Config.ClientURL;
        //Laya.URL.version = {
        //    'res/atlas/peiyu.atlas':'1.0',
        //    'res/atlas/peiyu.png':'1.0',
        //    'res/atlas/jiandie.atlas':'1.0',
        //    'res/atlas/jiandie.png':'1.0',
        //};
        this.mLoadUI = new LoadUI(); // 加载页
        Laya.stage.addChild(this.mLoadUI);
    }
    var proto = GameIndex.prototype;

    // 错误信息
    proto.showError = function () {
        this.mComDialog = new ComDialog("提示", "服务器繁忙，请稍后再试");
        this.mComDialog.popup();
    };

    window.mIndex = new GameIndex();
    //console.log(window.mIndex);
})();


