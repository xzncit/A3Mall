<template>
    <div>
        <van-nav-bar
            title="我的收藏"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />
        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <van-list
            v-if="!isEmpty"
            v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
            @load="loadGoods"
        >

            <van-swipe-cell v-for="(item,i) in list" :key="i">
                <van-card
                    :price="item.price"
                    :origin-price="item.origin_price"
                    :desc="item.desc"
                    :title="item.title"
                    class="goods-card"
                    :thumb="item.thumb"
                    @click="$router.push({ path: '/goods/view/' + item.id })"
                />
                <template #right>
                    <van-button square text="删除" type="danger" @click="deleteGoods(i,item.id)" class="delete-button" />
                </template>
            </van-swipe-cell>

        </van-list>

    </div>
</template>

<script>
import { NavBar } from 'vant';
import { List } from 'vant';
import { SwipeCell,Card,Button,Empty,Toast } from 'vant';
export default {
    name: 'Collect',
    components: {
        [NavBar.name]: NavBar,
        [List.name]: List,
        [SwipeCell.name]: SwipeCell,
        [Card.name]: Card,
        [Button.name]: Button,
        [Empty.name]: Empty,
    },
    data() {
        return {
            list: [],
            loading: false,
            finished: false,
            page: 1,
            isEmpty: false,
            emptyImage: "search",
            emptyDescription: "您还没有收藏商品哦",
        };
    },
    created() {
        // console.log(this.$route.params.id)
    },
    methods: {
        loadGoods(){
            this.isEmpty = false;
            let emptyCollect = this.$request.domain() + 'static/images/collect-empty.png';
            this.$request.get("/ucenter/favorite",{
                page: this.page
            }).then((result)=>{
                if(result.data.list == undefined && this.page == 1){
                    this.isEmpty = true;
                    this.emptyImage = emptyCollect;
                    this.emptyDescription = "您还没有收藏商品哦";
                } else if(result.status == 1){
                    this.list = this.list.concat(result.data.list);
                    this.loading = false;
                    this.page++;
                }else if(result.status == -1){
                    if(result.data == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = emptyCollect;
                        this.emptyDescription = "您还没有收藏商品哦";
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
        prev() {
            this.$tools.prev();
        },
        deleteGoods(index,id){
            this.$request.get("/ucenter/favorite_delete",{
                id: id
            }).then((res) => {
                if(res.status){
                    this.list.splice(index,1);
                    if(this.list.length <= 0){
                        this.isEmpty = true;
                        this.emptyImage = "search";
                        this.emptyDescription = "您还没有收藏商品哦";
                    }
                }else{
                    Toast(res.info);
                }
            }).catch((error) => {
                Toast("网络出错，请检查网络是否连接");
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.goods-card {
    margin: 0;
    background-color: #fff;
    border-bottom: 1px solid #eee;
}

.delete-button {
    height: 100%;
}
</style>
