/*!
 * 公共函数
 *
 * Date: 2017-1-1 by tangjian 2011224816@qq.com
 */
var Utils = {

    // 获取一个随机数
    getRandom: function (lowerValue, upperValue) {
        var choices = upperValue - lowerValue + 1;
        return Math.floor(Math.random() * choices + lowerValue);
    },

    // 继承
    extends: function (Child, Parent) {
        var F = function () {
        };
        F.prototype = Parent.prototype;
        Child.prototype = new F();
        Child.prototype.constructor = Child;
    },

    /**
     功能：计算A、B两点之间的距离。 A Bwei为数组
     */
    ABDistance: function (A, B) {
        var len;
        len = Math.pow((B[0] - A[0]), 2) + Math.pow((B[1] - A[1]), 2);
        len = Math.floor(Math.sqrt(len));
        return len;
    },

    /**计算两个触摸点之间的距离*/
    getDistance: function (points) {
        var distance = 0;
        if (points && points.length == 2) {
            var dx = points[0].stageX - points[1].stageX;
            var dy = points[0].stageY - points[1].stageY;

            distance = Math.sqrt(dx * dx + dy * dy);
        }

        return distance;
    },

    post: function (url, data, onComplete, onError, agrs) {
        var request = new Laya.HttpRequest();
       
        request.http.timeout = 10000;//设置超时
        request.http.ontimeout = onHttpErr;//超时处理
        request.once(Laya.Event.COMPLETE, this, onHttpRequestComplete,[agrs]);
        request.once(Laya.Event.ERROR, this, onError || onHttpRequestError);
        request.send(config.BaseURL + url, Utils.urlEncode(data), 'post', 'json');

        function onHttpRequestComplete(agrs,response) {
            //console.log(response);
            //var result = JSON.parse(response);
            if(onComplete){
                onComplete(response,agrs);
            }
        }
        function onHttpRequestError(e) {
            console.log("请求错误:"+e);
        }
    },

    urlEncode: function (obj) {
        if (obj == null) return '';
        var urlStr = '';
        for (var key in obj) {
            urlStr += key + "=" + obj[key] + "&";
        }
        return urlStr;
    },

    formatSeconds: function (value) {
        var theTime = parseInt(value);// 秒
        var theTime1 = 0;// 分
        var theTime2 = 0;// 小时
        var theTime3 = 0;//天

        if(theTime > 60) {
            theTime1 = parseInt(theTime/60);
            theTime = parseInt(theTime%60);

            if(theTime1 > 60) {
                theTime2 = parseInt(theTime1/60);
                theTime1 = parseInt(theTime1%60);

                if(theTime2 > 24){
                    theTime3 = parseInt(theTime2/24);
                    theTime2 = parseInt(theTime2%24);
                }
            }
        }
        var result = ""+parseInt(theTime)+"秒";
        if(theTime1 > 0 || (theTime2 > 0 && theTime1 == 0)) {
            result = ""+parseInt(theTime1)+"分"+result;
        }
        if(theTime2 > 0) {
            result = ""+parseInt(theTime2)+"时"+result;
        }
        if(theTime3 > 0) {
            result = ""+parseInt(theTime3)+"天"+result;
        }
        return result;
    },

    strToTime:function(str) {
        str = str+'';
        str=str.replace(/-/g, '/');
        return Date.parse(new Date(str))/1000;
    },

    isWifi:function() {
        return Laya.Browser.userAgent.indexOf('WIFI')>-1; //indexOf() 不存在返回 -1
    }
};

/**
 通用连接失败提示
 */
function onHttpErr()
{
    var dialog = new CommomConfirm("服务器繁忙，请刷新重试");
    dialog.popup();
}

function UrlSearch(url)
{
    var name,value;
    var str=url || location.href; //取得整个地址栏
    var num=str.indexOf("?");

    var domain = str.substr(0,num+1);
    this.domain = domain;
    str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]

    var arr=str.split("&"); //各个参数放到数组里
    for(var i=0;i < arr.length;i++){
        num=arr[i].indexOf("=");
        if(num>0){
            name=arr[i].substring(0,num);
            value=arr[i].substr(num+1);
            this[name]=value;
        }
    }
}

