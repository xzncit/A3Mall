<template>
  <div id="app">
    <div class="container" :class="{'container-wrap': isTabbar}">
      <router-view></router-view>
    </div>
    <van-tabbar v-if="isTabbar" route v-model="active" active-color="#b71c1c" inactive-color="#333">
      <van-tabbar-item icon="wap-home-o" to="/" replace>首页</van-tabbar-item>
      <van-tabbar-item icon="apps-o" to="/category/index">分类</van-tabbar-item>
      <van-tabbar-item icon="cart-o" to="/cart/index" :badge="cartCount">购物车</van-tabbar-item>
      <van-tabbar-item icon="friends-o" to="/ucenter/index">我的</van-tabbar-item>
    </van-tabbar>
  </div>
</template>

<script>
import { Tabbar, TabbarItem } from 'vant';
export default {
  name: 'App',
  data() {
    return {
      active: 0
    };
  },
  components: {
    [Tabbar.name]:Tabbar,
    [TabbarItem.name]:TabbarItem
  },
  created(){
  },
  computed:{
    cartCount(){
      let users = this.$store.state.users;
      return users == null ? 0 : users.shop_count;
    },
    isTabbar(){
      return this.$store.state.tabbar;
    }
  }
}
</script>

<style>
body,div,dl,dt,h1,h2,h3,ul,ol,li,p,form,input,textarea,cite,span,strong { margin:0; padding:0; }
.clear:after { content:" "; font-size:0; display:block; height:0; clear:both; visibility:hidden; }
.clear { *height:1%; }
.van-nav-bar i.van-icon { color: #666; }
i{
  font-style: normal;
}
#app {}
.container {
  position: fixed; width: 100%; background-color: #f8f8f8;
  top: 0; bottom:0px; overflow: hidden; overflow-y: auto;
}
.container-wrap {
  bottom: 50px;
}
</style>
