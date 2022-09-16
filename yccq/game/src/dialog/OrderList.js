/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    var Event = Laya.Event;
    var self = null;
    var index_num = null;
    function OrderListDialog()
    {
        OrderListDialog.__super.call(this);
        self = this;
        this.name ='ggldialog';
        this.selectedItem = null;
        this.refresh_num = 0;//今天已刷新订单次数
        this.max_refresh_num = 3;
        this.now_time = null;//当前请求接口时间
        this.max_completed_num = 20;//当日最多完成订单数
        this.completed_num = 0;//今日完成的订单数    
        this.click_index = 0; //当前点击的订单的下标从0开始
        this.TIMIE_RUN_SECOND = [0,0,0,0,0,0];//删除的订单走了多少秒
        this.orders = [{},{},{},{},{},{}];
        this.closeHandler = new Laya.Handler(this,this.clearAllTimer);
        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(9);
            dialog.popup();
        });

        this.order_list.renderHandler = new Laya.Handler(this,this.updateItem);
        this.order_content.renderHandler = new Laya.Handler(this,this.onContentListRender);
        this.order_content.visible = false;
        this.order_award.visible = false;
        this.order_award.renderHandler = new Laya.Handler(this,this.onAwardRender);

        this.complete.clickHandler = new Laya.Handler(this,this.onComleteCLick);
        this.initData();
        
    }
    Laya.class(OrderListDialog,"OrderListDialog",OrderListUI);
    var _proto = OrderListDialog.prototype;

    _proto.onOpened = function()
    {
        if(ZhiYinManager.dingdan == 0){
            var tips = new tipsDialog('dingdan');
            var ck = new Laya.Image(building.CangKu);
            ck.scale(0.5,0.5);
            ck.pos(tips.width/2-(ck.width*ck.scaleX)/2+20,25);
            tips.addChild(ck);
            tips.content.innerHTML = '生产的物品可以在仓库售卖，但做订单能<span color="#ae0626">赚取更多银元</span>！不想做的订单可以删除，<span color="#ae0626">等待</span>一段时间或者<span color="#ae0626">使用乐豆</span>立即更新后便有新的订单。';
            tips.content.y = 140;
            tips.popup();
        }
    };

    _proto.initData = function()
    {
        Utils.post('orders/lists',{uid:localStorage.GUID},this.onInitDataReturn, onHttpErr);
    };

    _proto.onInitDataReturn = function(res)
    {
         console.log(res);
         if(res.code == '0'){
             if(res.data.list.length){
                 for(var i = 0; i < res.data.list.length; i++){
                   
                     self.orders[res.data.list[i].order_index] = {
                         id:res.data.list[i].order_id,
                         order_index:res.data.list[i].order_index,
                         next_refresh_time:res.data.list[i].next_refresh_time,
                         title:res.data.list[i].name,
                         money:res.data.list[i].money,
                         exp:res.data.list[i].game_xp,
                         shop:res.data.list[i].shop,
                         info:res.data.list[i].content
                     };
                 }
             }
             self.refresh_num = Number(res.data.refresh_num);
             self.now_time = res.time;
             self.order_list.array = self.orders;
        
         }
    };

    _proto.updateItem = function(cell, index)
    {
        if(cell.dataSource.id != 0){
            this.setOrderContent(cell,index);
        }else {
            this.setOrderDeleted(cell);
        }
    };

    _proto.setOrderContent = function(cell,index)
    { 
        

        cell.getChildByName('no_content').visible = false;
        cell.getChildByName('waiting').visible = false;
        cell.getChildByName('refresh').visible = false;
        cell.getChildByName('time').visible = false;
        cell.getChildByName('has_content').visible = true;
        cell.getChildByName('title').visible = true;
        cell.getChildByName('money').visible = true;
        cell.getChildByName('exp').visible = true;
        cell.getChildByName('del').visible = true;
        cell.getChildByName('info_btn').visible = true;
        cell.getChildByName('has_content').on(Event.CLICK,this,this.onCellClick,[cell,false]);
        cell.getChildByName('del').clickHandler = new Laya.Handler(this,this.delOrder,[index]);
        cell.getChildByName('info_btn').clickHandler = new Laya.Handler(this,this.showOrderInfo,[cell.dataSource.info]);
        if(index == 0 && index_num == null){
            this.onCellClick(cell,true);
        }
       
        
    };

    _proto.setOrderDeleted = function(cell)
    {
        cell.getChildByName('no_content').visible = false;
        cell.getChildByName('has_content').visible = false;
        cell.getChildByName('title').visible = false;
        cell.getChildByName('money').visible = false;
        cell.getChildByName('exp').visible = false;
        cell.getChildByName('del').visible = false;
        cell.getChildByName('info_btn').visible = false;
        cell.getChildByName('waiting').visible = true;
        cell.getChildByName('refresh').visible = true;
        cell.getChildByName('time').visible = true;
        var next_refresh_time = Utils.strToTime(cell.dataSource.next_refresh_time);
        var now_time = Utils.strToTime(this.now_time);
        cell.count_down_time = next_refresh_time-now_time;
        cell.getChildByName('time').changeText(Utils.formatSeconds(cell.count_down_time));
        cell.CountDown = function(){
            if(cell.count_down_time < 0){
                //console.log(cell);
                //console.log(cell.count_down_time);
                cell.timer.clear(cell,cell.CountDown);
                Utils.post('orders/sys_refresh',{uid:localStorage.GUID,order_index:cell.dataSource.order_index},function(res){
                    if(res.code == 0)
                    {
                        self.clearAllTimer();
                        self.initData();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr)
            }else {
                cell.getChildByName('time').changeText(Utils.formatSeconds(cell.count_down_time));
            }
            cell.count_down_time --;
        };
        cell.timer.loop(1000,cell,cell.CountDown);
        if(this.refresh_num < this.max_refresh_num)
        {
            cell.getChildByName('refresh').label = '刷新 0';
        }else {
            cell.getChildByName('refresh').label = '刷新 1';
        }
        cell.getChildByName('refresh').on(Laya.Event.CLICK,this,this.onRefreshClick,[cell]);
    };

    _proto.onCellClick = function(cell,ani)
    {

        //动画效果
        if(!ani){
            Laya.Tween.to(cell,
                {
                    scaleX:0.9,
                    scaleY:0.9
                }, 100, Laya.Ease.backOut,new Laya.Handler(this,function(){
                    cell.scaleX = 1;
                    cell.scaleY = 1;
                }));
        }

        //查询物品数量
        Utils.post("orders/is_order_completed",{uid:localStorage.GUID,order_index:cell.dataSource.order_index},function(res){
            if(res.code == 0){
                var shop_data = res.data;
                    self.selectItem(cell,shop_data);     
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        }, onHttpErr)
    };

    _proto.showOrderInfo = function(info)
    {
        var dialog = new OrderInfoUI();
        dialog.panel.vScrollBar.hide = true;
        dialog.content.text = info;
        dialog.popup();
    };

    _proto.selectItem = function(cell,shop_data)
    {

        if (index_num != null) {
             this.order_list.cells[index_num].getChildByName('has_content').skin = 'orderlist/order_row_bg.png';
        }
        var data = cell.dataSource;
        if(this.selectedItem) this.selectedItem.getChildByName('has_content').skin = 'orderlist/order_row_bg.png';
        this.selectedItem = cell;
        this.selectedItem.getChildByName('has_content').skin = 'orderlist/order_selected_bg.png';
        //设置标题
        this.order_title.changeText(data.title);
        //设置达成条件
        var content_data = [];
        for(var i = 0; i < data.shop.length; i++){
            content_data.push({
                icon:ItemInfo[data.shop[i].shopid].thumb,
                name:ItemInfo[data.shop[i].shopid].name,
                num:shop_data[data.shop[i].shopid]+'/'+data.shop[i].shop_count,
                shopid:data.shop[i].shopid
            });
        }

        this.order_content.array = content_data;
        this.order_content.visible = true;
        //设置达成奖励
        var award_data = [];
        award_data.push({icon:"userinfo/lebi.png",num:data.money,type:'money'});
        award_data.push({icon:"sign/exp.png",num:data.exp,type:'exp'});
        this.order_award.array = award_data;
        this.order_award.visible = true;
    };

    _proto.onAwardRender = function(cell,index)
    {
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.type,true]);
        /*cell.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[cell,cell.dataSource.type]);
        cell.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[cell]);
        cell.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[cell]);
        cell.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[cell]);*/
    };

    _proto.onContentListRender = function(cell,index)
    {
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shopid,true]);
        var arr = cell.dataSource.num.split('/');
        var num_text = cell.getChildByName('num');
        if(Number(arr[0]) < Number(arr[1]))
        {
            num_text.color = 'red';
            this.complete.skin = 'orderlist/complete_btn_2.png';
            cell.done = false;
        }else {
            num_text.color = 'green';
            this.complete.skin = 'orderlist/complete_btn_1.png';
            cell.done = true;
        }
    };

    _proto.onComleteCLick = function()
    {
        if(!this.selectedItem){
            return;
        }
        var cells = this.order_content.cells;
        var len = this.order_content.length;
        var complete = true;
        for(var i = 0; i < len; i++)
        {
            if(!cells[i].done) complete = false;
        }
        if(complete){
            console.log('达成');
            this.completeOrder();
        }else {
            console.log('未达成');
            var dialog = new CommomConfirm('该订单还所需物品不足');
            dialog.popup();
        }
    };

    //完成订单
    _proto.completeOrder = function(){

        var order_index = this.selectedItem.dataSource.order_index;
        Utils.post('orders/complete',{uid:localStorage.GUID,order_index:order_index},this.completeOrderReturn, onHttpErr);
               
    };

    _proto.completeOrderReturn = function(res){
        console.log(res);
        if (res.code != '0') {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        } else {
            var data = self.selectedItem.dataSource;
            getMoney(data.money);
            self.clearAllTimer();
            if(res.data.suipian.length){
                var suipian_tips = new FragmentGetTips(res.data.suipian);
                suipian_tips.popup()
            }
            self.initData();
            var dialog = new CommomConfirm('订单提交成功');
            dialog.popup();
        }
        Laya.stage.getChildByName("MyGame").initUserinfo();
          
    };

    //删除订单
    _proto.delOrder = function(index){
        // console.log(index);
        var dialog = new Confirm1('确定要删除该订单吗？');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(Laya.Dialog.YES == name)
            {
                var cell = this.order_list.getCell(index);
                if(cell == this.selectedItem){
                    this.clearLeft();
                }
                var order_index = cell.dataSource.order_index;
                console.log('删除订单'+order_index);
                Utils.post('orders/delete_order',{uid:localStorage.GUID,order_index:order_index},this.delOrderReturn, onHttpErr, [cell,index]);
            }
        });

        
    };

    _proto.delOrderReturn = function(res,args){
        console.log(res);
        var cell = args[0];
        var index = args[1];
         if(res.code == '0'){//
             console.log(index);
          
             //self.initData();
             self.now_time = res.time;
             self.order_list.changeItem(index,{
                 id:0,
                 order_index:cell.dataSource.order_index,
                 next_refresh_time:res.data.next_refresh_time
             });

         }else {
             var dialog = new CommomConfirm(res.msg);
             dialog.popup();
         }
    };

   //刷新订单
   _proto.onRefreshClick = function(cell){
       var num = 0;
       var text = '每日前三次刷新免费，今日还可以免费刷新'+(this.max_refresh_num - this.refresh_num) +'次';
       if(this.refresh_num >= this.max_refresh_num)
       {
           num = 1;
           text = '刷新订单需要消耗'+num+'乐豆';
       }
       var dialog = new Confirm1(text);
       dialog.popup();
       dialog.closeHandler = new Laya.Handler(this,function(name){
           if(Laya.Dialog.YES == name){
               var order_index = cell.dataSource.order_index;
               index_num = order_index;
               Utils.post('orders/refresh',{uid:localStorage.GUID,order_index:order_index},this.refreshOrderReturn, onHttpErr,cell);
           }
       });

   };

   _proto.refreshOrderReturn = function(res,cell){
        console.log(res);
        // console.log(cell);
        if(res.code == '0'){
            cell.timer.clear(cell,cell.CountDown);
            self.clearAllTimer();
            self.initData();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        self.stage.getChildByName("MyGame").initUserinfo();
   };

    //清除所有定时器
    _proto.clearAllTimer = function()
    {
        var cells = this.order_list.cells;
        for(var i = 0; i < cells.length; i++)
        {
            if(cells[i].CountDown){
                this.timer.clear(cells[i],cells[i].CountDown);
            }
        }
    };

    //清除左边条件、奖励内容
    _proto.clearLeft = function()
    {
        this.order_title.changeText('');
        this.order_content.array = [];
        this.order_award.array = [];
        this.selectedItem = null;
    }
   
})();