<template>
    <div :style="customWrapStyle">
        <div :style="customInnerStyle" class="search-box" @click="onSearch">
            <van-icon color="search-icon" name="search" />
            <span class="search-text">{{rdata.params.keywords}}</span>
        </div>
    </div>
</template>

<script>
    import { Icon } from 'vant';
    export default {
        name: "search",
        components:{
            [Icon.name]: Icon,
        },
        data(){
            return {
                customWrapStyle:{},
                customInnerStyle:{}
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {
            this.customWrapStyle = {
                marginTop: this.rdata.params.marginTop+"px",
                marginBottom: this.rdata.params.marginBottom+"px",
                marginLeft: this.rdata.params.marginLeft+"px",
                marginRight: this.rdata.params.marginRight+"px"
            };

            this.customInnerStyle = {
                borderRadius: this.rdata.params.border+"px"
            };
        },
        methods: {
            onSearch() {
                this.$router.push('/search/index');
            },
        }
    }
</script>

<style lang="scss" scoped>
    .search-box {
        width: 93%; height: 35px;
        background-color: #fff;
        margin: 15px auto;
        border-radius: 50px;
        position: relative;
        .van-icon-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666666;
            font-size: 25px;
        }
        .search-text {
            position: absolute;
            left: 40px;
            color: #666;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
        }
    }
</style>