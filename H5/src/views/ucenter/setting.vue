<template>
    <div>
        <van-nav-bar
                title="帐号设置"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <van-form @submit="onSubmit">
            <van-field
                v-model="username"
                name="昵称"
                label="昵称"
                placeholder="昵称"
            />

            <van-field
                readonly
                clickable
                label="性别"
                :value="sex"
                placeholder="性别"
                @click="showPicker = true"
            />

            <van-popup v-model="showPicker" round position="bottom">
                <van-picker
                        show-toolbar
                        :columns="columns"
                        @cancel="showPicker = false"
                        @confirm="onConfirm"
                />
            </van-popup>

            <van-field
                    readonly
                    clickable
                    label="生日"
                    :value="birthday"
                    placeholder="生日"
                    @click="showBirthdayPicker = true"
            />

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

            <div style="margin: 16px;">
                <van-button round block type="info" @click="onSubmit" native-type="submit">
                    提交
                </van-button>
            </div>

            <div style="margin: 16px;">
                <van-button round block type="danger" @click="logout" native-type="button">
                    退出登录
                </van-button>
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
            }
        });
    },
    methods: {
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

<style scoped>

</style>
