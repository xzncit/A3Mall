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

class News extends A3Mall{

    protected $name = "wechat_news";

    protected $type = [
        "id"=>"integer",
        "admin_id"=>"integer",
        "is_deleted"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer"
    ];

    public function setMediaIdAttr($value){
        return strip_tags(trim($value));
    }

    public function setLocalUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setArticleIdAttr($value){
        return strip_tags(trim($value));
    }

}