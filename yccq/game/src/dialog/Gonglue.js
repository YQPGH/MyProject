/**
 * Created by 41496 on 2017/9/18.
 */
(function(){
    function Gonglue()
    {
        Gonglue.__super.call(this);
        this.shengchan_btn.selected = true;
        this.jichu_btn.selected = true;
        this.jingdianshu_btn.selected = true;
        this.other_shanghang_btn.selected = true;
        //调香书按钮数组   
        this.tiaoxiangshu_btn_arr = [
                                        this.jingdianshu_btn,this.yuanshengshu_btn,this.gailiangshu_btn,this.kaxiangshu_btn,
                                        this.lvsongshu_btn,this.jiuxiangshu_btn,this.jichushu_btn
                                    ];

        //调香书图片数组
        this.tiaoxiangshu_arr = [
                                    [this.jingdianshu_5,this.jingdianshu_4,this.jingdianshu_3,this.jingdianshu_2,this.jingdianshu_1],
                                    [this.yuanshengshu_5,this.yuanshengshu_4,this.yuanshengshu_3,this.yuanshengshu_2,this.yuanshengshu_1],
                                    [this.gailiangshu_5,this.gailiangshu_4,this.gailiangshu_3,this.gailiangshu_2,this.gailiangshu_1],
                                    [this.kaxiangshu_5,this.kaxiangshu_4,this.kaxiangshu_3,this.kaxiangshu_2,this.kaxiangshu_1],
                                    [this.lvsongshu_5,this.lvsongshu_4,this.lvsongshu_3,this.lvsongshu_2,this.lvsongshu_1],
                                    [this.jiuxiangshu_5,this.jiuxiangshu_4,this.jiuxiangshu_3,this.jiuxiangshu_2,this.jiuxiangshu_1],
                                    [this.jichushu_5,this.jichushu_4,this.jichushu_3,this.jichushu_2,this.jichushu_1]
                                ];
        //调香书数组
        this.tiaoxiangshu_content_name = [
                                    this.jingdianshu_content,this.yuanshengshu_content,this.gailiangshu_content,
                                    this.kaxiangshu_content,this.lvsongshu_content,this.jiuxiangshu_content,this.jichushu_content
                                ];
        
        //调香书发光背景变量名
        this.tiaoxiang_faguang_tex = [
                                        [this.jingdianshu_faguang_01,this.jingdianshu_faguang_02,this.jingdianshu_faguang_03,this.jingdianshu_faguang_04,this.jingdianshu_faguang_05],
                                        [this.yuanshengshu_faguang_01,this.yuanshengshu_faguang_02,this.yuanshengshu_faguang_03,this.yuanshengshu_faguang_04,this.yuanshengshu_faguang_05],
                                        [this.gailiangshu_faguang_01,this.gailiangshu_faguang_02,this.gailiangshu_faguang_03,this.gailiangshu_faguang_04,this.gailiangshu_faguang_05],
                                        [this.kaxiangshu_faguang_01,this.kaxiangshu_faguang_02,this.kaxiangshu_faguang_03,this.kaxiangshu_faguang_04,this.kaxiangshu_faguang_05],
                                        [this.lvsongshu_faguang_01,this.lvsongshu_faguang_02,this.lvsongshu_faguang_03,this.lvsongshu_faguang_04,this.lvsongshu_faguang_05],
                                        [this.jiuxiangshu_faguang_01,this.jiuxiangshu_faguang_02,this.jiuxiangshu_faguang_03,this.jiuxiangshu_faguang_04,this.jiuxiangshu_faguang_05],
                                        [this.jichushu_faguang_01,this.jichushu_faguang_02,this.jichushu_faguang_03,this.jichushu_faguang_04,this.jichushu_faguang_05]
                                        
                                    ];

        //调香书描述
        this.tiaoxiangshu_content_arr = [
            ['五星云贵烟叶·醇14份\n四星巴西烟叶·醇8份\n三星吕宋烟叶·醇5份\n经典嘴棒10份',
                '四星云贵烟叶·醇14份\n三星巴西烟叶·醇8份\n二星吕宋烟叶·醇13份\n经典嘴棒10份',
                '三星云贵烟叶·醇14份\n二星巴西烟叶·醇7份\n一星吕宋烟叶·醇5份\n经典嘴棒10份',
                '二星云贵烟叶·醇14份\n一星巴西烟叶·醇12份\n一星吕宋烟叶·醇8份\n经典嘴棒10份',
                '一星云贵烟叶·醇14份\n一星巴西烟叶·醇6份\n一星吕宋烟叶·醇4份\n经典嘴棒10份'],

            ['五星巴马烟叶·醇14份\n四星津巴布韦烟叶·醇8份\n三星云贵烟叶·醇5份\n玉米颗粒嘴棒10份',
                '四星巴马烟叶·醇14份\n三星津巴布韦烟叶·醇8份\n二星云贵烟叶·醇13份\n玉米颗粒嘴棒10份',
                '三星巴马烟叶·醇14份\n二星津巴布韦烟叶·醇7份\n一星云贵烟叶·醇5份\n玉米颗粒嘴棒10份',
                '二星巴马烟叶·醇14份\n一星津巴布韦烟叶·醇12份\n一星云贵烟叶·醇8份\n玉米颗粒嘴棒10份',
                '一星巴马烟叶·醇14份\n一星津巴布韦烟叶·醇6份\n一星云贵烟叶·醇4份\n玉米颗粒嘴棒10份'],

            ['五星津巴布韦烟叶·醇14份\n四星云贵烟叶·醇8份\n三星巴西烟叶·醇5份\n活性炭嘴棒10份',
                '四星津巴布韦烟叶·醇14份\n三星云贵烟叶·醇8份\n二星巴西烟叶·醇13份\n活性炭嘴棒10份',
                '三星津巴布韦烟叶·醇14份\n二星云贵烟叶·醇7份\n一星巴西烟叶·醇5份\n活性炭嘴棒10份',
                '二星津巴布韦烟叶·醇14份\n一星云贵烟叶·醇12份\n一星巴西烟叶·醇8份\n活性炭嘴棒10份',
                '一星津巴布韦烟叶·醇14份\n一星云贵烟叶·醇6份\n一星巴西烟叶·醇4份\n活性炭嘴棒10份'],

            ['五星吕宋烟叶·醇14份\n四星津巴布韦烟叶·醇8份\n三星巴西烟叶·醇5份\n咖啡颗粒嘴棒10份',
                '四星吕宋烟叶·醇14份\n三星津巴布韦烟叶·醇8份\n二星巴西烟叶·醇13份\n咖啡颗粒嘴棒10份',
                '三星吕宋烟叶·醇14份\n二星津巴布韦烟叶·醇7份\n一星巴西烟叶·醇5份\n咖啡颗粒嘴棒10份',
                '二星吕宋烟叶·醇14份\n一星津巴布韦烟叶·醇12份\n一星巴西烟叶·醇8份\n咖啡颗粒嘴棒10份',
                '一星吕宋烟叶·醇11份\n一星津巴布韦烟叶·醇6份\n一星巴西烟叶·醇4份\n咖啡颗粒嘴棒10份'],

            ['五星吕宋烟叶·醇14份\n四星巴西烟叶·醇8份\n三星云贵烟叶·醇5份\n金花茶提取液嘴棒10份',
                '四星吕宋烟叶·醇14份\n三星巴西烟叶·醇8份\n二星云贵烟叶·醇13份\n金花茶提取液嘴棒10份',
                '三星吕宋烟叶·醇14份\n二星巴西烟叶·醇7份\n一星云贵烟叶·醇5份\n金花茶提取液嘴棒10份',
                '二星吕宋烟叶·醇14份\n一星巴西烟叶·醇12份\n一星云贵烟叶·醇8份\n金花茶提取液嘴棒10份',
                '一星吕宋烟叶·醇14份\n一星巴西烟叶·醇6份\n一星云贵烟叶·醇4份\n金花茶提取液嘴棒10份'],

            ['五星云贵烟叶·醇14份\n四星津巴布韦烟叶·醇8份\n三星巴西烟叶·醇5份\n香槟爆珠嘴棒10份',
                '四星云贵烟叶·醇14份\n三星津巴布韦烟叶·醇8份\n二星巴西烟叶·醇13份\n香槟爆珠嘴棒10份',
                '三星云贵烟叶·醇14份\n二星津巴布韦烟叶·醇7份\n一星吕宋烟叶·醇5份\n香槟爆珠嘴棒10份',
                '二星云贵烟叶·醇14份\n一星津巴布韦烟叶·醇12份\n一星巴西烟叶·醇8份\n香槟爆珠嘴棒10份',
                '一星云贵烟叶·醇14份\n一星津巴布韦烟叶·醇6份\n一星巴西烟叶·醇4份\n香槟爆珠嘴棒10份'],

            ['五星巴西烟叶·醇14份\n四星吕宋烟叶·醇8份\n三星云贵烟叶·醇5份\n一点红嘴棒10份',
                '四星巴西烟叶·醇14份\n三星吕宋烟叶·醇8份\n二星云贵烟叶·醇13份\n一点红嘴棒10份',
                '三星巴西烟叶·醇14份\n二星吕宋烟叶·醇7份\n一星云贵烟叶·醇5份\n一点红嘴棒10份',
                '二星巴西烟叶·醇14份\n一星吕宋烟叶·醇12份\n一星云贵烟叶·醇8份\n一点红嘴棒10份',
                '一星巴西烟叶·醇14份\n一星吕宋烟叶·醇6份\n一星云贵烟叶·醇4份\n一点红嘴棒10份']
        ];

        //基础流程发光背景变量名
        this.faguang_tex = [this.faguang_01,this.faguang_02,this.faguang_03,this.faguang_04,this.faguang_05,this.faguang_06,this.faguang_07];
        this.jinjie_faguang_tex = [this.jinjie_faguang_01,this.jinjie_faguang_02,this.jinjie_faguang_03,this.jinjie_faguang_04,this.jinjie_faguang_05,this.jinjie_faguang_06,this.jinjie_faguang_07,this.jinjie_faguang_08,this.jinjie_faguang_09];

        //基础流程：每个步骤描述
        this.con = ['获取调香书与生产原料：\n到真龙商行购买调香书，根据调香书介绍的原料进行购买。','种植烟叶：\n到土地处播种。','烘烤烟叶:\n要在烘烤室中完成。','醇化烟叶:\n将烘烤后的烟叶置入醇化室进行醇化。','制烟工坊:\n选择调香书，集齐所有原料开始生产。','品鉴：\n收获香烟后，要在品鉴所进行品鉴操作。','幸运抽奖：\n完成品鉴后的香烟，可以用于抽奖。'];

        this.other_shanghang_img = ['gongluenew/other_shanghang_1.png','gongluenew/other_shanghang_2.png','gongluenew/other_shanghang_3.png','gongluenew/other_shanghang_4.png'];
        this.other_zhongzhi_img = ['gongluenew/other_zhongzhi_1.png','gongluenew/other_zhongzhi_2.png','gongluenew/other_zhongzhi_3.png','gongluenew/other_zhongzhi_4.png'];
        this.other_dingdan_img = ['gongluenew/other_dingdan_1.png','gongluenew/other_dingdan_2.png','gongluenew/other_dingdan_3.png','gongluenew/other_dingdan_4.png'];
        this.other_xiaotan_img = ['gongluenew/other_xiaotan_1.png','gongluenew/other_xiaotan_2.png','gongluenew/other_xiaotan_3.png','gongluenew/other_xiaotan_4.png','gongluenew/other_xiaotan_5.png'];
        this.other_jiandie_img = ['gongluenew/other_jiandie_1.png','gongluenew/other_jiandie_2.png','gongluenew/other_jiandie_3.png','gongluenew/other_jiandie_4.png'];
        this.other_chengjiu_img = ['gongluenew/other_chengjiu_1.png','gongluenew/other_chengjiu_2.png'];
        this.other_haoyou_img = ['gongluenew/other_haoyou_1.png','gongluenew/other_haoyou_2.png','gongluenew/other_haoyou_3.png','gongluenew/other_haoyou_4.png'];
        this.other_tufa_img = ['gongluenew/other_tufa_1.png','gongluenew/other_tufa_2.png','gongluenew/other_tufa_3.png','gongluenew/other_tufa_4.png'];
        
        //调香图鉴、生产流程、其他模块之间的切换
        this.tujian_btn.clickHandler = new Laya.Handler(this,this.onTabBtnClick,[0]);
        this.shengchan_btn.clickHandler = new Laya.Handler(this,this.onTabBtnClick,[1]);
        this.other_btn.clickHandler = new Laya.Handler(this,this.onTabBtnClick,[2]);

        //调香图鉴：七个调香书之间的切换
        this.jingdianshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[0]);
        this.yuanshengshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[1]);
        this.gailiangshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[2]);
        this.kaxiangshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[3]);
        this.lvsongshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[4]);
        this.jiuxiangshu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[5]);
        this.jichushu_btn.clickHandler = new Laya.Handler(this,this.onTuJianTabBtnClick,[6]);
        
        //每个调香书绑定点击事件
        for(var i=0; i<this.tiaoxiangshu_arr.length; i++){
            for(var j=0; j<5; j++){
                this.tiaoxiangshu_arr[i][j].on(Laya.Event.CLICK,this,this.onTuJianImgClick,[i,j]);
            }        
        };
        
        //生产流程：基础流程和进阶流程之间的切换
        this.jichu_btn.clickHandler = new Laya.Handler(this,this.onSCTabBtnClick,[0]);
        this.jinjie_btn.clickHandler = new Laya.Handler(this,this.onSCTabBtnClick,[1]);
        
        //绑定立即前往按钮
        this.goto_arr = [this.goto_btn_1,this.goto_btn_2,this.goto_btn_3,this.goto_btn_4,this.goto_btn_5,this.goto_btn_6,this.goto_btn_7];
        for(var i=0; i<this.goto_arr.length; i++){
            this.goto_arr[i].clickHandler = new Laya.Handler(this,this.onGotoBtnClick,[i]);
        }

        //基础流程：绑定基础流程的每个步骤图片点击事件
        this.shanghang_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[0]);
        this.zhongzhi_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[1]);
        this.hongkao_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[2]);
        this.chunhua_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[3]);
        this.zhiyan_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[4]);
        this.pinjian_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[5]);
        this.choujiang_btn.on(Laya.Event.CLICK,this,this.onJiChuImgClick,[6]);

        this.detail_img = [this.yanjiusuo,this.youleyuan,this.qiandao,this.dati];
        this.detail_text = [this.yanjiusuo_text,this.youleyuan_text,this.qiandao_text,this.dati_text];

        this.detail_img_2 = [this.peiyuzhongxin,this.shenmi,this.youleyuan_2,this.qiandao_2,this.dati_2];
        this.detail_text_2 = [this.peiyuzhongxin_text,this.shenmi_text,this.youleyuan_text_2,this.qiandao_text_2,this.dati_text_2];

        //进阶流程：绑定每个步骤图片点击事件
        this.get_tiaoxiangshu.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[0]);
        this.chakanpeifang.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[1]);
        this.get_zhongzi.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[2]);
        this.zhongzhi.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[3]);
        this.hongkao.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[4]);
        this.chunhua.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[5]);
        this.zhiyan.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[6]);
        this.pinjian.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[7]);
        this.choujiang.on(Laya.Event.CLICK,this,this.onJinJieImgClick,[8]);

        //进阶流程：绑定详细步骤下面的图片的点击事件
        this.yanjiusuo.on(Laya.Event.CLICK,this,this.onJinJieDetImgClick,[0]);
        this.youleyuan.on(Laya.Event.CLICK,this,this.onJinJieDetImgClick,[1]);
        this.qiandao.on(Laya.Event.CLICK,this,this.onJinJieDetImgClick,[2]);
        this.dati.on(Laya.Event.CLICK,this,this.onJinJieDetImgClick,[3]);

        //进阶流程：绑定详细步骤下面的图片的点击事件
        this.peiyuzhongxin.on(Laya.Event.CLICK,this,this.onJinJieDet2ImgClick,[0]);
        this.shenmi.on(Laya.Event.CLICK,this,this.onJinJieDet2ImgClick,[1]);
        this.youleyuan_2.on(Laya.Event.CLICK,this,this.onJinJieDet2ImgClick,[2]);
        this.qiandao_2.on(Laya.Event.CLICK,this,this.onJinJieDet2ImgClick,[3]);
        this.dati_2.on(Laya.Event.CLICK,this,this.onJinJieDet2ImgClick,[4]);

        //返回按钮
        this.go_back_btn.on(Laya.Event.CLICK,this,this.onGoBackBtnClick);
        this.go_back_btn_2.on(Laya.Event.CLICK,this,this.onGoBackBtn2Click);

        //进阶：获取调香书里的前往按钮
        this.shu_goto_btn_arr = [this.shu_goto_btn_1,this.shu_goto_btn_2,this.shu_goto_btn_3,this.shu_goto_btn_4];
        for(var i=0; i<this.shu_goto_btn_arr.length; i++){
            this.shu_goto_btn_arr[i].on(Laya.Event.CLICK,this,this.onShuGotoBtnClick,[i]);
        }

        this.zz_goto_btn_arr = [this.zz_goto_btn_1,this.zz_goto_btn_2,this.zz_goto_btn_3,this.zz_goto_btn_4,this.zz_goto_btn_5];
        for(var i=0; i<this.zz_goto_btn_arr.length; i++){
            this.zz_goto_btn_arr[i].on(Laya.Event.CLICK,this,this.onZzGotoBtnClick,[i]);
        }

        this.qita_goto_btn_arr = [this.zhongzhi_goto,this.hongkao_goto,this.chunhua_goto,this.zhiyan_goto,this.pinjian_goto,this.choujiang_goto];
        for(var i=0; i<this.qita_goto_btn_arr.length; i++){
            this.qita_goto_btn_arr[i].on(Laya.Event.CLICK,this,this.onQitaGotoBtnClick,[i]);
        }

        //其他模块发光背景数组
        this.other_btn_arr = [
                                this.other_shanghang_btn,this.other_zhongzhi_btn,this.other_dingdan_btn,this.other_xiaotan_btn,
                                this.other_jiandie_btn,this.other_chengjiu_btn,this.other_haoyou_btn
                            ];

        //其他模块之间的切换
        this.other_shanghang_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[0]);
        this.other_zhongzhi_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[1]);
        this.other_dingdan_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[2]);
        this.other_xiaotan_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[3]);
        this.other_jiandie_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[4]);
        this.other_chengjiu_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[5]);
        this.other_haoyou_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[6]);
        this.other_tufa_btn.clickHandler = new Laya.Handler(this,this.onOtherTabBtnClick,[7]);

        //其他模块，点击上下按钮
        this.shanghang_pre_btn.on(Laya.Event.CLICK,this,this.onChangeShangHangImgClick_1);
        this.shanghang_next_btn.on(Laya.Event.CLICK,this,this.onChangeShangHangImgClick_2);

        this.zhongzhi_pre_btn.on(Laya.Event.CLICK,this,this.onChangeZhongZhiImgClick_1);
        this.zhongzhi_next_btn.on(Laya.Event.CLICK,this,this.onChangeZhongZhiImgClick_2);

        this.dingdan_pre_btn.on(Laya.Event.CLICK,this,this.onChangeDingDanImgClick_1);
        this.dingdan_next_btn.on(Laya.Event.CLICK,this,this.onChangeDingDanImgClick_2);

        this.xiaotan_pre_btn.on(Laya.Event.CLICK,this,this.onChangeXiaoTanImgClick_1);
        this.xiaotan_next_btn.on(Laya.Event.CLICK,this,this.onChangeXiaoTanImgClick_2);

        this.jiandie_pre_btn.on(Laya.Event.CLICK,this,this.onChangeJianDieImgClick_1);
        this.jiandie_next_btn.on(Laya.Event.CLICK,this,this.onChangeJianDieImgClick_2);

        this.chengjiu_pre_btn.on(Laya.Event.CLICK,this,this.onChangeChengJiuImgClick_1);
        this.chengjiu_next_btn.on(Laya.Event.CLICK,this,this.onChangeChengJiuImgClick_2);

        this.haoyou_pre_btn.on(Laya.Event.CLICK,this,this.onChangeHaoYouImgClick_1);
        this.haoyou_next_btn.on(Laya.Event.CLICK,this,this.onChangeHaoYouImgClick_2);

        this.tufa_pre_btn.on(Laya.Event.CLICK,this,this.onChangeTuFaImgClick_1);
        this.tufa_next_btn.on(Laya.Event.CLICK,this,this.onChangeTuFaImgClick_2);

    }
    Laya.class(Gonglue,'Gonglue',gongluenewUI);
    var proto = Gonglue.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.gonglue == 0)
        {
            var tips = new tipsDialog('gonglue');
            tips.content.innerHTML = '好好查看攻略，我们在每个环节界面上加入了<span color="#ae0626">“帮助”按钮</span>，可以随时查看！努力升级，生产更多香烟，达到一定等级还有新的体验等着你！';
            tips.content.y = 80;
            tips.popup();
        }
    };

    proto.onTabBtnClick = function(index){
        console.log('index=',index);
        this.viewstack_1.selectedIndex = index;
        switch(index){
            case 0 :
            this.tujian_btn.selected = true;
            this.shengchan_btn.selected = false;
            this.other_btn.selected = false;
            break;
            case 1 :
            this.tujian_btn.selected = false;
            this.shengchan_btn.selected = true;
            this.other_btn.selected = false;
            break;
            case 2 :
            this.tujian_btn.selected = false;
            this.shengchan_btn.selected = false;
            this.other_btn.selected = true;
            this.other_panel.hScrollBar.hide = true;//隐藏列表的滚动条。
            break;
        }

    };

    proto.onTuJianTabBtnClick = function(index){
        console.log('图鉴=',index);
        this.tujian_viewstack.selectedIndex = index;
        for(var k=0;k<this.tiaoxiang_faguang_tex[index].length;k++){
            if(k==0){
                this.tiaoxiang_faguang_tex[index][0].visible = true;
            }else{
                this.tiaoxiang_faguang_tex[index][k].visible = false;
            } 
        }
        
        for(var i=0; i<this.tiaoxiangshu_btn_arr.length; i++){
            if(i==index){
                console.log('选中');
                this.tiaoxiangshu_btn_arr[index].selected = true;
            }else{
                this.tiaoxiangshu_btn_arr[i].selected = false;
            }
        }
    };

    proto.onTuJianImgClick = function(i,j){
        console.log('i=',i);
        console.log('j=',j);
        for(var k=0;k<this.tiaoxiang_faguang_tex[i].length;k++){
            if(k==j){
                this.tiaoxiang_faguang_tex[i][j].visible = true;
            }else{
                this.tiaoxiang_faguang_tex[i][k].visible = false;
            }   
        }
        this.tiaoxiangshu_content_name[i].text = this.tiaoxiangshu_content_arr[i][j];
    };

    proto.onSCTabBtnClick = function(index){
        console.log('index2=',index);
        this.shengchan_viewstack.selectedIndex = index;
        switch(index){
            case 0 :
            this.jichu_btn.selected = true;
            this.jinjie_btn.selected = false;
            break;
            case 1 :
            this.jichu_btn.selected = false;
            this.jinjie_btn.selected = true;
            this.jinjie_panel.hScrollBar.hide = true;//隐藏列表的滚动条。
            break;
        }
    };

    proto.onJiChuImgClick = function(index){
        console.log('jichu=',index);
        this.jichu_detail_viewstack.selectedIndex = index;
        for(var i=0;i<this.faguang_tex.length;i++){
            if(i==index){
                this.faguang_tex[index].visible = true;
            }else{
                this.faguang_tex[i].visible = false;
            }
            
        }
        // console.log('index3=',index);
        //this.content.text = this.con[index];
    };

    proto.onJinJieImgClick = function(index){
        console.log('jinjie=',index);
        this.jinjie_detail_viewstack.selectedIndex = index;
        for(var i=0;i<this.jinjie_faguang_tex.length;i++){
            if(i==index){
                this.jinjie_faguang_tex[index].visible = true;
            }else{
                this.jinjie_faguang_tex[i].visible = false;
            }
            
        }
    };

    proto.onJinJieDetImgClick = function(index){
        this.detail_text[index].visible = true;
        this.go_back_btn.visible = true;
        for(var i=0; i<this.detail_img.length; i++){
            console.log(i);
            this.detail_img[i].visible = false;
        }
        for(var j=0; j<this.shu_goto_btn_arr.length; j++){
            console.log(j);
            if(j==index){
                this.shu_goto_btn_arr[j].visible = true;
            }else{
                this.shu_goto_btn_arr[j].visible =false;
            }
        }
    }

    proto.onJinJieDet2ImgClick = function(index){
        this.detail_text_2[index].visible = true;
        this.go_back_btn_2.visible = true;
        for(var i=0; i<this.detail_img_2.length; i++){
            console.log(i);
            this.detail_img_2[i].visible = false;
        }
        for(var j=0; j<this.zz_goto_btn_arr.length; j++){
            console.log(j);
            if(j==index){
                this.zz_goto_btn_arr[j].visible = true;
            }else{
                this.zz_goto_btn_arr[j].visible =false;
            }
        }
    }

    proto.onGoBackBtnClick = function(){
        //this.detail_text[index].visible = false;
        for(var i=0; i<this.detail_img.length; i++){
            this.detail_img[i].visible = true;
        }
        for(var j=0; j<this.detail_text.length; j++){
            this.detail_text[j].visible = false;
        }
        this.go_back_btn.visible = false;
        for(var j=0; j<this.shu_goto_btn_arr.length; j++){
            this.shu_goto_btn_arr[j].visible = false;
        }
    }

    proto.onGoBackBtn2Click = function(){
        //this.detail_text[index].visible = false;
        for(var i=0; i<this.detail_img_2.length; i++){
            this.detail_img_2[i].visible = true;
        }
        for(var j=0; j<this.detail_text_2.length; j++){
            this.detail_text_2[j].visible = false;
        }
        this.go_back_btn_2.visible = false;
        for(var j=0; j<this.zz_goto_btn_arr.length; j++){
            this.zz_goto_btn_arr[j].visible = false;
        }
    }

    proto.onOtherTabBtnClick = function(index){
        console.log('other=',index);
        this.other_viewstack.selectedIndex = index;
        for(var i=0; i<this.other_btn_arr.length; i++){
            if(i==index){
                console.log('选中');
                this.other_btn_arr[index].selected = true;
            }else{
                this.other_btn_arr[i].selected = false;
            }
        }
    };

    proto.onChangeShangHangImgClick_1 = function(){
        this.shanghang_next_btn.visible = true;
        var key = this.other_shanghang.name;
        key = parseInt(key)-1;
        this.other_shanghang.name = key;
        if( parseInt(key) == 0){
            this.shanghang_pre_btn.visible = false;
        }
        this.other_shanghang.skin = this.other_shanghang_img[key];
    };

    proto.onChangeShangHangImgClick_2 = function(){
        this.shanghang_pre_btn.visible = true;
        var key = this.other_shanghang.name;
        key = parseInt(key)+1;
        this.other_shanghang.name = key;
        if( parseInt(key) == this.other_shanghang_img.length-1){
            this.shanghang_next_btn.visible = false;
        }
        this.other_shanghang.skin = this.other_shanghang_img[key];
    };

    proto.onChangeZhongZhiImgClick_1 = function(){
        this.zhongzhi_next_btn.visible = true;
        var key = this.other_zhongzhi.name;
        key = parseInt(key)-1;
        this.other_zhongzhi.name = key;
        if( parseInt(key) == 0){
            this.zhongzhi_pre_btn.visible = false;
        }
        this.other_zhongzhi.skin = this.other_zhongzhi_img[key];
    };

    proto.onChangeZhongZhiImgClick_2 = function(){
        this.zhongzhi_pre_btn.visible = true;
        var key = this.other_zhongzhi.name;
        key = parseInt(key)+1;
        this.other_zhongzhi.name = key;
        if( parseInt(key) == this.other_zhongzhi_img.length-1){
            this.zhongzhi_next_btn.visible = false;
        }
        this.other_zhongzhi.skin = this.other_zhongzhi_img[key];
    };

    proto.onChangeDingDanImgClick_1 = function(){
        this.dingdan_next_btn.visible = true;
        var key = this.other_dingdan.name;
        key = parseInt(key)-1;
        this.other_dingdan.name = key;
        if( parseInt(key) == 0){
            this.dingdan_pre_btn.visible = false;
        }
        this.other_dingdan.skin = this.other_dingdan_img[key];
    };

    proto.onChangeDingDanImgClick_2 = function(){
        this.dingdan_pre_btn.visible = true;
        var key = this.other_dingdan.name;
        key = parseInt(key)+1;
        this.other_dingdan.name = key;
        if( parseInt(key) == this.other_dingdan_img.length-1){
            this.dingdan_next_btn.visible = false;
        }
        this.other_dingdan.skin = this.other_dingdan_img[key];
    };

    proto.onChangeXiaoTanImgClick_1 = function(){
        this.xiaotan_next_btn.visible = true;
        var key = this.other_xiaotan.name;
        key = parseInt(key)-1;
        this.other_xiaotan.name = key;
        if( parseInt(key) == 0){
            this.xiaotan_pre_btn.visible = false;
        }
        this.other_xiaotan.skin = this.other_xiaotan_img[key];
    };

    proto.onChangeXiaoTanImgClick_2 = function(){
        this.xiaotan_pre_btn.visible = true;
        var key = this.other_xiaotan.name;
        key = parseInt(key)+1;
        this.other_xiaotan.name = key;
        if( parseInt(key) == this.other_xiaotan_img.length-1){
            this.xiaotan_next_btn.visible = false;
        }
        this.other_xiaotan.skin = this.other_xiaotan_img[key];
    };

    proto.onChangeJianDieImgClick_1 = function(){
        this.jiandie_next_btn.visible = true;
        var key = this.other_jiandie.name;
        key = parseInt(key)-1;
        this.other_jiandie.name = key;
        if( parseInt(key) == 0){
            this.jiandie_pre_btn.visible = false;
        }
        this.other_jiandie.skin = this.other_jiandie_img[key];
    };

    proto.onChangeJianDieImgClick_2 = function(){
        this.jiandie_pre_btn.visible = true;
        var key = this.other_jiandie.name;
        key = parseInt(key)+1;
        this.other_jiandie.name = key;
        if( parseInt(key) == this.other_jiandie_img.length-1){
            this.jiandie_next_btn.visible = false;
        }
        this.other_jiandie.skin = this.other_jiandie_img[key];
    };

    proto.onChangeChengJiuImgClick_1 = function(){
        this.chengjiu_next_btn.visible = true;
        var key = this.other_chengjiu.name;
        key = parseInt(key)-1;
        this.other_chengjiu.name = key;
        if( parseInt(key) == 0){
            this.chengjiu_pre_btn.visible = false;
        }
        this.other_chengjiu.skin = this.other_chengjiu_img[key];
    };

    proto.onChangeChengJiuImgClick_2 = function(){
        this.chengjiu_pre_btn.visible = true;
        var key = this.other_chengjiu.name;
        key = parseInt(key)+1;
        this.other_chengjiu.name = key;
        if( parseInt(key) == this.other_chengjiu_img.length-1){
            this.chengjiu_next_btn.visible = false;
        }
        this.other_chengjiu.skin = this.other_chengjiu_img[key];
    };

    proto.onChangeHaoYouImgClick_1 = function(){
        this.haoyou_next_btn.visible = true;
        var key = this.other_haoyou.name;
        key = parseInt(key)-1;
        this.other_haoyou.name = key;
        if( parseInt(key) == 0){
            this.haoyou_pre_btn.visible = false;
        }
        this.other_haoyou.skin = this.other_haoyou_img[key];
    };

    proto.onChangeHaoYouImgClick_2 = function(){
        this.haoyou_pre_btn.visible = true;
        var key = this.other_haoyou.name;
        key = parseInt(key)+1;
        this.other_haoyou.name = key;
        if( parseInt(key) == this.other_haoyou_img.length-1){
            this.haoyou_next_btn.visible = false;
        }
        this.other_haoyou.skin = this.other_haoyou_img[key];
    };

    proto.onChangeTuFaImgClick_1 = function(){
        this.tufa_next_btn.visible = true;
        var key = this.other_tufa.name;
        key = parseInt(key)-1;
        this.other_tufa.name = key;
        if( parseInt(key) == 0){
            this.tufa_pre_btn.visible = false;
        }
        this.other_tufa.skin = this.other_tufa_img[key];
    };

    proto.onChangeTuFaImgClick_2 = function(){
        this.tufa_pre_btn.visible = true;
        var key = this.other_tufa.name;
        key = parseInt(key)+1;
        this.other_tufa.name = key;
        if( parseInt(key) == this.other_tufa_img.length-1){
            this.tufa_next_btn.visible = false;
        }
        this.other_tufa.skin = this.other_tufa_img[key];
    };


    proto.onGotoBtnClick = function(index){
        console.log('前往=',index);
        switch(index){
            case 0 :
                var dialog = new ZLDialog();
                dialog.popup();
            break;
            case 1 :
                this.close();
                this.map = Laya.stage.getChildByName('MyGame').map;
                this.map.mapMoveTo(18,30);
            break;
            case 2 :
                var dialog = new BRDialog();
                dialog.popup();
            break;
            case 3 :
                var dialog = new ARDialog();
                dialog.popup();
            break;
            case 4 :
                var dialog = new JGCDialog();
                dialog.popup();
            break;
            case 5 :
                var dialog = new PinjianDialog();
                dialog.popup();
            break;
            case 6 :
                var dialog = new JiangChi();
                dialog.popup();
            break;
        }
        //this.close();
    }

    proto.onShuGotoBtnClick = function(index){
        switch(index){
            case 0 :
                this.stage.getChildByName('MyGame').YJS.event('click');
            //var dialog = new YJSDialog();
            //dialog.popup();
            break;
            case 1 :
            var dialog = new YouLeChangDialog();
            dialog.popup();
            break;
            case 2 :
            var dialog = new SignInDialog();
            dialog.popup();
            break;
            case 3 :
            var dialog = new YouLeChangDialog();
            dialog.popup();
            break;
        }
        this.close();
    }

    proto.onZzGotoBtnClick = function(index){
        switch(index){
            case 0 :
                this.stage.getChildByName('MyGame').Peiyushi.event('click');
            //var dialog = new PeiyushiDialog();
            //dialog.popup();
            break;
            case 1 :
            var dialog = new ZLDialog();
            dialog.popup();
            break;
            case 2 :
            var dialog = new YouLeChangDialog();
            dialog.popup();
            break;
            case 3 :
            var dialog = new SignInDialog();
            dialog.popup();
            break;
            case 4 :
            var dialog = new YouLeChangDialog();
            dialog.popup();
            break;
        }
        this.close();
    }

    proto.onQitaGotoBtnClick = function(index){
        switch(index){
            case 0 :
            this.map = Laya.stage.getChildByName('MyGame').map;
            this.map.mapMoveTo(18,30);
            break;
            case 1 :
            var dialog = new BRDialog();
            dialog.popup();
            break;
            case 2 :
            var dialog = new ARDialog();
            dialog.popup();
            break;
            case 3 :
            var dialog = new JGCDialog();
            dialog.popup();
            break;
            case 4 :
            var dialog = new PinjianDialog();
            dialog.popup();
            break;
            case 5 :
            var dialog = new JiangChi();
            dialog.popup();
            break;
        }
        this.close();
    }
    
})();