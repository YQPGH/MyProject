/**
 * Created by 41496 on 2017/6/1.
 */
//加工厂配方选择弹出框
(function(){
    var self = null;
    function JGCPeifang(JGCDialog,index)
    {
        JGCPeifang.__super.call(this);
        self = this;
        this.name = 'jgcpeifang';
        this.JGCDialog = JGCDialog;
        this.JGCIndex = index;

        this.KCData = {};//材料库存数据；
        this.PeifangData = {'1':[],'2':[],'3':[],'4':[],'5':[]};

        this.selectedItem = null;//选中的配方
        this.selectedStatus = true;//选中配方是否满足条件

        this.tab.selectHandler = new Laya.Handler(this,this.onTabSelect);
        //this.tab.selectedIndex = 0;
        this.tab.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
            {
                ZhiYinMask.instance().setZhiYin(2);
            }
        });

        this.PF_List.renderHandler = new Laya.Handler(this, this.updateItem);


        this.CL_List.renderHandler = new Laya.Handler(this, this.CLupdateItem);

        this.CL_List.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
            {
                ZhiYinMask.instance().setZhiYin(4);
            }
        });

        //确定按钮点击事件
        this.ok_btn.clickHandler = new Laya.Handler(this,this.onOKBtnClick);

        this.getKCData();

    }
    Laya.class(JGCPeifang,"JGCPeifang",JGCPeifangUI);
    var proto = JGCPeifang.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
        {
            ZhiYinMask.instance().setZhiYin(1);
        }
    };

    proto.getKCData = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:0},this.onKCDataReturn);
    };

    proto.onKCDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            //self.KCData = res.data;
            //醇化烟叶数据
            if(res.data.yanye_chun){
                for(var i = 0,len = res.data.yanye_chun.length; i < len; i++)
                {
                    self.KCData[res.data.yanye_chun[i].shopid] = res.data.yanye_chun[i];
                }
            }

            //滤嘴数据
            if(res.data.lvzui){
                for(var i = 0,len = res.data.lvzui.length; i < len; i++)
                {
                    self.KCData[res.data.lvzui[i].shopid] = res.data.lvzui[i];
                }
            }

            //配方数据
            if(res.data.peifang){
                for(var i = 0,len = res.data.peifang.length; i < len; i++)
                {
                    if(Number(res.data.peifang[i].total))
                    {
                        self.PeifangData[res.data.peifang[i].type2].push(res.data.peifang[i]);
                    }
                }
                for(var i in self.PeifangData)
                {
                    if(self.PeifangData[i].length > 0){
                        self.tab.selectedIndex = i-1;
                    }
                }
            }


        }
    };

    //配方列表更新
    proto.updateItem = function(cell,index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shopid,false]);
    };
    //材料列表更新
    proto.CLupdateItem = function(cell,index)
    {
        if(cell.dataSource.hasNum < cell.dataSource.needNum)
        {
            cell.getChildByName('hasNum').color = "#FF0000";
            this.selectedStatus = false;
            cell.getChildByName('gou').visible = false;
        }else
        {
            cell.getChildByName('hasNum').color = "#00FF00";
            cell.getChildByName('gou').visible = true;
        }
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shopid,true]);
    };

    proto.onListItemClick = function(cell)
    {
        if(this.selectedItem) this.selectedItem.getChildByName('gou').visible = false;
        this.selectedItem = cell;
        cell.getChildByName('gou').visible = true;
        this.selectedStatus = true;
        console.log(this.KCData);
        var Peifang = ItemInfo[cell.dataSource.shopid];

        this.explain.changeText("说明：此书能制作"+ItemInfo[Peifang.mubiao].name);

        var data = [];
        if(Peifang.zhuliao_id){
            var num = 0;
            if(this.KCData[Peifang.zhuliao_id]){
                num = this.KCData[Peifang.zhuliao_id].total;
            }
            data.push({shopid:Peifang.zhuliao_id,icon:ItemInfo[Peifang.zhuliao_id].thumb,hasNum:Number(num),needNum:Peifang.zhuliao_count,name:ItemInfo[Peifang.zhuliao_id].name});
        }

        if(Peifang.fuliao1_id){
            var num = 0;
            if(this.KCData[Peifang.fuliao1_id]){
                num = this.KCData[Peifang.fuliao1_id].total;
            }
            data.push({shopid:Peifang.fuliao1_id,icon:ItemInfo[Peifang.fuliao1_id].thumb,hasNum:Number(num),needNum:Peifang.fuliao1_count,name:ItemInfo[Peifang.fuliao1_id].name});
        }

        if(Peifang.fuliao2_id){
            var num = 0;
            if(this.KCData[Peifang.fuliao2_id]){
                num = this.KCData[Peifang.fuliao2_id].total;
            }
            data.push({shopid:Peifang.fuliao2_id,icon:ItemInfo[Peifang.fuliao2_id].thumb,hasNum:Number(num),needNum:Peifang.fuliao2_count,name:ItemInfo[Peifang.fuliao2_id].name});
        }

        if(Peifang.lvzui_id){
            var num = 0;
            if(this.KCData[Peifang.lvzui_id]){
                num = this.KCData[Peifang.lvzui_id].total;
            }
            data.push({shopid:Peifang.lvzui_id,icon:ItemInfo[Peifang.lvzui_id].thumb,hasNum:Number(num),needNum:Peifang.lvzui_count,name:ItemInfo[Peifang.lvzui_id].name});
        }

        this.CL_List.array = data;

        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
        {
            ZhiYinMask.instance().setZhiYin(3);
        }
    };

    proto.onTabSelect = function(index)
    {
        console.log(index);
        if(this.selectedItem) this.selectedItem.getChildByName('gou').visible = false;
        var data = [];
        var PeifangData = this.PeifangData[index+1];
        for(var i = 0; i < PeifangData.length; i++)
        {
            data.push({shopid:PeifangData[i].shopid,icon:ItemInfo[PeifangData[i].shopid].thumb,name:ItemInfo[PeifangData[i].shopid].name,num:PeifangData[i].total});
        }

        this.PF_List.array = data;
        this.PF_List.visible = true;
        if(!this.PF_List.length){
            var text = ['一','二','三','四','五'];
            this.PF_List.getChildByName('tips').text = '还未获得'+text[index]+'星调香书，可通过'+((index > 0)?'每日挑战':'商行')+'或调香研究所获得';
            this.PF_List.getChildByName('tips').visible = true;
        }else {
            this.PF_List.getChildByName('tips').visible = false;
        }
    };

    proto.onOKBtnClick = function()
    {
        if(!this.selectedItem) return;
        if(this.selectedStatus)
        {
            this.JGCDialog.setPeifang(this.JGCIndex,this.selectedItem.dataSource);
            this.close();
            if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 1)
            {
                ZhiYinMask.instance().setZhiYin(5);
            }
        }else
        {
            ZhiYinMask.instance().close();
            var cells = this.CL_List.cells;
            var text = "目前下列材料不足:\n";
            for(var i = 0; i < cells.length; i++){
                if(cells[i].dataSource.hasNum < cells[i].dataSource.needNum){
                    text += ItemInfo[cells[i].dataSource.shopid].name+(cells[i].dataSource.needNum - cells[i].dataSource.hasNum)+'份\n';
                }
            }
            var dialog = new CommomConfirm(text);
            dialog.popup();
        }
    };




})();