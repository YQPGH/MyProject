/**
 * Created by 41496 on 2017/11/1.
 */
(function(){
    var self = null;
    function JiangChi()
    {
        JiangChi.__super.call(this);
        self = this;
        this.name = 'choujiang';

        //绑定点击事件
        this.chuji_btn.clickHandler = new Laya.Handler(this,this.onChuJiBtnClick);
        this.zhongji_btn.clickHandler = new Laya.Handler(this,this.onZhongJiBtnClick);
        this.gaoji_btn.clickHandler = new Laya.Handler(this,this.onGaoJiBtnClick);
        this.duihuan.clickHandler = new Laya.Handler(this,this.onDuiHuanBtnClick);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(8);
            dialog.popup();
        });

        this.goto_btn.clickHandler = new Laya.Handler(this,function(){
            this.close();
            var dialog = new OrderListDialog();
            dialog.popup();
        });

        this.getMyJiFen();
        //this.getRecord();
    }
    Laya.class(JiangChi,'JiangChi',JiangChiUI);
    var proto = JiangChi.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.choujiang == 0)
        {
            this.tipsSetp = 0;
            this.tips.on(Laya.Event.CLICK,this,this.nextTips);
            var tips = new tipsDialog();
            tips.content.innerHTML = '欢迎来到<span color="#ae0626">幸运抽奖</span>，<br/>我们为你准备了丰厚的奖品呢！';
            tips.content.y = 100;
            tips.BZTS.visible = false;
            this.mask_1.blendMode = "destination-out";
            this.mask_2.blendMode = "destination-out";
            this.mask_3.blendMode = "destination-out";
            this.mask_4.blendMode = "destination-out";
            this.tips_4_ele.skewY = 180;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                this.nextTips();
            });
        }
    };

    proto.nextTips = function()
    {
        this.tipsSetp ++;
        if(this.tipsSetp <= 4)
        {
            this.tips.visible = true;
            if(this.tipsSetp > 1){
                this['tips_'+(this.tipsSetp-1)].visible = false;
                this['mask_'+(this.tipsSetp-1)].visible = false;
            }
            this['tips_'+this.tipsSetp].visible = true;
            this['mask_'+this.tipsSetp].visible = true;
        }else {
            this.tips.visible = false;
            var tips = new tipsDialog('choujiang');
            tips.content.innerHTML = '在乐豆9号店使用<span color="#ae0626">乐豆+品吸机会代金券</span>就可以换取一次品吸机会喔！';
            tips.content.y = 120;
            tips.popup();
        }
    };

    //获取积分
    proto.getMyJiFen = function()
    {
        Utils.post('user/detail',{uid:localStorage.GUID},this.onJiFenReturn,onHttpErr);
    };

    proto.onJiFenReturn = function(res)
    {
        if(res.code == 0)
        {
            self.jifen_num.changeText(res.data.jifen);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };
    //获取中奖纪录
    proto.getRecord = function()
    {
        Utils.post('prize/logs',{uid:localStorage.GUID},this.onRecordReturn,onHttpErr);
    };

    proto.onRecordReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                data.push({log_time:res.data[i].log_time,title:res.data[i].title});
            }
            self.list.array = data;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onChuJiBtnClick = function()
    {
        Laya.loader.load('luckdraw/jiangchi_bg.png',Laya.Handler.create(this,function(){
            var dialog = new ZY.ChouJiang();
            dialog.popup();
        }));

    };

    proto.onZhongJiBtnClick = function()
    {

        /*var dialog = new SelectYan(4);
        dialog.popup();*/
        Laya.loader.load('luckdraw/jiangchi_bg.png',Laya.Handler.create(this,function(){
            var dialog = new ZY.ChouJiangZhong();
            dialog.popup();
        }));

    };

    proto.onGaoJiBtnClick = function()
    {
        /*var dialog = new SelectYan(5);
        dialog.popup();*/
        Laya.loader.load('luckdraw/jiangchi_bg.png',Laya.Handler.create(this,function(){
            var dialog = new ZY.ChouJiangGao();
            dialog.popup();
        }));
    };

    proto.onDuiHuanBtnClick = function()
    {
        var dialog = new JiFenDuiHuan();
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,this.getMyJiFen);
    }
})();