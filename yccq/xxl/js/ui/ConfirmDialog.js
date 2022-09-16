// 消耗乐豆弹窗
(function () {
    var Image = Laya.Image;
    var Label = Laya.Label;
    var Button = Laya.Button;

    var DIALOG_WIDTH = 440;
    var DIALOG_HEIGHT = 302;

    function ConfirmDialog() {
        ConfirmDialog.__super.call(this);
        this.zOrder = 1000;
        this.code;
        // 背景
        this.bg = new Image("res/tankuang.png");
        this.addChild(this.bg);


        //确认
        this.confirmBtn = new Button("res/confirm.png");
        this.confirmBtn.stateNum = 1;
        this.confirmBtn.pos(230 , 205);
        this.confirmBtn.scaleX = 0.7;
        this.confirmBtn.scaleY = 0.7;
        this.addChild(this.confirmBtn);
        this.confirmBtn.on("click", this, this.confirmGame);

        // 取消
        this.backBtn = new Button("res/quxiao.png");
        this.backBtn.stateNum = 1;  
        this.backBtn.pos(40, 205);
        this.backBtn.scaleX = 0.7;
        this.backBtn.scaleY = 0.7;
        this.addChild(this.backBtn);
        //关闭弹框，回到游戏开始页
        this.backBtn.on("click", this, function(){
            this.close();
            Laya.stage.removeChild(mIndex.mGameUI);
            Laya.stage.addChild(mIndex.mMainUI);
        });


        // 内容文本
        this.content = new Label();
        this.content.text = '需要消耗2个乐豆，是否继续游戏？';
        this.content.wordWrap = true;
        this.content.color = '#ee6306';
        this.content.leading = 10;
        this.content.width = 280;
        this.content.height = 120;
        this.content.fontSize = 30;

        this.content.pos(100, 80);
        this.addChild(this.content);
    }

    Laya.class(ConfirmDialog, "ConfirmDialog", Laya.Dialog);
    var proto = ConfirmDialog.prototype;


    proto.confirmGame = function () {

        Server.updateBeans(mIndex.uid, this, function (data) {
                 //console.log(data);
               this.code = data.data.code;
                if (data.code>0) {

                    this.close();
                    var dialog = new ComDialog('提示',data.msg);
                    dialog.popup();
                    
                }else{

                    this.close();
                    mIndex.mGameUI.resetGame();  
                }
                        
        })

    };



})();