<template>
    <div>
        <div class="image-box" :style="imageBoxStyle">
            <img :src="image||a3mall" :style="imageStyle">
        </div>
    </div>
</template>

<script>
    export default {
        name: "images",
        props: {
            rdata:{
                required: true,
            }
        },
        data(){
          return {
              a3mall: require("@/assets/images/a3mall.png"),
              image: '',
              imageBoxStyle:{},
              imageStyle:{}
          }
        },
        created() {
            this.image = this.rdata.params.list[0].thumb_image;
            this.imageBoxStyle = {
                height: this.rdata.params.height+"px",
                borderRadius: this.rdata.params.border+"px",
                paddingTop: this.rdata.params.marginTop+"px",
                paddingBottom: this.rdata.params.marginBottom+"px",
                width: 100 - this.rdata.params.marginLeft - this.rdata.params.marginRight + "%"
            };
            this.imageStyle = {
                height: this.rdata.params.height+"px",
                borderRadius: this.rdata.params.border+"px",
            };
        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
.image-box{
    margin: 0 auto;
    img {
        width: 100%;
        height: 100%;
    }
}

</style>