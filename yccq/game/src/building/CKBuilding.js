/**
 * Created by lkl on 2017/2/14.
 */
//仓库建筑类
(function(){
    function CKBuilding(type)
    {
        CKBuilding.__super.call(this);
        this.data = null;
        this.size(265,264);
        this.initBuilding(building.CangKu,"tex/changku_text.png");
        this.pivot(Math.floor(this.width/2),200);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }

    }
    Laya.class(CKBuilding,"CKBuilding",Building);
    var proto = CKBuilding.prototype;

    proto.onClick = function()
    {
        console.log(hasScale,hasMove);
        if(hasScale) return;
        if(hasMove) return;
        var dialog = new CKDialog();
        dialog.popup();

    };

    proto.setCKData = function(data)
    {
        if(!data) return false;
        this.data = data;
        return true;
    };

    //加入物品
    proto.addItem = function(id,num,name)
    {

        if(this.data && id && num){
            if(this.data.goods[id] != undefined){
                this.data.goods[id].num = (Number(this.data.goods[id].num)+Number(num)).toString();
            }else {
                this.data.goods[id] = {goodsName:name,id:id.toString(),num:num.toString()};
            }
            console.log("%c添加物品:"+name+"ID:"+id+"数量:"+num,"color:green");
            return true;
        }
        return false;
    };

    //取出物品
    proto.subItem = function(id,num)
    {
        if(this.data && id && num){
            if(this.data.goods[id] != undefined){
                var a = Number(this.data.goods[id].num) - Number(num);
                if(a <= 0){
                    delete this.data.goods[id];
                }else {
                    this.data.goods[id].num = a.toString();
                }
                console.log("%c取出物品:ID:"+id+"数量:"+num,"color:green");
                return true;
            }
        }
        return false;
    };
})();