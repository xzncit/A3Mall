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

class Queue extends Model {

    protected $name = "system_queue";

    public function getExecTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}