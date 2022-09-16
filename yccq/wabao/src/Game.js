    var bar;
    //检测碰撞状态
    var statu = false;
    var obj = null;

    var record = [];
    var sound = true;
    var my_scorelable = null;
    var my_levelLabel = null;
    var my_gameinfo = null;
    //积分成绩
    var score = 0;
    var temp_score = 0;

    //升级等级所需的成绩数量 
    var level = 200;
    var goldArr = [];
    //定义时间 
    var seconds = 60;
    //关数
    var pass = 1;
    //挑战券
    var play_times = 3;
    //物体坐标
    var position;
    var dialogVS;
    // Laya.Config.isAntialias=true;
    //初始化引擎,设置游戏宽高
    Laya.init(960, 640, Laya.WebGL);
    //缩放模式，显示全部内容
    Laya.stage.scaleMode = "exactfit";
    //垂直居中对齐
    Laya.stage.alignV = "middle";
    //水平居中
    Laya.stage.alignH = "center";
    //场景布局类型
    Laya.stage.screenMode = "horizontal";

    Laya.URL.basePath = ClientURL;
    // Laya.URL.version = {
    //         'res/atlas/progressbar.json':'1.0',
    
    //     };
    //加载图集资源 
    Laya.loader.load("res/atlas/progressbar.json", Laya.Handler.create(this, onLoaded), Laya.Handler.create(this, onLoading, null, false), Laya.Loader.ATLAS);
    
    //加载进度条
    function onLoaded() {
        bar = new Progress();
        Laya.stage.addChild(bar);
    }

    // 加载进度侦听器
    function onLoading(progress) {
        console.log("加载进度: " + progress);
    }

    //游戏开始页面
    function MyGameStart() {
        var gstart = new GameStart(this);
        Laya.stage.addChild(gstart);
    }

    //关卡页面
    function CustomsPass() {
        var guanqia = new NumPass(this);
        Laya.stage.addChild(guanqia);
    }


    function addStage() {
        Laya.Animation.createFrames(['maoyan/maoyan_1.png', 'maoyan/maoyan_2.png', 'maoyan/maoyan_3.png', 
                                     'maoyan/maoyan_4.png', 'maoyan/maoyan_5.png'], "yan");
                                     
        //创建游戏背景
        this.bg = new BackGround();
        //把背景添加到舞台上显示出来
        Laya.stage.addChild(this.bg);

        removeAni();
        //创建游戏UI界面  人物
        this.goldman = new Goldman();
        this.goldman.visible = false;        
        //添加到舞台上
        Laya.stage.addChild(this.goldman);

        //创建游戏UI界面 信息
        this.gameinfo = new GameInfo(this.goldman);
        //添加到舞台上
        Laya.stage.addChild(this.gameinfo);

        my_gameinfo = this.gameinfo;
    }

    function spliceArray() {
        
        //循环清空舞台上的精灵          
        for (var k = 0; k < goldArr.length; k++) {
            goldArr[k].removeSelf();
        }
        //清空数组的元素
        goldArr.splice(0, goldArr.length);
    }


    //根据等级获取不同数组
    function mineral() {
// console.log(2222);
        for (var i = 0; i < arr[pass].length; i++) {

            for (var j = 0; j < arr[pass][i].num; j++) {

                var sprite = new Laya.Sprite();
                sprite.type = arr[pass][i].type;
                sprite.score = arr[pass][i].score;
                sprite.speed = arr[pass][i].speed;
               
                sprite.loadImage("gold/" + arr[pass][i].img);

                sprite.pivot(sprite.width / 2, sprite.height / 2);
                goldArr.push(sprite);
                sprite.zOrder = 1;
                // sprite.graphics.drawCircle(sprite.width/2,sprite.height/2,30,null,"#ff0000");
                Laya.stage.addChild(sprite);
            }

        }

        //关卡等级坐标 
        Position();
        //创建帧循环检测碰撞
        Laya.timer.frameLoop(1, this, collison);
    }


    function collison() {

        var get = this.goldman.hook.getChildAt(0);
        //   console.log(get.x,get.y);
        //转换全局坐标
        var pos1 = this.goldman.hook.localToGlobal(new Laya.Point(get.x, get.y));

        //检测碰撞     
        if (!statu && !position) {
            for (var i = 0; i < goldArr.length; i++) {
                if (ABDistance([goldArr[i].x, goldArr[i].y], [pos1.x, pos1.y]) < 60) {
                    statu = true;
                    obj = goldArr[i];
                    break;
                    //   console.log(obj);               
                }
            }
        } else {
            // console.log(obj); 
            if (obj) {
                obj.pos(pos1.x, pos1.y);
            }
        }
    }


    //计算两点间距离
    function ABDistance(A, B) {
        var len;
        len = Math.pow((B[0] - A[0]), 2) + Math.pow((B[1] - A[1]), 2);
        len = Math.floor(Math.sqrt(len));
        // console.log(len);
        return len;
    }

    Array.prototype.remove = function ( /**Number*/ from, /**Number*/ to) {
        var rest = this.slice((to || from) + 1 || this.length);
        this.length = from < 0 ? this.length + from : from;
        return this.push.apply(this, rest);
    };

    //移除数组中指定元素对象
    Array.prototype.removeObject = function (object) {
        for (var i = 0; i < this.length; ++i) {
            if (this[i] === object) {
                this.remove(i);
                break;
            }
        }

    }

  function removeAni(){
      var car =  Laya.stage.getChildByName('CarMove');
      var man = Laya.stage.getChildByName('man');
      if (car) {
          console.log(1);
          car.removeSelf();
      }
       if (man) {
           console.log(2);
          man.removeSelf();
      }
  }  