function getItem(shopid,num)
{
    if(!shopid) return;
    num = num || 1;
    var p = new Laya.Point(70,60);//终点坐标
    if(Array.isArray(shopid)){
        for(var i = 0; i < shopid.length; i++){
            if(shopid[i] instanceof Object){
                var skin = ItemInfo[shopid[i].shopid].thumb;
                num = shopid[i].num;
                var name = ItemInfo[shopid[i].shopid].name;
            }else {
                var skin = ItemInfo[shopid[i]].thumb;
                num = 1;
                var name = ItemInfo[shopid[i]].name;
            }
            var Item = createItem(skin,num,name);
            Laya.Tween.to(Item,
                {
                    x:p.x,
                    y: p.y
                }, 2000, Laya.Ease.backIn, new Laya.Handler(this,onTweenCompile,[Item]),i*500);
            Laya.stage.addChild(Item);
        }
    }else {
        var Item = createItem(ItemInfo[shopid].thumb,num,ItemInfo[shopid].name);
        Laya.Tween.to(Item,
            {
                x:p.x,
                y: p.y
            }, 2000, Laya.Ease.backIn, new Laya.Handler(this,onTweenCompile,[Item]));
        Laya.stage.addChild(Item);
    }
}

//获得金币动画
function getMoney(num)
{
    if(!num) return;
    var p = new Laya.Point(600,40);//终点坐标

    var Item = createItem(ItemIcon.MoneyIcon,num,'银元');
    Laya.Tween.to(Item,
        {
            x:p.x,
            y: p.y
        }, 2000, Laya.Ease.backIn, new Laya.Handler(this,onTweenCompile,[Item]));
    Laya.stage.addChild(Item);
}

//获得乐豆动画
function getBean(num)
{
    if(!num) return;
    var p = new Laya.Point(800,40);//终点坐标

    var Item = createItem(ItemIcon.BeanIcon,num,'乐豆');
    Laya.Tween.to(Item,
        {
            x:p.x,
            y: p.y
        }, 2000, Laya.Ease.backIn, new Laya.Handler(this,onTweenCompile,[Item]));
    Laya.stage.addChild(Item);
}

//获得闪电动画
function getShandian(num)
{
    if(!num) return;
    var p = new Laya.Point(420,40);//终点坐标

    var Item = createItem(ItemIcon.ShandianIcon,num,'闪电');
    Laya.Tween.to(Item,
        {
            x:p.x,
            y: p.y
        }, 2000, Laya.Ease.backIn, new Laya.Handler(this,onTweenCompile,[Item]));
    Laya.stage.addChild(Item);
}

function count_shandian(time)
{
    var num = 0;
    if(time > 0 && time <= 300){
        num = Math.ceil(time / 30);
    }else if(time > 300 && time <= 600){
        num = Math.ceil(10+(time - 300) / 60);
    }else if(time > 600 && time <= 1200){
        num = Math.ceil(15+(time - 600) / 90);
    }else if(time > 1200 && time <= 3600){
        num = Math.ceil(22+(time - 1200) / 120);
    }else if(time > 3600 && time <= 7200){
        num = Math.ceil(42+(time - 3600) / 150);
    }else if(time > 7200){
        num = Math.ceil(66+(time - 7200) / 180);
    }
    return num;
}

function createItem(skin,num,name)
{
    if(!skin) return;
    var Item = new Laya.Image(skin);
    Item.anchorX = 0.5;
    Item.pos(Laya.stage.width/2,Laya.stage.height/2);
    var label = new Laya.Label(name+'X'+num);
    label.color = '#aaFF00';
    label.anchorY = 0.5;
    label.fontSize = 20;
    label.pos(Item.width,Item.height/2);
    Item.addChild(label);
    Item.zOrder = 1000;
    return Item;
}

function onTweenCompile(Item)
{
    Item.removeSelf();
}

//长按弹出物品介绍
function onItemPress (item,index,shopid,click,e)
{
    // 鼠标按下后，HOLD_TRIGGER_TIME毫秒后hold
    if(e.touches && e.touches.lenght == 2) return;
    item.timer.once(200, this, onHold,[item,shopid,click]);
    item.on(Laya.Event.MOUSE_UP, this, onItemRelease,[item,index,click]);
    item.on(Laya.Event.MOUSE_OUT, this, onItemRelease,[item,index,click]);
    item.on(Laya.Event.MOUSE_MOVE, this, onItemRelease,[item,index,click]);
}

function onHold (item,shopid,click)
{

    item.isHold = true;
    console.log('按住');

    //if(!item.ItemInfo){
        item.ItemInfo = new ItemInfoDialog(shopid);
    //}
    item.ItemInfo.show();
    var p = new Laya.Point(item.x+item.width,item.y);

    item.parent.parent.localToGlobal(p);
    if(p.y < 180){
        if(p.x < 300){
            item.ItemInfo.pivot(0,item.ItemInfo.height/4);
        }else {
            item.ItemInfo.pivot(300,item.ItemInfo.height/4);
        }

    }
    item.ItemInfo.pos(p.x,p.y);
    if(!click){
        item.off(Laya.Event.CLICK, this, this.onListItemClick);
    }

}

