/**
 * Created by 41496 on 2017/6/15.
 */
(function(){
    //路边摊上架物品界面
    var self = null;
    
    function LBT_SJ(cell,index)
    {
        LBT_SJ.__super.call(this);
        self = this;
        var price_num_1 = 1;
        var price_num_2 = 2;
        this.cell = cell;//货架格子
        this.index = index;

        this.selectedItem = null;

        this.Lists = [this.List0,this.List1_0,this.List1_1,this.List1_2,this.List2,this.List3,this.List4];

        this.tabList = [this.tab_yanye1,this.tab_yanye2,this.tab_yanye3];

        for(var i = 0,len = this.Lists.length; i < len; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
            this.Lists[i].renderHandler = new Laya.Handler(this, this.updateItem);
        }

        this.tab_1.selectHandler = this.view_stack.setIndexHandler;
        for(var i = 0; i < this.tabList.length; i++)
        {
            this.tabList[i].clickHandler = new Laya.Handler(this,this.onTabClick,[i]);
        }
        this.tab_yanye1.selected = true;
        this.CheckBox1.selected = true;//勾选打广告

        //数量加减按钮事件
        this.num_sub_btn.clickHandler = new Laya.Handler(this,this.onSubBtnClick,[this.num,price_num_1]);
        this.num_add_btn.clickHandler = new Laya.Handler(this,this.onAddBtnClick,[this.num]);
        this.num_add_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.num_add_btn,this.num,this.onAddBtnClick]);
        this.num_sub_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.num_sub_btn,this.num,this.onSubBtnClick,price_num_1]);

        //价格加减按钮事件
        this.price_sub_btn.clickHandler = new Laya.Handler(this,this.onSubBtnClick,[this.price,price_num_1]);
        this.price_add_btn.clickHandler = new Laya.Handler(this,this.onAddBtnClick,[this.price,price_num_1]);
        this.price_add_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.price_add_btn,this.price,this.onAddBtnClick,price_num_1]);
        this.price_sub_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.price_sub_btn,this.price,this.onSubBtnClick,price_num_1]);
       
        this.hundred_sub_btn.clickHandler = new Laya.Handler(this,this.onSubBtnClick,[this.price,price_num_2]);
        this.hundred_add_btn.clickHandler = new Laya.Handler(this,this.onAddBtnClick,[this.price,price_num_2]);
        this.hundred_add_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.hundred_add_btn,this.price,this.onAddBtnClick,price_num_2]);
        this.hundred_sub_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.hundred_sub_btn,this.price,this.onSubBtnClick,price_num_2]);
        //出售按钮事件
        this.sale_btn.clickHandler = new Laya.Handler(this,this.onSaleBtnClick);

        this.getMyStore();
    }







    Laya.class(LBT_SJ,"LBT_SJ",LBT_SJUI);
    var proto = LBT_SJ.prototype;

    proto.getMyStore = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:0},this.onMyStoreReturn);
    };

    proto.onMyStoreReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.List0.array = self.initListData(res.data.zhongzi||[]);
            self.List1_0.array = self.initListData(res.data.yanye||[]);
            self.List1_1.array = self.initListData(res.data.yanye_kao||[]);
            self.List1_2.array = self.initListData(res.data.yanye_chun||[]);
            self.List2.array = self.initListData(res.data.peifang||[]);
            //self.List3.array = self.initListData(res.data.yan||[]);
            self.List3.array = self.initListData(res.data.lvzui||[]);
            self.view_stack.visible = true;
            self.checkList();
        }
    };

    proto.initListData = function(data)
    {
        var temp = [];
        for(var i = 0; i < data.length; i++)
        {
            if(Number(data[i].total))
            {
                temp.push({shop_id:data[i].shopid,item_id:data[i].id,name:ItemInfo[data[i].shopid].name,num:data[i].total,icon:ItemInfo[data[i].shopid].thumb});
            }
        }
        return temp;
    };

    proto.updateItem = function(cell,index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
    };

    proto.onListItemClick = function(cell)
    {
        if(this.selectedItem)
        {
            this.selectedItem.getChildByName('selected').visible = false;
        }
        this.selectedItem = cell;
        this.selectedItem.getChildByName('selected').visible = true;

        this.num.changeText('1');
        this.price.changeText('1');
       

    };

    proto.onTabClick = function(index)
    {
        for(j = 0,len=this.tabList.length; j < len; j++) {
            if(j == index)
            {
                this.tabList[j].selected = true;
            }else
            {
                this.tabList[j].selected = false;
            }
        }
        this.view_yanye.selectedIndex = index;
    };

    proto.onSubBtnClick = function(item,obj)
    {
        if(this.selectedItem)
        {
            var newNum = Number(item.text);
            if(newNum > 0){
                if (obj == 1) {
                    
                    item.changeText(newNum-1);
                }
                if(obj == 2){
                 
                   newNum = newNum-200;
                    if (newNum < 0) {
                        newNum = 0;
                    }
                    item.changeText(newNum);
                }
                
            }
        }
    };

    proto.onAddBtnClick = function(item,obj)
    {
        if(this.selectedItem)
        {
            var newNum = Number(item.text);
            if(item == this.num){

                if(newNum < this.selectedItem.getChildByName('num').text){
                    item.changeText(newNum+1);
                }
            }
            
            if(item == this.price)
            {
               
                if (obj == 1) {   
                    item.changeText(newNum+1);
                }
                if(obj == 2){ 
                    item.changeText(newNum+200);
                }
                
            }
            
        }
    };

    proto.onSaleBtnClick = function()
    {
        var num = Number(this.num.text);
        var price = Number(this.price.text);
        var ad = this.CheckBox1.selected;
        if(this.selectedItem && num && price)
        {
            var send_data = {uid:localStorage.GUID,shopid:this.selectedItem.dataSource.shop_id,total:num,money:price,ad:ad};
            Utils.post('market/start',send_data,this.onMarketStartReturn,onHttpErr);
        }
    };

    proto.onMarketStartReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.cell.parent.parent.refresh();
            self.cell.parent.parent.changeItem(self.index,{id:res.data.id,number:res.data.number,icon:ItemInfo[res.data.shopid].thumb,price:res.data.money,num:res.data.total,status:0});
            //self.cell.ItemEmpty = false;

            self.cell.getChildByName('jinbi').visible = true;
            self.cell.getChildByName('gg').visible = true;

            self.close();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onBtnPress = function(btn,label,onPress,type)
    {
        btn.timer.once(300, this, this.onHold,[btn,label,onPress,type]);
        btn.on(Laya.Event.MOUSE_UP, this, this.onBtnRelease,[btn,onPress]);
        btn.on(Laya.Event.MOUSE_OUT, this, this.onBtnRelease,[btn,onPress]);
    };

    proto.onHold = function(btn,label,onPress,type)
    {

        btn.isHold = true;
        console.log('按住');
        btn.timer.loop(100, this, onPress,[label,type]);


    };

    /** 鼠标放开后停止hold */
    proto.onBtnRelease = function(btn,onPress)
    {
        // 鼠标放开时，如果正在hold，则播放放开的效果
        if (btn.isHold)
        {
            btn.isHold = false;
            btn.timer.clear(this, onPress);
        }
        else // 如果未触发hold，终止触发hold
        {
            btn.timer.clear(this, this.onHold);
        }
        btn.off(Laya.Event.MOUSE_UP, this, this.onBtnRelease);

    };

    //检测列表是否有物品，没有则提示
    proto.checkList = function()
    {
        for(var i = 0; i < this.Lists.length; i++)
        {
            if(!this.Lists[i].length){
                this.Lists[i].getChildByName('tips').visible = true;
            }
        }
        if(this.List4.length)this.tab_1.selectedIndex = 4;
        if(this.List3.length)this.tab_1.selectedIndex = 3;
        if(this.List2.length)this.tab_1.selectedIndex = 2;
        if(this.List1_0.length || this.List1_1.length || this.List1_2.length){
            this.tab_1.selectedIndex = 1;
            if(this.List1_2.length)this.onTabClick(2);
            if(this.List1_1.length)this.onTabClick(1);
            if(this.List1_0.length)this.onTabClick(0);
        }
        if(this.List0.length)this.tab_1.selectedIndex = 0;
    }

})();