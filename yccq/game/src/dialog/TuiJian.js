/**
 * Created by 41496 on 2018/3/19.
 */
(function(){
    var self = null;
    function TuiJian()
    {
        TuiJian.__super.call(this);
        self = this;
        this.tuijian_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.tuijian_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.tuijian_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.apply_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.apply_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.apply_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.tab.selectHandler = new Laya.Handler(this,this.onTabSelected);

        this.tuijian_list.renderHandler = new Laya.Handler(this,this.updateItem);
        this.apply_list.renderHandler = new Laya.Handler(this,this.applyupdateItem);

        this.change_btn.clickHandler = new Laya.Handler(this,this.getRandomList);
        this.add_all_btn.clickHandler = new Laya.Handler(this,this.addAll);
        this.agree_all_btn.clickHandler = new Laya.Handler(this,this.agreeAll);

        this.getRandomList();
    }
    Laya.class(TuiJian,'TuiJian',TuiJianUI);
    var proto = TuiJian.prototype;

    proto.onTabSelected = function(index)
    {
        console.log(index);
        switch(index)
        {
            case 0:
                //this.getRandomList();
                break;
            case 1:
                this.getApplyList();
                break;
        }
        this.view_stack.selectedIndex = index;
    };

    proto.updateItem = function(cell, index)
    {
        cell.getChildByName('add_btn').on(Laya.Event.CLICK,this,this.onAddBtnClick,[cell.dataSource.fid]);
    };

    proto.applyupdateItem = function(cell, index)
    {
        cell.getChildByName('agree_btn').on(Laya.Event.CLICK,this,this.onAgreeBtnClick,[cell.dataSource.fid]);
        cell.getChildByName('jujue_btn').on(Laya.Event.CLICK,this,this.onJujueBtnClick,[cell.dataSource.fid]);
    };

    proto.getRandomList = function()
    {
        Utils.post('friend/randFriendList',{uid:localStorage.GUID},this.onRandomReturn,onHttpErr);
    };

    proto.onRandomReturn = function(res)
    {
        console.log(res);
        if(res.code == 0){
            var random_list = [];
            for(var i = 0; i < res.data.list.length; i++)
            {
                random_list.push({fid:res.data.list[i].fid,nickname:res.data.list[i].nickname,thumb:res.data.list[i].head_img,level:'等级:'+res.data.list[i].game_lv,login_time:'最近登录:'+res.data.list[i].last_time});
            }
            self.tuijian_list.array = random_list;
            if(res.data.is_apply > 0){
                self.has_apply.visible = true;
            }
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.getApplyList = function()
    {
        Utils.post('friend/applyList',{uid:localStorage.GUID},this.onApplyReturn,onHttpErr);
    };

    proto.onApplyReturn = function(res)
    {
        console.log(res);
        if(res.code == 0){
            var apply_list = [];
            self.has_apply.visible = false;
            for(var i = 0; i < res.data.length; i++){
                apply_list.push({fid:res.data[i].fid,nickname:res.data[i].nickname,thumb:res.data[i].head_img,level:'等级:'+res.data[i].game_lv,login_time:'最近登录:'+res.data[i].last_time});
            }
            self.apply_list.array = apply_list;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onAddBtnClick = function(fid)
    {
        console.log(fid);
        Utils.post('friend/addApply',{uid:localStorage.GUID,fid:fid},function(res){
            if(res.code == 0){
                var dialog = new CommomConfirm('你的好友添加请求已发送成功，正在等待对方确认');
                dialog.popup();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.addAll = function()
    {
        var cells = this.tuijian_list.array;
        var fids = [];
        for(var i = 0; i < cells.length; i++)
        {
            fids.push(cells[i].fid);
        }
        this.onAddBtnClick(fids.join(','));
    };

    proto.onAgreeBtnClick = function(fid)
    {
        Utils.post('friend/agreeApply',{uid:localStorage.GUID,fid:fid},function(res){
            if(res.code == 0){
                self.getApplyList();
                var dialog = new CommomConfirm('添加好友成功');
                dialog.popup();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.onJujueBtnClick = function(fid)
    {
        Utils.post('friend/refuseApply',{uid:localStorage.GUID,fid:fid},function(res){
            if(res.code == 0){
                self.getApplyList();
                var dialog = new CommomConfirm('已拒绝好友申请');
                dialog.popup();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.agreeAll = function()
    {
        var cells = this.apply_list.array;
        var fids = [];
        for(var i = 0; i < cells.length; i++)
        {
            fids.push(cells[i].fid);
        }
        this.onAgreeBtnClick(fids.join(','));
    }
})();