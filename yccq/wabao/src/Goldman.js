//UI文件
var Goldman = (function (_super) {

    function Goldman() {

        Goldman.super(this);

        this.zOrder = 2;
        var timeLine;
        this.name = 'man';
        //车子移动速度
        this.speed_arr = ['',4500,4000,3500,3000,2500,2000];
        // 钩子摆动速度
        this.hookspeed_arr = ['',100,90,80,70,60,50];
        //方向
        this.fx = 'right';
        //点击状态
        this.status = true;
        //钩子摆动或者往外移动(钩子只是摆动is_move=true,钩子往外移动is_move=false)
        this.is_move = true;

        var dist = new Laya.Sprite();
        // dist.graphics.drawCircle(0,0,30,null,"#ff0000");
        dist.pos(this.hook.width / 2, 74);

        this.hook.addChild(dist);
        Laya.Animation.createFrames(['role/role1.png', 'role/role1_2.png'], "role");
        //点击事件 钩子
        this.on(Laya.Event.CLICK, this, this.ropeControl);       
        this.line = new Laya.Sprite();
        this.line.pivot(1, 0);
        this.addChild(this.line);
        this.MoveCar();
        this.aniRole();
        this.MyAniRole();
        this.role.visible = false;

        this.bod = new Laya.Animation();
        this.bod.interval = 200;
        this.bod.play(0, true, 'yan'); 
        this.bod.pos(580,24);
        this.addChild(this.bod);
    }

    //注册类
    Laya.class(Goldman, "Goldman", _super);
    var _proto = Goldman.prototype;

    _proto.aniRole = function () {
        this.body = new Laya.Animation();
        this.body.interval = 400;
        this.body.play(0, true, 'role');
        this.body.pos(385, 52);
        this.addChild(this.body);
    }

    _proto.MyAniRole = function () {
        this.body.pos(403, 55);
        this.body.anchorX = 0.5;
        this.body.anchorY = 0.5;

    }
    _proto.onLoadAni = function(){
        Laya.Animation.createFrames(['role/role3_1.png', 'role/role3_2.png'], "role");
    }

    //初始化
    _proto.start = function () {

      for (var i = 1; i < this.hookspeed_arr.length; i++) {
            if (pass == i) {
                this.hook_speed = this.hookspeed_arr[i];
            }            
        }
        // console.log(this.hook_speed);
        Laya.timer.loop(this.hook_speed, this, this.hookRun);       
        
    }

    //钩子旋转角度
    _proto.hookRun = function () {

        this.status = true;
        this.is_move = true;

        //旋转角度   
        if (this.fx == "right") {
            this.hook.rotation -= 2;

        } else {
            this.hook.rotation += 2;

        }

        if (this.hook.rotation <= -90) {
            this.fx = "left";
            this.hook.rotation = -90;
            // this.ropeControl();
        }

        if (this.hook.rotation >= 90) {
            this.fx = "right";
            this.hook.rotation = 90;
        }
    }

    _proto.clearHookRun = function () {
        //停止旋转
        Laya.timer.clear(this, this.hookRun);
    }


    //控制绳子移动
    _proto.ropeControl = function (x, y) {

       
        this.is_move = false;      
        // var pivotY = this.mouseY;
        if (!this.status) return;
        // if (pivotY < 280) return;

        statu = false;
        //禁止点击
        this.status = false;
        this.clearHookRun();

        //临边
        var linbian = this.hook.x;
        //角度
        var angle = 90 - Math.abs(this.hook.rotation);
        // console.log(angle);
        //余弦
        var angle_yuxian = Math.cos(2 * Math.PI * angle / 360);

        //正弦
        var angle_zhengxian = Math.sin(2 * Math.PI * angle / 360);

        //对边
        var duibian = angle_zhengxian / angle_yuxian * linbian;
        // console.log(duibian);

        if (angle == 90) {
            var s = 640 - 159;
        } else {
            var s = linbian / angle_yuxian;
        }

        var v = 350; //速度，单位:像素/秒
        var t = s / v;

        if (this.hook.rotation < 0) {
            this.tween = Laya.Tween.to(this.hook, {
                x: 960,
                y: duibian + this.hook.y
            }, t * 1000);
        } else if (this.hook.rotation == 0) {
            this.tween = Laya.Tween.to(this.hook, {
                y: 640
            }, t * 1000);
        } else {
            this.tween = Laya.Tween.to(this.hook, {
                x: 0,
                y: duibian + this.hook.y
            }, t * 1000);
        }

        // console.log(this.hook.rotation);
        //  console.log(this.hook.x, this.hook.y);
        //更新回调
        this.tween.update = new Laya.Handler(this, this.lineControl, [this.hook.x, this.hook.y]);
        //清理定时器
        Laya.timer.clear(this, this.hookRun);
    }

   //车子移动
    _proto.MoveCar = function(){


        timeLine = new Laya.TimeLine();

        for (var i = 1; i < this.speed_arr.length; i++) {
            if (pass == i) {
                this.pass_speed = this.speed_arr[i];
            }
            
        }
        // console.log(pass);
        // console.log(this.pass_speed);
         timeLine.to(this, {x:200}, this.pass_speed, null, 0)
                .to(this, {x:0}, this.pass_speed, null, 0)
                .to(this, {x:-200}, this.pass_speed, null, 0)
                .to(this, {x:0}, this.pass_speed, null, 0);
    
        timeLine.play(0,true); 
    }

   //车子暂停
    _proto.PauseCar = function(){
        timeLine.pause();
    }
   // 车子恢复
    _proto.ResumeCar = function(){
        timeLine.resume();
    }
    
    _proto.lineControl = function (x, y) {

        position = this.hook.y >= 640 || this.hook.x == 960 || this.hook.x == 0;
        if (position || statu) {
            if (position) {
                this.removeChild(this.body);
                this.onLoadAni();
                this.aniRole();
            }

            //如果已碰撞
            if (statu) {
                
                this.removeChild(this.body);
                this.role.visible = true;
                this.role.skin = 'role/role2.png';
                this.role.y = 88;
                this.gameType();
                var number = 0 ;
                if (obj.type == 8) {
                    number = Math.floor(Math.random() * 100) + 30;
                    temp_score += number;
                    this.hook.skin = 'gold/jiaxiang.png';
                } else {
                    number = obj.score;
                    temp_score += obj.score;
                    if (temp_score < 0) {
                        temp_score = 0;
                    }
                    // my_scorelable.changeText(score);
                }
              var plus =  this.PlusAni(number,obj.x,obj.y);             
              this.stage.addChild(plus);
            }

            //暂停缓动
            this.tween.pause();
            //清除线条
            this.line.graphics.clear();
            //绳子缩回
            this.ropeBack();
        }
        //   console.log(x,y);
        //   console.log(this.hook.x, this.hook.y);  
        this.line.graphics.clear();
        //画线
        this.line.graphics.drawLine(x, y, this.hook.x, this.hook.y, "#795628", 4.5);

    }

    _proto.PlusAni = function(num,x,y){
        if (sound) {
            Laya.SoundManager.playSound("res/sound/money.mp3"); 
        }
        var label  = new Laya.Label();
        if (num > 0) {
            label.text = '+' + num;
           
        }else{
            label.text = num;
        }       
        label.fontSize = 40;
        label.color = '#f9f900';        
        label.pos(x,y);
        label.zOrder = 10;
        Laya.Tween.to(label,{x:120,y:100, scaleX:0.8, scaleY:0.8
        },1200,Laya.Ease.backIn,Laya.Handler.create(this,function(){
            label.removeSelf();
        })); 

        return label;
    }


    _proto.gameType = function () {
        
        if (obj.type == 1) {
            this.hook.skin = 'gold/jiajin1.png';
         
        }
        if (obj.type == 2) {
            this.hook.skin = 'gold/jiajin2.png';
        }
        if (obj.type == 3) {
            this.hook.skin = 'gold/jiajin3.png';
        }
        if (obj.type == 4) {
            this.hook.skin = 'gold/jiajin4.png';
        }
        if (obj.type == 5) {
            this.hook.skin = 'gold/jiajin5.png';
        }
        if (obj.type == 6) {
            this.hook.skin = 'gold/jiazhuan.png';
            this.diamondShine();
            if (sound) {
                Laya.SoundManager.playSound("res/sound/diamond.mp3");
            } 
        }
        if (obj.type == 7) {
            if (sound) {
                
                Laya.SoundManager.playSound("res/sound/stone.mp3");
            }
            this.removeChild(this.body);
            this.hook.skin = 'gold/jiashi.png';
            this.role.visible = true;
            this.role.skin = 'role/role4.png';
            this.role.y = 96;
        }
        
       if (obj.type == 9) {
            this.removeChild(this.body);
            // this.hook.skin = 'gold/jiazhadan.png';
            this.role.visible = true;
            this.role.skin = 'role/role_5.png';
            this.role.y = 90;
            Laya.Animation.createFrames(
            [
             'ani/boom_1.png',
             'ani/boom_2.png', 
             'ani/boom_3.png',
             'ani/boom_4.png', 
             'ani/boom_5.png', 
             'ani/boom_6.png', 
             'ani/boom_7.png'], "boom");
             this.body = new Laya.Animation();
            //  console.log(obj.x,obj.y);
             this.p = new Laya.Point(obj.x,obj.y);
             this.body.pos(this.p.x,this.p.y);      
             this.stage.addChild(this.body);
             if (sound) {
                  Laya.SoundManager.playSound("res/sound/bomb.mp3");
             }
            
             this.body.interval = 120;
             this.body.play(0, false, 'boom');
             var bounds = this.body.getBounds();
             this.body.pivot(bounds.width/2,bounds.height/2);
             this.body.on('complete',this,this.onBoomCOM,[this.body]);

        }
         if (obj.type == 10) {
            this.hook.skin = 'gold/jiagu_2.png';
            this.role.visible = false;
            this.onLoadAni();
            this.aniRole();
          
        }
         if (obj.type == 11) {
            this.hook.skin = 'gold/jiapin_2.png';
            this.role.visible = false;
            this.onLoadAni();
            this.aniRole(); 
        }
    }

   //爆炸后移除自己
    _proto.onBoomCOM = function(boom){
        boom.removeSelf();
        // console.log(this.p);
        this.sprite = new Laya.Sprite();
        this.sprite.scaleX = 0.7;
        this.sprite.scaleY = 0.7;
        this.sprite.loadImage('hunt/baolie.png');
        this.sprite.pos(this.p.x-110,this.p.y-90);
       
        Laya.stage.addChild(this.sprite);
      
    }

  // 钻石发光
    _proto.diamondShine = function(){
        Laya.Animation.createFrames(['stars/stars_1.png','stars/stars_2.png','stars/stars_3.png',
                                     'stars/stars_4.png','stars/stars_5.png','stars/stars_6.png',
                                     'stars/stars_7.png','stars/stars_8.png','stars/stars_9.png',
                                     'stars/stars_10.png','stars/stars_11.png','stars/stars_12.png'],'stars');
        this.body = new Laya.Animation();       
        this.hook.addChild(this.body); 
        this.body.interval = 80;
        this.body.play(0, false, 'stars'); 
        var bounds = this.body.getBounds();
        this.body.pivot(bounds.width/2.5,bounds.height/6); 
        this.body.on('complete',this,this.onStarsCOM,[this.body]);
                        
    }

    _proto.onStarsCOM = function(stars){
        stars.removeSelf();
    }


    //缩回绳子
    _proto.ropeBack = function () {
        this.clearHookRun();

        if (statu) {
            this.tween = Laya.Tween.to(this.hook, {
                x: 482,
                y: 159
            }, obj.speed, null, new Laya.Handler(this, this.complete));
            this.tween.update = new Laya.Handler(this, this.lineBack);
            
            //移除碰撞后的元素
            goldArr.removeObject(obj);
            //移除碰撞物
            obj.removeSelf();
        }
        if (position == true) {
            this.tween = Laya.Tween.to(this.hook, {
                x: 482,
                y: 159
            }, 800, null, new Laya.Handler(this, this.complete));
            this.tween.update = new Laya.Handler(this, this.lineBack);
        }

    }

    //回调函数
    _proto.complete = function () {
  
        this.is_move = true;
        this.start();
        //如果达到目标分数，则进行下一关
        if (seconds > 0) {
            score = temp_score;
            if (score >= level) {
                this.PauseCar();
                my_gameinfo.clearTime(); //消除定时器
                my_gameinfo.Victory(); //弹出胜利窗口
                this.clearHookRun(); //钩子停止摆动
            }
        }
        my_scorelable.changeText(score);

    }

    //收回线条
    _proto.lineBack = function () {
//  console.log(this.hook.x,this.hook.y);
        this.line.graphics.clear();
        //画线
        this.line.graphics.drawLine(482, 159, this.hook.x, this.hook.y, "#795628", 4.5);

        if (this.hook.x === 482 && this.hook.y === 159) {
            this.removeChild(this.body);
            this.hook.skin = 'hunt/hook.png';
            this.role.visible = false;
            Laya.Animation.createFrames(['role/role1.png', 'role/role1_2.png'], "role");
            this.aniRole();
            this.MyAniRole();

        }

    }



    return Goldman;
})(ui.HookUI);