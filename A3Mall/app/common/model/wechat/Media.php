<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\wechat;

use app\common\model\base\A3Mall;

class Media extends A3Mall {

    protected $name = "wechat_media";

    protected $type = [
        "id"=>"integer",
        "create_time"=>"integer",
    ];

    public function setAppidAttr($value){
        return strip_tags(trim($value));
    }

    public function setMd5Attr($value){
        return strip_tags(trim($value));
    }

    public function setTypeAttr($value){
        return strip_tags(trim($value));
    }

    public function setMediaIdAttr($value){
        return strip_tags(trim($value));
    }

    public function setLocalUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setMediaUrlAttr($value){
        return strip_tags(trim($value));
    }

}