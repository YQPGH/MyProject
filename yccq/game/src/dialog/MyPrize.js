/**
 * Created by 41496 on 2017/8/8.
 */
(function(){
    var self = null;
    function MyPrize()
    {
        MyPrize.__super.call(this);
        self = this;
        this.list.vScrollBarSkin = null;
        this.list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.list.renderHandler = new Laya.Handler(this, this.updateItem);

        this.dijia_list.vScrollBarSkin = null;
        this.dijia_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.dijia_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.dijia_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.dijia_list.renderHandler = new Laya.Handler(this, this.dijiaListUpdateItem);

        this.curr_page = 0;
        this.total_page = 1;

        this.pre_page.clickHandler = new Laya.Handler(this,this.onPrePageClick);
        this.next_page.clickHandler = new Laya.Handler(this,this.onNextPageClick);

        this.btn_other.clickHandler = new Laya.Handler(this,this.onTabClick,[0]);
        this.btn_dijiaquan.clickHandler = new Laya.Handler(this,this.onTabClick,[1]);

        this.onTabClick(0);

    }
    Laya.class(MyPrize,'MyPrize',AddressUI);
    var proto = MyPrize.prototype;

    proto.onTabClick = function(index)
    {
        this.view_stack.selectedIndex = index;
        if(index == 0){
            this.btn_other.selected = true;
            this.btn_dijiaquan.selected = false;
            this.getMyPrize();
        }else {
            this.btn_other.selected = false;
            this.btn_dijiaquan.selected = true;
            this.getQuan();
        }
    };

    proto.getMyPrize = function(page)
    {
        Utils.post('prize/logs',{uid:localStorage.GUID,page:page?page:0},this.onMyPrizeReturn);
    };

    proto.onMyPrizeReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                if(res.data[i].shop1){
                    data.push({icon:ItemInfo[res.data[i].shop1].thumb,name:ItemInfo[res.data[i].shop1].name,end_time:res.data[i].log_time});
                }else if(res.data[i].ledou){
                    data.push({icon:'userinfo/ledou.png',name:'乐豆*'+res.data[i].ledou,end_time:res.data[i].log_time});
                }else if(res.data[i].money){
                    data.push({icon:'userinfo/lebi_big.png',name:'银元*'+res.data[i].money,end_time:res.data[i].log_time});
                }else if(res.data[i].shandian){
                    data.push({icon:'userinfo/sandian.png',name:'闪电*'+res.data[i].shandian,end_time:res.data[i].log_time});
                }
            }
            self.list.repeatY = res.data.length+1;
            self.list.array = data;
            self.list.visible = true;
            //self.setPage(res.data.page);

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.updateItem = function(cell,index)
    {

    };

    proto.getQuan = function()
    {
        Utils.post('prize/logs_quan',{uid:localStorage.GUID},this.onQuanReturn,onHttpErr);
    };

    proto.onQuanReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {

                data.push({id:res.data[i].id,shopid:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,end_time:'有效期至：'+res.data[i].vali,status:res.data[i].status,is_overtime:res.data[i].is_overtime,url:res.data[i].url});
            }
            self.dijia_list.repeatY = res.data.length+1;
            self.dijia_list.array = data;
            self.dijia_list.visible = true;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.dijiaListUpdateItem = function(cell,index)
    {
        var btn = cell.getChildByName('goto');
        if(cell.dataSource.status == '0'){
            btn.clickHandler = new Laya.Handler(this,this.goTo,[cell]);
            if(cell.dataSource.is_overtime == '1'){
                btn.skin = 'prize/guoqi.png';
                btn.disabled = true;
            }
        }else {
            btn.skin = 'prize/duihuan.png';
            btn.disabled = true;
        }
    };

    proto.goTo = function(cell)
    {
        /*var dialog = new CommomConfirm('此功能还未开放');
        dialog.popup();*/
        if(cell.dataSource.shopid == '1601' || cell.dataSource.shopid == '1602' || cell.dataSource.shopid == '1603' || cell.dataSource.shopid == '1604' || cell.dataSource.shopid == '1605' || cell.dataSource.shopid == '1606'){
            window.location.href = config.BeanMyList;
        }else if(cell.dataSource.url == '') {
            window.location.href = config.BeanGoods;
        }else {
            window.location.href = cell.dataSource.url;
        }

    };

    proto.onBtnClick = function(data)
    {
        window.location.href = config.BaseURL+'Redirect/tel_address_view?uid='+localStorage.GUID+'&shopid='+data.shopid+'&id='+data.id;
    };

    proto.onPrePageClick = function()
    {
        if(this.curr_page == 1) return;
        this.curr_page --;
        if(this.curr_page <= 0) this.curr_page = 1;
        this.getMyPrize(this.curr_page-1);
    };

    proto.onNextPageClick = function()
    {
        if(this.curr_page == this.total_page) return;
        this.curr_page ++;
        this.getMyPrize(this.curr_page-1);
    };

    proto.setPage = function(data)
    {
        this.curr_page = data.curr_page;
        this.curr_page_text.changeText(this.curr_page);
        this.total_page = data.total_page;
        this.total_page_text.changeText(data.total_page);
    };

})();