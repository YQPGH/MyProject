/**
 * Created by 41496 on 2018/7/31.
 */
(function(){
    var self = null;
    function ChangeHeader()
    {
        ChangeHeader.__super.call(this);
        self = this;
        this.HeaderConfig = null;
        this.CurrHeader = Laya.stage.getChildByName('MyGame').UI.userInfo.header_frame;
        this.header_list.scrollBar.hide = true;
        this.header_list.scrollBar.elasticBackTime = 200;
        this.header_list.scrollBar.elasticDistance = 50;
        this.header_list.renderHandler = new Laya.Handler(this,this.onListUpdate);
        this.getHeaderConfig();
    }
    Laya.class(ChangeHeader,'ChangeHeader',ChangeHeaderUI);
    var proto = ChangeHeader.prototype;

    proto.getHeaderConfig = function()
    {
        Laya.loader.load("res/data/header.json",Laya.Handler.create(this,function(res){
            this.HeaderConfig = res;
            this.getHeaderList();
        }),null,Laya.Loader.JSON);
    };

    proto.getHeaderList = function()
    {

        Utils.post('user/getHeaderFrameList',{uid:localStorage.GUID},function(res){
            if(res.code == '0')
            {
                var data = res.data;
                var list_data = [];
                for(var i in self.HeaderConfig)
                {
                    var isSelected = false;
                    var clock = true;
                    if(i == self.CurrHeader) isSelected = true;
                    if(data.indexOf(Number(i)) != -1)
                    {
                        clock = false;
                    }
                    list_data.push({id:i,header:self.HeaderConfig[i].url,selected:isSelected,status:clock});
                }
				var arr = [];
				var arr1 = [];
				for(var j = 0; j < list_data.length; j++){
					if(!list_data[j].status){
						arr.push(list_data[j]);
					}else{
						arr1.push(list_data[j]);
					}
				}
				arr = arr.concat(arr1);
                self.header_list.array = arr;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    };

    proto.onListUpdate = function(cell,index)
    {
        var data = cell.dataSource;
        cell.getChildByName('selected').visible = data.selected;
        if(data.selected) this.setSelected(data.id);
        cell.getChildByName('suo').visible = data.status;
        cell.on(Laya.Event.CLICK,this,this.onItemClick,[cell,index,data.id]);
    };

    proto.onItemClick = function(cell,index,id)
    {
        this.clearSelected();
        var data = this.header_list.getItem(index);
        data.selected = true;
        this.header_list.changeItem(index,data);
        this.header_list.refresh();

        if(!cell.dataSource.status){
            Utils.post('user/setHeaderFrame',{uid:localStorage.GUID,frameid:id},function(res){
                if(res.code == '0'){
                    Laya.stage.getChildByName('MyGame').UI.setHeaderFrame(id);
                }else {
                    var dialog = new CommomConfirm(res.msg);
                    dialog.popup();
                }
            },onHttpErr);
        }

        this.setSelected(id);
    };

    proto.setSelected = function(id)
    {
        this.selected.skin = this.HeaderConfig[id].url;
        this.time.text = this.HeaderConfig[id].time;
        this.header_name.text = this.HeaderConfig[id].name;
        this.from.text = this.HeaderConfig[id].from;
    };

    proto.clearSelected = function()
    {
        var data = this.header_list.array;
        for(var i = 0; i < data.length; i++)
        {
            data[i].selected = false;
        }
        this.header_list.array = data;
    }
})();