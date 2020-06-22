<template>
    <div>

        <van-overlay :show="show">
            <div id="loading">
                <van-loading type="spinner" />
            </div>
        </van-overlay>
    </div>
</template>

<script>
    import {Overlay, Loading, Toast} from 'vant';
    export default {
        name: 'Oauth',
        components: {
            [Overlay.name]: Overlay,
            [Loading.name]: Loading
        },
        data() {
            return {
                show: true
            };
        },
        created() {
            if(this.$tools.isWeiXin()){
                this.$request.post("/oauth",{
                    code: this.$route.query.code,
                    state: this.$route.query.state
                }).then(result=>{
                    if(result.status == 2){
                        this.$store.commit("UPDATEUSERS",result.data);
                        let path = this.$storage.get("VUE_REFERER");
                        this.$storage.delete("VUE_REFERER");
                        if(!path){
                            path = "/";
                        }
                        this.$router.push({ path: path });
                    }else{
                        this.$router.push('/');
                    }
                });
            }else{
                this.$router.push('/');
                //console.log(this.$route.query);
            }
        },
        methods: {

        },
    }
</script>

<style lang="scss" scoped>
#loading{
    z-index: 99999;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
}
</style>