/** 鼠标放开后停止hold */
function onItemRelease (item,index,click)
{
    // 鼠标放开时，如果正在hold，则播放放开的效果
    if (item.isHold)
    {
        item.isHold = false;
        item.ItemInfo.close();
    }
    else // 如果未触发hold，终止触发hold
    {
        item.timer.clear(this, onHold);
        if(!click){
            item.on(Laya.Event.CLICK, this, this.onListItemClick,[item,index]);
        }

    }
    item.off(Laya.Event.MOUSE_UP, this, onItemRelease);
    item.off(Laya.Event.MOUSE_OUT, this, onItemRelease);
    item.off(Laya.Event.MOUSE_MOVE, this, onItemRelease);
}

function showItemInfo(obj,shopid)
{
    if(!obj.ItemInfo){
        obj.ItemInfo = new ItemInfoDialog(shopid);
        var point = new Laya.Point(obj.x,obj.y);
        obj.parent.localToGlobal(point);
        obj.ItemInfo.pos(point.x,point.y);
    }
    obj.ItemInfo.show();

}

function hideItemInfo(obj)
{
    if(obj.ItemInfo) obj.ItemInfo.close();
}

//指定范围的随机整数
function RandomNum(Min, Max) {
    var Range = Max - Min;
    var Rand = Math.random();
    if(Math.round(Rand * Range)==0){
        return Min + 1;
    }else if(Math.round(Rand * Max)==Max)
    {
        return Max - 1;
    }else{
        var num = Min + Math.round(Rand * Range) - 1;
        return num;
    }
}
//阿拉伯数字转中文
var chnNumChar = ["零","一","二","三","四","五","六","七","八","九"];
var chnUnitSection = ["","万","亿","万亿","亿亿"];
var chnUnitChar = ["","十","百","千"];
function NumberToChinese(num){
    var unitPos = 0;
    var strIns = '', chnStr = '';
    var needZero = false;

    if(num === 0){
        return chnNumChar[0];
    }

    while(num > 0){
        var section = num % 10000;
        if(needZero){
            chnStr = chnNumChar[0] + chnStr;
        }
        strIns = SectionToChinese(section);
        strIns += (section !== 0) ? chnUnitSection[unitPos] : chnUnitSection[0];
        chnStr = strIns + chnStr;
        needZero = (section < 1000) && (section > 0);
        num = Math.floor(num / 10000);
        unitPos++;
    }

    return chnStr;
}

function SectionToChinese(section){
    var strIns = '', chnStr = '';
    var unitPos = 0;
    var zero = true;
    while(section > 0){
        var v = section % 10;
        if(v === 0){
            if(!zero){
                zero = true;
                chnStr = chnNumChar[v] + chnStr;
            }
        }else{
            zero = false;
            strIns = chnNumChar[v];
            strIns += chnUnitChar[unitPos];
            chnStr = strIns + chnStr;
        }
        unitPos++;
        section = Math.floor(section / 10);
    }
    return chnStr;
}

//设置今日提示事件状态
function setShiJianTips() {
    var myDate = new Date();
    localStorage.setItem('shijian',myDate.toLocaleDateString());
}

//获取今日提示事件状态
function getShiJianTips() {
    var myDate = new Date();
    var flag = false;
    var shijian = localStorage.getItem('shijian');
    if(shijian != myDate.toLocaleDateString()){
        flag = true;
    }
    return flag;
}

function showShare() {
    var dialog = new shareUI();
    dialog.group = 'share';
    dialog.popupCenter = false;
    dialog.on(Laya.Event.CLICK,this,closeShare);
    dialog.popup();
}

function closeShare() {

    Dialog.manager.closeByGroup('share');
}

/**
 Removes a number of objects from the array
 @param from The first object to remove
 @param to (Optional) The last object to remove
 */
Array.prototype.remove = function (/**Number*/ from, /**Number*/ to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};

/**
 Removes a specific object from the array
 @param object The object to remove
 */
Array.prototype.removeObject = function (object) {
    for (var i = 0; i < this.length; ++i) {
        if (this[i] === object) {
            this.remove(i);
            break;
        }
    }
};

function hasClass(elem, cls) {
    cls = cls || '';
    if (cls.replace(/\s/g, '').length == 0) return false; //当cls没有参数时，返回false
    return new RegExp(' ' + cls + ' ').test(' ' + elem.className + ' ');
}

function addClass(ele, cls) {
    if (!hasClass(ele, cls)) {
        ele.className = ele.className == '' ? cls : ele.className + ' ' + cls;
    }
}

function removeClass(elem, cls) {
    if (hasClass(elem, cls)) {
        var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, '') + ' ';
        while (newClass.indexOf(' ' + cls + ' ') >= 0) {
            newClass = newClass.replace(' ' + cls + ' ', ' ');
        }
        elem.className = newClass.replace(/^\s+|\s+$/g, '');
    }
}




