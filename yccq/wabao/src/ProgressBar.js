var Progress = (function (_super) {
    function Progress() {

        Progress.super(this);

        // 无加载失败重试
        Laya.loader.retryNum = 0;

        this.loadBar();

        Laya.timer.frameLoop(5, this, this.onLoop);

        Laya.Animation.createFrames(['progressbar/bird1.png', 'progressbar/bird2.png'], "bird");
        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0, true, 'bird');
        this.body.pos(400, 300);
        this.addChild(this.body);

        Laya.loader.load(
            [
                "res/atlas/hunt.json",
                "res/atlas/role.json",
                "res/atlas/button.json",
                "res/atlas/guanqia.json",
                "res/atlas/gold.json",
                "res/atlas/icon.json",
                "res/atlas/ani.json",
                "res/atlas/maoyan.json",
                "res/atlas/stars.json",
                "res/atlas/sign.json",
                "res/atlas/button1.json"
            ], Laya.Handler.create(this, this.onLoaded), Laya.Handler.create(this, this.changeValue, null, false), Laya.Loader.ATLAS);


        // 侦听加载失败
        Laya.loader.on(Laya.Event.ERROR, this, this.onError);

    }

    Laya.class(Progress, "Progress", _super);
    var _proto = Progress.prototype;

    //云移动
    _proto.onLoop = function () {
        //每帧向右移动一像素
        this.cloud1.x += 1;
        this.cloud2.x += 1;

        if (this.cloud1.x + this.x >= 960) {
            this.cloud1.x -= 960 * 2;
        }

        if (this.cloud2.x + this.x >= 960) {
            this.cloud2.x += 960 * 2;
        }
    }

    _proto.onLoaded = function () {

        MyGameStart();


    }

    //加载进度条
    _proto.loadBar = function () {

        this.proBar.changeHandler = new Laya.Handler(this, this.onChange);
    }

    _proto.changeValue = function (p) {

        this.proBar.value = p;
    }

    _proto.onChange = function (value) {

        var pro = Math.floor(value * 100) + "%";
        this.proLabel.text = pro;

    }


    _proto.onError = function (err) {
        console.log("加载失败: " + err);
    }

    return Progress;
})(ui.ProgressBarUI);