<template>
    <div>
        <div class="textarea-box" v-html="rdata.params">
            {{rdata.params}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "textareas",
        components:{

        },
        data(){
            return {

            };
        },
        props: {
            rdata:{
                required: true,
            }
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>
.textarea-box{
    width: 100%;
    background-color: #fff;
    height: auto;
    margin: 10px auto;
    padding: 10px 20px;
}
</style>