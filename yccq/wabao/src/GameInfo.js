var GameInfo = (function(_super){

    function GameInfo(goldman){

        GameInfo.super(this);
        this.goldman = goldman;
        this.zOrder = 2;
        self = this;
       //音效
        sound = true;
        //注册按钮点击事件
        // this.pauseBtn.clickHandler = new Laya.Handler(this, this.onpauseBtnClick);
        this.musicBtn.clickHandler = new Laya.Handler(this, this.onmusicBtnClick);
        //显示积分
        my_scorelable = this.scoreLabel;
        //等级
        my_levelLabel = this.levelLabel;

        //初始化时间
        this.timeLabel.text  = seconds + " 秒";     

    }
    
    //注册类
    Laya.class(GameInfo,"GameInfo",_super);
    var _proto = GameInfo.prototype;
    //音乐
    _proto.onmusicBtnClick = function(){
         
        if (sound) {           
            sound = false;;
            Laya.SoundManager.stopAllSound();
          
        }else{
            sound = true;
            
        }
      
    }

  
    //开始游戏
    _proto.clickStart = function(){
        this.goldman.clearHookRun();  
    }


    //初始化
    _proto.reset = function(){
        Laya.timer.loop(1000,this,this.isShow); 
    }

    //清理定时器
    _proto.clearTime = function(){
        Laya.timer.clear(this, this.isShow);
    }

    //初始化
    _proto.isShow = function(){
        //console.log(this.goldman.hook.x,this.goldman.hook.y);
        //如果时间为0，游戏结束，做出相关的操作
        if(seconds == 0){
            this.clearTime();               //调用清理定时器方法
            this.goldman.clearHookRun();    //钩子停止摆动

            //如果达不到目标分数且时间为0，则游戏结束
            if(score < level){
                self.goldman.PauseCar();
                my_gameinfo.clearTime();    //消除定时器
                // this.saveScore();           //记录分数
                this.getPass();
            }            
        }else{     
            seconds --;
            //获取时间
            this.timeLabel.text = seconds +" 秒";
        }               
    }
    //从后台获取数据   关数
    _proto.getPass = function(){
        comfun.post('Hunt_game/getPass', {uid:localStorage.GUID}, this.ongetPassReturn, onHttpErr);
    };

    _proto.ongetPassReturn = function(res){
        console.log("当前通关总关数",res.data.my_pass.pass);
        pass = res.data.my_pass.pass;
        self.gameover();
        //play_times = res.data.my_pass.play_times;
    }


    //胜利提示
    _proto.Victory = function(){
        dialogVS = new gameVictory();
        dialogVS.popup();
        if(pass <= 6){
           
            pass ++;            //关数             
            level += 150;       //升级等级所需的成绩数量
        }
        Laya.timer.once(1200,this, this.saveScore);   //保存成绩到数据库
     
    }


    //下一关页面弹框提示
    _proto.nextDialog = function(){
      CustomsPass();
    }

    _proto.saveScore = function(){
        // console.log('关',pass);
        comfun.post('Hunt_game/gameover', {uid:localStorage.GUID, pass:pass, score:score}, this.onSaveScoreReturn, onHttpErr);
    }

    //保存成绩，提示获得的奖品
    _proto.onSaveScoreReturn = function(res){
        pass = res.data.my_pass.pass;
        // console.log("fanhui",pass);
        if(res.code == 0){
            for(var i in res.data.my_record){
                if(Object.prototype.hasOwnProperty.call(res.data.my_record,i)){
                    record[res.data.my_record[i].pass] = 1;
                }
            }
                    dialogVS.close();
                    var vic = new gamePrizeUI();                    
                        var num = 1;
                        vic.show_text_1.text = '恭喜获得' + res.data.prize.money + '银元';
                       
                        if(res.data.prize.shop1_total!=0){
                            vic.box_bg_2.visible = true;
                            vic.goods_icon_1.skin = res.data.prize.shop1_thumb;
                            vic.show_text_2.text =  res.data.prize.shop1_name+'*'+res.data.prize.shop1_total;
                            num ++;
                        }
                        if(res.data.prize.shop2_total!=0){
                            vic.goods_icon_2.skin = res.data.prize.shop2_thumb;
                            vic.box_bg_3.visible = true;
                            vic.show_text_2.text = res.data.prize.shop1_name+'*'+res.data.prize.shop1_total+'、'+res.data.prize.shop2_name+'*'+res.data.prize.shop2_total;
                            num ++;
                        } 
                        //根据随机获得奖品数量显示物品框，计算物品框的距离
                        var boxs = [vic.box_bg_1,vic.box_bg_2,vic.box_bg_3];
                        var width_total = vic.prizeBox.width;
                        var width = vic.box_bg_2.width * num +14 * (num-1); 
                        var start_x = (width_total - width)/2;
                        // console.log(start_x);
                        for(var i = 0; i < boxs.length; i++)
                        {
                            boxs[i].x = start_x + i*vic.box_bg_2.width + 14 * (i);
                        }
                        //领取按钮点击事件
                        vic.recieveBtn.once(Laya.Event.CLICK, this, recieveClick);                      
                        vic.popup();   
                          function recieveClick(){                
                            // console.log(444);
                              removeAni();
                              spliceArray();
                              self.removeSelf();
                              self.nextDialog(); 
                        }                                                
        }
    }


 

    //游戏结束
    _proto.gameover = function(){
        console.log("游戏结束");
        if(this.goldman.is_move == true){
            this.goldman.clearHookRun();
        }else{
            this.goldman.tween.pause();
        }   
        var over = new gameOver(this);
        over.popup(); 
    }

    //退出游戏
    _proto.gameQuit = function(){
        this.clickStart();
    }

    //暂停游戏
    // _proto.onpauseBtnClick = function(){
    //     self.goldman.PauseCar();
    //     var dialog = new GamePause(this);
    //     dialog.popup();
    //     //清理定时器
    //     this.clearTime();
    //     if(this.goldman.is_move == true){
    //         this.goldman.clearHookRun();
    //     }else{
    //         this.goldman.tween.pause();
    //     }   
    // }

    //恢复游戏
    _proto.gameResume = function(){
        self.goldman.ResumeCar();
        //恢复时间
        this.reset();
        console.log("is_move=",this.goldman.is_move);
        // if(this.goldman.is_move == true){
            this.goldman.start();
        // }else{
        //     this.goldman.tween.resume();
        // } 
    }


    //开启点击状态
    _proto.hookStatus = function(){
        this.goldman.status = true;
    }

    return GameInfo;

})(ui.GameInfoUI);