<template>
    <div>
        <div class="video-wrap" v-for="(video,index) in rdata.params.list" :key="index">
            <video v-if='rdata.params.autoplay=="true"' autoplay="autoplay" preload muted="muted" :src="video.url" :poster="video.thumb_image" controls="controls" width="100%" height="200px;"></video>
            <video v-if="rdata.params.autoplay=='false'" :src="video.url" :poster="video.thumb_image||a3mall" controls="controls" width="100%" height="200px;"></video>
        </div>
    </div>
</template>

<script>
    export default {
        name: "videoList",
        props: {
            rdata:{
                required: true,
            }
        },
        data(){
            return {
                a3mall: require("@/assets/images/a3mall.png")
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>