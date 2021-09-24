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

class Bonus extends A3Mall{

    protected $name = "promotion_bonus";

    protected $type = [
        "id"=>"integer",
        "amount"=>"float",
        "type"=>"integer",
        "point"=>"integer",
        "giveout"=>"integer",
        "used"=>"integer",
        "order_amount"=>"float",
        "status"=>"integer",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            throw new \Exception("没有数据了哦！",-1);
        }

        $data = $this->where($condition)->order("id","DESC")->paginate($size);

        return [
            "count"=>$count,
            "total"=>$total,
            "data"=>$data->items()
        ];
    }



    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}