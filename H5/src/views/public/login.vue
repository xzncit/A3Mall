<template>
    <div>
        <nav-bar
            left-arrow
            :fixed="true"
            :transparent="true"
            :z-index="9999"
            @click-left="prev"
        />
        <div class="wrap">
            <div class="top">
                <div class="title">A3Mall</div>
                <div class="ctitle">素烟姿</div>
            </div>

            <van-form @submit="onSubmit">
                <div class="the-form-box">
                    <div class="the-form-fields">
                        <van-field
                            v-model="username"
                            type="tel"
                            name="用户名"
                            left-icon="contact"
                            placeholder="请输入手机号码"
                            class="the-form-field"
                        />
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
                            登 录
                        </van-button>
                    </div>

                    <div class="hp-box">
                        <span @click="goRegister">立即注册</span>
                        <span @click="goForget">忘记密码？</span>
                    </div>

                </div>
            </van-form>
        </div>

    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
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
                username: '18319517777',
                password: 'admin888',
                loading: false,
                clientHeight: window.outerHeight - 46 - 50
            };
        },
        created() {

        },
        methods: {
            prev(){
                this.$router.replace('/');
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

                let params = {
                    username: this.username,
                    password: this.password
                };

                let spread_id = parseInt(this.$cookie.get("spread_id"));
                if(spread_id > 0){
                    params.spread_id = spread_id;
                }

                this.loading = true;
                this.$http.sendLogin(params).then((result)=>{
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
.top {
    width: 100%;
    height: 210px;
    background-image: url("../../assets/images/user-login-bg.png");
    .title {
        width: 100%;
        float: left;
        text-align: center;
        position: relative;
        color: #fff;
        font-size: 40px;
        margin-top: 60px;
    }
    .title:after {
        position: absolute;
        width: 115px;
        height: 3px;
        background-color: #cf656b;
        content: " ";
        left: 50%;
        transform: translateX(-50%);
        bottom: -7px;
    }
    .ctitle {
        width: 100%;
        float: left;
        text-align: center;
        font-size: 31px;
        color: #fff000;
        margin-top: 10px;
    }
}
.wrap{
    width: 100%;
    height: 100vh;
    background-color: #fff;
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
            &:first-child {
                border-bottom: 1px solid #d6d2d2;
            }
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
        margin: 25px auto 0 auto;
        /deep/ .van-button{
            background-color: #b91922;
            border-radius: 5px;
            font-size: 18px;
        }
    }
}
.hp-box{
    color: #999;
    height: 25px;
    line-height: 25px;
    padding-top: 25px;
    span {
        font-size: 15px;
        color:#888888;
        &:first-child{
            float: left;
        }
        &:last-child {
            float: right;
        }
    }
}

</style>
