<template>
    <div>
        <van-nav-bar
                title="资讯内容"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />
        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <div v-if="!isEmpty" class="main clear">
            <div class="title">{{data.title}}</div>
            <div class="info">
                <span>{{data.cat_name}}</span>
                <span><van-icon name="clock-o" />{{data.create_time}}</span>
                <span><van-icon name="eye-o" />{{data.hits}}</span>
            </div>
            <div class="content clear" v-html="data.content">{{data.content}}</div>
        </div>

    </div>
</template>

<script>
    import { NavBar } from 'vant';
    import { PullRefresh,List } from 'vant';
    import { Empty,Icon } from 'vant';
    export default {
        name: 'NewsView',
        components: {
            [NavBar.name]: NavBar,
            [PullRefresh.name]: PullRefresh,
            [List.name]: List,
            [Empty.name]: Empty,
            [Icon.name]: Icon,
        },
        data() {
            return {
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "您搜索的关键字暂无内容",
                data:{}
            };
        },
        created() {
            this.$http.getNewsDetail({
                id: this.$route.params.id
            }).then(res=>{
                this.isEmpty = false;
                if(res.status){
                    this.data = res.data;
                }else{
                    this.isEmpty = true;
                }
            }).catch(err=>{
                this.isEmpty = true;
                this.emptyImage = "network";
                this.emptyDescription = "网络出错，请检查网络是否连接";
            });
        },
        methods: {
            prev() {
                this.$tools.prev();
            }
        },
    }
</script>

<style lang="scss" scoped>
    .main{
        background-color: #fff;
        width: 95%;
        margin: 0 auto;
        margin-top: 10px;
        .title{
            width: 100%;
            height: 40px;
            line-height: 25px;
            padding: 0 10px;
            padding-top: 10px;
        }
        .info{
            width: 100%;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            font-size: 13px;
            float: left;
            span{
                float: left;
            }
            span:nth-child(1){
                color: #ff3700;
                border: 1px solid #ff3700;
                font-size: 12px;
                padding: 1px 1px;
                position: relative;
                top: -3px;
                margin-left: 10px;
            }
            span:nth-child(2){
                margin-left: 10px;
                i{
                    position: relative;
                    top: 1px;
                    margin-right: 5px;
                }
            }
            span:nth-child(3){
                margin-left: 10px;
                i{
                    position: relative;
                    top: 2px;
                    margin-right: 5px;
                }
            }
        }
        .content{
            padding: 10px;
            float: left;
        }
    }

</style>
