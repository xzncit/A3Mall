<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\goods;

use app\common\models\goods\Goods as GoodsModel;
use app\common\models\Category;

class Goods extends GoodsModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchCatIdAttr($query, $value, $data){
        if(!empty($value) && $value != '-1'){
            $query->where('goods.cat_id','=',$value);
        }
    }

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data){
        if(!empty($value) && $value != '-1'){
            $query->where('goods.status','=',$value);
        }
    }

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchBrandIdAttr($query, $value, $data){
        if(!empty($value) && $value != '-1'){
            $query->where('goods.brand_id','=',$value);
        }
    }

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchTitleAttr($query, $value, $data){
        if(!empty($value)){
            $query->where('goods.title','like','%'.$value.'%');
        }
    }

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchGoodsTypeAttr($query, $value, $data){
        if(isset($value)){
            $array = explode(",",$value);
            if(count($array) == 1){
                $query->where('goods.goods_type','=',$value);
            }else if(count($array) > 1){
                $query->where('goods.goods_type','in',$value);
            }
        }
    }

    public function category(){
        return $this->hasOne(Category::class,"id","cat_id")->bind(["cat_name"=>"title"])->joinType("LEFT");
    }

}