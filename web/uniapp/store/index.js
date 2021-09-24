import Vue from 'vue'
import Vuex from 'vuex'
import storage from '../common/storage'

Vue.use(Vuex);

const store = new Vuex.Store({
	// 属性值
	state: {
		users: storage.getJson("users")
	},
	// 对外方问state属性内容
	getters: {
		getCart: state => {
			let users = storage.getJson("users");
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
		UPDATEUSERS(state, data){
			state.users = data;
			storage.setJson("users",data);
		},
		DELETEUSERS(state,name){
			state.users = null;
			storage.remove(name);
		},
		UPDATECART(state, data){
			state.users.shop_count = data;
			let users = storage.getJson("users");
			users.shop_count = data;
			storage.setJson("users",users);
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
		usersStatus(context){
			return new Promise(function (resolve, reject) {
				let users = storage.getJson("users");
				if(users == null || users.token == undefined){
					reject();
				}else{
					resolve();
				}
			});
		}
	}
})

export default store