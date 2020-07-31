<template>
    <div>
        <van-nav-bar
                title="我的积分"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <div class="top">
            <div class="top-box">
                <span>可用积分</span>
                <span>{{ point }}</span>
            </div>
        </div>

        <div class="list-wrap">
            <van-list
                v-model="loading"
                :finished="finished"
                finished-text="没有更多了"
                @load="onLoad"
            >
                <div class="point-list">
                    <div class="point-item clear" v-for="(item,index) in list" :key="index">
                        <div class="t">
                            <span>{{item.time}}</span>
                            <span>类型：{{item.operation}}</span>
                        </div>
                        <div class="m">
                            {{item.description}}
                        </div>
                        <div class="b">
                            <span>{{item.point}}</span>
                        </div>
                    </div>
                </div>
            </van-list>
        </div>
    </div>
</template>

<script>
import { NavBar,List,Cell } from 'vant';
export default {
    name: 'Point',
    components: {
        [NavBar.name]: NavBar,
        [List.name]: List,
        [Cell.name]: Cell,
    },
    data() {
        return {
            list: [],
            loading: false,
            finished: false,
            point:0,
            page: 1
        };
    },
    created() {
        this.point = this.$storage.get("users.point",true,0);
    },
    methods: {
        prev() {
            this.$tools.prev();
        },
        onLoad() {
            this.$http.getUcenterPointList({
                page: this.page
            }).then((res) => {
                // 加载状态结束
                this.loading = false;
                if(res.data.list == undefined && this.page == 1){
                    this.finished = true;
                } else if(res.status == 1){
                    this.list = this.list.concat(res.data.list);
                    this.page++;
                    this.point = res.data.point;
                }else if(res.status == -1){
                    this.finished = true;
                }
            }).catch((error) => {
                // 加载状态结束
                this.loading = false;
                this.finished = true;
            });
        },
    },
}
</script>

<style lang="scss" scoped>
.online-box{ width: 90%; margin: 0 5%; margin-top: 20px; }
.top{
    width: 100%;
    height: 150px;
    background-color: #fff;
    padding-top: 10px;
    .top-box{
        background-color: #f7f7f7;
        width: 90%;
        height: 140px;
        margin: 0 auto;
        border-radius: 10px;
        text-align: center;
        color: #666;
        span {
            display: block;
        }
        span:first-child {
            padding-top: 30px;
            font-size: 14px;
        }
        span:last-child{
            padding-top: 20px;
            font-size: 25px;
        }
    }
}
.list-wrap{
    margin-top: 10px;
    .point-list{
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
        font-size: 14px;
        .point-item{
            width: 95%;
            margin: 0 auto;
            margin-bottom: 10px;
            border-radius: 6px;
            height: auto !important;
            height: 130px;
            min-height: 130px;
            background-color: #fff;
            border-bottom: 1px solid #eee;
            .t{
                height: 40px;
                line-height: 40px;
                border-bottom: 1px solid #eee;
                span:first-child{
                    float: left;
                    padding-left: 10px;
                }
                span:last-child{
                    float: right;
                    padding-right: 10px;
                }
            }
            .m{
                padding: 5px 10px;
                display: block;
                height: auto !important;
                height: 50px;
                min-height: 50px;
            }
            .b{
                height: 30px;
                line-height: 30px;
                border-top: 1px solid #eee;
                span{
                    float: right;
                    margin-right: 10px;
                }
            }
        }
    }
}

</style>
