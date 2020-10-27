<template>
    <div>
        <div class="coupon-action" :class="{'coupon-show': value==true}" style="background-color: #f8f8f8">
            <div class="coupon-title">选择优惠劵</div>
            <div class="coupon-body" :style="{'max-height':maxHeight+'px'}">

                <div v-if="coupons.length <= 0" class="coupon-empty">
                    暂无优惠劵
                </div>

                <div class="coupon-list" v-if="coupons.length">
                    <div class="coupon-box"

                         v-for="(item,index) in coupons" :key="index"
                         @click="onCoupon(item)"
                    >
                        <div class="coupon-l-box">
                            <div class="coupon-amount">
                                {{ item.price }}
                                <span>元</span>
                            </div>
                            <div class="coupon-condition">{{ item.condition }}</div>
                        </div>
                        <div class="coupon-r-box">
                            <div class="coupon-name">{{ item.name }}</div>
                            <div class="coupon-valid">{{ item.startAt }} - {{ item.endAt }}</div>
                        </div>
                        <div class="coupon-corner-checkbox">
                            <span class="iconfont" :class="{ active: active == item.id }">&#xe641;</span>
                        </div>
                    </div>

                </div>

                <div style="width: 100%;height: 60px; float: left"></div>
            </div>
            <i id="close" class="fa fa-times-circle" @click.stop="onClose"></i>
            <div class="coupon-button" @click="onCancelBonus"><span>不使用优惠劵</span></div>
        </div>
        <popup v-model="value"></popup>
    </div>
</template>

<script>
    import Popup from '../../components/popup/popup';
    export default {
        name: "Coupon",
        components: {
            [Popup.name]: Popup,
        },
        props: {
            value: {
                type: Boolean,
                default: false
            },
            coupons: {
                type: Array,
                default: function() {
                    return []
                }
            }
        },
        data(){
            return {
                maxHeight:0,
                active: 0
            };
        },
        mounted() {
            this.maxHeight = window.innerHeight - 200;
        },
        watch:{

        },
        methods: {
            onClose(){
                this.$emit("input",!this.value)
            },
            onCoupon(value){
                this.active = value.id;
                this.$emit("coupon-event",{
                    id: value.id,
                    value: "-￥" + value.valueDesc + value.unitDesc
                });
            },
            onCancelBonus(){
                this.active = 0;
                this.$emit("coupon-event",{
                    id: 0,
                    value: this.coupons.length <= 0 ? "暂无优惠劵" : this.coupons.length + "张可用"
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .coupon-action{
        position: fixed;
        left: 0;
        bottom: 0;
        background-color: #fff;
        width: 100%;
        border-radius: 10px 10px 0 0;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        min-height: 50%;
        max-height: 80%;
        font-size: 14px;
        z-index: 999;
        overflow: hidden;
        transition:all .3s cubic-bezier(.65,.7,.7,.9);
        transform:translate3d(0,100%,0);
        .coupon-title { font-size: 16px; text-align: center; width: 100%; height: 50px; background-color: #fff; line-height: 50px; }
        .coupon-button {
            width: 100%; height: 60px; line-height: 60px; position: absolute; left: 0; bottom: 0; background-color: #fff;
            span {
                text-align: center; background-color: #c21313;
                width: 90%; height: 50px; line-height: 50px;
                margin: 5px auto; display: block;
                font-size: 15px; color: #fff; border-radius: 20px;
            }
        }
        .coupon-body {
            flex: 1 1 auto;
            min-height: 44px;
            overflow-y: scroll;
            -webkit-overflow-scrolling: touch;
            .coupon-empty { width: 100%; text-align: center; font-size: 18px; height: 50px; line-height: 50px; position: absolute; top: 50%; transform: translateY(-50%) }
            .coupon-list {
                width: 95%;
                margin-left: 2.5%;
                float: left;
                margin-top: 10px;

                .coupon-box {
                    float: left;
                    margin-bottom: 10px;
                    width: 100%;
                    height: 85px;
                    background-color: #fff;
                    border-radius: 10px;
                    position: relative;
                    .coupon-l-box {
                        position: absolute;
                        left: 0;
                        top: 0;
                        height: 85px;
                        width: 110px;
                        padding: 0 5px;
                        .coupon-amount {
                            width: 100%;
                            text-align: center;
                            padding-top: 15px;
                            font-size: 25px;
                            color: #c21313;
                            span { font-size: 14px; }
                        }
                        .coupon-condition { text-align: center; color: #c21313; width: 100%; padding-top: 5px; }
                    }
                    .coupon-r-box {
                        float: left;
                        margin-left: 130px;
                        height: 85px;
                        position: relative;
                        .coupon-name {
                            font-size: 15px;
                            padding-top: 18px;
                        }
                        .coupon-valid{
                            padding-top: 12px;
                            font-size: 14px;
                        }
                    }
                    .coupon-corner-checkbox{
                        position: absolute;
                        color: #999;
                        right: 15px;
                        height: 20px;
                        top: 50%;
                        transform: translateY(-50%);
                        span { font-size: 20px; }
                        .active { color: #c21313; }
                    }
                }
            }

        }
        #close { position: absolute; top: 15px; right: 15px; z-index: 1; color: #c8c9cc; font-size: 22px; cursor: pointer; }
    }
    .coupon-show{
        transform:translate3d(0,0,0);
    }
</style>