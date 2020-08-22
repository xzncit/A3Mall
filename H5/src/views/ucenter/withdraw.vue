<template>
    <div>
        <van-nav-bar
            title="申请提现"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />
        <van-notice-bar color="#1989fa" background="#ecf9ff" left-icon="info-o">
            当前可提现金额: {{money}}
        </van-notice-bar>
        <van-field
            readonly
            clickable
            label="转帐方式"
            :value="bank_type"
            placeholder="选择银行"
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

        <van-field v-model="name" label="持卡人" />
        <van-field v-model="code" label="卡号" />
        <van-field v-model="price" type="number" label="提现金额" />

        <div class="btn">
            <van-button type="danger" @click="doSubmit" block round>提交</van-button>
        </div>
    </div>
</template>

<script>
    import {Field, Picker, NavBar,Popup,Button,NoticeBar,Toast} from 'vant';
    export default {
        name: 'Settlement',
        components: {
            [NavBar.name]: NavBar,
            [Field.name]: Field,
            [Picker.name]: Picker,
            [Popup.name]: Popup,
            [Button.name]: Button,
            [NoticeBar.name]: NoticeBar,
        },
        data() {
            return {
                bank_type: '',
                name: '',
                code: '',
                price: '',
                money: '0.00',
                showPicker: false,
                columns: ['请选择'],
            };
        },
        created() {
            this.$http.getWalletSettlement().then(res=>{
                if(res.status){
                    this.columns = res.data.bank;
                    this.money = res.data.money;
                }
            });
        },
        methods: {
            prev() {
                this.$tools.prev();
            },
            onConfirm(value) {
                this.bank_type = value;
                this.showPicker = false;
            },
            doSubmit(){
                if(this.bank_type.length == 0){
                    Toast("请选择银行");
                    return false;
                }

                if(this.name.length == 0){
                    Toast("请填写持卡人");
                    return false;
                }

                if(this.code.length == 0){
                    Toast("请填写卡号");
                    return false;
                }

                if(!/^([1-9]{1})(\d{15}|\d{18})$/.test(this.code)){
                    Toast("您填写的银行卡号不正确");
                    return false;
                }

                if(this.price <= 0){
                    Toast("请填写提现金额");
                    return false;
                }

                this.$http.editWalletSettlement({
                    bank_type: this.bank_type,
                    name: this.name,
                    code: this.code,
                    price: this.price
                }).then(res=>{
                    if(res.status){
                        Toast(res.info);
                        setTimeout(()=>{
                            this.$tools.prev();
                        },2000);
                    }else{
                        Toast(res.info);
                    }
                }).catch(err=>{
                    Toast("连接网络出错，请稍后在试。");
                });
            }
        },
    }
</script>

<style lang="scss" scoped>
    .btn{
        padding: 0 10px;
        margin-top: 20px;
    }
</style>