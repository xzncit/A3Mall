import tools from './Tools';
export default {

    set(name,vlaue){
        if(tools.isDataType(vlaue,"Object")){
            vlaue = JSON.stringify(vlaue);
        }

        sessionStorage.setItem(name,vlaue);
    },

    get(name,json=false){
        let value = sessionStorage.getItem(name);
        if(json){
            return JSON.parse(value);
        }

        return value;
    },

    listener(fn){
        // console.log('key', e.key); console.log('oldValue', e.oldValue);
        // console.log('newValue', e.newValue); console.log('url', e.url);
        window.addEventListener('storage', function (e) {
            fn(e);
        })
    },

    delete(name){
        sessionStorage.removeItem(name);
    },

    clear(){
        sessionStorage.clear();
    }
}