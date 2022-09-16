/*
 规则说明UI
 */
(function () {
    var Image = Laya.Image;

    var Button = Laya.Button;


    // 通用对话窗口，基类
    function GuizeDialog() {
        GuizeDialog.__super.call(this);

        // 背景
        this.bg = new Image("res/guize.png");
        this.bg.size(mIndex.stageHeight-30, mIndex.stageHeight-30);
        this.addChild(this.bg);

        // 关闭按钮
        this.button = new Image("com/close.png");
        //this.button.size(50, 50);
        this.button.pos(this.bg.width - 100, 20);
        this.addChild(this.button);
        this.button.on("click",this, function () {
            this.close();
        });
    }

    Laya.class(GuizeDialog, "GuizeDialog", Laya.Dialog);
    var proto = GuizeDialog.prototype;


})();