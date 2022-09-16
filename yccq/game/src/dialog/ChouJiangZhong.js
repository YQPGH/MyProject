/**
 * Created by 41496 on 2017/11/2.
 */
//中级抽奖
var ChouJiangZhong = (function(_super){
    var self = null;
    function ChouJiangZhong(shopid){
        ChouJiangZhong.__super.call(this);
        self = this;
        this.shopid = 0;
        this.list_data = [];
        this.ItemList = [this.item0,this.item1,this.item2,this.item3,this.item4,this.item5,this.item6,this.item7,this.item8,this.item9,this.item10,this.item11];
        this.start_index = 0;
        this.currIndex = 0;//当前位置
        this.run_num = 0;//跑了多少圈
        this.max_run_num = 6;//跑多少圈出结果;
        this.des_num = null;

        //
        //this.setItem(0);
        this.initView();
        this.start_btn.clickHandler = new Laya.Handler(this,this.onStartBtnClick);
        //this.run();
        this.getPrizeList();
    }
    Laya.class(ChouJiangZhong,'ZY.ChouJiangZhong',_super);
    var proto = ChouJiangZhong.prototype;

    proto.getPrizeList = function()
    {
        Utils.post('prize/yan4_list',{uid:localStorage.GUID},this.onPrizeListReturn,onHttpErr);
    };

    proto.onPrizeListReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].money) > 0){
                    self.list_data.push({type:'money',num:'银元*'+res.data[i].money,count:res.data[i].money});
                }else if(Number(res.data[i].shandian) > 0){
                    self.list_data.push({type:'shandian',num:'闪电*'+res.data[i].shandian,count:res.data[i].shandian});
                }else if(Number(res.data[i].ledou) > 0){
                    self.list_data.push({type:'ledou',num:'乐豆*'+res.data[i].ledou,count:res.data[i].ledou});
                }else if(Number(res.data[i].shopid) > 0){
                    self.list_data.push({type:'shop',num:ItemInfo[res.data[i].shopid].name+'*'+res.data[i].shop_num,shopid:res.data[i].shopid,count:res.data[i].shop_num});
                }
            }
            self.initView();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.initView = function()
    {

        for(var i = 0; i < this.list_data.length; i++)
        {
            switch(this.list_data[i].type)
            {
                case 'shop':
                    this.ItemList[i].getChildByName('icon').skin = ItemInfo[this.list_data[i].shopid].thumb;
                    break;
                case 'money':
                    this.ItemList[i].getChildByName('icon').skin = 'userinfo/lebi_big.png';
                    break;
                case 'shiwu':
                    this.ItemList[i].getChildByName('icon').skin = this.list_data[i].img;
                    break;
                case 'shandian':
                    this.ItemList[i].getChildByName('icon').skin = 'userinfo/sandian.png';
                    break;
                case 'ledou':
                    this.ItemList[i].getChildByName('icon').skin = 'userinfo/ledou.png';
                    break;
            }
            this.ItemList[i].getChildByName('num').text = this.list_data[i].num;
            //this.ItemList[i].getChildByName('num').changeText('x'+this.list_data[i].num);
        }
    };

    proto.setItem = function(index)
    {
        var pre_index = (index-1 < 0)?this.ItemList.length-1:index-1;
        this.ItemList[pre_index].getChildByName('item_mask').visible = false;
        this.ItemList[index].getChildByName('item_mask').visible = true;
    };

    proto.run = function()
    {
        if(this.run_num == this.max_run_num)return;
        this.setItem(this.currIndex++);
        if(this.run_num == this.max_run_num-1 && this.des_num === null){//没返回中奖结果则继续跑
            this.run_num = 4;
        }
        if(this.run_num == this.max_run_num-1 && this.currIndex-1 == this.des_num){
            this.start_index = this.currIndex;
            this.des_num = null;
            this.start_btn.disabled = false;
            console.log('中奖'+(this.currIndex-1));
            this.timer.once(300,this,this.showResult,[this.currIndex-1]);
            if(this.currIndex == this.ItemList.length){
                this.currIndex = 0;
                this.start_index = 0;
            }
            return;
        }

        if(this.currIndex == this.ItemList.length){
            this.currIndex = 0;
        }
        if(this.start_index == this.currIndex){
            this.run_num++;
        }
        this.timer.once(100*Math.ceil((this.run_num+1) / 4),this,this.run);
    };

    proto.setYan = function(shopid)
    {
        this.shopid = shopid;
        this.selected_name.changeText(ItemInfo[shopid].name);
        this.send();
    };

    proto.onStartBtnClick = function()
    {

        this.popSelectYan();

    };

    proto.popSelectYan = function()
    {
        var dialog = new SelectYan(4,this);
        dialog.popup();
    };

    proto.send = function(){
        this.run_num = 0;
        this.start_btn.disabled = true;
        Utils.post('prize/yan4_result',{uid:localStorage.GUID,shopid:this.shopid},this.onResultReturn,onHttpErr);
    };

    proto.onResultReturn = function(res){
        console.log(res);
        if(res.code == 0){
            self.run();
            self.des_num = Number(res.data.index);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.showResult = function(index)
    {
        var text = '';
        var icon = '';
        switch(this.list_data[index].type){
            case 'shop':
                //text += ItemInfo[this.list_data[index].shopid].name;
                icon = ItemInfo[this.list_data[index].shopid].thumb;
                break;
            case 'money':
                //text += '银元';
                icon = 'userinfo/lebi_big.png';
                break;
            case 'shiwu':

                break;
            case 'shandian':
                //text += '闪电';
                icon = 'userinfo/sandian.png';
                break;
            case 'ledou':
                //text += '闪电';
                icon = 'userinfo/ledou.png';
                break;
        }
        text = this.list_data[index].num;
        var dialog = new LuckDrawResultUI();
        dialog.show_text.changeText(text);
        dialog.goods_icon.skin = icon;
        dialog.closeHandler = new Laya.Handler(this,function(){
            switch(this.list_data[index].type){
                case 'shop':
                    getItem(this.list_data[index].shopid,this.list_data[index].count);
                    break;
                case 'money':
                    getMoney(this.list_data[index].count);
                    break;
                case 'shiwu':

                    break;
                case 'shandian':
                    getShandian(this.list_data[index].count);
                    break;
                case 'ledou':
                    getBean(this.list_data[index].count);
                    break;
            }
            this.stage.getChildByName("MyGame").initUserinfo();
        });
        dialog.popup();
    }

})(ChouJiangZhongUI);