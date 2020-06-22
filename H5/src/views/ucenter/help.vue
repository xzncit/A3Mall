<template>
    <div>
        <van-nav-bar
            title="帮助中心"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <div class="collapse-box">
            <van-collapse v-model="activeNames" accordion>
                <van-collapse-item v-for="(item,index) in list" :key="index" :title="item.title" :name="item.id">
                    <div v-html="item.content">
                        {{ item.content }}
                    </div>
                </van-collapse-item>
            </van-collapse>
        </div>
    </div>
</template>

<script>
import { NavBar } from 'vant';
import { Collapse, CollapseItem,Toast } from 'vant';
export default {
    name: 'Help',
    components: {
        [NavBar.name]: NavBar,
        [Collapse.name]: Collapse,
        [CollapseItem.name]: CollapseItem
    },
    data() {
        return {
            activeNames: '0',
            list: []
        };
    },
    created() {
        this.$request.get("/ucenter/help").then((res)=>{
            if(res.status){
                this.list = res.data;
                this.activeNames = res.data[0].id;
            }else{
                Toast(res.info);
            }
        }).catch((error)=>{
            Toast("网络出错，请检查网络是否连接");
        });
    },
    methods: {
        prev() {
            this.$tools.prev();
        }
    },
}
</script>

<style scoped>

</style>
