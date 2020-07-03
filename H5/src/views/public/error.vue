<template>
    <div class="wrap">
        <div class="top c">
            <img src="../../assets/images/error.png">
        </div>
        <div class="title c">页面500</div>
        <div class="intro c">抱歉！您当前访问的页面发生错误，请稍后在试</div>
        <div class="btn c">
            <span @click="$router.replace('/')">返回首页</span>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.wrap{
    background-color: #fff;
    height: 100vh;
    .c{
        width: 100%;
        float: left;
        text-align: center;
    }
    .top{
        margin-top: 100px;
        img {
            width: 50%;
        }
    }
    .title{
        font-size: 23px;
        color: #333;
        margin-top: 35px;
    }
    .intro{
        margin-top: 30px;
        color: #666;
    }
    .btn{
        margin-top: 45px;
        span{
            width: 100px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            color: #fff;
            background-color: #b71c1c;
            font-size: 15px;
            display: inline-block;
            border-radius: 5px;
        }
    }
}
</style>