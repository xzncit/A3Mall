<template>
    <div>
        <div class="grid-box">
            <div class="grid-box-item" :style="gridStyle" v-for="(value,i) in rdata.params.list" :key="i" @click="$router.push(value.url)">
                <span class="grid-box-item-img">
                    <img :src="value.thumb_image">
                </span>
                <span class="grid-box-item-text">{{ value.text }}</span>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "navbar",
        components:{

        },
        data(){
            return {
                gridStyle:{}
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {
            let value = "33.333";
            if(this.rdata.params.limit == 4){
                value = "25";
            }else if(this.rdata.params.limit == 5){
                value = "20";
            }

            this.gridStyle = { width: value + "%" };
        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
    .grid-box{background: #fff;display: flex;flex-direction: row;flex-wrap: wrap;padding-top: 10px;}
    .grid-box-item {width: 25%;height: auto !important;height: 80px;min-height: 80px;text-align: center;}
    .grid-box-item-img {display: block;}
    .grid-box-item-img img {width: 50px;height: 50px;border-radius: 50%;}
    .grid-box-item-text {display: block;font-size: 13px;color: #666;width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
</style>