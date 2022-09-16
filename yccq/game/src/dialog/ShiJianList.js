/**
 * Created by 41496 on 2017/11/28.
 */
//事件列表界面
(function(){
    var self = null;
    function ShiJianList()
    {
        ShiJianList.__super.call(this);
        self = this;
        this.list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.list.renderHandler = new Laya.Handler(this,this.onListRender);

        this.init();
    }
    Laya.class(ShiJianList,'ShiJianList',ShiJiangUI);
    var proto = ShiJianList.prototype;

    proto.init = function()
    {
        Utils.post('event/lists',{uid:localStorage.GUID},this.onListDataReturn,onHttpErr);

    };

    proto.onListDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0){
            var data = [];
            for(var i = 0; i < res.data.length; i++){
                data.push({id:res.data[i].id,name:'事件'+NumberToChinese(i+1),type:(res.data[i].type1 == 1)?res.data[i].type2:3,time:res.data[i].add_time,content:res.data[i].title,land_id:res.data[i].land_id});
            }
            console.log(data);
            self.list.array = data;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onListRender = function(cell,index)
    {
        cell.getChildByName('hulue').clickHandler = new Laya.Handler(this,this.onHuLueBtnClick,[cell.dataSource]);
        cell.getChildByName('chuli').clickHandler = new Laya.Handler(this,this.onChuLiBtnClick,[cell.dataSource]);
    };

    proto.onHuLueBtnClick = function(data)
    {
        var dialog = new Confirm1('如果不及时处理可能会引起烟叶的降级或损失，确定忽略吗？');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                console.log('已忽略事件id'+data.id+data.name);
                Utils.post('event/cancel',{uid:localStorage.GUID,id:data.id},this.onCancel,onHttpErr);

            }
        });
    };

    proto.onCancel = function(res)
    {
        if(res.code == 0){
            self.init();
            ShiJianManager.instance().init();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onChuLiBtnClick = function(data)
    {
        if(data.type == 1 || data.type == 2){
            var dialog = new ChuLi(data);
            dialog.popup();
            dialog.closeHandler = new Laya.Handler(this,function(){
                this.init();
                ShiJianManager.instance().init();
            });
        }else if(data.type == 3){
            var dialog = new BRDialog();
            dialog.popup();
            this.close();
        }
        //this.close();
    };
})();