/**
 * Created by 41496 on 2017/8/15.
 */
(function(){
    var self = null;
    function Chengjiu()
    {
        Chengjiu.__super.call(this);
        self = this;
        self.CJ_data = null;
        //this.data = data;
        this.getUserData();

    }
    Laya.class(Chengjiu,'Chengjiu',chengjiuUI);
    var proto = Chengjiu.prototype;

    proto.getUserData = function()
    {
        Utils.post('user/detail',{uid:localStorage.GUID},this.onUserDataReturn);
    };

    proto.onUserDataReturn = function(res)
    {
        if(res.code == 0){
            self.CJ_data = res.data;
            self.initView(res.data);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.initView = function(data)
    {
        this.setPinjian(data.pinjian_lv,data.pinjian_total);
        this.setPlant(data.yannong_lv,data.yannong_total);
        this.setJiaoyi(data.jiaoyi_lv,data.jiaoyi_total);
        this.setZhiyan(data.zhiyan_lv,data.zhiyan_total);
    };

    proto.setPinjian = function(lv,total)
    {
        lv = Number(lv);
        total = Number(total);
        switch(lv)
        {
            case 4:
                this.pin_icon_3.gray = false;
            case 3:
                this.pin_icon_2.gray = false;
            case 2:
                this.pin_icon_1.gray = false;
            case 1:
                this.pin_icon_0.gray = false;
        }
        this.pin_icon_3.on(Laya.Event.CLICK,this,this.onIconClick,[3,'Pinjian']);
        this.pin_icon_2.on(Laya.Event.CLICK,this,this.onIconClick,[2,'Pinjian']);
        this.pin_icon_1.on(Laya.Event.CLICK,this,this.onIconClick,[1,'Pinjian']);
        this.pin_icon_0.on(Laya.Event.CLICK,this,this.onIconClick,[0,'Pinjian']);
        if(lv < 4){
            this.pin_text.changeText('还需完成'+(config.Achievement['Pinjian'].needNum[lv]-total)+'次品鉴才能解锁'+config.Achievement['Pinjian'].name[lv]);
        }else {
            this.pin_text.changeText('已达成所有奖章')
        }

    };

    proto.setPlant = function(lv,total)
    {
        lv = Number(lv);
        total = Number(total);
        switch(lv)
        {
            case 4:
                this.plant_icon_3.gray = false;
            case 3:
                this.plant_icon_2.gray = false;
            case 2:
                this.plant_icon_1.gray = false;
            case 1:
                this.plant_icon_0.gray = false;
        }
        this.plant_icon_3.on(Laya.Event.CLICK,this,this.onIconClick,[3,'Yannong']);
        this.plant_icon_2.on(Laya.Event.CLICK,this,this.onIconClick,[2,'Yannong']);
        this.plant_icon_1.on(Laya.Event.CLICK,this,this.onIconClick,[1,'Yannong']);
        this.plant_icon_0.on(Laya.Event.CLICK,this,this.onIconClick,[0,'Yannong']);
        if(lv < 4)
        {
            this.plant_text.changeText('还需完成'+(config.Achievement['Yannong'].needNum[lv]-total)+'次种植才能解锁'+config.Achievement['Yannong'].name[lv]);
        }else {
            this.plant_text.changeText('已达成所有奖章')
        }

    };

    proto.setJiaoyi = function(lv,total)
    {
        lv = Number(lv);
        total = Number(total);
        switch(lv)
        {
            case 4:
                this.jiao_icon_3.gray = false;
            case 3:
                this.jiao_icon_2.gray = false;
            case 2:
                this.jiao_icon_1.gray = false;
            case 1:
                this.jiao_icon_0.gray = false;
        }
        this.jiao_icon_3.on(Laya.Event.CLICK,this,this.onIconClick,[3,'Jiaoyi']);
        this.jiao_icon_2.on(Laya.Event.CLICK,this,this.onIconClick,[2,'Jiaoyi']);
        this.jiao_icon_1.on(Laya.Event.CLICK,this,this.onIconClick,[1,'Jiaoyi']);
        this.jiao_icon_0.on(Laya.Event.CLICK,this,this.onIconClick,[0,'Jiaoyi']);
        if(lv < 4){
            this.jiao_text.changeText('还需完成'+(config.Achievement['Jiaoyi'].needNum[lv]-total)+'次订单才能解锁'+config.Achievement['Jiaoyi'].name[lv]);
        }else {
            this.jiao_text.changeText('已达成所有奖章')
        }

    };

    proto.setZhiyan = function(lv,total)
    {
        lv = Number(lv);
        total = Number(total);
        switch(lv)
        {
            case 4:
                this.jia_icon_3.gray = false;
            case 3:
                this.jia_icon_2.gray = false;
            case 2:
                this.jia_icon_1.gray = false;
            case 1:
                this.jia_icon_0.gray = false;

        }
        this.jia_icon_3.on(Laya.Event.CLICK,this,this.onIconClick,[3,'Zhiyan']);
        this.jia_icon_2.on(Laya.Event.CLICK,this,this.onIconClick,[2,'Zhiyan']);
        this.jia_icon_1.on(Laya.Event.CLICK,this,this.onIconClick,[1,'Zhiyan']);
        this.jia_icon_0.on(Laya.Event.CLICK,this,this.onIconClick,[0,'Zhiyan']);
        if(lv < 4){
            this.jia_text.changeText('还需完成'+(config.Achievement['Zhiyan'].needNum[lv]-total)+'次制烟才能解锁'+config.Achievement['Zhiyan'].name[lv]);
        }else {
            this.jia_text.changeText('已达成所有奖章');
        }
    };

    proto.onIconClick = function(lv,name)
    {

        var dialog = new chengjiu_introUI();
        dialog.icon.skin = config.Achievement[name].icon[lv];
        dialog.chengjiu_name.text = config.Achievement[name].name[lv];
        //dialog.text.changeText(config.Achievement[name].name[lv]+','+config.Achievement[name].text[lv]);
        var jiacheng = '';
        if(config.Achievement[name].text[lv] != '无'){
            jiacheng = '（加成:'+config.Achievement[name].text[lv]+'）'
        }
        if(Number(this.CJ_data[name.toLowerCase()+'_lv']) >= lv+1){
            dialog.share_btn.disabled = false;
            dialog.text.changeText('恭喜获得'+config.Achievement[name].name[lv]+jiacheng+'，快把成就炫耀给好友吧！');
        }else {
            dialog.share_btn.disabled = true;
            dialog.text.changeText(config.Achievement[name].name[lv]+jiacheng);
        }

        dialog.share_btn.clickHandler = new Laya.Handler(this,function(){

            console.log('分享');
            //console.log('成就分享',self.CJ_data['nickname']+'在烟草传奇中获得'+config.Achievement[name].name[lv]+'成就，快去围观大神的操作吧！');
            share('成就分享',self.CJ_data['nickname']+'在烟草传奇中获得'+config.Achievement[name].name[lv]+'，快去围观大神的操作吧！');
            showShare();

        });
        dialog.popup();
    }
})();