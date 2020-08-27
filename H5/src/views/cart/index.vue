<template>
    <div>
        <van-nav-bar
            title="购物车"
            right-text="管理"
            :fixed="true"
            :placeholder="true"
            class="cart-nav"
            @click-right="onClickRight"
        />

        <div class="refresh-wrap">
            <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
            <van-pull-refresh
                v-if="!isEmpty"
                v-model="refreshing"
                @refresh="onRefresh"
            >
                <van-list
                    v-model="loading"
                    :finished="finished"
                    finished-text="没有更多了"
                    @load="loadGoods"
                >

                    <van-checkbox-group v-model="array" ref="checkboxGroup">
                        <div class="list-box">
                            <div
                                class="list-item"
                                v-for="item in list"
                                :key="item.id"
                            >
                                <div class="item-check">
                                    <van-checkbox :name="item.id"></van-checkbox>
                                </div>
                                <div class="pic"
                                     @click="$router.push({ path: '/goods/view/' + item.goods_id })">
                                    <img :src="item.photo">
                                </div>
                                <div class="goods">
                                    <div class="t"
                                         @click="$router.push({ path: '/goods/view/' + item.goods_id })">{{ item.title }}</div>
                                    <div class="m">{{ item.attr }}</div>
                                    <div class="b">
                                        <span>{{ item.price }}</span>
                                        <span>
                                            <van-stepper v-model="item.goods_nums" min="1" :max="item.nums" disable-input @change="stepperNum($event,item.goods_id,item.product_id)" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </van-checkbox-group>

                </van-list>
            </van-pull-refresh>
        </div>

        <div>
            <van-submit-bar
                v-if="!operator"
                :loading="false"
                :disabled="false"
                :price="total"
                button-text="提交订单"
                @submit="onSubmit"
            >
                <van-checkbox @click="checkAll" v-model="checked">全选</van-checkbox>
            </van-submit-bar>

            <div v-if="operator" class="operator-box">
                <div class="c">
                    <div class="l">
                        <van-checkbox @click="checkAll" v-model="checked">全选</van-checkbox>
                    </div>

                    <div class="r">
                        <span class="delete" @click="deleteGoods">删除</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { SubmitBar,Checkbox,NavBar,Button,PullRefresh,List,CheckboxGroup,Stepper } from 'vant';
