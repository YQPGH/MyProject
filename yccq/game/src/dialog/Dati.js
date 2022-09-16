/**
 * Created by 41496 on 2017/6/5.
 */
(function(){
    //答题界面
    var self = null;
    function Dati()
    {
        Dati.__super.call(this);
        self = this;

        this.question = [];
        this.currIndex = 0;//数组问题的下标
        this.question_index = 0;//问题的下标
        this.total = 0;
        this.trueNum = 0;//正确的题数

        this.status = true;

        this.option = [this.option1,this.option2,this.option3,this.option4];

        this.option1.clickHandler = new Laya.Handler(this,this.onOptionClick,[1]);
        this.option2.clickHandler = new Laya.Handler(this,this.onOptionClick,[2]);
        this.option3.clickHandler = new Laya.Handler(this,this.onOptionClick,[3]);
        this.option4.clickHandler = new Laya.Handler(this,this.onOptionClick,[4]);

        this.start_btn.on(Laya.Event.CLICK, this, function(){
            this.intro_bg.visible = false;
        });

        this.getQuestion();
    }
    Laya.class(Dati,"Dati",DatiUI);
    var proto = Dati.prototype;

    proto.getQuestion = function()
    {
        Utils.post("question/lists",{uid:localStorage.GUID},this.onQuestionReturn);
    };

    proto.onQuestionReturn = function(res)
    {
        if(res.code == 0)
        {
            console.log(res);

            if(parseInt(res.data.is_finish) == 1){              
                var dialog = new Dati_jiangpin();
                    dialog.popup();              
                    return;
            }else{
                self.popup();
            }

            self.question = res.data.list;
            self.question_data = res.data;
            self.total = parseInt(res.data.questions_num);
            self.trueNum = parseInt(res.data.right_num);
            self.question_index = parseInt(res.data.finish_num);     
            self.initQuestion(res.data);
            self.option1.visible = true;
            self.option2.visible = true;
            self.option3.visible = true;
            self.option4.visible = true;
        }
    };

    proto.initQuestion = function()
    {
        
        if(this){
            for(var i = 0; i < this.option.length; i++)
            {
                this.option[i].selected = false;
            }

            console.log(this.question[this.currIndex]);           
            console.log('this.currIndex'+this.currIndex);
            this.cuo.visible = false;
            this.dui.visible = false;
            this.ti_num.changeText("第"+( this.question_index+1)+'题');
            this.content.text = this.question[this.currIndex].title;
            this.option1.label = "A. "+this.question[this.currIndex].option1;
            this.option2.label = "B. "+this.question[this.currIndex].option2;
            this.option3.label = "C. "+this.question[this.currIndex].option3;
            this.option4.label = "D. "+this.question[this.currIndex].option4;
            this.progress.changeText("答题进度 "+( this.question_index+1)+"/"+this.total);
            this.precision.changeText("正确率: "+((this.trueNum)/this.total).toFixed(2)*100+"%");
        }

    };

    proto.onOptionClick = function(option)
    {
        if(!this.status)return;
        this.status = false;
        console.log("问题id"+this.question[this.currIndex].id+"选项："+option);

        this.option[option-1].selected = true;

        Utils.post("question/answer",{uid:localStorage.GUID,id:this.question[this.currIndex].id,option:option},this.onAnswerReturn,onHttpErr,option);

    };

    proto.onAnswerReturn = function(res,option)
    {
        if(res.code == 0)
        {
            console.log(res);
            console.log(self.option[option-1]);
            self.dui.y = self.option[option-1].y+self.option[option-1].height/2;
            self.cuo.y = self.option[option-1].y+self.option[option-1].height/2;
            if(res.data.right == 1)
            {
                self.cuo.visible = false;
                self.dui.visible = true;

                self.trueNum ++;
                if(self.trueNum > self.total) self.trueNum = self.total;
                self.precision.changeText("正确率: "+(self.trueNum/self.total).toFixed(2)*100+"%");

            }else {
                self.dui.visible = false;
                self.cuo.visible = true;
            }
            self.timer.once(500, self, self.nextQuestion);
        }
    };

    proto.nextQuestion = function()
    {
        if(this.question_index+1 == this.total)
        {
            this.close();
            var dialog = new Dati_jiangpin();
            dialog.popup();
           
        }else
        {
            this.status = true;
            this.currIndex ++;
            this.question_index++;
            this.initQuestion();
        }
    }
})();