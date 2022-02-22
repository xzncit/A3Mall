<h1 align="center">A3Mall V2开源商城系统</h1> 

<p align="center">    
    <b>如果对您有所帮助，您可以点右上角 "Star" 收藏一下 ，获取第一时间更新，谢谢！</b>
</p>

## 导航栏目
 [官网地址](http://www.a3-mall.com)
 | [文档中心](http://doc.a3-mall.com/uniapp-b2c/)
 | [视频教程](http://doc.a3-mall.com/uniapp-b2c/video)


## 项目介绍
   A3Mall 后端基于 ThinkPHP6 + Bootstrap 开发的开源商城系统，前端采用uniapp开发，支持微信公众号商城、H5商城、小程序商城、APP商城、PC商城，前后端源码100%开源，支持免费商用。


## 功能特性：
    遵守MVC设计模式，并且具有明确的model和service层
    基于命名空间和PSR-4规范，加入PHP7新特性
    内置WeChat微信开发框架，二次开发更方便
    Composer一键引入三方扩展
    代码注释清晰，方便二次开发功能
    接口认证采用JWT方式认证
    支持在线一键安装，方便快捷
    统一附件管理
    前后端所有代码开源

## 软件架构：
    PHP 7.2.5>=、7.3、7.4、8.0
    MySQL >= 5.6
    Thinkphp 6.0.7
    uniapp


## 安装A3Mall

```html
安装后端程序
1. 下载好程序文件，解压上传到web根目录
2. 需要绑定域名访问到public目录，确保其它目录不在WEB目录下面
3. Linux下需要给程序根目录下的runtime目录权限
4. 访问：http://域名.com/install
5. 按照提示安装

使用uni-app发布H5端
1. 打开HBuilderX -> 顶部菜单栏 -> 发行 -> 网站H5-手机版
2. 打包后的文件路径：/unpackage/dist/build/h5
3. 将打包完成的所有文件 复制到商城后端/pulic/wap目录下，全部替换

使用uni-app发布APP端
1. 打开HBuilderX -> 顶部菜单栏 -> 发行 -> 原生APP-云打包
2. 打包后的文件路径：/unpackage/release/apk
3. 使用真机安装测试

```

## QQ交流群
A3Mall开源商城官方群: 892150829  <a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=lBxucAil6e6WTlwX0tNvQwpOtfLP2ptd&jump_from=webapi"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="A3Mall官方交流1群" title="A3Mall官方交流1群"></a>

### 页面展示
![输入图片说明](https://github.com/xzncit/A3Mall/blob/master/readme/images/1.png "1.png")
![输入图片说明](https://github.com/xzncit/A3Mall/blob/master/readme/images/2.png "2.png")
![输入图片说明](https://github.com/xzncit/A3Mall/blob/master/readme/images/3.png "3.png")

### 产品终端
![输入图片说明](https://github.com/xzncit/A3Mall/blob/master/readme/images/4.png "4.png")

### 后端演示
后台地址：https://demo.a3-mall.com/admin
用户：admin
密码：admin888

前台地址：http://demo.a3-mall.com/
用户：18026740326
密码：admin888

### 商业版演示
后台演示
地址：http://b2cv2.a3-mall.com/admin
账号：admin
密码：admin888

PC商城演示
网址：http://b2cv2.a3-mall.com/
账号：18026740326
密码：admin888

移动端
网址：https://b2cv2.a3-mall.com/wap/
账号：18026740326
密码：admin888

客服端
网址：http://b2cv2.a3-mall.com/online
账号：demo
密码：admin888

#### 后端功能列表
```
平台
    控制面板
        > 站点首页
        > 系统信息
        > 清理缓存
    微信管理
        > 公众号
            > 接口设置
            > 支付设置
            > 粉丝管理
            > 图文管理
            > 菜单管理
            > 回复管理
            > 关注回复
            > 默认回复
        > 小程序
            > 支付设置
            > 基本设置
            > 小程序码
            > 订阅消息
    内容管理
        > 文章列表
        > 分类列表
        > 单页列表
    运营管理
        > 导航列表
        > 数据列表
        > 搜索词组
    媒体管理
        > 图片列表
        > 资源列表
    版本管理
        > 版本列表
商品
    商品管理
        > 商品列表
        > 分类列表
        > 品牌列表
        > 规格列表
        > 模型列表
订单
    订单管理
        > 订单列表
        > 收款列表
        > 发货列表
        > 退款列表
    订单设置
        > 基本设置
会员
    会员管理
        > 会员列表
        > 等级列表
        > 会员标签
    评论管理
        > 评价列表
        > 咨询列表
        > 举报管理
        > 建议管理
    财务管理
        > 提现申请
        > 佣金日志
        > 资金日志
        > 退款日志
        > 积分日志
        > 经验日志
    会员设置
        > 基础设置
        > 注册协议
营销
     营销管理
        > 订单活动
     优惠券管理
        > 优惠券列表
统计
     销售排行
        > 销售统计
        > 购买排行
        > 销售明细
        > 商品排行
     数据统计
        > 订单统计
        > 注册统计
        > 销售统计
        > 消费统计
     搜索统计
        > 搜索分析
        > 搜索排行
        > 搜索明细
系统
     站点管理
        > 站点设置
        > 邮箱设置
        > 物流设置
        > 上传设置
        > 联系方式
     开放平台
        > 快捷登录
        > 支付管理
     短信管理
        > 短信设置
        > 短信模板
     角色管理
        > 用户管理
        > 权限管理
     系统日志
        > 登录日志
```

### 开源版使用须知

- 允许个人学习、毕设、公益、教学案例；
- 支持个人商业用途，仅限自运营，但必须保留A3Mall版权信息；
- 不允许对程序代码以任何形式任何目的的再发行或出售，否则将追究侵权者法律责任;
- 如果需要商业使用推荐购买商业版；

### 版权信息
本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2020-2021 by A3Mall (http://www.a3-mall.com) All rights reserved。

 **bug反馈**

如果您使用过程中发现BUG或者其他问题都欢迎大家提交Issue，我们将及时修复并更新。
