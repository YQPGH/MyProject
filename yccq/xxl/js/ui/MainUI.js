// 首页
(function () {

    var Sprite = Laya.Sprite;
    var SoundManager = Laya.SoundManager;
    var music;
    
    function MainUI() {
        MainUI.__super.call(this);
        this.musicON = Config.musicON;
        this.showView();
        this.init();
        //console.log(123);
    }

    Laya.class(MainUI, "MainUI", Sprite);
    var proto = MainUI.prototype;

    // 加载视图
    proto.showView = function () {
        // 背景图片
        var bg = new Sprite();
        bg.loadImage("res/home.jpg", 0, 0, mIndex.stageWidth, mIndex.stageHeight);
        this.addChild(bg);

        // 开始游戏按钮
        this.gameBtn = new Laya.Image("com/start_btn.png");
        this.gameBtn.pos(mIndex.stageWidth / 2 - 100, mIndex.stageHeight / 2 - 10);
        this.gameBtn.on("click", this, startGame);
        this.addChild(this.gameBtn);
        console.log(this.gameBtn.height);

        // 游戏规则按钮
        var guize = new Laya.Image("com/guize_btn.png");
        guize.pos(this.gameBtn.x, this.gameBtn.y + 64 + 30);
        guize.on("click", this, showGuize);
        this.addChild(guize);

        // 返回按钮
        var back = new Laya.Image("com/back.png");
        back.pos(mIndex.stageWidth - 70, 10);
        back.size(60, 60);
        this.addChild(back);
        back.on("click", this, function () {
            window.location.href = "http://yccq.zlongwang.com/client/";
        });

        // 音乐开关
        // music = new Laya.Sprite();
        // music.loadImage("images/music1.png", 0, 0, 50, 50);
        // music.x = mIndex.stageWidth - 60;
        // this.addChild(music);
        // music.on("click", this, this.switchMusic);
        // if (this.musicON) SoundManager.playMusic("images/bg.mp3", 0);

        // 动画
        // var roleAni = new Laya.Animation();
        // roleAni.loadImage("com/dou1.png");
        // roleAni.y = 300;
        // this.addChild(roleAni);
        // roleAni.on("click", this, function () {
        //     roleAni.graphics.clear();
        //     roleAni.loadAtlas("res/bom.atlas");
        //     roleAni.play(0, false);
        // });
    };

    proto.switchMusic = function () {
        if (this.musicON) {
            music.loadImage("images/music2.png", 0, 0, 50, 50);
            this.musicON = 0;
            SoundManager.stopAll();
        } else {
            music.loadImage("images/music1.png", 0, 0, 50, 50);
            this.musicON = 1
            SoundManager.playMusic("images/bg.mp3", 0);
        }
    };

    // 开始
    proto.init = function () {

    };

    function startGame() {
        mIndex.mGameUI = mIndex.mGameUI || new GameUI();
        mIndex.mGameUI.restart();

        Laya.stage.addChild(mIndex.mGameUI);
        Laya.stage.removeChild(this);
    }

    function showGuize() {
        var dialog = new GuizeDialog();
        dialog.popup();
    }

})();