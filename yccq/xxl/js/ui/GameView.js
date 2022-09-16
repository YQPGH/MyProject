// 游戏页 UI
(function () {
    var Text = Laya.Text;
    var Sprite = Laya.Sprite;
    var Image = Laya.Image;
    var Animation = Laya.Animation;
    var stepText, scoreText, timeText;

    function GameView() {
        GameView.__super.call(this);


        this.step = 0;
        this.score = 0;
        this.time = 0;
        this.iconPos = [];

        var bg = new Sprite();
        bg.loadImage("res/bg.jpg", 0, 0, mIndex.stageWidth, mIndex.stageHeight);
        this.addChild(bg);

        // 左边栏
        var menu = new Sprite();
        menu.loadImage("com/menu.png", 0, 0, 250, 550);

        menu.x = 10;
        this.addChild(menu);

        // 分数
        scoreText = new Text();
        scoreText.color = "#FF5722";
        scoreText.bold = true;
        scoreText.pos(80, 120);
        scoreText.fontSize = 40;
        menu.addChild(scoreText);

        // 步数
        stepText = new Text();
        stepText.color = "#FF5722";
        stepText.fontSize = 40;
        stepText.bold = true;
        stepText.pos(80, 265);
        menu.addChild(stepText);

        // 时间
        timeText = new Text();
        timeText.color = "#FF5722";
        timeText.fontSize = 30;
        timeText.pos(97, 482);
        menu.addChild(timeText);

        // 添加图标列表
        this.addIcon();

        // 右边栏
        this.rightView();

        this.restart();
    }

    Laya.class(GameView, "GameView", Sprite);
    var proto = GameView.prototype;

    // 重置
    proto.restart = function () {
        this.step = Config.step;
        this.score = 0;
        this.time = Config.time;
        stepText.text = this.step;
        scoreText.text = this.score;
        timeText.text = this.time;
    };

    // 更新步数
    proto.stepUpdate = function () {
        this.step -= 1;
        stepText.text = this.step;
    };

    // 更新分数
    proto.scoreUpdate = function (score) {
        this.score += score;
        scoreText.text = this.score;
        this.scoreAn(score);
    };

    // 更新
    proto.timeUpdate = function () {
        this.time -= 1;
        if(this.time <= -1) this.time = 0;
        timeText.text = this.time;
    };

    // 刷新显示图案
    proto.addIcon = function () {
        this.iconView = new Sprite();
        //console.log(this.iconView);
        var width = mIndex.stageHeight - 20;
        this.iconView.loadImage("com/game_bg.png", 0, 0, width, width);
        this.iconView.size(width, width);
        this.iconView.pos(300, 10);
        this.addChild(this.iconView);

        // 加载到屏幕
        var splitWidth = 2; // 间距
        this.iconWidth = ((mIndex.stageHeight - 20) / 7) - splitWidth - 4;

        var mXiao = new Xiao();
        var list = mXiao.list;

        var i = 0, n = 0;
        for (var key in list) {
            var sp = new Animation();
            sp.name = key;
            sp.loadImage("com/dou1.png", 0, 0, this.iconWidth, this.iconWidth);

            var x = this.iconWidth * n + (n * splitWidth) + 10;
            var y = this.iconWidth * i + i * splitWidth + 10
            sp.pos(x, y);
            this.iconPos[key] = {x: x, y: y};

            sp.size(this.iconWidth, this.iconWidth);
            this.iconView.addChild(sp);
            n++;
            //如果每行满7个，就换下一行
            if (n == 7) {
               //每行从第一列开始
                n = 0;
                i++;

            }
        }
    }

    // 根据图标编号获取位置
    proto.getPos = function (iconName) {

    };

    // 图案发光滤镜 动画 消除行/列动画
    proto.iconFilter = function (bomList) {
        for (var i = 0; i < bomList.length; i++) {
            var id = bomList[i];
            var sp = this.iconView.getChildByName("" + id);
            //console.log(sp);
            sp.graphics.clear();
            sp.clear();
            if (bomList.length > 6)
                sp.loadAtlas("res/bom2.atlas");
            else
                sp.loadAtlas("res/bom.atlas");
            sp.play(0, false);
            //创建一个发光滤镜
            var glowFilter = new Laya.GlowFilter("#ffff00", 10, 0, 0);
            //设置滤镜集合为发光滤镜
            sp.filters = [glowFilter];
        }
    }

    // 得分动画
    proto.scoreAn = function (score) {
        var ani = new Animation();
        ani.size(60, 60);
        ani.loadAtlas("res/scoreAni.atlas");
        ani.pos(mIndex.stageWidth / 2, mIndex.stageHeight / 2);
        ani.play(0, false);
        this.addChild(ani);

        var scoreTemp = new Text();
        scoreTemp.color = "#FF5722";
        scoreTemp.fontSize = 45;
        scoreTemp.stroke = 10;
        scoreTemp.strokeColor = "#FFFFFF";
        scoreTemp.text = score;
        scoreTemp.pos(50, 30);
        ani.addChild(scoreTemp);

        Laya.Tween.to(ani, {x: 100, y: 100}, 1000, Laya.Ease.backIn, Laya.Handler.create(this, function () {
            this.removeChild(ani);
        }));
    };

    // 右边视图
    proto.rightView = function () {
        var back = new Image("com/back.png");
        back.pos(mIndex.stageWidth - 70, 10);
        back.size(60, 60);
        this.addChild(back);
        back.on("click", this, function () {
            mIndex.mGameUI.clearTimer();
            mIndex.mGameUI = null;
            Laya.stage.removeChild(mIndex.mGameUI);
            Laya.stage.addChild(mIndex.mMainUI);
        });

        var music = new Image("com/music1.png");
        music.pos(mIndex.stageWidth - 70, 80);
        music.size(60, 60);
        this.addChild(music);

        var help = new Image("com/help.png");
        help.pos(mIndex.stageWidth - 70, 150);
        help.size(60, 60);
        this.addChild(help);
        help.on("click", this, function showGuize() {
            var dialog = new GuizeDialog();
            dialog.popup();
        });
    };

})();