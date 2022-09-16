// 游戏结束弹窗
(function () {
    var Image = Laya.Image;
    var Label = Laya.Label;
    var Button = Laya.Button;

    var DIALOG_WIDTH = 400;
    var DIALOG_HEIGHT = 400;

    function OverDialog(title, content) {
        OverDialog.__super.call(this);
        this.zOrder = 1000;

        // 设置拖动范围
        //this.dragArea = "0,0," + Browser.clientWidth + "," + Browser.clientHeight;

        // 背景
        this.bg = new Image("res/dialog_no.png");
        //this.bg.size(mIndex.stageWidth, mIndex.stageHeight);
        //this.bg.width = DIALOG_WIDTH;
        //this.bg.height = DIALOG_HEIGHT;
        //this.bg.sizeGrid = "31,5,5,5,1";//设置 bg 对象的网格信息。
        this.addChild(this.bg);

        // 再来一次
        this.closeBtn = new Button("com/restart.png");
        this.closeBtn.stateNum = 2;
        this.closeBtn.pos(200 , 350);
        this.addChild(this.closeBtn);
        this.closeBtn.on("click", this, this.startGame);

        // 返回主页
        this.backBtn = new Button("com/out.png");
        this.backBtn.stateNum = 2;
        //this.button.size(CLOSE_BTN_WIDTH, CLOSE_BTN_WIDTH);
        this.backBtn.pos(200, 450);
        this.addChild(this.backBtn);
        this.backBtn.on("click", this, this.backHome);

        // 标题文本
        this.title = new Label();
        this.title.text = title;
        this.title.fontSize = 25;
        this.title.pivot(this.title.width / 2, this.title.height / 2);
        this.title.pos(DIALOG_WIDTH / 2, 15);
        //this.addChild(this.title);

        // 内容文本
        this.content = new Label();
        this.content.text = content;
        this.content.fontSize = 25;
        this.content.pos(5, 35);
        //this.addChild(this.content);
    }

    Laya.class(OverDialog, "OverDialog", Laya.Dialog);
    var proto = OverDialog.prototype;

    // 游戏结束显示弹窗
    proto.setContent = function (content) {
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

})();