<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\promotion;

use app\common\models\promotion\PromotionOrder as PromotionOrderModel;

class PromotionOrder extends PromotionOrderModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchNameAttr($query, $value, $data){
        if(!empty($value)){
            $query->where('name','like','%'.$value.'%');
        }
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}