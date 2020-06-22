<template>
    <div>
        <van-nav-bar
            title="搜索商品"
            left-arrow
            @click-left="prev"
        />

        <div class="search-form-box">
            <form action="/search/view" method="get">
                <van-search
                        v-model="value"
                        show-action
                        placeholder="请输入搜索关键词"
                        @search="onSearch"
                >
                <template #action>
                    <div @click="onSearch">搜索</div>
                </template>
                </van-search>
            </form>
        </div>

        <div class="search-host-list clear">
            <div class="host-list clear">
                <div class="title">热门搜索</div>
                <div class="list">
                    <span v-for="(item,i) in keywords" :key="i" @click="onSearch(item)">{{ item }}</span>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import { NavBar } from 'vant';
import { Search } from 'vant';
export default {
    name: 'Search',
    components: {
        [NavBar.name]: NavBar,
        [Search.name]: Search
    },
    data() {
        return {
            value:"",
            keywords:[]
        };
    },
    created() {
        this.$request.get("/search").then((result)=>{
            if(result.status){
                this.keywords = result.data;
            }
        });
    },
    methods: {
        prev() {
            this.$tools.prev();
        },
        onSearch(val){
            if(typeof val == 'string'){
                this.value = val;
            }
            this.$router.push({
                path:'/search/list/',
                query: { keywords: this.value }
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.search-host-list {
    width: 100%; margin-top: 10px;
    height: auto !important;
    height: 100px; min-height: 100px;
    padding: 10px 0;
    background-color: #fff;
    .host-list{
        .title{
            float: left;
            color: #666;
            font-size: 16px;
            width: 100%;
            height: 45px;
            line-height: 45px;
            text-indent: 10px;
        }
        .list{
            span {
                font-size: 14px;
                padding: 5px 10px;
                background-color: #f1f1f1;
                color: #333;
                margin: 5px 10px;
                border-radius: 10px;
                float: left;
            }
        }
    }
}

</style>
