<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models\shop;

use app\common\models\Model;

class PagesItems extends Model {

    protected $name = "pages_items";

    public function setWidgetNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setParamsAttr($value){
        return json_encode($value,JSON_UNESCAPED_UNICODE);
    }

    public function getParamsAttr($value){
        return json_decode($value,true);
    }
}