/*
 * 网络处理类
 *
 */

var Server = {
    user: function (uid, caller, callback) {
        Utils.post(Config.baseURL + "api/xxl/user", {uid: uid}, function (data) {
            if (data.code > 0) { // 错误
                caller.showError(data.msg);
            } else {
                callback.call(caller, data.data);
            }
        }, function (data) {
            var dialog = new ComDialog("系统错误，请稍后再试。");
            dialog.popup();
        });
    },

    startGame: function (uid, caller, callback) {
        Utils.post(Config.baseURL + "api/xxl/startGame", {uid: uid}, function (data) {
            //console.log(data);
            if (data.code > 0) { // 错误
                caller.showError(data.msg);
            } else {
                callback.call(caller, data.data);
            }
        }, function (data) {
            var dialog = new ComDialog("系统错误，请稍后再试。");
            dialog.popup();
        });
    },

    stopGame: function (data, caller, callback) {
        Utils.post(Config.baseURL + "api/xxl/stopGame", data, function (data) {
            console.log(data);
            if (data.code > 0) { // 错误
                caller.showError(data.msg);
            } else {
                callback.call(caller, data.data);
            }
        }, function (data) {
            var dialog = new ComDialog("系统错误，请稍后再试。");
            dialog.popup();
        });
    },


    updateBeans: function (uid, caller, callback) {
    
       Utils.post(Config.baseURL + "api/xxl/updateBeans",  {uid: uid}, function (data) {
           //console.log(data);
               callback.call(caller, data);
       }, function (data) {
           var dialog = new ComDialog("系统错误，请稍后再试。");
           dialog.popup();
       });
    }
};
