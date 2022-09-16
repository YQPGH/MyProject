/**
 * Created by 41496 on 2018/9/29.
 */
(function(){
    function ChongziFriendList(type){
        ChongziFriendList.__super.call(this);
        this.ChongziType = type;
        this.friend_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.friend_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.friend_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.friend_list.renderHandler = new Laya.Handler(this,this.onFriendListRender);

        this.init();
    }
    Laya.class(ChongziFriendList,'ChongziFriendList',chongzi_friendlistUI);
    var proto = ChongziFriendList.prototype;

    proto.init = function(){
        var self = this;
        Utils.post('Chongzi/friend_list',{uid:localStorage.GUID},function (res){
            if(res.code == 0){
                var data = [];
                for(var i = 0; i < res.data.length; i++){
                    data.push({icon:res.data[i].user_thumb,nickname:res.data[i].nickname,code:res.data[i].code});
                }
                self.friend_list.repeatY = data.length;
                self.friend_list.array = data;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.onFriendListRender = function(cell,index){
        cell.getChildByName('btn_paiqian').clickHandler = new Laya.Handler(this,this.onPaiQianBtnClick,[cell.dataSource]);
    };

    proto.onPaiQianBtnClick = function(data){
        console.log('给好友'+data.code+'派遣'+this.ChongziType+'虫子');
        var dialog = new Confirm1("即将派遣虫子到好友"+data.nickname+"的农场");
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                var self = this;
                Utils.post('Chongzi/start',{uid:localStorage.GUID,type:this.ChongziType,code:data.code},function(res){
                    if(res.code == 0){
                        self.close();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr);
            }
        });

    };
})();