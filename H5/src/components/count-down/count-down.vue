<template>
    <div class="aaa-count-down" :class="theme">
        <div :class="'wrap-'+theme" v-if="isShow">
            <span class="before" v-if="before">{{before}}</span>
            <span class="day" v-if="time.day > 0">
                <i>{{time.day}}</i>
                <i>{{dayText}}</i>
            </span>
                <span class="hour">
                <i>{{time.hour}}</i>
                <i>{{hourText}}</i>
            </span>
                <span class="minute">
                <i>{{time.minute}}</i>
                <i>{{minuteText}}</i>
            </span>
            <span class="second">
                <i>{{time.second}}</i>
                <i>{{secondText}}</i>
            </span>
            <span class="after" v-if="after">{{after}}</span>
        </div>
        <div v-if="!isShow" :class="'tips-'+theme">{{tips}}</div>
    </div>
</template>

<script>
    export default {
        name: "CountDown",
        props: {
            nowTime: {
                type: [String,Number],
                default: "0"
            },
            startTime: {
                type: [String,Number],
                default: "0"
            },
            endTime: {
                type: [String,Number],
                default: "0"
            },
            startText: {
                type: String,
                default: "距开始仅剩："
            },
            endText: {
                type: String,
                default: "距结束仅剩："
            },
            finishText: {
                type: String,
                default: "活动己结束"
            },
            dayText: {
                type: String,
                default: "天"
            },
            hourText: {
                type: String,
                default: "小时"
            },
            minuteText: {
                type: String,
                default: "分"
            },
            secondText: {
                type: String,
                default: "秒"
            },
            theme: {
                type: String,
                default: "simple"
            },
            fillZero: {
                type: Object,
                default: function () {
                    return {
                        day    : {  count : 86400, num : 2,  def : '00' },
                        hour   : {  count : 3600,  num : 2,  def : '00' },
                        minute : {  count : 60,    num : 2,  def : '00' },
                        second : {  count : 1 ,    num : 2,  def : '00' }
                    }
                }
            }
        },
        data(){
            return {
                time: {
                    day: "",
                    hour: "",
                    minute: "",
                    second: ""
                },
                before: "",
                after: "",
                current: 0,
                start: 0,
                end: 0,
                total: 0,
                nextTotal: 0,
                tips: "",
                isShow: false,
                timer: null
            };
        },
        watch: {
            endTime: {
                handler(newValue) {
                    this.run();
                },
                immediate: true
            }
        },
        methods: {
            run(){
                if(this.nowTime == undefined || this.startTime == undefined || this.endTime == undefined){
                    return ;
                }

                this.now = this.nowTime;
                this.start = this.startTime;
                this.end = this.endTime;
                this.total = this.end - this.now;

                this.message(false,"");
                if(this.total <= 0){
                    this.updateStatus(0);
                    this.message(false,this.finishText);
                    return ;
                }else if(this.nowTime < this.startTime){
                    this.before = this.startText;
                    this.total = this.startTime - this.nowTime;
                    this.nextTotal = this.end - this.now - this.total;
                    this.type = 1;
                    this.updateStatus(2);
                }else if(this.nowTime > this.startTime && this.endTime > this.nowTime){
                    this.before = this.endText;
                    this.type = 2;
                    this.updateStatus(1);
                }

                this.runTime();
                this.timer = setInterval(()=>{
                    this.runTime();
                },1000);
            },
            runTime(){
                if(this.total <= 0){
                    this.timer && clearInterval(this.timer);
                    if(this.type == 1){
                        this.total = this.nextTotal;
                        this.type = 2;
                        this.updateStatus(1);
                        this.before = this.endText;
                        this.runTime();
                        this.timer = setInterval(()=>{
                            this.runTime();
                        },1000);
                    }else{
                        this.message(false,this.finishText);
                        this.updateStatus(0);
                    }
                    return ;
                }

                let dateTime = this.total;
                for(let i in this.fillZero){
                    let data = this.fillZero[i];
                    let flag = dateTime >= data.count ? true : false;
                    if(!flag){
                        this.time[i] = data.def;
                    }

                    if(flag){
                        let value = Math.floor(dateTime / data.count);
                        this.time[i] = this.fill(value.toString(),data.num);
                        dateTime -= value * data.count;
                    }
                }

                this.total--;
                this.message(true);
            },
            fill(i,n){
                let str = '' + i;
                while(str.length < n){
                    str = '0' + str;
                }

                return str;
            },
            message(status,msg){
                this.tips = msg || "";
                this.isShow = status;
            },
            updateStatus(status){
                this.$emit("update:status",status);
            }
        }
    }
</script>

<style>
    .aaa-count-down i { font-style: normal; }
    /*.aaa-count-down.simple {  }*/
    /*.aaa-count-down.simple span { float: left; }*/
</style>