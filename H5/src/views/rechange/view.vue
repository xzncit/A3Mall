<template>
    <div>
        <van-nav-bar
            title="充值"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <van-field
            readonly
            clickable
            label="充值方式"
            :value="payment"
            placeholder="选择充值方式"
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

        <van-field v-model="price" type="number" label="提现金额" placeholder="0.00" />

        <div class="btn">
            <van-button type="danger" @click="doSubmit" block round>提交</van-button>
        </div>
    </div>
</template>

<script>
    import {Field, Picker, NavBar,Popup,Button,NoticeBar,Toast} from 'vant';
    export default {
        name: 'Rechange',
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
                payment: '',
                price: '',
                showPicker: false,
                columns: ['微信支付'],
                orderBtnFlag: false
            };
        },
        methods: {
            prev() {
                this.$tools.prev();
            },
            onConfirm(value) {
                this.payment = value;
                this.showPicker = false;
            },
            doSubmit(){
                if(this.payment.length == 0){
                    Toast("请选择充值方式");
                    return false;
                }

                if(this.price <= 0){
                    Toast("请填写提现金额");
                    return false;
                }

                if(this.orderBtnFlag){
                    return false;
                }

                this.orderBtnFlag = true;
                Toast.loading({
                    message: '加载中...',
                    forbidClick: true,
                    loadingType: 'spinner',
                    duration: 0
                });

                this.$http.paymentWallet({
                    payment: "wechat",
                    source: this.$tools.isWeiXin() ? 2 : 1,
                    price: this.price
                }).then(res=>{
                    Toast.clear();
                    if(res.status){
                        this.resultOrderData(res.data);
                    }else{
                        Toast(res.info);
                    }
                    this.orderBtnFlag = false;
                }).catch(err=>{
                    this.orderBtnFlag = false;
                    Toast("连接网络出错，请稍后在试。");
                });
            },
            resultOrderData(data){
                switch (data.pay+"") {
                    case "1":
                        this.$wx.config(data.result.config);
                        var options = data.result.options;
                        var that = this;
                        options.success = function(){
                            Toast("支付成功");
                            setTimeout(()=>{
                                that.$router.replace('/ucenter/bill/fund');
                            },1500);
                        }
                        this.$wx.chooseWXPay(options);
                        break;
                    case "2":
                        location.href = data.result.url+"&redirect_url="+location.origin+'/ucenter/bill/fund';
                        break;
                    case "99":
                        Toast(data.msg);
                        break;
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
    .btn{
        padding: 0 10px;
        margin-top: 20px;
    }
</style>