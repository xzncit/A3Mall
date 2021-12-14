<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\models\Archives as ArchivesModel;
use app\common\models\Category;

class Archives extends ArchivesModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchPidAttr($query, $value, $data){
        if(!empty($value) && $value != '-1'){
            $query->where('archives.pid','=',$value);
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
            $query->where("archives.title","like","%".$value."%");
        }
    }

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function category(){
        return $this->hasOne(Category::class,'id','pid')->joinType("LEFT")->bind(["cat_name"=>"title"]);
    }

}