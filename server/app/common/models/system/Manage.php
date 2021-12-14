<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models\system;

use app\common\models\Model;

class Manage extends Model {

    protected $name = "system_manage";

    public function getPurviewAttr($value){
        if($value == '-1'){
            return $value;
        }

        return json_decode($value,true);
    }

}