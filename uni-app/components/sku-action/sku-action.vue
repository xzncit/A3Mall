<template>
    <view>
        <view class="sku-action" :class="{'sku-show': value==true}">
            <view class="sku-header">
                <view class="sku-header-image">
                    <image :src="goods.photo">
                </view>
                <view class="sku-header-goods-info">
                    <slot name="sku-header-price" v-bind:price="goodsPrice">
                        <view class="sku-header-goods-price">
                            <text class="symbol">￥</text>
                            <text class="price">{{goodsPrice||""}}</text>
                        </view>
                    </slot>
                    <view class="sku-header-item">
						<view class="sku-stock">
							剩余<text class="stock-num">{{goodsStockNumber||goods.store_nums||"0"}}</text>件
						</view>
                    </view>
                    <view class="sku-header-item">{{specSelected||""}}</view>
                </view>
            </view>
            <view class="sku-body" :style="{'max-height':maxHeight+'px'}">
                <view class="sku-group-container">
                    <view class="sku-row" v-for="(item,index) in attribute" :key="index">
                        <view class="sku-row-title">{{item.name}}</view>
                        <view
                            v-for="(childItem, childIndex) in item.list"
                            :key="childIndex"
                            class="sku-row-item"
                            :class="{'sku-row-item-active': childItem.selected && childItem.disable == false,'sku-row-item-disable':childItem.disable}"
                        >
                            <text
                                class="sku-row-item-name"
                                @click="onSelected(item.id, childItem.id)"
                            >{{childItem.value}}</text>
                        </view>

                    </view>
                </view>
                <view class="sku-stepper-stock">
                    <view class="sku-stepper-title">购买数量</view>
                    <view class="stepper sku-stepper">
                        <view @click.stop="minus" class="stepper-minus" :class="{'stepper-minus-disabled':number<=1}"></view>
                        <input type="number"  class="stepper-input" :value="number" disabled="disabled">
                        <view @click.stop="plus" class="stepper-plus" :class="{'stepper-minus-disabled':number >= goodsStockNumber}"></view>
                    </view>
                </view>
                <view style="width: 100%;height: 110rpx;"></view>
            </view>
            <text class="iconfont close" @click.stop="onClose">&#xe601;</text>
        </view>
        
    </view>
</template>

