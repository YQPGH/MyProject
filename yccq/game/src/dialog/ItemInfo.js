/**
 * Created by 41496 on 2017/7/14.
 */
(function(){
    function ItemInfoDialog(shopid)
    {
        ItemInfoDialog.__super.call(this);
        this.popupCenter = false;
        this.popupEffect = null;
        this.closeEffect = null;

        //this.item_intro.changeText(ItemInfo[shopid].description);

        this.panel.vScrollBar.hide = true;
        //this.select_details.style.color = '#ffedd5';
        this.select_details.style.fontSize = 16;
        this.select_details.style.padding = [5,0,5,0];
        switch(shopid){
            case 'money':
                this.item_name.changeText('银元');
                this.select_details.innerHTML = config.contentArr[1];
                break;
            case 'exp':
                this.item_name.changeText('经验');
                this.select_details.innerHTML = config.contentArr[3];
                break;
            case 'bean':
                this.item_name.changeText('乐豆');
                this.select_details.innerHTML = config.contentArr[2];
                break;
            case 'shandian':
                this.item_name.changeText('闪电');
                this.select_details.innerHTML = config.contentArr[0];
                break;
            default:
                this.item_name.changeText(ItemInfo[shopid].name);
                this.select_details.innerHTML = ItemInfo[shopid].description;
        }
    }
    Laya.class(ItemInfoDialog,"ItemInfoDialog",ItemInfoUI);
    var proto = ItemInfoDialog.prototype;
})();