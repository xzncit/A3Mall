<template>
    <div>
        <van-nav-bar
            title="注册"
            left-arrow
            @click-left="prev"
        />

        <div class="login" :style="'height:'+clientHeight+'px'">
            <van-form @submit="onSubmit">
                <van-field
                    v-model="username"
                    type="tel"
                    name="用户名"
                    left-icon="phone-o"
                    placeholder="请输入手机号码"
                />
                <van-field
                    v-model="code"
                    center
                    clearable
                    left-icon="phone-circle-o"
                    placeholder="请输入短信验证码"
                >
                    <template #button>
                        <van-button @click.stop.prevent="sendSms" size="small" round color="#f35446" type="primary">{{ smsText }}</van-button>
                    </template>
                </van-field>
                <van-field
                        v-model="password"
                        type="password"
                        name="密码"
                        left-icon="lock"
                        placeholder="请填写密码"
                />
                <div class="forget-password">
                    <span @click="goForget">忘记密码？</span>
                </div>
                <div style="margin: 16px;">
                    <van-button :loading="loading" loading-text="数据提交中" round block color="#f35447" type="info" native-type="submit">
                        注册
                    </van-button>
                </div>
                <div class="tips-box">
                    己有帐号？<i @click="goLogin">立即登录</i>
                </div>
            </van-form>
        </div>

    </div>
</template>

<script>
import { NavBar } from 'vant';
import { Form,Field,Button,Toast } from 'vant';
Toast.setDefaultOptions({ duration: 5000 });
export default {
    name: 'Register',
    components: {
        [NavBar.name]: NavBar,
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

            this.$request.get("/send_sms",{
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

            this.loading = true;
            this.$request.post("/register",{
                username: this.username,
                password: this.password,
                code: this.code
            }).then((result)=>{
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
.login{
    width: 100%;
    background-color: #fff;
    padding-top: 50px;
}
.forget-password{
    color: #999;
    height: 25px;
    line-height: 25px;
    padding-top: 5px;
    span {
        float: right;
        font-size: 14px;
    }
}
.tips-box{
    text-align: center;
    color: #999;
    i {
        font-style: normal;
        color: #ff7159;
    }
}
</style>
