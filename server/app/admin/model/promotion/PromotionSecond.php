<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\promotion;

use app\common\models\goods\Goods;
use app\common\models\promotion\PromotionSecond as PromotionSecondModel;

class PromotionSecond extends PromotionSecondModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchTitleAttr($query, $value, $data){
        if(!empty($value)){
            $query->where('Promotion_second.title','like','%'.$value.'%');
        }
    }

    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id')->joinType("LEFT")->bind(["photo"]);
    }

}