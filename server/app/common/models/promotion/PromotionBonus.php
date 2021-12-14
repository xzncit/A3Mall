<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models\promotion;


use app\common\models\Model;

class PromotionBonus extends Model {

    protected $name = "promotion_bonus";

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}