import { Empty,Toast } from 'vant';
export default {
    name: 'Cart',
    components: {
        [NavBar.name]: NavBar,
        [SubmitBar.name]: SubmitBar,
        [Checkbox.name]: Checkbox,
        [Button.name]: Button,
        [PullRefresh.name]: PullRefresh,
        [List.name]: List,
        [CheckboxGroup.name]: CheckboxGroup,
        [Stepper.name]: Stepper,
        [Empty.name]: Empty,
    },
    data() {
        return {
            isEmpty: false,
            emptyImage: "search",
            emptyDescription: "您的购物车为空！",
            clientHeight: window.outerHeight - 46 - 50 - 50,
            checked: false,
            operator:false,
            list: [],
            loading: false,
            finished: false,
            refreshing: false,
            array: [],
            page: 1,
            checkboxGrouped:false,
            total:0
        };
    },
    methods: {
        loadGoods(){
            this.isEmpty = false;
            if (this.refreshing) {
                this.list = [];
                this.refreshing = false;
                this.page = 1;
            }

            let emptyCart = this.$request.domain() + 'static/images/cart-empty.png';
            this.$http.getCartList({
                page: this.page
            }).then((result)=>{
                if(result.data.list == undefined && this.page == 1){
                    this.isEmpty = true;
                    this.emptyImage = emptyCart;
                    this.emptyDescription = "您的购物车为空！";
                } else if(result.status == 1){
                    this.list = this.list.concat(result.data.list);
                    this.loading = false;
                    this.page++;
                }else if(result.status == -1){
                    if(result.data == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = emptyCart;
                        this.emptyDescription = "您的购物车为空！";
                    }else{
                        this.loading = false;
                        this.finished = true;
                    }
                }
            }).catch((error)=>{
                this.isEmpty = true;
                this.emptyImage = "network";
                this.emptyDescription = "网络出错，请检查网络是否连接";
            });
        },
        onRefresh() {
            // 清空列表数据
            this.finished = false;

            // 重新加载数据
            // 将 loading 设置为 true，表示处于加载状态
            this.loading = true;
            setTimeout(()=>{
                this.loadGoods();
            },1500);
        },
        onSubmit(){
            if(this.array.length <= 0){
                Toast("请选择需要结算的商品");
                return false;
            }
            this.$router.push({
                path: "/cart/confirm",
                query: {
                    id: this.array.join(","),
                    type: "cart"
                }
            });
        },
        onClickRight(){
            this.operator = !this.operator;
        },
        checkAll(){
            this.checkboxGrouped = !this.checkboxGrouped;
            this.$refs.checkboxGroup.toggleAll(this.checkboxGrouped);
        },
        deleteGoods(){
            let arr = [];
            this.list = this.list.filter((item)=>{
                if(this.$tools.in_array(item.id,this.array)){
                    arr.push(item);
                }
                return !this.$tools.in_array(item.id,this.array);
            });

            this.$store.commit("UPDATECART",this.list.length);
            if(arr.length <= 0){
                return ;
            }

            let id = [];
            arr.forEach((item)=>{
                id.push(item.id);
            })

            this.$http.deleteCart({
                id: id.join(",")
            });
        },
        /**
         * 更新商品数量
         * @param value     数量
         * @param detail    索引
         */
        stepperNum(value,goods_id,products_id){
            this.$http.updateCartGoods({
                id: goods_id,
                sku_id: products_id,
                num: value
            }).then(res=>{
                if(res.status){
                    this.$store.commit("UPDATECART",res.data.count);
                }else{
                    Toast(res.info);
                }
            });
        }
    },
    watch:{
        array(value){
            let total = 0;
            this.list.forEach((item,index)=>{
                if(this.$tools.in_array(item.id,value)){
                    total += parseFloat(item.price) * item.goods_nums;
                }
            });

            this.total = total * 100;
        }
    }
}
</script>

<style lang="scss" scoped>
.cart-nav /deep/ .van-nav-bar__text{
     color: #555;
 }
.refresh-wrap{
    position: relative;
    width: 100%;
    margin-bottom: 50px;
}
.list-box{
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    .list-item{
        width: 88%;
        height: 80px;
        margin: 0 auto;
        background-color: #fff;
        margin-bottom: 10px;
        border-radius: 5px;
        font-size: 14px;
        padding: 10px 10px;
        &:first-child{
            margin-top: 10px;
        }
        .item-check{
            float: left;
            width: 7%;
            height: 100%;
            position: relative;
            margin-top: 30px;
        }
        .pic{
            float: left;
            width: 20%;
            img {
                width: 80px;
                height: 80px;
            }
        }
        .goods{
            float: right;
            width: 68%;
            font-size: 14px;
            color: #333;
            .t{
                width: 100%;
                height: 40px;
                display: -webkit-box;
                overflow: hidden;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            .m{
                color: #666;
                font-size: 12px;
            }
            .b{
                height: 30px;
                span:first-child{
                    float: left;
                    line-height: 30px;
                    color: red;
                }
                span:last-child{
                    float: right;
                }
            }
        }
    }

}
.van-submit-bar{
    width: 100%;
    height: 50px;
    position: fixed;
    bottom: 49px;
    left: 0;
    border-top: 1px solid #eee;
}
.operator-box{
    position: fixed;
    bottom: 49px;
    left: 0;
    z-index: 100;
    width: 100%;
    height: 50px;
    line-height: 50px;
    background-color: #fff;
    -webkit-user-select: none;
    user-select: none;
    border-top: 1px solid #eee;
    .c{
        height: 50px;
        padding: 0 16px;
        font-size: 14px;
        display: flex;
        align-items: center;
        .l{
            width: 30%;
            float: left;
        }
        .r{
            width: 70%;
            float: right;
            text-align: right;
            .delete{
                background: linear-gradient(to right,#ff6034,#ee0a24);
                color: #fff;
                padding: 10px 35px;
                font-size: 14px;
                text-align: center;
                border-radius: 20px;
            }
            button{
                border-radius: 25px;
                padding: 0 30px;
            }
            button:before {
                padding: 0 30px;
            }
        }
    }

}
</style>
