<template>
    <div>
        <van-nav-bar
            title="我的地址"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <div class="address-list">
            <div class="address-box" v-for="(item,index) in list" :key="index">
                <div class="t">
                    <div class="t-1">
                        <span>{{item.name}}</span>
                        <span>{{item.tel}}</span>
                    </div>
                    <div class="desc">{{item.address}}</div>
                </div>
                <div class="b">
                    <div v-if="item.is_default" class="b-1">默认地址</div>
                    <div class="b-2">
                        <span @click="onEdit(item.id)">修改</span>
                        <span @click="onDelete(item.id)">删除</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="btn">
            <div class="add" @click="$router.push('/ucenter/address/add')">新增地址</div>
        </div>
    </div>
</template>

<script>
import { NavBar } from 'vant';
import { AddressList } from 'vant';
export default {
    name: 'Address',
    components: {
        [NavBar.name]: NavBar
    },
    data() {
        return {
            list: []
        };
    },
    created() {
        this.$http.getAddress().then(res=>{
            if(res.status){
                this.list = res.data;
            }
        });
    },
    methods: {
        prev() {
            this.$tools.prev();
        },
        onDelete(id){
            this.list = this.list.filter(function (item) {
                if(item.id != id){
                    return item;
                }
            });

            this.$http.editorAddressDelete({
                id: id
            });
        },
        onEdit(id) {
            this.$router.push({ name: "AddressEditor", params: { id: id } });
        },
    },
}
</script>

<style lang="scss" scoped>
.address-list{
    .address-box{
        background-color: #fff;
        width: 100%;
        height: auto !important;
        height: 130px;
        float: left;
        margin-top: 15px;
        min-height: 130px;
        .t{
            padding: 15px;
            border-bottom: 1px solid #e8e8e8;
            color:#777;
            font-size: 15px;
            .t-1{
                height: 26px;
                min-height: 26px;
                span:first-child{
                    float: left;
                }
                span:last-child{
                    float: right;
                }
            }
            .desc{

            }
        }
        .b{
            height: 50px;
            color:#777;
            font-size: 15px;
            padding: 0 15px;
            .b-1 {
                float: left;
                display: inline-block;
                color: #b91922;
                border: 1px solid #b91922;
                border-radius: 5px;
                font-size: 12px;
                width: 65px;
                height: 25px;
                line-height: 25px;
                text-align: center;
                margin-top: 12px;
            }
            .b-2{
                float: right;
                span {
                    display: inline-block;
                    color: #777777;
                    border: 1px solid #777777;
                    border-radius: 5px;
                    font-size: 12px;
                    width: 65px;
                    height: 25px;
                    line-height: 25px;
                    text-align: center;
                    margin-top: 12px;
                }
                span:last-child{
                    margin-left: 10px;
                }
            }
        }
    }
}
.btn{
    width: 100%;
    background-color: #fff;
    position: fixed;
    left: 0;
    bottom: 0;
    height: 60px;
    padding-top: 10px;
    .add{
        display: block;
        height: 50px;
        line-height: 50px;
        text-align: center;
        background-color: #b91922;
        color: #fff;
        margin: 0 15px;
        border-radius: 5px;
    }
}
</style>
