
// 游戏类
(function () {
    var Sprite = Laya.Sprite;
    var mXiao, list;
    var id1 = 0, id2 = 0;
    var icon1;
    var view;
    var spWidth;
    var icon1X, icon1Y;
    var stopdrag = 1;  // 禁止拖动图标
    var superID = 0;
    var that;
    var dialogShowing = 0;

    // 通用对话窗口，基类
    function GameUI() {
        GameUI.__super.call(this);
        that = this;
        view = new GameView();
        this.addChild(view);

        this.code;  // 游戏记录随机码

        mXiao = new Xiao();
        list = mXiao.list;

        view.iconView.on(Laya.Event.MOUSE_UP, this, change);
    }

    Laya.class(GameUI, "GameUI", Sprite);
    var proto = GameUI.prototype;

    // 开始
    proto.restart = function () {
        // 先发送记录到后台
        Server.startGame(mIndex.uid, this, function (data) {
            //console.log(data);
           //剩余次数
            this.play_times =  data.my_play.play_times;
            this.code = data.code;
            if(this.play_times > 0){
                this.resetGame();
            }else{
              var dialog = new ConfirmDialog();
              dialog.popup();

           }

        });

    };

    proto.resetGame = function(){

        stopdrag = 0;
        // 分数UI重置
        view.restart();

        // 图案重置
        list = mXiao.randListNoSame();
        refresh();

        // 开始计时
        Laya.timer.loop(1000, this, this.timeUpdate);
    }


    // 刷新显示图案
    function refresh() {
        for (var key in list) {
            var sp = view.iconView.getChildByName(key);
            //console.log(sp);
            sp.graphics.clear();
            sp.clear();
            sp.filters = [];
            sp.offAll();

            if (list[key]) {
                if (mXiao.images.contains(list[key])) {
                    sp.loadImage(list[key], 0, 0, view.iconWidth, view.iconWidth);
                    sp.on(Laya.Event.MOUSE_DOWN, this, drag);

                } else if (list[key] == mXiao.superRowImg || list[key] == mXiao.superColImg || list[key] == mXiao.superAllImg) {
                    // 超级图标 点击
                    sp.loadAtlas(list[key]);
                    sp.interval = 200;
                    sp.play(0);
                    sp.once("click", this, superClick);
                }
            }
        }
    }

    // 拖动图案
    function drag(e) {
        icon1 = e.target;
        id1 = e.target.name;
        icon1.zOrder = 100;
        var temp = view.iconPos[id1];
        //console.log(view);
        var dragRegion = new Laya.Rectangle(temp.x, temp.y, spWidth, spWidth);

        icon1.startDrag(dragRegion, false, 80);
        //获取坐标
        icon1X = icon1.x;
        icon1Y = icon1.y;
        //console.log(icon1X,icon1Y);
    }

    // 两个格子对换图片
    function change(e) {
        if (stopdrag == 1) return;

        id2 = getID2();
        console.log(id1, id2);

        // 格子对换图片
        if (id1 && id2) {
            if (mXiao.change(id1, id2)) {
                stopdrag = 1;
                refresh();
                Laya.timer.once(200, this, function () {
                    bombRow(); // 消除开始
                });
            }
        }

        id1 = id2 = 0;
    }

    // 通过图标1移向位置获取图标2， 上下左右追溯
    function getID2() {
        var absX = icon1X - icon1.x;
        var absY = icon1Y - icon1.y;
        //console.log(absX,absY);
        //判断距离
        if (absX > 30)  return id1 - 1;
        if (absX < -30) return parseInt(id1) + 1;

        if (absY > 30)  return id1 - 10;
        if (absY < -30) return parseInt(id1) + 10;
    }

    // 1消除, 判断消除-》动画-》消除-》下移-》填充--》回到判断
    function bombRow(tempList) {
        //console.log(id1, id2);
        var bomList;
        if (tempList) {
            bomList = tempList;
        } else {
            bomList = mXiao.getSameIds(list);
        }
        console.log(bomList);

        if (bomList.length > 0) {
            updateScore(bomList);
            view.iconFilter(bomList); // 发光
            // 消除
            Laya.timer.once(400, this, function () {
                for (var i = 0; i < bomList.length; i++) {
                    var id = bomList[i];
                    view.iconView.getChildByName(id).graphics.clear();
                    list[id] = "";
                }
                // 四消以上，生成超级图标
                if (bomList.length > 3 && !tempList) {
                    addSuperIcon(bomList);
                }

                // 音效
                if (mIndex.mMainUI.musicON) Laya.SoundManager.playSound("images/btn.mp3", 1);

                refresh();
                Laya.timer.once(200, this, function () {

                    mXiao.down(); // 下移
                    refresh();
                    Laya.timer.once(200, this, function () {
                        mXiao.update(); // 更新
                        refresh();
                        bombRow();
                    });

                });
            });

        } else {
            stopdrag = 0;
            if (view.step <= 1) {
                that.stopGame();
            }
            // 步数更新
            view.stepUpdate();
            refresh();
        }
    }

    // 更新
    proto.timeUpdate = function () {

        if (view.time == 0 && stopdrag == 0) {
            this.stopGame();
        }
        view.timeUpdate();
    };

    // 结束游戏
    proto.stopGame = function () {
        Laya.timer.clear(this, this.timeUpdate);

        // 先发送记录到后台
        var post = {
            code: this.code,
            uid: mIndex.uid,
            step: view.step,
            score: view.score,
            time: view.time
        };
        Server.stopGame(post, this, function (data) {
            //console.log(data);
            if (data && data.money) {
                var mSuccessDialog = new SuccessDialog(data);
                mSuccessDialog.popup();
            } else {
                var mOverDialog = new OverDialog();
                mOverDialog.popup();
            }
        });
    }

    // 结束游戏
    proto.clearTimer = function () {
        Laya.timer.clear(this, this.timeUpdate);
    }

    // 更新分数
    function updateScore(bomList) {
        var s = bomList.length; // 更新分数
        var score = Config.score[s];
        view.scoreUpdate(score); // 更新分数
    }

    // 3更新
    // function update() {
    //     mXiao.update();
    //     refresh();
    //     Laya.timer.once(200, this, function () {
    //         bombRow();
    //     });
    // }

    // 生成超级图标
    function addSuperIcon(bomList) {
        superID = bomList[0];
        console.log(parseInt(bomList[0]) + 1);
        console.log(bomList);
        if (parseInt(bomList[0]) + 1 == parseInt(bomList[1])) {
            list[superID] = mXiao.superRowImg;
        } else {
            list[superID] = mXiao.superColImg;
        }

        // 五消以上，生成超级图标
        if (bomList.length >= 5) {
            list[superID] = mXiao.superAllImg;
        }
    }

    // 点击超级图标，消除所在行和列
    function superClick(e) {
        console.log("superClick" + e.target.name);
        if (stopdrag) return;
        var bomList = mXiao.superList(e.target.name);
        bombRow(bomList);
    }

    // 错误信息
    proto.showError = function (msg) {
        console.log(msg);
        this.mComDialog = new ComDialog("提示", msg);
        // this.mComDialog.onClosed = function () {
            // Laya.stage.removeChild(mIndex.mGameUI);
            // Laya.stage.addChild(mIndex.mMainUI);
        // };
        this.mComDialog.popup();
    };

})();