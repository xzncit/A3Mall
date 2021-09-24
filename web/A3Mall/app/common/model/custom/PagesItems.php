<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\custom;

use app\common\model\base\A3Mall;

class PagesItems extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "position_id"=>"integer"
    ];

    public function saveItemData($data, $id){
        if(!Pages::where("id",$id)->count()){
            throw new \Exception("编辑错误，请稍在试",0);
        }

        $this->startTrans();
        $this->where("pid",$id)->delete();
        $array = [];
        foreach($data as $k=>$v){
            if(empty($v)) continue;
            $array[] = [
                'widget_name' => $v['type'],
                'pid'         => $id,
                'position_id' => $k,
                'params'      => $v["value"],
            ];
        }

        if(!$this->saveAll($array)){
            $this->rollback();
            throw new \Exception("保存失败，请稍后在试",0);
        }

        $this->commit();
        return true;
    }

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