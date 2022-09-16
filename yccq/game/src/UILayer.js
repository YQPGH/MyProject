/**
 * Created by lkl on 2017/4/6.
 */
(function(){
    var self = null;
    function UILayer(userInfo)
    {
        UILayer.__super.call(this);
        self = this;
        game_level = userInfo.game_lv;
        this.ex_level = null;//升级前等级
        //this.initUI(userInfo);
        this.bg_shandian.on(Laya.Event.CLICK,this,this.onBGClick,[0]);
        this.bg_money.on(Laya.Event.CLICK,this,this.onBGClick,[1]);
        this.bg_bean.on(Laya.Event.CLICK,this,this.onBGClick,[2]);
        this.music_btn.clickHandler = new Laya.Handler(this,this.onMusicBtnClick);
        Laya.SoundManager.playMusic("./music/yancaochuanqi.mp3", 0);
        this.getIsSale();
    }
    Laya.class(UILayer,"UILayer",UILayerUI);
    var proto = UILayer.prototype;

    proto.getIsSale = function()
    {
        Utils.post('user/is_sale_money_shandian',{uid:localStorage.GUID},function(res){
            if(res.code == '0')
            {
                self.sale.visible = Boolean(res.data.is_sale);
                self.sale_1.visible = Boolean(res.data.is_sale);
            }
        });
    };

    proto.onMusicBtnClick = function()
    {
        console.log(this.music_btn.selected);
        if(this.music_btn.selected){
            Laya.SoundManager.musicMuted = true;
            Laya.SoundManager.stopAll();
        }else {
            Laya.SoundManager.musicMuted = false;
            Laya.SoundManager.playMusic("./music/yancaochuanqi.mp3", 0);
        }

    };

    proto.initUI = function(userInfo)
    {
        this.userInfo = userInfo;

        if(this.ex_level && Number(userInfo.game_lv) > Number(this.ex_level)){
            this.levelUpAni(userInfo.game_lv);
        }
     
        //this.levelUpAni(userInfo.game_lv);
        this.ex_level = userInfo.game_lv;
        this.NickName.changeText(this.userInfo.nickname);
        this.header_img.skin = this.userInfo.head_img == ''?this.userInfo.local_img:this.userInfo.head_img;
        this.level.changeText(this.userInfo.game_lv);
        this.setHeaderFrame(Number(userInfo.header_frame));

        if(this.userInfo.game_lv >= 10){
            if(Laya.stage.getChildByName('MyGame').NPC){
                Laya.stage.getChildByName('MyGame').NPC.NPCEnable = true;
            }
        }

        if(this.userInfo.game_lv == config.MaxLevel){
            this.ExpText.changeText("100%");
            this.ProgressExp.value = 1;
            
        }else {
            this.ProgressExp.value = Number(this.userInfo.game_xp)/Number(this.userInfo.game_xp_all);
            this.ExpText.changeText(Number(this.userInfo.game_xp)+'/'+Number(this.userInfo.game_xp_all));
           
        }
        // this.ProgressExp.changeHandler = new Laya.Handler(this, this.onExpchange);

        this.Gold.changeText(this.userInfo.money);
        this.Bean.changeText(this.userInfo.ledou);
        this.Shandian.changeText(Number(this.userInfo.shandian));

        this.header_img.on(Laya.Event.CLICK,this,this.onHeaderImgClick);
        this.Dati.on(Laya.Event.CLICK,this,this.onDatiClick);
        this.Signin.on(Laya.Event.CLICK,this,this.onSigninClick);
        this.Activity.on(Laya.Event.CLICK,this,this.onActivityClick);
        this.chaxun.clickHandler = Laya.Handler.create(this,this.onChaXunClick,null,false);
        //this.DayTask.on(Laya.Event.CLICK,this,this.onDayTaskClick);
        this.Shijian.clickHandler = Laya.Handler.create(this,this.onShijianClick,null,false);
        this.add_shandian_btn.clickHandler = Laya.Handler.create(this,this.onAddShandianBtnClick,null,false);
        this.add_money_btn.clickHandler = Laya.Handler.create(this,this.onAddMoneyBtnClick,null,false);
        this.Gonglue.clickHandler = Laya.Handler.create(this,this.onGonglueBtnClick,null,false);
        this.Jiangpin.clickHandler = Laya.Handler.create(this,this.onJiangPinClick,null,false);
        this.dazhuanpan_btn.clickHandler = Laya.Handler.create(this,this.onDaZhuanPanBtnClick,null,false);

        //好友按钮
        this.friend_btn.clickHandler = Laya.Handler.create(this,this.onFriendBtnClick,null,false);

        //右边面板开关
        this.Activity_btn.clickHandler = Laya.Handler.create(this,this.onActivityBtnClick,null,false);
        //意见反馈
        this.ranking_btn.clickHandler = Laya.Handler.create(this,this.onRankingBtnClick,null,false);
        this.zhaoji_btn.clickHandler = Laya.Handler.create(this,this.onZhaojiBtnClick,null,false);



        /*//指引面板开关
        if(!this.guide_view){
            this.guide_view = new GuideView();
            this.addChild(this.guide_view);
        }*/


        //--------以下为测试调试面板正式发布后删除-----------
        /*this.debugpanel_btn.clickHandler = Laya.Handler.create(this,this.onDebugPanelClick,[this.debugpanel_btn],false);
        this.addGold_btn.clickHandler = Laya.Handler.create(this,this.onAddGoldClick,null,false);
        this.subGold_btn.clickHandler = Laya.Handler.create(this,this.onSubGoldClick,null,false);
        this.addBean_btn.clickHandler = Laya.Handler.create(this,this.onAddBeanClick,null,false);
        this.subBean_btn.clickHandler = Laya.Handler.create(this,this.onSubBeanClick,null,false);
        this.addExp_btn.clickHandler = Laya.Handler.create(this,this.onAddExpClick,null,false);
        this.enterGame_btn.clickHandler = Laya.Handler.create(this,this.onEnterGameClick,null,false);*/
        //--------以上为测试调试面板正式发布后删除-----------
    };

    proto.friendUI = function(data){
        this.NickName.changeText(data.nickname);
        this.header_img.skin = data.thumb;
        this.level.changeText(data.game_lv);

        this.ProgressExp.value = Number(data.game_xp)/Number(data.game_xp_all);
        this.ExpText.changeText(Number(data.game_xp)+'/'+Number(data.game_xp_all));

        this.getChildByName('gold_info').visible = false;
        this.Activity_bg.visible = false;
        this.Gonglue.visible = false;
        this.chaxun.visible = false;
        this.Jiangpin.visible = false;
        this.dazhuanpan_btn.visible = false;

        this.friend_panel.visible = true;
        this.BackHome.clickHandler = Laya.Handler.create(this,this.onBackHomeClick,null,false);
        this.fire_btn.clickHandler = Laya.Handler.create(this,this.onFireBtnClick,null,false);
    };

    proto.onBackHomeClick = function()
    {
        BackHome();
    };

    proto.onFireBtnClick = function()
    {
        var dialog = new FriendFireDialog();
        dialog.popup();
    };

    //经验条数值改变处理事件
    proto.onExpchange = function(value) {
        this.ExpText.changeText((value*100).toFixed(2)+"%");
    };

    //获得经验
    proto.addExp = function(value) {
        if(!value) return false;
        if(this.userInfo.game_lv < config.MaxLevel){
            this.userInfo.game_xp = Number(this.userInfo.game_xp) + Number(value);
            this.ProgressExp.value = Number(this.userInfo.game_xp)/Number(this.userInfo.game_xp_all);
            this.ExpText.changeText(Number(this.userInfo.game_xp)+'/'+Number(this.userInfo.game_xp_all));
            console.log("%c获取经验:"+value,"color:green");
            if(this.userInfo.game_xp >= this.userInfo.game_xp_all){
                this.LevelUp();
            }
            return true;
        }else {
            console.log("您已经满级");
            return false;
        }
    };

    //角色升级
    proto.LevelUp = function() {
        if(this.userInfo.game_lv < config.MaxLevel){
            this.userInfo.game_xp = this.userInfo.game_xp - this.userInfo.game_xp_all;
            this.userInfo.game_xp_all = this.userInfo.game_xp_all + 5*this.userInfo.game_lv;
            this.ProgressExp.value = this.userInfo.game_xp/this.userInfo.game_xp_all;
            this.ExpText.changeText(Number(this.userInfo.game_xp)+'/'+Number(this.userInfo.game_xp_all));
            this.userInfo.game_lv ++;
            this.level.text = this.userInfo.game_lv;
            console.log("%c等级+1","color:green");
            //执行升级动画
            //升级动画();
            if(this.userInfo.game_lv == config.MaxLevel){
                this.userInfo.game_xp = 0;
                this.ProgressExp.value = 1;
                //this.ExpText.changeText("100%");
            }
            //检测剩余经验是否满足升级条件，满足则继续升级
            if(this.userInfo.game_xp >= this.userInfo.game_xp_all){
                this.LevelUp();
            }
            return true;
        }else {
            console.log("您已经满级");
            return false;
        }
    };

    proto.levelUpAni = function(level)
    {
        Utils.post('user/queryGameLvPrize',{uid:localStorage.GUID,game_lv:level},function(res){
            if(res.code == 0){
                var dialog = new LevelUpUI();
                dialog.level.text = ''+level;
                /*if(level == 15){
                    dialog.content.text = '恭喜获得凌云品吸机会代金券*2，\n可点击头像“奖品”查看详情。';
                }else if(level == 20){
                    dialog.content.text = '恭喜获得500乐豆，\n可在微信菜单“我的乐豆”中查看明细。';
                }else if(level == 25){
                    dialog.content.text = '恭喜获得鸿韵品吸机会代金券*2，\n可点击头像“奖品”查看详情。';
                }else if(level == 28){
                    dialog.content.text = '恭喜获得600乐豆，\n可在微信菜单“我的乐豆”中查看明细。';
                }else if(level == 30){
                    dialog.content.text = '恭喜获得起源品吸机会代金券*2，\n可点击头像“奖品”查看详情。';
                }*/
                dialog.zOrder = 3000;
                dialog.popup();
                dialog.timer.once(5000,this,function(){
                    if(dialog.isPopup){
                        dialog.close();
                    }
                });
                dialog.closeHandler = new Laya.Handler(this,function(){
                    if(level == 8)//培育中心解锁提示
                    {
                        self.showPYTips();
                    }else if(level == 9)
                    {
                        self.showYJSTips();//调香书解锁提示
                    }
                    else if(level == 12)//路边摊解锁提示
                    {
                        self.showLBTTips();
                    }else if(level == 10)
                    {
                        self.showJianDieTips();//间谍解锁提示
                    }

                    //Utils.post('user/queryGameLvPrize',{uid:localStorage.GUID,game_lv:level},function(res){
                    // console.log(res);
                    //if(res.code == 0){
                    for(var i = 0; i < res.data.length; i++){
                        if(res.data[i].type == 'ledou'){
                            getBean(res.data[i].num);
                        }else if(res.data[i].type == 'money'){
                            getMoney(res.data[i].num);
                        }else if(res.data[i].type == 'shandian'){
                            getShandian(res.data[i].num);
                        }else if(res.data[i].shopid > 0){
                            getItem(res.data[i].shopid,res.data[i].num);
                        }
                    }
                    //}
                    //});
                });
            }
        });

    };

    //设置头像
    proto.setHeaderFrame = function(id)
    {
        Laya.loader.load("res/data/header.json",Laya.Handler.create(this,function(res){
            if(id < res.length){
                this.header_frame.skin = res[id].url;
                this.userInfo.header_frame = id;
            }
        }),null,Laya.Loader.JSON);

    };

    //添加金币
    proto.addGlod = function(value) {

        if(!isNaN(value) && value > 0){
            this.userInfo.money = Number(this.userInfo.money) + value;
            this.Gold.text = this.userInfo.money;
            console.log("%c添加金币:"+value,"color:green");
            return true;
        }
        return false;
    };

    //扣除金币
    proto.subGlod = function(value) {

        if(!isNaN(value) && value > 0){
            if(this.userInfo.money >= value){
                this.userInfo.money = Number(this.userInfo.money) - value;
                this.Gold.text = this.userInfo.money;
                console.log("%c扣除金币:"+value,"color:green");
                return true;
            }else {
                return 0;//代表金额不足
            }
        }
        return false;
    };

    //添加乐豆
    proto.addBean = function(value) {

        if(!isNaN(value) && value > 0){
            this.userInfo.ledou = Number(this.userInfo.ledou) + value;
            this.Bean.text = this.userInfo.ledou;
            console.log("%c添加乐豆:"+value,"color:green");
            return true;
        }
        return false;
    };

    //扣除乐豆
    proto.subBean = function(value) {

        if(!isNaN(value) && value > 0){
            if(this.userInfo.ledou >= value){
                this.userInfo.ledou = Number(this.userInfo.ledou) - value;
                this.Bean.text = this.userInfo.ledou;
                console.log("%c扣除乐豆:"+value,"color:green");
                return true;
            }else {
                return 0;//代表金额不足
            }
        }
        return false;
    };

    //扣除闪电
    proto.subShandian = function(value) {

        if(!isNaN(value) && value >= 0){
            if(this.userInfo.shandian >= value){
                this.userInfo.shandian = Number(this.userInfo.shandian) - value;
                this.Shandian.text = this.userInfo.shandian;
                console.log("%c扣除闪电:"+value,"color:green");
                return true;
            }else {
                return 0;//代表金额不足
            }
        }
        return false;
    };

    proto.onHeaderImgClick = function()
    {
        var dialog = new UserInfoDialog();
        dialog.popup();
    };

    proto.onDatiClick = function()
    {
        var dialog = new YouLeChangDialog();
        dialog.popup();
    };

    proto.onSigninClick = function()
    {
        var dialog = new SignInDialog();
        dialog.popup();

    };

    proto.onFriendBtnClick = function()
    {
        if(!this.friend_dialog){
            this.friend_dialog = new FriendDialog();
            this.friend_dialog.group = 'friend';
            this.friend_dialog.popupCenter = false;
            this.friend_dialog.pos(Laya.stage.width-this.friend_dialog.width+20,Laya.stage.height-this.friend_dialog.height);
            this.friend_dialog.closeHandler = new Laya.Handler(this,function(){
                this.friend_dialog = null;
            });
        }
        //dialog.size(703,220);
        this.friend_dialog.show();
    };

    proto.onActivityClick = function()
    {
        var dialog = new HuoDong();
        dialog.popup();
    };

    proto.onDayTaskClick = function()
    {
        var dialog = new DayTaskDialog();
        dialog.popup();
    };

    proto.onAddShandianBtnClick = function()
    {
        var dialog = new RechargeDialog('shandian');
        dialog.popup();
    };

    proto.onAddMoneyBtnClick = function()
    {
        var dialog = new RechargeDialog('money');
        dialog.popup();
    };

    proto.onShijianClick = function()
    {
        this.stage.getChildByName("MyGame").map.mapMoveTo(25,33);
    };

    proto.onJiangPinClick = function()
    {
        var dialog = new MyPrize();
        dialog.popup();
    };

    //触发烘烤事件
    proto.setShijian = function()
    {
        this.Shijian.visible = true;
    };

    proto.clearShijian = function()
    {
        this.Shijian.visible = false;
    };

    proto.onGonglueBtnClick = function()
    {
        Laya.loader.load('res/atlas/gongluenew.atlas',Laya.Handler.create(this, function(){
            var dialog = new Gonglue();
            dialog.popup();
        }), null, Laya.loader.TEXT);
    };

    proto.onChaXunClick = function()
    {
        Laya.loader.load('factory/tiaoxiangshujiemian.png',Laya.Handler.create(this,function(){
            var dialog = new ChaXun();
            dialog.popup();
        }),null);
    };

    proto.onActivityBtnClick = function()
    {
        if(this.Activity_btn.selected){
            Laya.Tween.to(this.Activity_bg,{x:Laya.stage.width+32},500);
        }else {
            Laya.Tween.to(this.Activity_bg,{x:Laya.stage.width-this.Activity_bg.width},500);
        }
    };

    proto.onRankingBtnClick = function()
    {
        var dialog = new Ranking();
        dialog.popup();
    };

    proto.onZhaojiBtnClick = function()
    {
        Laya.loader.load([{url:'res/atlas/laxin.atlas',type:Laya.Loader.ATLAS},{url:'laxin/laxin_diban.png',type:Laya.Loader.IMAGE},{url:'laxin/laxin_kuang_3.png',type:Laya.Loader.IMAGE},{url:'laxin/laxin_renwulian_1.png',type:Laya.Loader.IMAGE},{url:'laxin/laxin_renwulian_2.png',type:Laya.Loader.IMAGE}], new Laya.Handler(this, function(){
            var dialog = new Laxin();
            dialog.popup();
        }), null, Laya.Loader.TEXT);

    };

    proto.onShowGuideBtnClick = function()
    {
        if(this.show_guide_btn.selected)
        {

            Laya.Tween.to(this.guide_box,{y:Laya.stage.height-this.GuideDialog.height},500);
        }else {
            Laya.Tween.to(this.guide_box,{y:Laya.stage.height},500);
        }
    };
    //解锁路边摊提示
    proto.showLBTTips = function()
    {
        Laya.stage.getChildByName('MyGame').LBT.clearTips();
        Laya.loader.load('res/atlas/zhiyin.atlas',new Laya.Handler(this,function(){
            var tips = new tipsDialog();
            tips.content.innerHTML = '告诉你一个好消息，只需要<span color="#ae0626">500个乐豆</span>就能解锁路边小摊了呢！解锁后可以与<span color="#ae0626">游戏中所有玩家</span>相互买卖生产香烟的原料，十分方便生产！';
            tips.content.y = 100;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                Laya.stage.getChildByName('MyGame').map.mapMoveTo(20,12);
            });
        }));
    };
    //解锁培育中心提示
    proto.showPYTips = function()
    {
        Laya.stage.getChildByName('MyGame').Peiyushi.clearTips();
        Laya.loader.load('res/atlas/zhiyin.atlas',new Laya.Handler(this,function(){
            var tips = new tipsDialog();
            tips.content.innerHTML = '你的努力我看到啦！8级后使用<span color="#ae0626">200乐豆或20000银元</span>，就可以拥有种子培育中心了！在种子培育中心里用<span color="#ae0626">两片低等级</span>的烟叶就可以培育一颗<span color="#ae0626">高等级的种子</span>！';
            tips.content.y = 80;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                Laya.stage.getChildByName('MyGame').map.mapMoveTo(23,20);
            });
        }));
    };

    proto.showYJSTips = function()
    {
        Laya.stage.getChildByName('MyGame').YJS.clearTips();
        Laya.loader.load('res/atlas/zhiyin.atlas',new Laya.Handler(this,function(){
            var tips = new tipsDialog();
            tips.content.innerHTML = '我就知道你一定很渴望拥有自己的调香研究所！用<span color="#ae0626">3本低星级调香书</span>，就<span color="#ae0626">有机会合成</span>出一本高星级调香书喔！别再犹豫了，<span color="#ae0626">250个乐豆或25000银元</span>就能解锁！快去看看！';
            tips.content.y = 80;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                Laya.stage.getChildByName('MyGame').map.mapMoveTo(28,19);
            });
        }));
    };

    proto.showJianDieTips = function()
    {
        Laya.loader.load('res/atlas/zhiyin.atlas',new Laya.Handler(this,function(){
            var tips = new tipsDialog();
            tips.content.innerHTML = '听说真龙商行的掌柜来了个新朋友，希望对你有帮助喔！快去看看吧！';
            tips.content.y = 100;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                Laya.stage.getChildByName('MyGame').map.mapMoveTo(19,19);
            });
        }));
    };

    proto.onDaZhuanPanBtnClick = function() {
        Laya.loader.load([{url:'dazhuanpan/haoyunlinmen_bg.png',type:Laya.Loader.IMAGE},{url:'dazhuanpan/haoyunlinmen_zhuanpan.png',type:Laya.Loader.IMAGE},{url:'res/atlas/dazhuanpan.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
            var dialog = new DaZhuanPan();
            dialog.popup();
        }),null,Laya.Loader.TEXT);
    };

    proto.onBGClick = function(index)
    {
        Dialog.closeByGroup('tips');

        var content = config.contentArr[index];
        switch(index){
            case 0:
                var dialog = new ledouTips(content);
                dialog.pos(370,50);
                break;
            case 1:
                var dialog = new ledouTips(content);
                dialog.pos(570,50);
                break;
            case 2:
                var dialog = new ledouTips(content,1);
                dialog.pos(570,50);
        }
        dialog.group = 'tips';
        dialog.show();
    };

    //----------以下为测试调试面板正式发布后删除-----------
    proto.onDebugPanelClick = function(obj)
    {
        if(obj.selected)
        {
            this.debug.visible = true;
        }else {
            this.debug.visible = false;
        }
    };

    proto.onAddGoldClick = function()
    {
        this.addGlod(10);
    };

    proto.onSubGoldClick = function()
    {
        this.subGlod(10);
    };

    proto.onAddBeanClick = function()
    {
        this.addBean(10);
    };

    proto.onSubBeanClick = function()
    {
        this.subBean(10);
    };

    proto.onAddExpClick = function()
    {
        this.addExp(2000);
    };

    proto.onEnterGameClick = function()
    {
        console.log("进入关卡游戏");
        Laya.stage.getChildByName("MyGame").destroy();
        var LoadingScene = new loadingUI();
        Laya.stage.addChild(LoadingScene);
        console.log(Laya.stage);
    };
    //----------以上为测试调试面板正式发布后删除-----------
})();