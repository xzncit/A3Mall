<template>
    <div>
        <div v-if="!isEmpty" class="notice-box">
            <van-notice-bar @click="onNotice" color="#b91922" background="transparent" left-icon="volume-o" :text="text" />
        </div>
    </div>
</template>

<script>
    import {NoticeBar, Toast} from 'vant';
    export default {
        name: "notice",
        components:{
            [NoticeBar.name]:NoticeBar
        },
        data(){
            return {
                text: "",
                isEmpty: false
            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {
            if(this.rdata.params.list[0] == undefined){
                this.isEmpty = true;
            }else{
                this.text = this.rdata.params.list[0].title;
            }
        },
        methods: {
            onNotice(){
                this.$router.push('/news/view/'+this.rdata.params.list[0].id);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .notice-box /deep/ .van-notice-bar { background: transparent; }
</style>