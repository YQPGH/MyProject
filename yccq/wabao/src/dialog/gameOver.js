var gameOver = (function (_super) {

    function gameOver(cont) {

        gameOver.super(this);
        this.cont = cont;
        this.zOrder = 10000;
        //按钮点击事件
        this.returnBtn.clickHandler = new Laya.Handler(this, this.onreturnBtnClick);
        this.quitBtn.clickHandler = new Laya.Handler(this, this.onquitBtnClick);
    }


    //注册类
    Laya.class(gameOver, "gameOver", _super);
    var _proto = gameOver.prototype;

    _proto.onreturnBtnClick = function () {
        //点击关闭
        this.close();
        removeAni();
        // console.log(pass);
        if (pass != 1) {
            spliceArray();
        }
        
        my_gameinfo.removeSelf();
        this.cont.nextDialog();
    }

    //退出游戏
    _proto.onquitBtnClick = function () {
        //点击关闭
        this.close();
        removeAni();
        spliceArray();
        my_gameinfo.removeSelf();
        MyGameStart();
    }


    return gameOver;
})(ui.gameOverUI);