/*!
 * 公共函数
 *
 * Date: 2017-1-1 by tangjian
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
     功能：计算A、B两点之间的距离。 A B为数组
     */
    ABDistance: function (A, B) {
        var len;
        len = Math.pow((B[0] - A[0]), 2) + Math.pow((B[1] - A[1]), 2);
        len = Math.floor(Math.sqrt(i));
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

    // 提交数据只后端 POST
    post: function (url, data, onComplete, onError) {
        var request = new Laya.HttpRequest();
        request.once(Laya.Event.COMPLETE, this, onRequestComplete);
        request.once(Laya.Event.ERROR, this, onError || onRequestError);
        request.send(url, Utils.urlEncode(data), 'post', 'json');

        function onRequestComplete(response) {
            onComplete(response);
        }

        function onRequestError(e) {
            console.log("请求错误:" + e);
            if(onError) onError(e);
        }
    },

    urlEncode: function (obj) {
        if (obj == null) return '';
        var urlStr = '';
        for (var key in obj) {
            urlStr += key + "=" + obj[key] + "&";
        }
        return urlStr;
    }
};


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
}

Array.prototype.removeAll = function () {
    for (var i = 0; i < this.length; ++i) {
        this.remove(i);

    }
}

Array.prototype.contains = function (obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}


var cloneObj = function (obj) {
    var str, newobj = obj.constructor === Array ? [] : {};
    if (typeof obj !== 'object') {
        return;
    } else if (window.JSON) {
        str = JSON.stringify(obj); //系列化对象
        newobj = JSON.parse(str); //还原
    } else {
        for (var i in obj) {
            newobj[i] = typeof obj[i] === 'object' ?
                cloneObj(obj[i]) : obj[i];
        }
    }

    return newobj;
};





