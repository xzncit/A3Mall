<template>
    <div>
        <div class="address-action" :class="{'address-show': value==true}" style="background-color: #f8f8f8">
            <div class="address-title">请选择地址</div>
            <div class="address-body" :style="{'max-height':maxHeight+'px'}">

                <div v-if="array.length <= 0" class="address-empty">
                    您还没有添加地址哦
                </div>

                <div class="address-list" v-if="array.length">

                    <div class="address-box"
                         v-for="(item,index) in array" :key="index"
                         @click="onCoupon(item)"
                    >
                        <div class="address-r-box">
                            <div class="address-name">{{ item.name }} {{ item.tel }}</div>
                            <div class="address-valid">{{ item.address }}</div>
                        </div>
                        <div class="address-corner-checkbox">
                            <span class="iconfont" :class="{ active: active == item.id }">&#xe641;</span>
                        </div>
                    </div>

                </div>

                <div style="width: 100%;height: 60px; float: left"></div>
            </div>
            <i id="close" class="fa fa-times-circle" @click.stop="onClose"></i>
            <div class="address-button" @click="onAddAddress"><span>新增地址</span></div>
        </div>
        <popup v-model="value"></popup>
    </div>
</template>

<script>
    import Popup from '../../components/popup/popup';
    export default {
        name: "AddressList",
        components: {
            [Popup.name]: Popup,
        },
        props: {
            value: {
                type: Boolean,
                default: false
            },
            array: {
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
                this.$emit("address-event",value);
            },
            onAddAddress(){
                this.$emit("onAdd",{});
            }
        }
    }
</script>

<style lang="scss" scoped>
    .address-action{
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
        .address-title { font-size: 16px; text-align: center; width: 100%; height: 50px; background-color: #fff; line-height: 50px; }
        .address-button {
            width: 100%; height: 60px; line-height: 60px; position: absolute; left: 0; bottom: 0; background-color: #fff;
            span {
                text-align: center; background-color: #c21313;
                width: 90%; height: 50px; line-height: 50px;
                margin: 5px auto; display: block;
                font-size: 15px; color: #fff; border-radius: 20px;
            }
        }
        .address-body {
            flex: 1 1 auto;
            min-height: 44px;
            overflow-y: scroll;
            -webkit-overflow-scrolling: touch;
            .address-empty { width: 100%; text-align: center; font-size: 18px; height: 50px; line-height: 50px; position: absolute; top: 50%; transform: translateY(-50%) }
            .address-list {
                width: 95%;
                margin-left: 2.5%;
                float: left;
                margin-top: 10px;

                .address-box {
                    float: left;
                    margin-bottom: 10px;
                    width: 100%;
                    height: 85px;
                    background-color: #fff;
                    border-radius: 10px;
                    position: relative;
                    .address-r-box {
                        float: left;
                        margin-left: 30px;
                        height: 85px;
                        position: relative;
                        .address-name {
                            font-size: 15px;
                            padding-top: 18px;
                        }
                        .address-valid{
                            padding-top: 12px;
                            font-size: 14px;
                        }
                    }
                    .address-corner-checkbox{
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
    .address-show{
        transform:translate3d(0,0,0);
    }
</style>