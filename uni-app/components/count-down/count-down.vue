<template>
    <view class="aaa-count-down" :class="theme">
        <view :class="'wrap-'+theme" v-if="isShow">
            <view class="before" v-if="isBefore">{{before}}</view>
            <view class="day" v-if="time.day > 0">
                <text>{{time.day}}</text>
                <text>{{dayText}}</text>
            </view>
            <view class="hour">
                <text>{{time.hour}}</text>
                <text>{{hourText}}</text>
            </view>
            <view class="minute">
                <text>{{time.minute}}</text>
                <text>{{minuteText}}</text>
            </view>
            <view class="second">
                <text>{{time.second}}</text>
                <text>{{secondText}}</text>
            </view>
            <view class="after" v-if="after">{{after}}</view>
        </view>
        <view v-if="!isShow" :class="'tips-'+theme">{{tips}}</view>
    </view>
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
            startTexts: {
                type: String,
                default: "距开始仅剩："
            },
            endTexts: {
                type: String,
                default: "距结束仅剩："
            },
            finishTexts: {
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
			isBefore: {
				type: Boolean,
				default: false
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
				
                this.updateStatus(false);
                this.message(false,"");
                if(this.total <= 0){
                    this.message(false,this.finishTexts);
                    return ;
                }else if(this.nowTime < this.startTime){
                    this.before = this.startTexts;
                }else if(this.nowTime > this.startTime && this.endTime > this.nowTime){
                    this.before = this.endTexts;
                    this.updateStatus(true);
                }

                this.timer = setInterval(()=>{
                    this.runTime();
                },1000);
            },
            runTime(){
                if(this.total <= 0){
                    this.timer && clearInterval(this.timer);
                    this.message(false,this.finishText);
                    this.updateStatus(false);
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
    .aaa-count-down .day,
	.aaa-count-down .hour,
	.aaa-count-down .minute,
	.aaa-count-down .second,
	.aaa-count-down .day,
	.aaa-count-down .day { float: left; }
</style>