// 消消图案类库
(function () {
    function Xiao() {
        // 普通图标
        this.images = [
            "com/dou1.png",
            "com/dou2.png",
            "com/dou3.png",
            "com/dou4.png",
            "com/dou5.png",
        ];

        // 超级图标
        this.superRowImg = "res/super1.atlas";
        this.superColImg = "res/super2.atlas";
        this.superAllImg = "res/super3.atlas";

        this.list = {
            "11": "", "12": "", "13": "", "14": "", "15": "", "16": "", "17": "",
            "21": "", "22": "", "23": "", "24": "", "25": "", "26": "", "27": "",
            "31": "", "32": "", "33": "", "34": "", "35": "", "36": "", "37": "",
            "41": "", "42": "", "43": "", "44": "", "45": "", "46": "", "47": "",
            "51": "", "52": "", "53": "", "54": "", "55": "", "56": "", "57": "",
            "61": "", "62": "", "63": "", "64": "", "65": "", "66": "", "67": "",
            "71": "", "72": "", "73": "", "74": "", "75": "", "76": "", "77": ""
        };


    }

    var proto = Xiao.prototype;
    window.Xiao = Xiao;

    // 获取没有可消的数据
    proto.randListNoSame = function () {
        this.randList();
        if (this.getSameIds(this.list).length > 0) {
            //console.log("same");
            return this.randListNoSame();
        }
        return this.list;
    };

    // 获取随机图片
    proto.randList = function () {
        for (var key in this.list) {
            var rand = Utils.getRandom(0, 4);
            this.list[key] = this.images[rand];
        }

    };

    // 找出每行每列有连续三个以上相同的id
    proto.getSameIds = function () {
        var rows = this.rowSameIds();
        var columns = this.columnSameIds();

        // 有交集优先返回
        if (rows.length && rows.length) {
            for (var i = 0; i < rows.length; i++) {
                var id = rows[i];
                if (columns.contains(id)) {
                    rows.remove(i);
                    return rows.concat(columns);
                }
            }
        }

        if (rows.length) {
            return rows;
        } else {
            return columns;
        }
    };

    // 找出每行有连续三个以上相同的id
    proto.rowSameIds = function () {
        var ids = [];
        var tempImg = "";
        var number = 0;

        for (var key in this.list) {
            if (ids.length == 0) {
                ids.push(key)
                tempImg = this.list[key];
            } else {
                if (tempImg == this.list[key]) {
                    ids.push(key)
                } else {
                    if (ids.length >= 3)  return ids;
                    ids = [];
                    ids.push(key)
                    tempImg = this.list[key];
                }
            }
            //  换行
            number++;
            if (number == 7) {
                if (ids.length >= 3) {
                    return ids;
                }
                number = 0;
                ids = [];
                tempImg = "";
            }
        }
        return ids;
    };

    // 找出每列有连续三个以上相同的id
    proto.columnSameIds = function () {
        var ids = [];
        var tempImg = "";

        for (var i = 11; i <= 17; i++) {
            for (var n = 0; n <= 60; n += 10) {
                var key = i + n;
                if (ids.length == 0) {
                    ids.push("" + key);
                    tempImg = this.list[key];
                } else {
                    if (tempImg == this.list[key]) {
                        ids.push("" + key)
                    } else {
                        if (ids.length >= 3)  return ids;
                        ids = [];
                        ids.push("" + key)
                        tempImg = this.list[key];
                    }
                }
            }

            if (ids.length >= 3) {
                return ids;
            }
            ids = [];
            tempImg = "";
        }

        return ids;
    };

    // 2下移
    proto.down = function () {


        for (var key in this.list) {

            if (this.list[key] == "" &&
                this.list[key - 10] != undefined && this.list[key - 10] != "") {
                    this.list[key] = this.list[key - 10];
                    this.list[key - 10] = "";
                    this.down();
            }
        }
    }

    // 3 更新
    proto.update = function () {
        for (var key in this.list) {
            if (this.list[key] == "") {
                var rand = Utils.getRandom(0, 4);
                this.list[key] = this.images[rand];
            }
        }
    };

    // 互换两个格子, return bool
    proto.change = function (id1, id2) {

        id1 = parseInt(id1);
        id2 = parseInt(id2);
        //console.log(id1,id2);
        if (id1 == id2) return false;

        // 跟周围的换
        var around = this.getAround(id1);

        if (around.contains(id2)) {
            //this.tempList = cloneObj(this.list);
            var tempImg = this.list[id1];
            this.list[id1] = this.list[id2];
            this.list[id2] = tempImg;

            //return true;
            
            // 有消除才可以换
            var bomList = this.getSameIds();

            if (bomList.length > 0) {
                return true;
            } else {
                //this.list = cloneObj(this.tempList);
                var tempImg = this.list[id1];
                this.list[id1] = this.list[id2];
                this.list[id2] = tempImg;
                return false;
            }
        }

        return false;
    };

    // 找出格子周围的格子, return array
    proto.getAround = function (id) {
        id = parseInt(id);
        var around = [];

        if (this.list[id - 10]) around.push(id - 10);
        if (this.list[id + 1]) around.push((id + 1));
        if (this.list[id + 10]) around.push(id + 10);
        if (this.list[id - 1]) around.push(id - 1);

        return around;
    };

    // 找出超级id所在的行和列 ids
    proto.superList = function (superID) {
        var list = [];
        if (this.list[superID] == this.superRowImg) {
            list = this.superRow(superID);
        } else if (this.list[superID] == this.superColImg) {
            list = this.superCol(superID);
        } else {
            var row = this.superRow(superID);
            var col = this.superCol(superID);
            list = row.concat(col);
        }

        return list;
    }

    // 找出超级id所在的行
    proto.superRow = function (superID) {
        var list = [];
        if (superID >= 11 && superID <= 17) list = ["11", "12", "13", "14", "15", "16", "17"];
        if (superID >= 21 && superID <= 27) list = ["21", "22", "23", "24", "25", "26", "27"];
        if (superID >= 31 && superID <= 37) list = ["31", "32", "33", "34", "35", "36", "37"];
        if (superID >= 41 && superID <= 47) list = ["41", "42", "43", "44", "45", "46", "47"];
        if (superID >= 51 && superID <= 57) list = ["51", "52", "53", "54", "55", "56", "57"];
        if (superID >= 61 && superID <= 67) list = ["61", "62", "63", "64", "65", "66", "67"];
        if (superID >= 71 && superID <= 77) list = ["71", "72", "73", "74", "75", "76", "77"];

        return list;
    }

    // 找出超级id所在的列 ids
    proto.superCol = function (superID) {
        var list = [];
        if (superID % 10 == 1) list.push("11", "21", "31", "41", "51", "61", "71");
        if (superID % 10 == 2) list.push("12", "22", "32", "42", "52", "62", "72");
        if (superID % 10 == 3) list.push("13", "23", "33", "43", "53", "63", "73");
        if (superID % 10 == 4) list.push("14", "24", "34", "44", "54", "64", "74");
        if (superID % 10 == 5) list.push("15", "25", "35", "45", "55", "65", "75");
        if (superID % 10 == 6) list.push("16", "26", "36", "46", "56", "66", "76");
        if (superID % 10 == 7) list.push("17", "27", "37", "47", "57", "67", "77");

        return list;
    }

})();