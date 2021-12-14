<style>
	.jcPopUp{
		top:0;
		left: 0;
		width: 100%;
		height: 100%;
		position: fixed;
		z-index:9999;
	}
    .jcPopUp-mark{
		top: 0; 
		left: 0;
		width: 100%;
		height: 100%;
		z-index:9997;
		position: absolute;
		background: rgba(0,0,0,0.5);
    }
	.jcPopUp-content{
		width:100%;
		height:100%;
		top:0;
		left:0;
		position: absolute;
		z-index:9998;
	}
	.st{
		animation: showTop 0.25s;
	}
	.sl{
		animation: showLeft 0.25s;
	}
	.sr{
		animation: showRight 0.25s;
	}
	.sb{
		animation: shoBottom 0.25s;
	}
	.ht{
		animation: hideTop 0.25s;
	}
	.hl{
		animation: hideLeft 0.25s;
	}
	.hr{
		animation: hideRight 0.25s;
	}
	.hb{
		animation: hideBottom 0.25s;
	}
	@keyframes showTop{
		from {
			transform: translateY(-100%);
		}
		to {
			transform: translateY(0);
		}
	}
	@keyframes showLeft{
		from {
			transform: translateX(-100%);
		}
		to {
			transform: translateY(0);
		}
	}
	@keyframes showRight{
		from {
			transform: translateX(100%);
		}
		to {
			transform: translateX(0);
		}
	}
	@keyframes showBottom{
		from {
			transform: translateY(100%);
		}
		to {
			transform: translateY(0);
		}
	}
	@keyframes hideTop{
		from {
			transform: translateY(0);
		}
		to {
			transform: translateY(-100%);
		}
	}
	@keyframes hideLeft{
		from {
			transform: translateY(0);
		}
		to {
			transform: translateX(-100%);
		}
	}
	@keyframes hideRight{
		from {
			transform: translateX(0);
		}
		to {
			transform: translateX(100%);
		}
	}
	@keyframes hideBottom{
		from {
			transform: translateY(0);
		}
		to {
			transform: translateY(100%);
		}
	}
</style>
 
<template name="jc-popUp">
	<view class="jcPopUp" v-show="popshow" @click.stop="" v-if="markMove=='false'" @touchmove.stop="">	 
		<view class="jcPopUp-mark"></view>	
		<view class="jcPopUp-content" @tap="markTap" :class="position=='top'&&!hideanimation?'st':position=='left'&&!hideanimation?'sl':position=='right'&&!hideanimation?'sr':position=='bottom'&&!hideanimation?'sb':position=='top'&&hideanimation?'ht':position=='left'&&hideanimation?'hl':position=='right'&&hideanimation?'hr':position=='bottom'&&hideanimation?'hb':''">	
			<view style="z-index:1000000;" @tap.stop="">
				<slot></slot>
			</view>			 
			<icon type="clear" :size="iconSize" color="#FFFFFF" style="position:absolute;" :style="'left:'+iconLeft+'%;top:'+iconTop+'%;'" v-if="closeIcon=='true'" @tap="iconTap"/>
		</view>				
	</view>
	<view class="jcPopUp" v-show="popshow" @click.stop="" @tap.stop="" v-else="">
		<view class="jcPopUp-mark"></view>
		<view class="jcPopUp-content" @tap="markTap" :class="position=='top'&&!hideanimation?'st':position=='left'&&!hideanimation?'sl':position=='right'&&!hideanimation?'sr':position=='bottom'&&!hideanimation?'sb':position=='top'&&hideanimation?'ht':position=='left'&&hideanimation?'hl':position=='right'&&hideanimation?'hr':position=='bottom'&&hideanimation?'hb':''">
			<view style="z-index:1000000;" @tap.stop="">
				<slot></slot>
			</view>	 			
			<icon type="clear" :size="iconSize" color="#FFFFFF" style="position:absolute;" :style="'left:'+iconLeft+'%;top:'+iconTop+'%;'" v-if="closeIcon=='true'" @tap="iconTap"/>
		</view>						
	</view>
</template>

<script>
	export default {
		props:{
			position:{	//弹层出现的方位
				type:String,
				default:""  
			},
			markTapHide:{	//点击蒙层是否关闭弹层
				type:String,
				default:''
			},
			markMove:{	// 蒙层是否可以滑动击穿
				type:String,
				default:'false'
			},
			closeIcon:{	//是否显示 关闭 图标
				type:String,
				default:''
			},
			iconSize:{	//图标大小
				type:String,
				default:'26'
			},
			iconTop:{	//图标距离顶部的位置
				type:String,
				default:'0'
			},
			iconLeft:{	//图标距离左边的位置
				type:String,
				default:'0'
			}
		},
		data() { 
			return { 
				popshow:false,
				hideanimation:false
			};
		},
		methods:{
			show(){
				this.popshow = true;
			},
			hide(){
				let that = this;			
				that.hideanimation = true;
				if(that.position==null){
					that.popshow = false;
				}else{
					setTimeout(function(){
						that.popshow = false;
						that.hideanimation = false;
					},250)
				};				
			},
			markTap(){
				let that = this;
				if(that.markTapHide != 'true'){
					return false
				};				
				that.hideanimation = true;
				if(that.position==null){
					that.popshow = false;
				}else{
					setTimeout(function(){
						that.popshow = false;
						that.hideanimation = false;
					},250)
				};				
			},
			iconTap(){
				let that = this;
				if(that.closeIcon != 'true'){
					return false
				};				
				that.hideanimation = true;
				if(that.position==null){
					that.popshow = false;
				}else{
					setTimeout(function(){
						that.popshow = false;
						that.hideanimation = false;
					},250)
				};
			}
		},
	}
</script>

