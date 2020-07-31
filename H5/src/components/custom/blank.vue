<template>
    <div class="blank-box" :style="{height:rdata.params.height+'px', backgroundColor: rdata.params.backgroundColor }"></div>
</template>

<script>
    export default {
        name: "blank",
        props: {
            rdata:{
                required: true,
            }
        }
    }
</script>

<style lang="scss" scoped>
    .blank-box{
        width: 100%;
    }
</style>