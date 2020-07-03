import router from "../router";

export default {
    prev(n){
        if(window.history.length > 1){
            let m = n || -1;
            router.go(m);
        }else{
            router.push('/');
        }
    },

    in_array(search,array){
        let flag = false;
        for(let i in array){
            if(array[i]==search){
                flag = true;
                break;
            }
        }

        return flag;
    },

    isDataType(data,type){
        return Object.prototype.toString.call(data) === '[object '+type+']';
    },

    isWeiXin() {
        return window.navigator.userAgent.toLowerCase().indexOf("micromessenger") !== -1;
    },

    ltrim(str,char){
        let pos = str.indexOf(char);
        let sonstr = str.substr(pos+1);
        return sonstr;
    },

    rtrim(str,char){
        let pos = str.lastIndexOf(char);
        let sonstr = str.substr(0,pos);
        return sonstr;
    }
}