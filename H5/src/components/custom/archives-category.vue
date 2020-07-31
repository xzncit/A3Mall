<template>
    <div>
        <div class="arc-wrap">
            <div class="title">
                <span>{{rdata.params.title}}</span>
                <span>更多 &gt;</span>
            </div>
            <div class="arc-list">
                <div
                        class="arc-box"
                        v-for="(item,index) in rdata.params.list"
                        :key="index"
                >
                    <div class="l">
                        <div>{{item.title}}</div>
                        <div>{{item.intro||""}}</div>
                        <div>
                            <span>{{item.create_time}}</span>
                            <span>阅读{{item.hits}}</span>
                        </div>
                    </div>
                    <div class="r">
                        <img :src="item.photo||a3mall">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ArchivesCategory",
        data(){
            return {
                a3mall: require("@/assets/images/a3mall.png")
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {

        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
.arc-wrap{
    background-color: #fff;
    .title{
        width: 100%;
        height: 49px;
        line-height: 49px;
        border-bottom: 1px solid #f1f1f1;
        font-size: 17px;
        color: #333;
        span:first-child{
            float: left;
            margin-left: 15px;
        }
        span:last-child {
            float: right;
            margin-right: 15px;
            color:#999;
            font-size: 15px;
        }
    }
    .arc-list{
        width: 92%;
        height: auto;
        margin: 0 auto;
        padding: 10px 0;
        .arc-box{
            padding: 10px 0;
            height: 65px;
            position: relative;
            .l{
                height: 65px;
                position: relative;
                padding-right: 115px;
                div:nth-child(1){ font-size: 14px; color: #333; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 1;-webkit-box-orient: vertical; }
                div:nth-child(2){ padding-top: 5px; font-size: 12px; color: #999; display: -webkit-box;overflow: hidden;-webkit-line-clamp: 1;-webkit-box-orient: vertical; }
                div:nth-child(3){
                    position: absolute; bottom: 0; left: 0; font-size: 12px; color: #999;
                    span:first-child { padding-right: 10px; position: relative; }
                    span:first-child:after { position: absolute; top: 2px; right: 4px; background-color: #b6b6b6; content: " "; width: 1px; height: 12px; }
                    span:last-child {  }
                }
            }
            .r{
                position: absolute;
                top: 10px;
                right: 0;
                width: 110px;
                height: 65px;
                background-color: #909399;
                img {
                    width: 110px;
                    height: 65px;
                }
            }
        }
    }
}
</style>