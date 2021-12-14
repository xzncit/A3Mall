<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models;

class Payment extends Model {

    protected $name = "payment";

    public function getPayConfig($name){
        $row = $this->where("code",$name)->find();
        if(empty($row["config"])) return [];
        return json_decode($row["config"],true);
    }

}