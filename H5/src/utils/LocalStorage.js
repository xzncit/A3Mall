import tools from './Tools';
export default {

    set(name,vlaue){
        if(tools.isDataType(vlaue,"Object")){
            vlaue = JSON.stringify(vlaue);
        }

        localStorage.setItem(name,vlaue);
    },

    get(name,json=false){
        let value = localStorage.getItem(name);
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
        localStorage.removeItem(name);
    },

    clear(){
        localStorage.clear();
    }
}