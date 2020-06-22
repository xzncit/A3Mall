<template>
    <div>
        <van-nav-bar
            title="登录"
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
                        登录
                    </van-button>
                </div>
                <div class="tips-box">
                    没有帐号？<i @click="goRegister">立即注册</i>
                </div>
            </van-form>
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { Form,Field,Button,Toast } from 'vant';
    export default {
        name: 'Login',
        components: {
            [NavBar.name]: NavBar,
            [Form.name]: Form,
            [Field.name]: Field,
            [Button.name]: Button
        },
        data() {
            return {
                username: '',
                password: '',
                loading: false,
                clientHeight: window.outerHeight - 46 - 50
            };
        },
        created() {

        },
        methods: {
            prev(){
                this.$tools.prev();
            },
            goRegister(){
                this.$router.push({
                    path:'/public/register/'
                });
            },
            goForget(){
                this.$router.push({
                    path:'/public/forget/'
                });
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
                }

                this.loading = true;
                this.$request.post('/public/login',{
                    username: this.username,
                    password: this.password
                }).then((result)=>{
                    if(result.status){
                        this.$store.commit("UPDATEUSERS",result.data);
                        let path = this.$storage.get("VUE_REFERER");
                        this.$storage.delete("VUE_REFERER");
                        if(!path){
                            path = "/";
                        }
                        this.$router.push({ path: path });
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
