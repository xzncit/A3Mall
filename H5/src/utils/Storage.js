import session from './SessionStorage';
import local from './LocalStorage';
import tools from "./Tools";

export default {

    drive: 1,

    change(type=1){
        this.drive = type;
    },

    init(){
        switch (this.drive) {
            case 1:
                return session;
            case 2:
                return local;
            default:
                return session;
        }
    },

    set(name,vlaue){
        this.init().set(name,vlaue);
    },

    get(name,json=false,def=""){
        let arr = name.split(".");
        let data =  this.init().get(arr[0],json);

        if(data != null && arr.length == 2){
            return data[arr[1]] != undefined ? data[arr[1]] : def;
        }

        return data;
    },

    update(name,value,json=false){
        let arr = name.split(".");

        if(arr.length == 1){
            this.init().set(name,value);
        }else{
            let data = this.init().get(arr[0],json);
            if(data != null && arr.length == 2 && data[arr[1]] != undefined){
                data[arr[1]] = value;
                this.init().set(arr[0],data);
            }
        }

    },

    listener(fn){
        this.init().listener(fn);
    },

    delete(name){
        this.init().delete(name);
    },

    clear(){
        this.init().clear();
    }

}