/**
 * Created by 41496 on 2017/9/8.
 */
//我的农场间谍派遣、查看
(function(){
    var self = null;
    function JianDieInfo()
    {
        JianDieInfo.__super.call(this);
        self = this;
        this.selected_item = null;//选中的选项
        var Lists = [this.FriendList,this.ShouHuoList];
        this.Tab = [this.tab_paiqian,this.tab_chakan];
        for(var i = 0; i < Lists.length; i++)
        {
            Lists[i].vScrollBarSkin = null;
            Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        }

        this.FriendList.renderHandler = new Laya.Handler(this,this.onFriendListRender);

        this.tab_paiqian.clickHandler = new Laya.Handler(this,this.onTabClick,[0]);
        this.tab_chakan.clickHandler = new Laya.Handler(this,this.onTabClick,[1]);

        this.btn_paiqian.clickHandler = new Laya.Handler(this,this.onPaiqianBtnClick);
        this.btn_shouqu.clickHandler = new Laya.Handler(this,this.onShouquBtnClick);

        this.tab_paiqian.selected = true;
        this.getMyFriend();
        this.timer.loop(500,this,this.countdown);

        this.closeHandler = Laya.Handler.create(this,this.onDialogClose);
    }
    Laya.class(JianDieInfo,'JianDieInfo',jiandie_infoUI);
    var proto = JianDieInfo.prototype;

    proto.onTabClick = function(index)
    {
        console.log(index);
        this.Tab[Number(!index)].selected = false;
        this.Tab[index].selected = true;
        this.ViewStack.selectedIndex = index;

        if(index){
            this.getShouRu();
        }else {
            this.getMyFriend();
        }
    };

    proto.getMyFriend = function()
    {
        Utils.post('friend/lists',{uid:localStorage.GUID},this.onFriendDataReturn,onHttpErr);
    };

    proto.onFriendDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            //console.log(res.data);
            var data = [];
            for(var i = 0; i < res.data.list.length; i++)
            {
                data.push({img:res.data.list[i].user_thumb,name:res.data.list[i].nickname,code:res.data.list[i].code});
            }
            console.log(data);
            self.FriendList.array = data;

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onFriendListRender = function(cell,index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemSelected,[cell,index]);
    };

    proto.onListItemSelected = function(cell,index)
    {
        console.log(index);
        if(this.selected_item){
            this.selected_item.getChildByName('Check').selected = false;
        }
        this.selected_item = cell;
        var CheckBox = this.selected_item.getChildByName('Check');

        CheckBox.selected = true;
    };

    proto.onPaiqianBtnClick = function()
    {
        if(this.selected_item){
            console.log(this.selected_item.dataSource.code);
            Utils.post('jiandie/start',{uid:localStorage.GUID,code:this.selected_item.dataSource.code},this.onPaiqianReturn,onHttpErr);
        }else {
            var dialog = new CommomConfirm('请选择好友');
            dialog.popup();
        }
    };

    proto.onPaiqianReturn = function(res)
    {
        console.log(res);
        if(res.code == '0')
        {
            Laya.stage.getChildByName('MyGame').JianDie.start(res.data.start_time,res.data.stop_time);
            self.close();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.countdown = function()
    {
        var JianDie = Laya.stage.getChildByName('MyGame').JianDie;
        this.CountDown.changeText(Utils.formatSeconds(JianDie.Time));
    };

    proto.onDialogClose = function()
    {
        this.timer.clear(this,this.countdown);
    };

    proto.getShouRu = function()
    {
        Utils.post('jiandie/list_shouru',{uid:localStorage.GUID},this.onShouRuDataReturn,onHttpErr);
    };

    proto.onShouRuDataReturn = function(res)
    {
        console.log(res);
        if(res.code == '0')
        {
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                data.push({shopid:res.data[i].shopid,img:res.data[i].thumb,name:res.data[i].name,time:res.data[i].add_time});
            }
            self.ShouHuoList.array = data;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onShouquBtnClick = function()
    {
        if(self.ShouHuoList.length){
            Utils.post('jiandie/to_store',{uid:localStorage.GUID},this.onShouquDataReturn,onHttpErr);
        }

    };

    proto.onShouquDataReturn = function(res)
    {
        console.log(res);
        if(res.code == '0')
        {
            var arr = self.ShouHuoList.array;
            var ids = [];
            for(var i = 0; i < arr.length; i++)
            {
                ids.push(arr[i].shopid);
            }
            getItem(ids);
            self.ShouHuoList.array = [];

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    }

})();