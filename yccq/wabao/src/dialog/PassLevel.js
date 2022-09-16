var PassLevel = (function (_super) {

    function PassLevel() {
        
        PassLevel.super(this);

        this.zOrder = 1000;
        this.nextLevel();
        //按钮点击事件
        this.nextBtn.clickHandler = new Laya.Handler(this, this.onnextBtnClick);
        this.QuitBtn.clickHandler = new Laya.Handler(this, this.onquitBtnClick);
    }


    //注册类
    Laya.class(PassLevel, "PassLevel", _super);
    var _proto = PassLevel.prototype;

    _proto.nextLevel = function () {

        this.LevelLabel.text = "当前关数为：" + pass;
        this.scoreLabel.text = "过关时间：" + "60秒";
        this.passLabel.text = "过关银元：" + level;
    }

    //开始下一关
    _proto.onnextBtnClick = function () {

        //点击关闭
        this.close();
        
        Laya.stage.getChildByName('CarMove').visible = false;
        Laya.stage.getChildByName('man').visible = true;
        // console.log(this.stage.getChildByName('CarMove'));
        // console.log(this.stage.getChildByName('man'));
        //下一关重新计时
        seconds = 60;
        score = 0;
        temp_score = 0;
        my_scorelable.changeText(score); //重置分数0
        my_levelLabel.changeText(level); //重置过关所需分数
        // this.variable.gameResume();
        my_gameinfo.gameResume();
    }

    //返回游戏开始页面
    _proto.backgame = function () {
        my_gameinfo.clickStart();

    }

    //退出游戏
    _proto.onquitBtnClick = function () {
        //点击关闭
        this.close();
        spliceArray();
        my_gameinfo.removeSelf();
        MyGameStart();
       
    }




    return PassLevel;
})(ui.PassLevelUI);