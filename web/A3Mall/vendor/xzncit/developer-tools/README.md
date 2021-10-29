<h1 align="center">PHP Wechat Development Framework</h1> 
<p align="center">
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Website-A3Mall-important.svg" />
    </a>
<a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Licence-GPL3.0-green.svg" />
    </a>
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Edition-v0.2.8-blue.svg" />
    </a>
</p>
<p align="center">    
    <b>如果本PHP WeChat 开发包对您有所帮助，您可以点右上角 "Star" 支持一下 谢谢！</b>
</p>

#### 环境要求
- PHP >= 7.2.5
- Composer
- Openssl PHP Extension
- MBstring PHP Extension
- FileInfo PHP Extension

#### 安装
```
composer require "xzncit/developer-tools:^0.2.3"
```

#### 基本使用
```php
include "vendor/autoload.php";

use xzncit\Factory;

try {
    // wechat 
    $app = Factory::Wechat([
        "token"=>"omJNpZEhZ5VFbk1HeHj1ZxFECKkP48BP",
        "appid"=>"wxa02e77d8a507d608",
        "appsecret"=>"3396f50c1f55c2089f4316b6f7c9f71b"
    ]);
    
    // 创建自定义菜单
    $response = $app->menu->create([
        // ...
    ]);
    
    // 返回信息
    var_dump($response);
}catch (\Exception $ex){
    echo("error: ".$ex->getMessage());
}

try {
    // mini
    $app = Factory::MiniProgram([
        "appid"=>"wxa02e77d8a507d608",
        "appsecret"=>"3396f50c1f55c2089f4316b6f7c9f71b"
    ]);
    
    // 登录凭证校验
    $response = $app->oauth->code2Session("31f55c2089f4316b6");
    
    // 返回信息
    var_dump($response);
}catch (\Exception $ex){
    echo("error: ".$ex->getMessage());
}
```

#### 文档
文档整理中...

 **bug反馈**

如果您使用过程中发现BUG或者其他问题都欢迎大家提交Issue,或者发送邮件给我 158373108@qq.com，我们将及时修复并更新。
