<template>
    <div>
        <div class="sku-action" :class="{'sku-show': value==true}">
            <div class="sku-header">
                <div class="sku-header-image">
                    <img :src="goods.photo">
                </div>
                <div class="sku-header-goods-info">
                    <slot name="sku-header-price" v-bind:price="goodsPrice">
                        <div class="sku-header-goods-price">
                            <span class="symbol">￥</span>
                            <span class="price">{{goodsPrice||""}}</span>
                        </div>
                    </slot>
                    <div class="sku-header-item">
                    <span class="sku-stock">
                        剩余<i class="stock-num">{{goodsStockNumber||goods.store_nums||"0"}}</i>件
                    </span>
                    </div>
                    <div class="sku-header-item">{{specSelected||""}}</div>
                </div>
            </div>
            <div class="sku-body" :style="{'max-height':maxHeight+'px'}">
                <div class="sku-group-container">
                    <div class="sku-row" v-for="(item,index) in attribute" :key="index">
                        <div class="sku-row-title">{{item.name}}</div>
                        <span
                            v-for="(childItem, childIndex) in item.list"
                            :key="childIndex"
                            class="sku-row-item"
                            :class="{'sku-row-item-active': childItem.selected && childItem.disable == false,'sku-row-item-disable':childItem.disable}"
                        >
                            <i
                                class="sku-row-item-name"
                                @click="onSelected(item.id, childItem.id)"
                            >{{childItem.value}}</i>
                        </span>

                    </div>
                </div>
                <div class="sku-stepper-stock">
                    <div class="sku-stepper-title">购买数量</div>
                    <div class="stepper sku-stepper">
                        <button @click.stop="minus" type="button" class="stepper-minus" :class="{'stepper-minus-disabled':number<=1}"></button>
                        <input type="number"  class="stepper-input" :value="number" disabled="disabled">
                        <button @click.stop="plus" type="button" class="stepper-plus" :class="{'stepper-minus-disabled':number >= goodsStockNumber}"></button>
                    </div>
                </div>
                <div style="width: 100%;height: 55px;"></div>
            </div>
            <i id="close" class="fa fa-times-circle" @click.stop="onClose"></i>
        </div>
        <popup v-model="value"></popup>
    </div>
</template>

<script>
    import Popup from '../../components/popup/popup';
    export default {
        name: "SkuAction",
        components: {
            [Popup.name]: Popup,
        },
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
            this.maxHeight = window.innerHeight - 200;
        },
        watch:{
            goods:{
                handler(newValue, oldValue) {
                    this.goodsStockNumber = this.goods.store_nums;
                    this.goodsPrice = this.goods.sell_price;

                    let fields = {};
                    for(let i in this.goods){
                        if(this.$tools.in_array(i,this.fields)){
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
                                if(flag == true){
                                    specArray[i] = value.pid+":"+value.id;
                                }
                            }else{
                                this.$set(this.attribute[i]['list'][j], 'selected', false);
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
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
.sku-action{
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
    .sku-header {
        margin: 0 16px;
        border-bottom: 1px solid #ebedf0;
        .sku-header-image{
            position: relative;
            float: left;
            width: 96px;
            height: 96px;
            margin: 12px 0;
            overflow: hidden;
            background: #f7f8fa;
            border-radius: 4px;
            img {
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
            padding: 12px 20px 12px 8px;
            overflow: hidden;
            .sku-header-goods-price{
                color: #ee0a24;
                .symbol {
                    font-size: 16px;
                    vertical-align: bottom;
                }
                .price{
                    font-weight: 500;
                    font-size: 22px;
                    vertical-align: bottom;
                    word-wrap: break-word;
                }
            }
            .sku-header-item {
                margin-top: 8px;
                color: #969799;
                font-size: 12px;
                line-height: 16px;
                .sku-stock{
                    display: inline-block;
                    margin-right: 8px;
                    color: #969799;
                    font-size: 12px;
                    .stock-num {
                        display: inline-block;
                        margin:0 4px;
                        color: #969799;
                        font-size: 12px;
                        font-style: normal;
                    }
                }
            }
        }
    }
    .sku-body {
        flex: 1 1 auto;
        min-height: 44px;
        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;
        .sku-group-container{
            padding-top: 12px;
            .sku-row{
                margin: 0 16px 12px;
                border-bottom: 1px solid #ebedf0;
                .sku-row-title {
                    padding-bottom: 12px;
                }
                .sku-row-item{
                    position: relative;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 40px;
                    margin: 0 12px 12px 0;
                    overflow: hidden;
                    color: #323233;
                    font-size: 13px;
                    line-height: 16px;
                    vertical-align: middle;
                    border-radius: 4px;
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
                    i {
                        font-style: normal;
                        z-index: 1;
                        padding: 8px;
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
            padding: 12px 16px;
            overflow: hidden;
            line-height: 30px;
            .sku-stepper-title {
                float: left;
            }
            .stepper {
                font-size: 0;
                user-select: none;
            }
            .sku-stepper {
                float: right;
                padding-left: 4px;
            }
            .stepper-input {
                box-sizing: border-box;
                width: 32px;
                height: 28px;
                margin: 0 2px;
                padding: 0;
                color: #323233;
                font-size: 14px;
                line-height: normal;
                text-align: center;
                vertical-align: middle;
                background-color: #f2f3f5;
                border: 0;
                border-width: 1px 0;
                border-radius: 0;
                -webkit-appearance: none;
            }
            .stepper-minus,
            .stepper-plus {
                position: relative;
                box-sizing: border-box;
                width: 28px;
                height: 28px;
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
                height: 1px;
            }
            .stepper-plus:after {
                width: 1px;
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
                border-radius: 4px 0 0 4px;
            }
        }
    }
    #close { position: absolute; top: 15px; right: 15px; z-index: 1; color: #c8c9cc; font-size: 22px; cursor: pointer; }
}
.sku-show{
    transform:translate3d(0,0,0);
}
</style>