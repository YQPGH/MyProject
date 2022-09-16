/*
* name;
*/
(function () {
    function GuideBook(tab_index) {
        GuideBook.__super.call(this);
        this.tab_index = tab_index?tab_index:0;
        this.curr_page_num = 1;
        this.curr_tab_num = this.tab_index+1;
        this.curr_page_counts = ['', 2, 4, 7, 3, 4, 6, 3, 2, 6, 4, 4, 7];
        this.ren_imgs = ['', 'zhanggui', 'yannong_nv', 'gongre_nv', 'gongre_nv',  'pinjian_nan', 'gongre_nan', 'pinjian_nan', 'pinjian_nan', 'pinjian_nv', 'yannong_nan', 'yannong_nan', 'yannong_nan'];
        this.panel.hScrollBar.hide = true;
        if(this.tab_index > 6) {
            this.panel.hScrollBar.setScroll(0,1000,1000);
        }
        this.init();
        
    }
    Laya.class(GuideBook,"GuideBook",GuideBookUI);
    var _proto = GuideBook.prototype;

    _proto.init = function(){
        this.guide_tab.selectedIndex = this.tab_index;
        this.pre_page.on(Laya.Event.CLICK, this, this.changePage, ['pre']);
        this.next_page.on(Laya.Event.CLICK, this, this.changePage, ['next']);
        this.guide_tab.selectHandler = new Laya.Handler(this, this.changeTab);
        this.guide_img.skin = 'guidebook/' + this.curr_tab_num + '_' + this.curr_page_num + '.png';
        this.pages_count.changeText( this.curr_page_counts[this.curr_tab_num] );
        this.curr_page.changeText(this.curr_page_num);
        this.ren.skin = 'guidebook/' + this.ren_imgs[this.curr_tab_num] + '.png';
        this.ren.pos(695,123);

    }
    _proto.changePage = function(type){        
        if(type == 'pre'){
            if(this.curr_page_num == 1) return;
            this.curr_page_num--;                             
        }
        if(type == 'next'){
            if(this.curr_page_counts[this.curr_tab_num] == this.curr_page_num) return;
            this.curr_page_num++;  
                             
        }
       // 695,74
       //10,85
       if (this.curr_tab_num == 3 && (this.curr_page_num == 2 || this.curr_page_num == 4)) {
           this.ren.skin = 'guidebook/gongre_nv_1.png';
           this.ren.pos(0, 85);
       }else if( this.curr_tab_num == 5 && ( this.curr_page_num == 2 ||  this.curr_page_num == 4) ){
           this.ren.skin = 'guidebook/pinjian_nan_1.png';
           this.ren.pos(10, 85);
       }else  if( this.curr_tab_num == 6 && ( this.curr_page_num == 3 ||  this.curr_page_num == 6)){
           this.ren.skin = 'guidebook/gongre_nan_1.png';
           this.ren.pos(10, 85);
       }else{
            this.ren.skin = 'guidebook/' + this.ren_imgs[this.curr_tab_num] + '.png';
            this.ren.pos(695,123);
       }


        this.curr_page.changeText(this.curr_page_num);        
        this.guide_img.skin = 'guidebook/' + this.curr_tab_num + '_' + this.curr_page_num + '.png';
    }

    _proto.changeTab = function(){
        var tab_indx = this.guide_tab.selectedIndex;            
        this.curr_tab_num = Number(tab_indx) + 1;  
        this.curr_page_num = 1;   
        this.curr_page.changeText(this.curr_page_num);  
        this.pages_count.changeText( this.curr_page_counts[this.curr_tab_num] );
        this.guide_img.skin = 'guidebook/' + this.curr_tab_num + '_' + this.curr_page_num + '.png';
        this.ren.pos(695,123);
        this.ren.skin = 'guidebook/' + this.ren_imgs[this.curr_tab_num] + '.png';
       
    }

    return GuideBook;
}());