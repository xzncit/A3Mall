<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model\promotion;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class Order extends A3Mall {

    protected $name = "promotion_order";

    protected $type = [
        "id"=>"integer",
        "group_id"=>"integer",
        "type"=>"integer",
        "amount"=>"float",
        "status"=>"integer",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "create_time"=>"integer",
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setExpressionAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}