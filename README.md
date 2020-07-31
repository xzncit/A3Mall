<h1 align="center">A3Mall商城系统</h1> 
<p align="center">
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Website-A3Mall-important.svg" />
    </a>
<a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Licence-GPL3.0-green.svg" />
    </a>
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Edition-v1.0-blue.svg" />
    </a>
</p>
<p align="center">    
    <b>如果本系统对您有所帮助，您可以点右上角 "Star" 支持一下 谢谢！</b>
</p>


## 项目介绍
   A3Mall商城系统是基于ThinkPhp6.0+Vue开发的一套移动电商系统，
   支持微信公众号商城、H5商城、小程序商城，支持多种营销活动，优惠劵、订单活动、团购、秒杀、会员特价、积分商品等功能。前后端全部开源。
   
## 软件架构
    PHP >= 7.2.0
    MySQL >= 5.6
    PDO PHP Extension
    MBstring PHP Extension
   
## 安装A3Mall

```html
下载好程序文件，解压上传到web根目录
需要绑定域名访问到public目录，确保其它目录不在WEB目录下面
访问：http://域名.com/install
按照提示安装

前端页面
cd H5  进入前端目录
修改.env.production配置文件
npm run serve 调试前端页面
npm run build 打包前端页面，复制dist目录内容到public目录替换 index.html static/wap 
```

## Linux Shell命令
```html
定时取消未支付订单
php think task cancle

定时签收已发货订单
php think task sign

定时完成已评价订单
php think task complete

定时清理购物车
php think task cart

默认从后台获取订单配置时间
如果需要手动指定时间，可以使用：
php think task cancle|sign|complete|cart --time 30 (单位：天)
```

## 功能介绍
- 平台，微信管理，内容管理，运营管理，媒体管理，版本管理
- 商品，商品，分类，品牌，规格，模型，配送，物流，地区，发货
- 订单，支付，收款，发货，退款，售后等
- 会员，会员管理，会员分组，财务管理，评论管理
- 营销，商品促销、订单促销、优惠券、团购，秒杀，特价，积分
- 统计，搜索统计，数据统计
- 系统，站点设置，邮箱设置，门店设置，上传设置，短信，权限，管理员，日志

   
## QQ交流群
 <a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=de316f1a1dbf61859529484891ee50369e3c2bc6fe37e15bb94f8bf731cc3482"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="A3Mall开发交流群" title="A3Mall开发交流群"></a>

## 前台H5演示

<img src="https://gitee.com/xzncit/A3Mall/raw/master/readme/images/qrcode.png" width="300" height="300" alt="前台H5演示" align="center" />

<br>

账号：18319517777  密码：admin888


## 演示站后台
后台演示暂时不提供，如果需要可以自行安装查看默认帐号密码是

账号：admin  密码：admin888


### 前台截图演示
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/1.jpg "1.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/2.jpg "2.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/3.jpg "3.jpg")


### 后台截图演示
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/0.png "0.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/1.png "1.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/2.png "2.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/3.png "3.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/4.png "4.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/5.png "5.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/6.png "6.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/7.png "7.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/8.png "8.png")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/a/9.png "9.png")







