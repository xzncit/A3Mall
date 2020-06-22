import Vue from 'vue'
import Vuex from 'vuex'
import Storage from "../utils/Storage";

Vue.use(Vuex)

export default new Vuex.Store({
    // 属性值
    state: {
        users: Storage.get("users",true),
        tabbar: true
    },
    // 对外方问state属性内容
    getters: {
        getCart: state => {
            let users = Storage.get("users",true);
            if(users == null){
                return 0;
            }

            return users.shop_count;
        }
    },
    // Mutation 必须是同步函数
    // 更改state属性内容
    // 使用：this.$store.commit("setUserInfo",{  });
    mutations: {
        UPDATETABBAR(state, bool){
            state.tabbar = bool;
        },
        UPDATEUSERS(state, data){
            state.users = data;
            Storage.set("users",data);
        },
        DELETEUSERS(state,name){
            state.users = null;
            Storage.delete(name);
        },
        UPDATECART(state, data){
            state.users.shop_count = data;
            Storage.update("users.shop_count",data,true);
        }
    },
    // Action 可以包含任意异步操作
    // 通过 context.commit 可以方问mutations方法
    // 也可以获得getters内容
    // 通过 this.$store.dispatch("getUser") 来取得内容
    actions: {
        getCart(context){
            //console.log(context.getters.cart)
        },
        isUsers(context){
            return new Promise(function (resolve, reject) {
                if(Storage.get("users") == null){
                    reject();
                }else{
                    resolve();
                }
            });
        }
    }
})
