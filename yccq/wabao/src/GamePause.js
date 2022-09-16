// var GamePause = (function (_super) {

//     function GamePause(obj) {

//         GamePause.super(this);
//         this.obj = obj;

//         //按钮点击事件
//         this.resumeBtn.clickHandler = new Laya.Handler(this, this.onresumeBtnClick);

//     }


//     //注册类
//     Laya.class(GamePause, "GamePause", _super);
//     var _proto = GamePause.prototype;

//     _proto.onresumeBtnClick = function () {

//         //恢复游戏
//         this.obj.gameResume();
//         //关闭弹框
//         this.close();

//         //this.obj.hookStatus();

//     }


//     return GamePause;
// })(ui.GamePauseUI);