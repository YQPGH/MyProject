/**
 * Created by 41496 on 2018/1/15.
 */
(function(){
    function tipsDialog(building)
    {
        tipsDialog.__super.call(this);
        this.building = building;
        this.content.style.leading = 10;
        this.content.style.padding = [10,0,0,0];
        this.content.style.align = 'center';
        this.content.style.color = '#4d2202';
        this.content.style.fontSize = 24;

        if(building){
            this.ok_btn.clickHandler = new Laya.Handler(this,this.onOkBtnClick);
        }else {
            this.BZTS.visible = false;
        }
    }
    Laya.class(tipsDialog,'tipsDialog',tipsUI);
    var proto = tipsDialog.prototype;

    proto.onOkBtnClick = function()
    {
        if(this.BZTS.selected){
            ZhiYinManager[this.building] = 1;
            Utils.post('Guide/close_tips',{uid:localStorage.GUID,building:this.building},null);
        }
    }
})();