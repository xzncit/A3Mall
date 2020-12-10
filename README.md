<h1 align="center">A3Mall商城系统</h1> 
<p align="center">
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Website-A3Mall-important.svg" />
    </a>
<a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Licence-GPL3.0-green.svg" />
    </a>
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Edition-v1.5.4-blue.svg" />
    </a>
</p>
<p align="center">    
    <b>如果本系统对您有所帮助，您可以点右上角 "Star" 支持一下 谢谢！</b>
</p>


## 导航栏目
 [官网地址](http://www.a3-mall.com)
 | [微信小程序下载](https://gitee.com/xzncit/A3Mall-miniprogram)
 | [帮助文档](http://doc.a3-mall.com)
 | [安装文档](http://doc.a3-mall.com/help/)


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
Linux下需要给程序根目录下的runtime目录权限
访问：http://域名.com/install
按照提示安装

H5
cd H5  进入前端目录
npm install
修改.env.production配置文件
npm run serve 调试前端页面
npm run build 打包前端页面，复制dist目录内容到public目录替换 index.html static/wap 

小程序
下载好小程序源码，解压
下载安装微信开发者工具
导入微信小程序运行
修改小程序根目录下 config.js 配置文件
```

## Linux Shell命令
```html
定时取消未支付订单
php think task cancle

定时签收已发货订单
php think task sign

定时处理完成订单
php think task complete

定时清理购物车
php think task cart

默认从后台获取订单配置时间
如果需要手动指定时间，可以使用：
php think task cancle|sign|complete|cart --time 30 (单位：天)
```
   
## QQ交流群
 <a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=de316f1a1dbf61859529484891ee50369e3c2bc6fe37e15bb94f8bf731cc3482"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="A3Mall开发交流群" title="A3Mall开发交流群"></a>


![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/1.jpg "1.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/2.jpg "2.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/3.jpg "3.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/4.jpg "4.jpg")
![输入图片说明](https://gitee.com/xzncit/A3Mall/raw/master/readme/images/web/5.jpg "5.jpg")


 **bug反馈**

如果您使用过程中发现BUG或者其他问题都欢迎大家提交Issue,或者发送邮件给我 158373108@qq.com，我们将及时修复并更新。
