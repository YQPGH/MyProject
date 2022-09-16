'use strict';

UIConfig.closeDialogOnSide = false; // 点击其他地方关闭弹窗
UIConfig.popupBgAlpha = 0.7; // 弹窗透明度

// 通用配置类
var Config = {
    //baseURL: "http://yccq.zlongwang.com/server/",
    baseURL: "http://192.168.1.149/yccq/",
    ClientURL:"http://192.168.1.149/yccq/xxl/",
   //baseURL: "http://localhost/yccq/",
    stageWidth: 1017, // 画布宽度，高度自适应
    musicON: 0, // 音乐开1
    step:25,
    time:120,
    score: { // 分数设置，比如消除4个格子得6分
        3: 30,
        4: 50,
        5: 70,
        6: 100,
        7: 150,
        8: 180,
        9: 200,
        10: 250,
        11: 300,
        12: 350,
        13: 400,
        14: 500,
        15: 600,
        16: 700
    }
};




