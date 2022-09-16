/**
 * Created by 41496 on 2017/8/21.
 */
(function(){
    self = this;
    function QuanDialog()
    {
        QuanDialog.__super.call(this);
        self = this;
        this.selectedItem = null;
        this.list.selectEnable = true;
        this.list.selectHandler = new Laya.Handler(this,this.onItemClick);
        this.getQuanList();
    }
    Laya.class(QuanDialog,'QuanDialog',QuanDialogUI);
    var proto = QuanDialog.prototype;

    proto.getQuanList = function()
    {
        Utils.post('yan/quan_lists',{uid:localStorage.GUID},this.onListReturn,onHttpErr);
    };

    proto.onListReturn = function(res)
    {
        console.log(res);
        if(res.code == '0')
        {
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                data.push({shopid:res.data[i].shopid,num:res.data[i].total,icon:ItemInfo[res.data[i].shopid].thumb});
            }
            self.list.array = data;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onItemClick = function(index)
    {
        var cell = this.list.getCell(index);
        if(this.selectedItem){
            this.selectedItem.getChildByName('selected').visible = false;
        }
        this.selectedItem = cell;
        cell.getChildByName('selected').visible = true;
        this.intro.changeText(ItemInfo[cell.dataSource.shopid].description);
    }

})();