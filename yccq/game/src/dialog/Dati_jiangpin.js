/**
 * Created by 41496 on 2017/6/6.
 */
(function(){
    //答题奖励界面
    var self = null;
    function Dati_jiangpin()
    {
        Dati_jiangpin.__super.call(this);
        self = this;
        this.getResult();
    }
    Laya.class(Dati_jiangpin,"Dati_jiangpin",Dati_jianliUI);
    var proto = Dati_jiangpin.prototype;

    proto.getResult = function()
    {
        Utils.post("question/result",{uid:localStorage.GUID},this.onResultReturn);
    };

    proto.onResultReturn = function(res)
    {
        console.log(res);
        if(res.code == 0 || res.code == 1)
        {
            var text = "";
            self.title.changeText("你答对了"+res.data.right+"题,正确率为"+((Number(res.data.right)/5).toFixed(2)*100+"%")+"，获得奖励");
            if(res.data.shopid && ItemInfo[res.data.shopid])
            {
                self.item0.skin = ItemInfo[res.data.shopid].thumb;               
                self.item1.visible = true;
                text += ItemInfo[res.data.shopid].name + "*" + res.data.shop_num + "\n";
            }

            if(res.data.money > 0)
            {
                self.item3.skin = "userinfo/lebi.png";             
                text += "银元*"+res.data.money+"\n";
            }

            if(res.data.ledou > 0)
            {
                self.item3.skin = "userinfo/ledou.png";              
                text += "乐豆*"+res.data.money+"\n";
            }

            if(self.item0.skin != null && self.item3.skin != null){
                self.item1.visible = true;
                self.item2.visible = true;
                 
            }else if(self.item0.skin != null && self.item3.skin == null){               
                self.item0.x = 226;
                self.item1.x = 261;
                self.item1.visible = true;

            }else if(self.item0.skin == null && self.item3.skin != null){               
                self.item3.x = 261;
                self.item2.x = 223;
                self.item2.visible = true;   
            }          
            self.content.changeText(text);
            self.stage.getChildByName("MyGame").initUserinfo();
        }
    }
})();