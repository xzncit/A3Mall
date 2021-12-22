// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
(function ($) {

    var defaluts = {
        fillZero: {
            day    : {  count : 86400, num : 2,  def : '00' },
            hour   : {  count : 3600,  num : 2,  def : '00' },
            minute : {  count : 60,    num : 2,  def : '00' },
            second : {  count : 1 ,    num : 2,  def : '00' }
        },
        sClass: {
            before: ".before",
            day: ".day",
            hour: ".hour",
            minute: ".minute",
            second: ".second",
            after: ".after",
            tips: ".tips"
        },
        text: {
            after: {
                "day" : "天","hour" : "时", "minute" : "分","second" : "秒"
            }
        },
        isStatus: function (status,index){}
    };
    // return false; break
    // return true; continue
    $.fn.countdown = function (options){
        var config = $.extend({}, defaluts, options);
        return $(this).each(function (index) {
            var that = $(this);
            var nowTime = parseInt($(this).attr("data-nowTime"));
            var startTime = parseInt($(this).attr("data-startTime"));
            var endTime = parseInt($(this).attr("data-endTime"));
            var totalTime  = endTime - nowTime;
            var nextTotal = 0;
            var type = 0;
            var timer = null;

            initTag(that,config);
            if(totalTime <= 0){
                config.isStatus(0,index);
                $(config.sClass.tips,that).html("活动己结束");
                return true;
            }else if(nowTime < startTime){
                $(config.sClass.before,that).html("距开始仅剩：");
                totalTime = startTime - nowTime;
                nextTotal = endTime - nowTime - totalTime;
                type = 1;
                config.isStatus(2);
            }else if(nowTime > startTime && endTime > nowTime){
                $(config.sClass.before,that).html("距结束仅剩：");
                type = 2;
                config.isStatus(1);
            }

            var run = function (){
                if(totalTime <= 0){
                    timer && clearInterval(timer);
                    if(type == 1){
                        totalTime = nextTotal;
                        type = 2;
                        config.isStatus(1);
                        $(config.sClass.before,that).html("距结束仅剩：");
                        run();
                        timer = setInterval(run,1000);
                    }else{
                        config.isStatus(0);
                        initTag(that,config);
                        $(config.sClass.tips,that).html("活动己结束");
                    }
                }else{
                    let dateTime = totalTime;
                    var json = { "day" : "","hour" : "", "minute" : "","second" : "" };
                    for(let i in config.fillZero){
                        let data = config.fillZero[i];
                        let flag = dateTime >= data.count ? true : false;
                        if(!flag){
                            json[i] = data.def;
                        }

                        if(flag){
                            let value = Math.floor(dateTime / data.count);
                            json[i] = fill(value.toString(),data.num);
                            dateTime -= value * data.count;
                        }

                        if($(config.sClass[i])[0]){
                            $(config.sClass[i],that).html(parseInt(json[i]) + config.text.after[i]);
                        }
                    }

                    totalTime--;
                }
            };

            run();
            timer = setInterval(run,1000);
        });
    };

    var initTag = function (obj,config){
        for(var i in config.sClass){
            $(config.sClass[i],obj).html("");
        }
    };

    var fill = function (i,n){
        var str = '' + i;
        while(str.length < n){
            str = '0' + str;
        }
        return str;
    };

})(jQuery);