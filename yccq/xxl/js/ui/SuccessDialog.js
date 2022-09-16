// 游戏结束弹窗
(function () {
    var Image = Laya.Image;
    var Label = Laya.Label;
    var Button = Laya.Button;
    var Sprite = Laya.Sprite;

    function SuccessDialog(data) {
        SuccessDialog.__super.call(this);
        this.zOrder = 1000;

        // 背景
        this.bg = new Image("res/dialog_ok.png");
        this.bg.size(600, 250);
        this.addChild(this.bg);

        // 再来一次
        this.closeBtn = new Button("com/restart.png");
        this.closeBtn.stateNum = 2;
        this.closeBtn.size(200, 60);
        this.closeBtn.pos(60, 420);
        this.addChild(this.closeBtn);
        this.closeBtn.on("click", this, this.startGame);

        // 返回主页
        this.backBtn = new Button("com/out.png");
        this.backBtn.stateNum = 2;
        this.backBtn.size(200, 60);
        this.backBtn.pos(310, 420);
        this.addChild(this.backBtn);
        this.backBtn.on("click", this, this.backHome);

        this.showPrize(data);
    }

    Laya.class(SuccessDialog, "SuccessDialog", Laya.Dialog);
    var proto = SuccessDialog.prototype;

    // 游戏结束显示弹窗
    proto.setContent = function (data) {
        this.content.text = content;
    };

    proto.startGame = function () {
        mIndex.mGameUI.restart();
        this.close();
    };

    // 返回主页
    proto.backHome = function () {
        mIndex.mGameUI = null;
        Laya.stage.removeChild(mIndex.mGameUI);
        Laya.stage.addChild(mIndex.mMainUI);
        this.close();
    };

    // 显示奖品视图
    proto.showPrize = function (data) {
        console.log(data);
        // 奖品框
        var prize_box2 = new Sprite();
        prize_box2.loadImage("com/prize_box.png");
        prize_box2.size(130, 130);
        prize_box2.pos(150, 265);
        this.addChild(prize_box2);
        // 银元
        var money = new Sprite();
        money.loadImage("prize/lebi.png", 0, 0, 80, 80);
        money.pos(30, 20);
        prize_box2.addChild(money);
        // 文字
        var title = new Label();
        title.text = '银元' + data.money;
        title.fontSize = 15;
        title.color = "#FFF"
        title.pos(40, 100);
        prize_box2.addChild(title);

        // 奖品框
        var prize_box = new Sprite();
        prize_box.loadImage("com/prize_box.png");
        prize_box.size(130, 130);
        prize_box.pos(300, 265);
        this.addChild(prize_box);
        // 种子
        var prize = new Sprite();
        if (data.shopid > 1000) {
            prize.loadImage("prize/quan_1.png", 0, 0, 80, 80);
        } else {
            prize.loadImage("prize/zhongzi_1_1.png", 0, 0, 80, 80);
        }

        prize.pos(30, 20);
        prize_box.addChild(prize);
        // 文字
        var title = new Label();
        title.text = data.shop_name;
        title.fontSize = 15;
        title.color = "#FFF"
        title.pos(15, 100);
        prize_box.addChild(title);


    };

    proto.box = function () {

    }

})();