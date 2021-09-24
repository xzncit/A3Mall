<template>
    <view>
		<navbar :iSimmersive="false" title-color="#ffffff" background="#1b43c4" :placeholder="true" title="我的地址"></navbar>
        <view v-if="!isLoading && isError == false">
			<view class="address-list">
			    <view class="address-box" v-for="(item,index) in list" :key="index">
			        <view class="t">
			            <view class="t-1">
			                <text>{{item.name}}</text>
			                <text>{{item.tel}}</text>
			            </view>
			            <view class="desc">{{item.address}}</view>
			        </view>
			        <view class="b">
			            <view v-if="item.is_default" class="b-1">默认地址</view>
			            <view class="b-2">
			                <text @click="onEdit(item.id)">修改</text>
			                <text @click="onDelete(item.id)">删除</text>
			            </view>
			        </view>
			    </view>
			
			</view>
			
			<view class="btn">
			    <view class="add" @click="onAdd">新增地址</view>
			</view>
		</view>
		
		<loading v-if="isLoading"></loading>
		<empty-box type="address" v-if="isError && isLoading == false" @onEvents="onAdd"></empty-box>
    </view>
</template>

<script>
import loading from '../../components/tool/loading'
import navbar from "@/components/navbar/navbar";
export default {
	components:{
		loading,navbar
	},
    data() {
        return {
			isLoading:true,
			isError: false,
            list: []
        };
    },
	onShow() {
		let users = this.$storage.getJson("users");
		if(users == null){
			this.$utils.navigateTo('public/login');
		}else{
			this.$http.getAddress().then(res=>{
			    if(res.status){
			        this.list = res.data;
					if(this.list.length){
						this.isLoading = false;
						this.isError = false;
					}else{
						this.isLoading = false;
						this.isError = true;
					}
			    }else{
					this.isLoading = false;
					this.isError = true;
				}
			}).catch(err=>{
				this.isLoading = false;
				this.isError = true;
			});
		}
	},
    methods: {
        onDelete(id){
            this.list = this.list.filter(function (item) {
                if(item.id != id){
                    return item;
                }
            });

            this.$http.editorAddressDelete({ id: id });
			if(this.list.length){
				this.isLoading = false;
				this.isError = false;
			}else{
				this.isLoading = false;
				this.isError = true;
			}
        },
		onAdd(){
			this.$utils.navigateTo('ucenter/address_editor');
		},
        onEdit(id) {
			this.$utils.navigateTo('ucenter/address_editor',{ id: id });
        }
    },
}
</script>

<style lang="scss" scoped>
.address-list{
    .address-box{
        background-color: #fff;
        width: 100%;
        height: auto !important;
        height: 260rpx;
        float: left;
        margin-top: 30rpx;
        min-height: 260rpx;
        .t{
            padding: 30rpx;
            border-bottom: 2rpx solid #e8e8e8;
            color:#777;
            font-size: 30rpx;
            .t-1{
                height: 52rpx;
                min-height: 52rpx;
                text:first-child{
                    float: left;
                }
                text:last-child{
                    float: right;
                }
            }
            .desc{

            }
        }
        .b{
            height: 100rpx;
            color:#777;
            font-size: 30rpx;
            padding: 0 30rpx;
            .b-1 {
                float: left;
                display: inline-block;
                color: #1b43c4;
                border: 1rpx solid #1b43c4;
                border-radius: 10rpx;
                font-size: 24rpx;
                width: 130rpx;
                height: 50rpx;
                line-height: 50rpx;
                text-align: center;
                margin-top: 24rpx;
            }
            .b-2{
                float: right;
                text {
                    display: inline-block;
                    color: #777777;
                    border: 1rpx solid #777777;
                    border-radius: 10rpx;
                    font-size: 24rpx;
                    width: 130rpx;
                    height: 50rpx;
                    line-height: 50rpx;
                    text-align: center;
                    margin-top: 24rpx;
                    margin-left: 20rpx;
                }
                text:last-child{
					    
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
    height: 120rpx;
    padding-top: 20rpx;
    .add{
        display: block;
        height: 100rpx;
        line-height: 100rpx;
        text-align: center;
        background-color: #1b43c4;
        color: #fff;
        margin: 0 30rpx;
        border-radius: 10rpx;
    }
}
</style>
