/**
 * Created by 41496 on 2018/6/25.
 */
(function(){
    var self = null;
    function HuoDong(){
        HuoDong.__super.call(this);
        self = this;
        this.panel.vScrollBar.hide = true;
        this.data = [];
        this.goto_btn.clickHandler = new Laya.Handler(this,this.onGotoBtnClick);
        this.getHuoDongList();
    }
    Laya.class(HuoDong,'HuoDong',HuoDongUI);
    var proto = HuoDong.prototype;

    proto.getHuoDongList = function()
    {
        Utils.post('user/rank_index_list',{uid:localStorage.GUID},function(res){
            console.log(res);
            if(res.code == '0')
            {
                self.data = res.data;
                self.createTab();
            }
        });
    };

    proto.createTab = function () {
        var tab_data = [];
        for(var i = 0; i < this.data.length; i++){
            tab_data.push(this.data[i].name);
        }
        this.tab.labels = tab_data.join(',');
        this.tab.selectHandler = new Laya.Handler(this,this.onTabSelected);
        this.onTabSelected(0);
    };

    proto.onTabSelected = function(index){
        this.img.skin = this.data[index].img;
        this.intro.text = this.data[index].intro;
        if(this.data[index].type == null){
            this.goto_btn.visible = false;
        }else {
            this.goto_btn.visible = true;
        }
    };

    proto.onGotoBtnClick = function(){
        var index = this.tab.selectedIndex;

        switch(this.data[index].type){
            case 'system':
                this.runAction(this.data[index].action);
                break;
            case 'goto':
                window.location.href = this.data[index].action;
                break;
        }
    };

    proto.runAction = function(action){
        switch(action){
            case 'signin':
                this.openSignin();
                break;
            case 'ranking':
                this.openRanking();
                break;
            case 'task':
                this.openTask();
                break;
            case 'YLC':
                this.openYLC();
                break;
            case 'chongzi':
                this.openChongzi();
                break;
            case 'tiaozhan':
                this.openTiaoZhan();
                break;
            case 'suipiange':
                this.openSuiPianGe();
                break;
        }
    };

    proto.openSignin = function(){
        var dialog = new SignInDialog('signin');
        dialog.popup();
    };

    proto.openTask = function(){
        var dialog = new SignInDialog('task');
        dialog.popup();
    };

    proto.openRanking = function(){

        var dialog = new Ranking();
        dialog.popup();

    };

    proto.openYLC = function(){
        var dialog = new YouLeChangDialog();
        dialog.popup();
    };

    proto.openChongzi = function(){
        Laya.loader.load('res/atlas/chongzi.atlas',Laya.Handler.create(this,function(){
            var dialog = new ChongziDialog();
            dialog.popup();
        }),null,Laya.Loader.ATLAS);
    };

    proto.openTiaoZhan = function() {
        var dialog = new YouLeChangDialog();
        dialog.popup();
    }

    proto.openSuiPianGe = function() {
        Laya.loader.load([{url:'fragment/suipiange_bg.png',type:Laya.Loader.IMAGE},{url:'fragment/xuanzebaoxiang_bg.png',type:Laya.Loader.IMAGE},{url:'fragment/xuanzehaoyou_bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/fragment.atlas',type:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
            var dialog = new Fragment();
            dialog.popup();
        }),null,Laya.Loader.TEXT);
    }
})();