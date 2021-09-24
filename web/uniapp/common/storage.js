export default {
	
	set(name,value){
		uni.setStorageSync(name,value);
	},
	
	setJson(name,value){
		uni.setStorageSync(name,JSON.stringify(value));
	},
	
	get(name){
		return uni.getStorageSync(name);
	},
	
	getJson(name){
		const content = uni.getStorageSync(name);
		if(!content){
			return null;
		}
		
		return JSON.parse(content);
	},
	
	remove(name){
		uni.removeStorageSync(name);
	},
	
	clear(){
		uni.clearStorageSync();
	}
};