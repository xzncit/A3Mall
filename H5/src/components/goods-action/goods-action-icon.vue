<template>
    <div class="goods-action-icon" :class="'goods-action-icon-'+icon">
        <div @click="btn">
            <span v-if="icon == 'home'" class="icon iconfont" style="font-size: 18px">&#xe657;</span>
            <span v-if="icon == 'cart'" class="icon iconfont" style="font-size: 18px;">&#xe60f;</span>
            <span v-if="icon == 'collect'" class="icon iconfont" :class="{active:active}" style="font-size: 18px">&#xe635;</span>
            <i v-if="count">{{count}}</i>
        </div>
        <div><span :class="{active:active}">{{text}}</span></div>
    </div>
</template>

<script>
    export default {
        name: "GoodsActionIcon",
        props: {
            icon: {
                type: String,
                default: ""
            },
            text: {
                type: String,
                default: ""
            },
            count: {
                type: [Number,String],
                default: 0
            },
            active: {
                type: Boolean,
                default: false
            }
        },
        methods:{
            btn(){
                this.$emit("click");
            }
        }
    }
</script>

<style lang="scss" scoped>
.goods-action-icon{
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 54px;
    height: 50px;
    color: #646566;
    font-size: 12px;
    text-align: center;
    background-color: #fff;
    cursor: pointer;
    div {
        display: block; text-align: center;
        font-size: 13px; color: #666;
        position: relative;
        &:first-child {
            position: relative;
            span {
                display: inline-block;
                width: 18px; height: 18px;
                padding: 3px 0;
            }
            .active {
                color: #ff5000;
            }
            i {
                position: absolute;
                top: 5px;
                right: 12px;
                box-sizing: border-box;
                min-width: 16px;
                padding: 0 3px;
                color: #fff;
                font-weight: 500;
                font-size: 12px;
                line-height: 14px;
                text-align: center;
                background-color: #ee0a24;
                border: 1px solid #fff;
                border-radius: 16px;
                transform: translate(50%,-50%);
                transform-origin: 100%;
            }
        }
        &:last-child{
            font-size: 12px;
            -webkit-transform:scale(0.90);
            .active {
                color: #ff5000;
            }
        }
    }
}

</style>