<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\utils;


use mall\utils\Check;
use think\facade\Request;

class Image{

    /**
     * 获取会员头像
     * @param $image
     * @param bool $root
     * @return string
     */
    public static function avatar($image,$root=true){
        if(Check::url($image)){
            return $image;
        }

        if(Request::domain() == "http://" || Request::domain() == "https://"){
            $domain = $root ? trim(env("web.app_web_url"),"/") : "";
        }else{
            $domain = $root ? trim(Request::domain(),"/") : "";
        }

        $default = $domain . "/static/images/avatar.png";
        $file = trim($image,"/");
        if(empty($image) || !file_exists($file)){
            return $default;
        }

        return $domain . '/' . $file;
    }

}