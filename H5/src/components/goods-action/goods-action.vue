<template>
    <div class="goods-action">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "GoodsAction",
    }
</script>

<style lang="scss" scoped>
.goods-action{
    position: fixed;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    align-items: center;
    box-sizing: content-box;
    height: 50px;
    border-top: 1px solid #d9d9d9;
    padding-bottom: constant(safe-area-inset-bottom);
    padding-bottom: env(safe-area-inset-bottom);
    background-color: #fff;
    z-index: 100000;
    overflow: hidden;
}
</style>