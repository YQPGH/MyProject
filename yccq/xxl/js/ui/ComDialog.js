// 通用对话窗口，基类
(function () {
    var Image = Laya.Image;
    var Label = Laya.Label;

    var DIALOG_WIDTH = 400;
    var DIALOG_HEIGHT = 300;

    // 通用对话窗口，基类
    function ComDialog(title,content) {
        ComDialog.__super.call(this);

        // 背景
        this.bg = new Image("res/tankuang.png");

        this.bg.width = DIALOG_WIDTH;
        this.bg.height = DIALOG_HEIGHT;
        this.bg.sizeGrid = "30,30,30,30,0";//设置 bg 对象的网格信息。
        this.addChild(this.bg);

        // 关闭按钮
        this.button = new Image("res/confirm.png");

        this.button.pos(120 , 200);
        this.button.scaleX = 0.7;
        this.button.scaleY = 0.7;
        //this.button.pos(this.bg.width - 60, 10);
        this.addChild(this.button);
        this.button.on("click", this, this.closeFN);

        // 标题文本
        this.title = new Label();
        this.title.text = title;
        this.title.fontSize = 25;
        this.title.color = "#ee6306";
        this.title.pos(40, 80);
        this.addChild(this.title);

        // 内容文本
        this.content = new Label();
        this.content.text = content;
        this.content.wordWrap = true;
        this.content.width = 200;
        this.content.height = 120;
        this.content.fontSize = 25;
        this.content.leading = 5;
        this.content.color = "#ee6306";
        this.content.pos(100, 120);
        this.addChild(this.content);
    }

    Laya.class(ComDialog, "ComDialog", Laya.Dialog);
    var proto = ComDialog.prototype;

    proto.closeFN = function () {
        this.close();
        Laya.stage.removeChild(mIndex.mGameUI);
       Laya.stage.addChild(mIndex.mMainUI);
    };


})();