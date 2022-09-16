// 加载类，首屏和整站资源加载
(function () {
    var Event = Laya.Event;
    var Loader = Laya.Loader;
    var Handler = Laya.Handler;
    var Sprite = Laya.Sprite;
    var Label = Laya.Label;
    var ProgressBar = Laya.ProgressBar;

    var bg, loadLabel, progressBar, callback, woniu;

    // 通用对话窗口，基类
    function LoadUI(callback1) {
        LoadUI.__super.call(this);
        callback = callback1;
        //Laya.stage.width = 0; // 解决加载中黑屏问题

        // 第一页加载资源
        Laya.loader.load([
            "res/loading.jpg",
            "res/load.atlas"
        ], Handler.create(this, showView));
    }

    Laya.class(LoadUI, "LoadUI", Sprite);
    //var proto = LoadUI.prototype;

    function showView() {
        document.getElementById("topContainer").style.display = "none";

        bg = new Sprite();
        bg.loadImage("res/loading.jpg", 0, 0, mIndex.stageWidth, mIndex.stageHeight);
        this.addChild(bg);

        progressBar = new ProgressBar("load/progressBar.png");
        progressBar.width = 350;
        progressBar.x = (Laya.stage.width - progressBar.width) / 2;
        progressBar.y = Laya.stage.height / 2 + 50;
        progressBar.sizeGrid = "5,5,5,5";
        this.addChild(progressBar);

        woniu = new Sprite();
        woniu.loadImage("load/woniu1.png");
        woniu.pos(progressBar.x, progressBar.y + 11);
        this.addChild(woniu);

        loadLabel = new Label();
        //loadLable.font = "Microsoft YaHei";
        loadLabel.text = "游戏加载中";
        loadLabel.fontSize = 20;
        loadLabel.color = "#FFFFFF";
        loadLabel.pos((Laya.stage.width - loadLabel.width) / 2, Laya.stage.height / 2 + 20);
        //this.addChild(loadLabel);

        // 完整游戏资源
        loadAsset();
    }

    // 加载资源
    function loadAsset() {

        var assets = [
            'res/com.atlas',
            //'res/com_1.atlas',
            'res/bom.atlas',
            'res/bom2.atlas',
            'res/load.atlas',
            'res/super1.atlas',
            'res/super2.atlas',
            'res/super3.atlas',
            'res/scoreAni.atlas',
            'res/prize.atlas',

            'res/bg.jpg',
            'res/home.jpg',
            'res/dialog_no.png',
            'res/dialog_ok.png',
            'res/tankuang.png',
            'res/confirm.png',
            'res/quxiao.png',
        ];

        //assets.push([]);
        Laya.loader.load(assets, Handler.create(this, onAssetLoaded), Handler.create(this, onLoading, null, false));
        // 侦听加载失败
        Laya.loader.on(Event.ERROR, this, onError);
    }

    function onAssetLoaded(texture) {
        console.log("资源加载结束");
        Laya.timer.once(300, this, function () {
            mIndex.mMainUI = new MainUI();
            Laya.stage.removeChild(mIndex.mLoadUI);
            Laya.stage.addChild(mIndex.mMainUI);
            mIndex.mLoadUI = null;
        });
    }

    // 加载进度侦听器
    function onLoading(progress) {
        woniu.x = progressBar.x + parseInt(progress * 250);
        loadLabel.text = "游戏加载中:" + (progress * 100).toFixed(2) + "%";
        progressBar.value = progress;
        //console.log("加载进度: " + +(progress * 100) + "%");
    }

    function onError(err) {
        console.log("加载失败: " + err);
    }

})();