<template>
    <div>
        <div class="wrap">
            <div class="bg">
                <img src="../../assets/images/register-banner.png">
            </div>

            <div class="login">
                <van-form @submit="onSubmit">
                    <div class="the-form-box">
                        <div class="the-form-fields">
                            <van-field
                                v-model="username"
                                type="tel"
                                name="用户名"
                                left-icon="phone-o"
                                placeholder="请输入手机号码"
                                class="the-form-field"
                            />
                            <van-field
                                v-model="code"
                                center
                                clearable
                                left-icon="envelop-o"
                                placeholder="请输入短信验证码"
                                class="the-form-field"
                            >
                                <template #button>
                                    <van-button class="send-sms-btn" @click.stop.prevent="sendSms" size="small" color="#b91922" type="primary">{{ smsText }}</van-button>
                                </template>
                            </van-field>
                            <van-field
                                v-model="password"
                                type="password"
                                name="密码"
                                left-icon="lock"
                                placeholder="请填写密码"
                                class="the-form-field"
                            />
                        </div>
                        <div class="btn">
                            <van-button :loading="loading" loading-text="数据提交中" block color="#b91922" type="info" native-type="submit">
                                注册
                            </van-button>
                        </div>
                        <div class="tips-box">
                            己有帐号？<i @click="goLogin">立即登录</i>
                        </div>
                    </div>
                </van-form>
            </div>
        </div>
    </div>
</template>

<script>
import { Form,Field,Button,Toast } from 'vant';
Toast.setDefaultOptions({ duration: 5000 });
export default {
    name: 'Register',
    components: {
        [Form.name]: Form,
        [Field.name]: Field,
        [Button.name]: Button
    },
    data() {
        return {
            username: '',
            code: '',
            smsText: '发送验证码',
            smsNum:60,
            smsFlag: false,
            password: '',
            timer: null,
            loading: false,
            clientHeight: window.outerHeight - 46 - 50
        };
    },
    created() {
        // console.log(this.$route.params.id)
    },
    methods: {
        prev(){
            this.$tools.prev();
        },
        goLogin(){
            this.$router.push({
                path:'/public/login/'
            });
        },
        goForget(){
            this.$router.push({
                path:'/public/forget/'
            });
        },
        sendSms(){
            if(this.smsFlag){
                return false;
            }

            if(this.username == ''){
                Toast("请填写手机号码");
                return false;
            }else if(!/^1[3-9]\d{9}$/.test(this.username)){
                Toast("您填写的手机号码不正确！");
                return false;
            }

            this.$http.sendSMS({
                username: this.username,
                type: "register"
            }).then(function (result) {
                if(result.status){
                    Toast(result.info);
                }else{
                    Toast(result.info);
                }
            }).catch(function (error) {
                Toast("连接网络错误，请检查网络是否连接！");
            });

            this.smsFlag = true;
            this.timer = setInterval(()=>{
                if(this.smsNum <= 0){
                    this.smsFlag = false;
                    this.smsNum = 60;
                    this.smsText = '发送验证码';
                    clearInterval(this.timer);
                }else{
                    this.smsText = (this.smsNum--) + '秒重发';
                }
            },1000);
        },
        onSubmit(values) {
            if(this.loading){
                return ;
            }

            if(this.username == ''){
                Toast("请填写手机号码");
                return ;
            }else if(!/^1[3-9]\d{9}$/.test(this.username)){
                Toast("您填写的手机号码不正确！");
                return ;
            }else if(this.password == ''){
                Toast("请填写密码！");
                return ;
            }else if(this.code == ''){
                Toast("请填写验证码！");
                return ;
            }

            let params = {
                username: this.username,
                password: this.password,
                code: this.code
            }

            let spread_id = parseInt(this.$cookie.get("spread_id"));
            if(spread_id > 0){
                params.spread_id = spread_id;
            }

            this.loading = true;
            this.$http.register(params).then((result)=>{
                if(result.status){
                    this.$store.commit("UPDATEUSERS",result.data);
                    let path = this.$storage.get("VUE_REFERER");
                    this.$storage.delete("VUE_REFERER");
                    if(!path){
                        path = "/ucenter/index";
                    }

                    Toast(result.info);
                    setTimeout(()=>{
                        this.$router.push({ path: path });
                    },2000);
                }else{
                    Toast(result.info);
                }

                this.loading = false;
            }).catch((error)=>{
                this.loading = false;
                Toast("连接网络错误，请检查网络是否连接！");
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.wrap{
    width: 100%;
    height: 100vh;
    background-color: #fff;
}
.bg{
    width: 100%;
    height: 205px;
    img {
        display: block;
        width: 100%;
        height: 100%;
    }
}
.the-form-box{
    width: 90%;
    margin: 0 auto;
    padding-top: 40px;
    .the-form-fields{
        width: 100%;
        border-radius: 10px;
        border:1px solid #d6d2d2;
        overflow: hidden;
        .the-form-field{
            position: relative;
            display: block;
            box-sizing: content-box;
            width: 100%;
            height: 50px;
            line-height: 10px;
            padding: 0;
            overflow: hidden;
            color: #323233;
            font-size: 14px;
            background-color: #fff;
            /deep/ .van-field__left-icon{
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                left: 15px;
                /deep/ .van-icon {
                    font-size: 20px;
                    color: #aaa;
                }
            }
            /deep/ .van-field__value {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                left: 45px;
                width: 80%;
                font-size: 15px;
            }

        }
    }
    .btn{
        margin: 20px auto 0 auto;
        /deep/ .van-button{
            background-color: #b91922;
            border-radius: 5px;
            font-size: 18px;
        }
    }
}
/deep/ .van-field__body{ position: relative; }
/deep/ .van-cell::after{
     position: absolute;
     box-sizing: border-box;
     content: ' ';
     pointer-events: none;
     right: 0;
     bottom: 0;
     left: 0px;
     border-bottom: 1px solid #d6d2d2;
     -webkit-transform: scaleY(.5);
     transform: scaleY(.5);
}
/deep/ .send-sms-btn{
    right: -18px;
    height: 42px;
    border-radius: 6px;
    font-size: 14px;
}

.login{
    width: 100%;
    background-color: #fff;
}
.tips-box{
    text-align: center;
    color: #999;
    margin-top: 30px;
    i {
        font-style: normal;
        color: #ff7159;
    }
}
</style>
