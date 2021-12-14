export default {
	
	set(name,value){
		sessionStorage.setItem(name,vlaue);
	},
	
	setJson(name,value){
		sessionStorage.setItem(name,JSON.stringify(value));
	},
	
	get(name){
		return sessionStorage.getItem(name);
	},
	
	getJson(name){
		const content = sessionStorage.getItem(name);
		if(!content){
			return null;
		}
		
		return JSON.parse(content);
	},
	
	remove(name){
		sessionStorage.removeItem(name);
	},
	
	clear(){
		sessionStorage.clear();
	}
};