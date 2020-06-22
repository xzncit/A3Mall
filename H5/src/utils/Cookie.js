import cookies from 'vue-cookies';

cookies.config(60 * 60 * 24 * 30,'');
export default {

    set(name,value){
        return cookies.set(name,value);
    },

    get(name){
        return cookies.get(name);
    },

    delete(name){
        return cookies.remove(name);
    },

    is(name){
        return cookies.isKey(name);
    },

    all(){
        return cookies.keys();
    }
}