<script>
    // import Popup from '../../components/popup/popup';
    export default {
        // components: {
        //     [Popup.name]: Popup,
        // },
        props: {
            value: {
              type: Boolean,
              default: false
            },
            fields:{
                required: true,
                type: Array,
                default: function() {
                    return [];
                }
            },
            goods:{
                default: function() {
                    return {}
                }
            },
            attribute:{
                default: function() {
                    return []
                }
            },
            item:{
                default: function() {
                    return {}
                }
            },
            goodsInfo: {
                default: function() {
                    return {}
                }
            }
        },
        data(){
            return {
                maxHeight:0,
                number: 1,
                minNumber:1,
                maxNumber:0,
                specSelected: '',
                selectedSku:[],
                goodsPrice: "",
                goodsStockNumber: "",
                selectedGoodsInfo: {}
            };
        },
        mounted() {
			let info = this.$utils.getSystemInfo();
            this.maxHeight = info.h - this.$utils.px2rpx(200);
        },
        watch:{
            goods:{
                handler(newValue, oldValue) {
                    this.goodsStockNumber = this.goods.store_nums;
                    this.goodsPrice = this.goods.sell_price;

                    let fields = {};
                    for(let i in this.goods){
                        if(this.$utils.in_array(i,this.fields)){
                            fields[i] = this.goods[i];
                        }
                    }

                    Object.assign(fields,{
                        num: this.number,
                        isSubmit:true,
                        selectedSku: { id: "", specSelected: "" }
                    });

                    this.selectedGoodsInfo = fields;
                },
                deep: true
            },
            attribute:{
                handler(newValue, oldValue) {
                    if(this.attribute.length <= 0){
                        return ;
                    }

                    let arr = [];
                    this.selectedSku = [];

                    for(let obj in this.attribute){
                        for(let index in this.attribute[obj]['list']){
                            if(this.attribute[obj]['list'][index]['selected'] && !this.attribute[obj]['list'][index]['disable']){
                                this.selectedSku.push(this.attribute[obj]['list'][index]);
                                arr.push({ name:this.attribute[obj].name, value:this.attribute[obj]['list'][index].value });
                            }
                        }
                    }

                    let selectedIndex = [];
                    for(let obj in this.selectedSku){
                        selectedIndex.push(this.selectedSku[obj].pid+"_"+this.selectedSku[obj].id);
                    }

                    if(this.attribute.length == selectedIndex.length && this.item[selectedIndex.join("_")] != undefined){
                        let g = this.item[selectedIndex.join("_")];
                        this.goodsPrice = g.sell_price;
                        this.goodsStockNumber = g.store_nums;
                        if(this.number >= g.store_nums){
                            this.number = g.store_nums;
                        }

                        this.selectedGoodsInfo.selectedSku.id = g.product_id;
                        this.selectedGoodsInfo.isSubmit = true;
                    }else{
                        this.selectedGoodsInfo.isSubmit = false;
                    }

                    this.specSelected = '';
                    let s = [];
                    for(let i in arr){
                        s.push(arr[i].name + ":" + arr[i].value);
                    }

                    if(s.length > 0){
                        this.selectedGoodsInfo.selectedSku.specSelected = s.join(",");
                        this.specSelected = "己选择：" + s.join(",");
                    }
                },
                deep: true
            },
            selectedGoodsInfo:{
                handler(newValue, oldValue) {
                    this.$emit("update:goods-info",this.selectedGoodsInfo);
                },
                deep: true
            }
        },
        methods: {
            minus(){
                if(this.number <= 1){
                    return ;
                }

                this.number -= 1;
                this.selectedGoodsInfo.num = this.number;
            },
            plus(){
                if(this.number >= this.goodsStockNumber){
                    return ;
                }

                this.number += 1;
                this.selectedGoodsInfo.num = this.number;
            },
            onClose(){
                this.$emit("input",!this.value)
            },
            checkStatus(id, pid){
                let attr = this.attribute;
                let flag = false;
                for(let i in attr){
                    if(id != attr[i]['id']){
                        continue;
                    }

                    for(let j in attr[i]['list']){
                        let value = attr[i]['list'][j];
                        if((id == value['pid'] && pid == value['id']) && value['disable']){
                            flag = true;
                            break;
                        }
                    }
                }

                return flag;
            },
            onSelected(id, pid){
                if(this.checkStatus(id, pid)){
                    return ;
                }

                let specArray = [];
                for(let i in this.attribute){
                    specArray[i] = "[A-Za-z0-9_\\:\\,]+";
                    if(id == this.attribute[i]['id']){
                        for(let j in this.attribute[i]['list']){
                            let value = this.attribute[i]['list'][j];
                            if(id == value['pid'] && pid == value['id']){
                                let flag = !value.selected;
                                this.$set(this.attribute[i]['list'][j], 'selected', flag);
								// #ifdef MP-WEIXIN
								this.$emit("sku",this.attribute);
								// #endif
                                if(flag == true){
                                    specArray[i] = value.pid+":"+value.id;
                                }
                            }else{
                                this.$set(this.attribute[i]['list'][j], 'selected', false);
								// #ifdef MP-WEIXIN
								this.$emit("sku",this.attribute);
								// #endif
                            }
                        }
                    }
                }

                for(let i in this.attribute){
                    for(let j in this.attribute[i]['list']){
                        let value = this.attribute[i]['list'][j];
                        let temp = specArray.slice();
                        temp[i] = value.pid + ":" + value.id;
                        let flag = true;
                        for(let j in this.item){
                            let reg = new RegExp(temp.join(","));
                            if(reg.test(this.item[j].key) && this.item[j].store_nums > 0) {
                                flag = false;
                            }
                        }

                        this.$set(this.attribute[i]['list'][j], 'disable', flag);
						// #ifdef MP-WEIXIN
						this.$emit("sku",this.attribute);
						// #endif
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
.sku-action{
	box-shadow:0px 1px 10px #999;
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: #fff;
    width: 100%;
    border-radius: 20rpx 20rpx 0 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    min-height: 50%;
    max-height: 80%;
    font-size: 28rpx;
    z-index: 9999;
    overflow: hidden;
    transition:all .3s cubic-bezier(.65,.7,.7,.9);
    transform:translate3d(0,100%,0);
    .sku-header {
        margin: 0 32rpx;
        border-bottom: 2rpx solid #ebedf0;
        .sku-header-image{
            position: relative;
            float: left;
            width: 192rpx;
            height: 192rpx;
            margin: 24rpx 0;
            overflow: hidden;
            background: #f7f8fa;
            border-radius: 8rpx;
            image {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                max-width: 100%;
                max-height: 100%;
                margin: auto;
            }
        }
        .sku-header-goods-info{
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 96px;
            padding: 24rpx 40rpx 24rpx 16rpx;
            overflow: hidden;
            .sku-header-goods-price{
                color: #ee0a24;
                .symbol {
                    font-size: 32rpx;
                    vertical-align: bottom;
                }
                .price{
                    font-weight: 500;
                    font-size: 44rpx;
                    vertical-align: bottom;
                    word-wrap: break-word;
                }
            }
            .sku-header-item {
                margin-top: 16rpx;
                color: #969799;
                font-size: 24rpx;
                line-height: 32rpx;
                .sku-stock{
                    display: inline-block;
                    margin-right: 16rpx;
                    color: #969799;
                    font-size: 24rpx;
                    .stock-num {
                        display: inline-block;
                        margin:0 8rpx;
                        color: #969799;
                        font-size: 24rpx;
                        font-style: normal;
                    }
                }
            }
        }
    }
    .sku-body {
        flex: 1 1 auto;
        min-height: 88rpx;
        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;
        .sku-group-container{
            padding-top: 24rpx;
            .sku-row{
                margin: 0 32rpx 24rpx;
                border-bottom: 2rpx solid #ebedf0;
                .sku-row-title {
                    padding-bottom: 24rpx;
                }
                .sku-row-item{
                    position: relative;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 80rpx;
                    margin: 0 24rpx 24rpx 0;
                    overflow: hidden;
                    color: #323233;
                    font-size: 26rpx;
                    line-height: 32rpx;
                    vertical-align: middle;
                    border-radius: 8rpx;
                    cursor: pointer;
                    &:before {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: #f7f8fa;
                        content: "";
                    }
                    text {
                        font-style: normal;
                        z-index: 1;
                        padding: 16rpx;
                    }
                }
                .sku-row-item-active {
                    color: #ee0a24;
                    &:before {
                        background: #ee0a24;
                        opacity: .1;
                    }
                }
                .sku-row-item-disable {
                    color: #a8a7a7;
                }
            }
        }
        .sku-stepper-stock{
            padding: 24rpx 32rpx;
            overflow: hidden;
            line-height: 60rpx;
            .sku-stepper-title {
                float: left;
            }
            .stepper {
                font-size: 0;
                user-select: none;
            }
            .sku-stepper {
                float: right;
                padding-left: 8rpx;
            }
            .stepper-input {
                box-sizing: border-box;
                width: 64rpx;
                height: 56rpx;
                margin: 0 4rpx;
                padding: 0;
                color: #323233;
                font-size: 28rpx;
                line-height: normal;
                text-align: center;
                vertical-align: middle;
                background-color: #f2f3f5;
                border: 0;
                border-width: 2rpx 0;
                border-radius: 0;
                -webkit-appearance: none;
				float: left;
            }
			.stepper-minus { float: left; }
			.stepper-plus { float: right; }
            .stepper-minus,
            .stepper-plus {
				display: inline-block;
                position: relative;
                box-sizing: border-box;
                width: 56rpx;
                height: 56rpx;
                margin: 0;
                padding: 0;
                color: #323233;
                vertical-align: middle;
                background-color: #f2f3f5;
                border: 0;
                cursor: pointer;
            }
            .stepper-minus:before,
            .stepper-plus:before {
                width: 50%;
                height: 2rpx;
            }
            .stepper-plus:after {
                width: 2rpx;
                height: 50%;
            }
            .stepper-minus:after,
            .stepper-minus:before,
            .stepper-plus:after,
            .stepper-plus:before {
                position: absolute;
                top: 50%;
                left: 50%;
                background-color: rgb(50, 50, 51);
                -webkit-transform: translate(-50%,-50%);
                transform: translate(-50%,-50%);
                content: "";
            }
            .stepper-minus-disabled,
            .stepper-plus-disabled {
                color: #c8c9cc;
                background-color: #f7f8fa;
                cursor: not-allowed;
            }
            .stepper-minus-disabled:after,
            .stepper-minus-disabled:before,
            .stepper-plus-disabled:after,
            .stepper-plus-disabled:before {
                color: #c8c9cc;
                background-color: #c8c9cc;
            }
            .stepper-minus {
                border-radius: 8rpx 0 0 8rpx;
            }
        }
    }
    .close { position: absolute; display: inline-block; top: 30rpx; right: 30rpx; z-index: 1; color: #c8c9cc; font-size: 44rpx; cursor: pointer; }
}
.sku-show{
    transform:translate3d(0,0,0);
}
</style>