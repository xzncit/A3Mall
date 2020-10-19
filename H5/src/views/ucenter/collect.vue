<template>
    <div style="background-color: #fff;">
        <nav-bar
                left-arrow
                right-arrow
                right-icon="delete"
                title="我的收藏"
                :fixed="true"
                :transparent="true"
                :z-index="9999"
                @click-left="prev"
                @click-right="onDelete"
        />
        <div style="width: 100%;height: 50px;background-color: #b91922"></div>

        <van-empty v-if="isEmpty" :image="emptyImage" :description="emptyDescription" />
        <van-list
                v-if="!isEmpty"
                v-model="loading"
                :finished="finished"
                finished-text="没有更多了"
                class="collect-list clear"
                style="border-radius: 10px; background-color: #fff"
                @load="loadGoods"
        >
            <van-checkbox-group v-model="array" ref="checkboxGroup">
                <div class="item-box" v-for="(item,index) in list" :key="index">
                    <div class="l">
                        <div class="item-check">
                            <van-checkbox class="c-check" :name="item.id"></van-checkbox>
                        </div>
                        <div class="item-image">
                            <img :src="item.thumb">
                        </div>
                    </div>
                    <div class="r">
                        <div class="title">{{ item.title }}</div>
                        <div class="price">￥{{ item.price }}</div>
                    </div>
                </div>
            </van-checkbox-group>

        </van-list>
    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
    import { List } from 'vant';
    import { Empty,Toast } from 'vant';
    import { Checkbox, CheckboxGroup } from 'vant';
    export default {
        name: 'Collect',
        components: {
            [NavBar.name]: NavBar,
            [List.name]: List,
            [Checkbox.name]: Checkbox,
            [CheckboxGroup.name]: CheckboxGroup,
            [Empty.name]: Empty
        },
        data() {
            return {
                array: [],
                list: [],
                loading: false,
                finished: false,
                page: 1,
                isEmpty: false,
                emptyImage: "search",
                emptyDescription: "您还没有收藏商品哦",
            };
        },
        created() {
            // console.log(this.$route.params.id)
        },
        methods: {
            loadGoods(){
                this.isEmpty = false;
                let emptyCollect = this.$request.domain() + 'static/images/collect-empty.png';
                this.$http.getCollectList({
                    page: this.page
                }).then((result)=>{
                    if(result.data.list == undefined && this.page == 1){
                        this.isEmpty = true;
                        this.emptyImage = emptyCollect;
                        this.emptyDescription = "您还没有收藏商品哦";
                    } else if(result.status == 1){
                        this.list = this.list.concat(result.data.list);
                        this.loading = false;
                        this.page++;
                    }else if(result.status == -1){
                        if(result.data == undefined && this.page == 1){
                            this.isEmpty = true;
                            this.emptyImage = emptyCollect;
                            this.emptyDescription = "您还没有收藏商品哦";
                        }else{
                            this.loading = false;
                            this.finished = true;
                        }
                    }
                }).catch((error)=>{
                    this.isEmpty = true;
                    this.emptyImage = "network";
                    this.emptyDescription = "网络出错，请检查网络是否连接";
                });
            },
            prev() {
                this.$tools.prev();
            },
            onDelete() {
                if(this.array.length <= 0){
                    Toast("请选择要删除的内容");
                    return ;
                }

                let arr = [];
                this.list = this.list.filter((item)=>{
                    if(this.$tools.in_array(item.id,this.array)){
                        arr.push(item);
                    }
                    return !this.$tools.in_array(item.id,this.array);
                });

                if(arr.length <= 0){
                    return ;
                }

                let id = [];
                arr.forEach((item)=>{
                    id.push(item.id);
                })

                this.$http.deleteCollect({
                    id: id.join(",")
                });
            }
        },
    }
</script>

<style lang="scss" scoped>
    .collect-list{
        .item-box {
            width: 90%;
            float: left;
            padding: 0 16px;
            margin-top: 20px;
            border-radius: 10px;
            height: 130px;
            .l {
                width: 160px;
                position: absolute;
                .item-check {
                    float: left; width: 30px; height: 130px;
                    position: relative;
                    .c-check { position: absolute; top: 50%; transform: translateY(-50%); }
                }
                .item-image{
                    img { width: 130px; height: 130px; }
                }
            }
            .r{
                padding-left: 170px;
                color: #888; font-size: 14px;
                .price{
                    padding-top: 10px;
                    font-size: 17px; color: #b91922;
                }
            }
        }
    }
</style>
