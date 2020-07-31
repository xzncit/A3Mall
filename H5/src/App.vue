<template>
  <div id="app">
    <div class="container" :class="{'container-wrap': isTabbar}">
      <router-view></router-view>
    </div>

    <van-tabbar v-if="isTabbar" route v-model="active" active-color="#b71c1c" inactive-color="#333">
      <van-tabbar-item icon="wap-home-o" to="/" replace>
        <span>首页</span>
        <template #icon="props">
          <img v-if="!props.active" src="@/assets/images/tabbar/menu-1.png">
          <img v-if="props.active" src="@/assets/images/tabbar/menu-active-1.png">
        </template>
      </van-tabbar-item>
      <van-tabbar-item icon="apps-o" to="/category/index">
        <span>分类</span>
        <template #icon="props">
          <img v-if="!props.active" src="@/assets/images/tabbar/menu-2.png">
          <img v-if="props.active" src="@/assets/images/tabbar/menu-active-2.png">
        </template>
      </van-tabbar-item>
      <van-tabbar-item icon="cart-o" to="/cart/index" :badge="cartCount">
        <span>购物车</span>
        <template #icon="props">
          <img v-if="!props.active" src="@/assets/images/tabbar/menu-3.png">
          <img v-if="props.active" src="@/assets/images/tabbar/menu-active-3.png">
        </template>
      </van-tabbar-item>
      <van-tabbar-item icon="friends-o" to="/ucenter/index">
        <span>我的</span>
        <template #icon="props">
          <img v-if="!props.active" src="@/assets/images/tabbar/menu-4.png">
          <img v-if="props.active" src="@/assets/images/tabbar/menu-active-4.png">
        </template>
      </van-tabbar-item>
    </van-tabbar>
  </div>
</template>

<script>
import { Tabbar, TabbarItem } from 'vant';
export default {
  name: 'App',
  data() {
    return {
      active: 0,
      icon: {
        home: {
          active: '../assets/images/tabbar/menu-1.png',
          inactive: '../../assets/images/tabbar/menu-1.png',
        }
      },
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
