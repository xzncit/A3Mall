<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\promotion;

use app\common\models\promotion\PromotionBonus as BonusModel;

class PromotionBonus extends BonusModel {

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

}