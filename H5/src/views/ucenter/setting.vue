<template>
    <div class="wrap">
        <van-nav-bar
                title="帐号设置"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <div class="avatar">
            <div>
                <img :src="avatar||a3mall">
                <a href="javascript:;" class="file">上传
                    <input type="file" @change="upload($event)">
                </a>
            </div>
        </div>

        <van-form @submit="onSubmit">
            <div class="the-form-box">
                <div class="the-form-fields">
                    <van-field
                        v-model="username"
                        name="昵称"
                        label="昵称"
                        placeholder="昵称"
                    />
                </div>

                <div class="the-form-fields">
                    <van-field
                        readonly
                        clickable
                        label="性别"
                        :value="sex"
                        placeholder="性别"
                        @click="showPicker = true"
                    />
                </div>

                <van-popup v-model="showPicker" round position="bottom">
                    <van-picker
                            show-toolbar
                            :columns="columns"
                            @cancel="showPicker = false"
                            @confirm="onConfirm"
                    />
                </van-popup>

                <div class="the-form-fields">
                    <van-field
                            readonly
                            clickable
                            label="生日"
                            :value="birthday"
                            placeholder="生日"
                            @click="showBirthdayPicker = true"
                    />
                </div>

                <van-popup v-model="showBirthdayPicker" round position="bottom">
                    <van-datetime-picker
                        v-model="currentDate"
                        type="date"
                        title="选择年月日"
                        :min-date="minDate"
                        :max-date="maxDate"
                        :formatter="formatter"
                        @confirm="confirmBirthdayPicker"
                        @cancel="cancelBirthdayPicker"
                    />
                </van-popup>

                <div class="btn">
                    <van-button round block type="info" @click="onSubmit" native-type="submit">
                        提交
                    </van-button>
                </div>

                <div class="logout">
                    <van-button round block type="danger" @click="logout" native-type="button">
                        退出登录
                    </van-button>
                </div>
            </div>
        </van-form>

    </div>
</template>

<script>
import { NavBar } from 'vant';
import { Form,Field,Button,Picker,Popup,DatetimePicker,Toast } from 'vant';
export default {
    name: 'Setting',
    components: {
        [NavBar.name]: NavBar,
        [Form.name]: Form,
        [Field.name]: Field,
        [Button.name]: Button,
        [Picker.name]: Picker,
        [Popup.name]: Popup,
        [DatetimePicker.name]: DatetimePicker,
    },
    data() {
        return {
            a3mall: require("@/assets/images/avatar.png"),
            avatar: "",
            username: "",
            sex:"男",
            showPicker:false,
            columns: ['男', '女', '未知'],
            birthday:'',
            showBirthdayPicker: false,
            minDate: new Date(1950, 0, 1),
            maxDate: new Date(2025, 10, 1),
            currentDate: new Date(),
        };
    },
    created() {
        this.$http.getUserInfo().then((res)=>{
            if(res.status){
                this.username  = res.data.nickname;
                this.sex  = res.data.sex;
                this.birthday  = res.data.birthday;
                this.avatar = res.data.avatar;
            }
        });
    },
    methods: {
        upload(event){
            this.$http.uploadUsersAvatar({ event: event }).then(res=>{
                if(res.status){
                    this.avatar = res.data;
                }else{
                    Toast(res.info);
                }
            }).catch(err=>{
                console.log(err);
            });
        },
        prev() {
            this.$tools.prev();
        },
        onSubmit(){
            this.$http.editUserInfo({
                username: this.username,
                sex: this.sex,
                birthday: this.birthday
            }).then((res)=>{
                Toast(res.info);
            });
        },
        onConfirm(value) {
            this.sex = value;
            this.showPicker = false;
        },
        formatter(type, val) {
            if (type === 'year') {
                return `${val}年`;
            } else if (type === 'month') {
                return `${val}月`;
            }else if(type == 'day'){
                return `${val}日`;
            }

            return val;
        },
        confirmBirthdayPicker(val) {
            this.showBirthdayPicker = false;
            let date = new Date(val);
            let year = date.getFullYear().toString();
            let mon = (date.getMonth()+1).toString();
            let day = date.getDate().toString();
            this.birthday = year + "-" + mon + "-" + day;
        },
        cancelBirthdayPicker(){
            this.showBirthdayPicker = false;
        },
        logout(){
            this.$store.commit("DELETEUSERS","users");
            this.$router.push('/public/logout');
        }
    },
}
</script>

<style lang="scss" scoped>
.wrap{
    width: 100%;
    height: 100vh;
    background-color: #fff;
    .avatar{
        width: 100%;
        height: 80px;
        text-align: center;
        padding: 20px 0;
        div {
            position: relative;
            height: 80px;
            width: 80px;
            text-align: center;
            display: inline-block;
            &:before {
                width: 80px;
                height: 80px;
                display: block;
                content: " ";
                left: 50%;
                transform: translateX(-50%);
                position: absolute;
                z-index: 99;
                background: rgba(0,0,0,0.2);
                border-radius: 50%;
            }
            .file {
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                z-index: 1111;
                color: #333;
                position: absolute; display:inline-block; background: #fff;
                width: 40px;
                height: 20px;
                line-height: 20px;
                 overflow: hidden;
                text-decoration: none;
                text-indent: 0; cursor: pointer;
                border-radius: 5px;
                font-size: 13px;
                input { position: absolute; font-size: 10px; right: 0; top: 0; opacity: 0; cursor: pointer; }
            }

            img {
                width: 80px;
                height: 80px;
                overflow: hidden;
                border-radius: 50%;
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    }
}
.the-form-box{
    width: 90%;
    margin: 0 auto;
    padding-top: 10px;
    .the-form-fields{
        width: 100%;
        border:1px solid #d6d2d2;
        border-left: 0;
        border-right: 0;
        overflow: hidden;
        &:nth-child(1){
            border-bottom: 0;
        }
        &:nth-child(2){
            border-bottom: 0;
        }
        /deep/ .van-field__label {
            color: #999;
        }
    }
    .btn{
        margin: 25px auto 25px auto;
        /deep/ .van-button{
            background-color: #b91922;
            border: 1px solid #b91922;
            color: #fff;
            border-radius: 5px;
            font-size: 18px;
        }
    }
    .logout {
        /deep/ .van-button{
            background-color: #ffffff;
            border: 1px solid #d6d6d6;
            color: #a1a1a1;
            border-radius: 5px;
            font-size: 18px;
        }
    }
}
</style>
