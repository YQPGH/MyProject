/**
 * Created by 41496 on 2018/3/7.
 */
(function(){
    function ledouTips(content,arrow)
    {
        ledouTips.__super.call(this);
        this.popupCenter = false;
        arrow = arrow?arrow:0;
        if(arrow) this.bg.skewY = 180;
        this.content.text = content;
    }
    Laya.class(ledouTips,'ledouTips',ledouTipsUI);

})();