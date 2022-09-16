/**
 * Created by 41496 on 2017/7/31.
 */
(function(){
    //升级实体香烟结果
    //type 1:成功 0:失败
    function ShengjiSuccess(type,data) {
        ShengjiSuccess.__super.call(this);
        this.type = type;
        switch(type)
        {
            case 1:
                break;
            case 0:
                this.bg.skin = 'pinjian/shengji_no.png';
                break;
        }

        switch(data.shopid)
        {
            case 'ledou':
                this.icon.skin = 'userinfo/ledou.png';
                break;
            case 'money':
                this.icon.skin = 'userinfo/lebi.png';
                break;
            default:
                this.icon.skin = ItemInfo[data.shopid].thumb;
        }
        this.text.changeText(data.result);
    }
    Laya.class(ShengjiSuccess,'ShengjiSuccess',ShengjiSuccessUI);
    var proto = ShengjiSuccess.prototype;
})();