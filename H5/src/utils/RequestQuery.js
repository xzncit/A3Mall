
export default {

    parse(){
        let res = {};
        let urlStr = location.href;
        let url = "?" + urlStr.split("?")[1];
        if (url.indexOf("?") != -1) {
            let arr = url.substr(1).split("&");
            for (let i = 0; i < arr.length; i++) {
                res[arr[i].split("=")[0]] = decodeURI(arr[i].split("=")[1]);
            }
        }

        return res;
    },

    get(name){
        let param = this.parse();
        if(param[name] != undefined){
            return param[name];
        }

        return null;
    }

}