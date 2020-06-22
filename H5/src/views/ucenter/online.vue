<template>
    <div>
        <van-nav-bar
            title="联系我们"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <div class="online-box">
            <van-button type="primary" round block>QQ客服</van-button>
            <van-button style="margin-top: 25px;" type="danger" round block>电话客服</van-button>
        </div>

    </div>
</template>

<script>
import { NavBar } from 'vant';
import { Button } from 'vant';
export default {
    name: 'Online',
    components: {
        [NavBar.name]: NavBar,
        [Button.name]: Button
    },
    data() {
        return {

        };
    },
    created() {
        // console.log(this.$route.params.id)
    },
    methods: {
        prev() {
            this.$tools.prev();
        }
    },
}
</script>

<style lang="scss" scoped>
.online-box{ width: 90%; margin: 0 5%; margin-top: 20px; }
</style>
