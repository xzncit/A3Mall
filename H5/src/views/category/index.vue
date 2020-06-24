<template>
    <div>
        <div class="search" @click="$router.push('/search/index')">
            <van-search
                    v-model="value"
                    shape="round"
                    background="#fff"
                    placeholder="请输入搜索关键词"
                    @search="onSearchSubmit"
            />
        </div>

        <div class="category-box" :style="'height:'+clientHeight+'px'">
            <div class="menu">
                <div class="wrapper-menu" ref="menu" :style="'height:'+clientHeight+'px'">
                    <ul>
                        <li
                                v-for="(data,i) in category"
                                :key="i" @click="selectMenu(i)"
                                :class="{active:currentIndex==i}"
                                ref="cat"
                        >{{ data.title }}</li>
                    </ul>
                </div>
            </div>

            <div class="content">
                <div class="wrapper-content" ref="content" :style="'height:'+clientHeight+'px'">
                    <ul>
                        <li v-for="(data,i) in category" :key="i" ref="item">
                            <div class="title">
                                <span>{{ data.title }}</span>
                            </div>
                            <div class="children">
                                <div
                                        v-for="(children, index) in data.children"
                                        :key="index"
                                        @click="$router.push({ path: '/goods/list/' + children.id })"
                                        class="n"
                                >
                                    <span><img :src="children.thumb_img"></span>
                                    <span>{{ children.name }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Form,Search } from 'vant';
    import BScroll from 'better-scroll';
    export default {
        name: 'Category',
        components: {
            [Form.name]: Form,
            [Search.name]: Search,
        },
        data() {
            return {
                value: "",
                clientHeight: window.outerHeight - 100,
                scrollMenu: null,
                scrollContent: null,
                currentIndex:0,
                category:[],
                menuH:[],
                arrH: [],
                scrollY: 0,
                menuScrollY: 0
            };
        },
        created() {

        },
        mounted() {
            this.$request.get("/category").then((result)=>{
                if(result.status){
                    this.category = result.data;
                    this.$nextTick(()=>{
                        this.initScroll();
                    });
                }
            });
        },
        watch: {
            scrollY: function(value){
                this.currentIndex = this.arrH.findIndex((item, index) =>{
                    return value >= this.arrH[index] && value < this.arrH[index + 1];
                });

                this.menuScrollY = this.menuH[this.currentIndex] - this.clientHeight;
                this.scrollMenu.scrollToElement(this.$refs.menu,100,0,this.menuScrollY+40);
            }
        },
        methods: {
            initScroll(){
                this.scrollMenu = new BScroll(this.$refs.menu,{
                    click:true,
                    startY:0,
                    scrollX:false,
                    scrollY: true
                });

                const menuLi = this.$refs.cat;
                let m = 0;
                this.menuH.push(m);
                menuLi.forEach(item=>{
                    m += item.clientHeight;
                    this.menuH.push(m);
                });


                this.scrollContent = new BScroll(this.$refs.content,{
                    probeType: 3,
                    startY:0,
                    click: true,
                    scrollX:false,
                    scrollY: true
                });

                this.scrollContent.on('scroll', (pos) => {
                    this.scrollY = Math.abs(Math.round(pos.y));
                });

                const lis = this.$refs.item;
                let height = 0;
                this.arrH.push(height);
                lis.forEach(item =>{
                    height += item.clientHeight;
                    this.arrH.push(height);
                });
            },
            selectMenu(index){
                this.currentIndex = index;
                this.scrollContent.scrollToElement(this.$refs.item[index], 500);
            },
            onSearchSubmit(value){
                console.log(value)
            }
        },
    }
</script>

<style lang="scss" scoped>
    .search {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10000;
        border-bottom: 1px solid #eee;
    }
    .category-box{
        width: 100%;
        position: fixed;
        z-index: 999;
        top: 55px;
        background-color: #fff;
    }
    .menu {
        width: 25%;
        color: #666;
        background-color: #f7f7f7;
        font-size: 14px ;
        float: left;
        ul li {
            text-align: center;
            height: 40px;
            line-height: 40px;
        }
        .active{
            background-color: #fff;
            color: #c21313;
            border-left: 2px solid #c21313;
        }
    }
    .content {
        width: 75%;
        float: right;
        .title{
            color: #333;
            font-size: 16px;
            font-weight: bold;
            padding-bottom: 5px;
            padding-left: 5px;
        }
        li {
            margin: 19px 7px 0;
        }
        li:last-child{
            padding-bottom: 30px;
        }
        .children{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            .n{
                width: 33.33%;
                text-align: center;
                font-size: 13px;
                span{

                }
                span:last-child{
                    color: #333;
                    height: 35px;
                    margin-top: 2px;
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                }
                img{
                    width: 70px;
                    height: 70px;
                }
            }
        }
    }
</style>
