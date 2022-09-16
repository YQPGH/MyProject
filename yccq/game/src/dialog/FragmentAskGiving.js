(function(){
    function FragmentAskGiving(){
        FragmentAskGiving.__super.call(this);

        this.giving_list.scrollBar.hide = true;
        this.giving_list.scrollBar.elasticBackTime = 200;
        this.giving_list.scrollBar.elasticDistance = 50;
        this.giving_list.renderHandler = new Laya.Handler(this,this.onListRender);
        this.sp_num = [0,0,0,0,0,0];
        this.getSPNum();


    }
    Laya.class(FragmentAskGiving,'FragmentAskGiving',FragmentGivingUI);
    var proto = FragmentAskGiving.prototype;

    //获取碎片数量
    proto.getSPNum = function() {
        var self = this;
        Utils.post('Fragment/fragment_num',{uid:localStorage.GUID},function(res){
            if(res.code == 0) {
                for(var i in res.data.list){
                    self.sp_num[Number(i)-1] = Number(res.data.list[i]);
                }
                self.getList();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },null);
    };

    proto.getList = function() {
        var self = this;
        Utils.post('Fragment/friend_list',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                var data = [];
                for(var i = 0; i < res.data.length; i++){
                    data.push({id:res.data[i].share_id,nickname:res.data[i].nickname,header:res.data[i].head_img,shopid:res.data[i].shop,done:res.data[i].receive_num,is_mysak:res.data[i].is_myask});
                }
                self.giving_list.repeatY = data.length;
                self.giving_list.array = data;
                self.giving_list.visible = true;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    };

    proto.onListRender = function(cell,index){
        var data = cell.dataSource;
        cell.getChildByName('btn_giving').clickHandler = new Laya.Handler(this,this.onBtnGivingClick,[cell,index]);
        cell.getChildByName('icon').skin = ItemInfo[data.shopid].thumb;
        cell.getChildByName('sp_name').text = ItemInfo[data.shopid].name;
        cell.getChildByName('sp_num').text = '持有：'+this.sp_num[Number(ItemInfo[data.shopid].type2)-1];
        cell.getChildByName('enable_tips').visible = true;
        if(data.done){
            cell.getChildByName('giving_num').text = "1/1";
            cell.getChildByName('progress').value = 1;
            cell.getChildByName('enable_tips').text = "已获得";
            cell.getChildByName('btn_giving').visible = false;

        }else {
            if(!this.sp_num[Number(ItemInfo[data.shopid].type2)-1]){
                cell.getChildByName('enable_tips').text = "碎片不足";
                cell.getChildByName('btn_giving').visible = false;
            }else {
                cell.getChildByName('btn_giving').visible = true;
                cell.getChildByName('enable_tips').visible = false;
            }
            if(data.is_mysak == '1'){
                cell.getChildByName('btn_giving').visible = false;
            }
        }
    };

    proto.onBtnGivingClick = function(cell,index){
        console.log(cell.dataSource.id);
        var self = this;
        var dialog = new FragmentConfirm('giving');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                Utils.post('Fragment/toSendSuipian',{uid:localStorage.GUID,share_id:cell.dataSource.id},function(res){
                    if(res.code == 0){
                        self.getSPNum();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr);
            }
        });

    };



})();