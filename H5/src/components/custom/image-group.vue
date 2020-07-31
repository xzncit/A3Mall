<template>
    <div class="image-wrap clear" :class="'image-group-'+rdata.params.style">
        <div v-if="rdata.params.style==0">
            <div class="display">
                <div class="image-group-left">
                    <img :src="rdata.params.list[0].thumb_image||a3mall" alt="">
                </div>
                <div class="image-group-right">
                    <div class="image-group-right1">
                        <img :src="rdata.params.list[1].thumb_image||a3mall" alt="">
                    </div>
                    <div class="image-group-right2">
                        <div class="left">
                            <img :src="rdata.params.list[2].thumb_image||a3mall" alt="">
                        </div>
                        <div class="right">
                            <img :src="rdata.params.list[3].thumb_image||a3mall" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div
                class="image-group"
                :style="{padding:rdata.params.margin+'px'}"
                v-for="(item,index) in rdata.params.list" :key="index"
            >
                <img :src="item.thumb_image||a3mall" alt="">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ImageGroup",
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
    .image-wrap{
        width: 100%;
        height: auto;
        .image-group{
            float: left;
            box-sizing: border-box;
        }
    }
    .image-wrap .display { height: 0; width: 100%; margin: 0; padding-bottom: 50%; position: relative; }
    .image-wrap .display .image-group-left { width: 50%; height: 100%; position: absolute; top: 0; left: 0; }
    .image-wrap .display .image-group-right { width: 50%; height: 100%; position: absolute; top: 0; left: 50%; }
    .image-wrap .display .image-group-right .image-group-right1 { width: 100%; height: 50%; position: absolute; top: 0; left: 0; }
    .image-wrap .display .image-group-right .image-group-right2 { width: 100%; height: 50%; position: absolute; top: 50%; left: 0; }
    .image-wrap .display .image-group-right .image-group-right2 .left { width: 50%; height: 100%; position: absolute; top: 0; left: 0; }
    .image-wrap .display .image-group-right .image-group-right2 .right { width: 50%; height: 100%; position: absolute; top: 0; left: 50%; }
    .image-group-2 .image-group { width: 50%; height: 150px; }
    .image-group-3 .image-group { width: 33%; height: 150px; }
    .image-group-4 .image-group { width: 25%; height: 150px; }
    .image-wrap img { width: 100%; height: 100%; }
</style>