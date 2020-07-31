<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

use mall\basic\Area;

class Deliver extends A3Mall{

    protected $type =[
        "id"=>"integer",
        "country"=>"integer",
        "province"=>"integer",
        "city"=>"integer",
        "area"=>"integer",
        "is_default"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        $list = [];
        foreach($data->items() as $key=>$item){
            $list[$key] = $item;
            if($item["province"] && $item["city"] && $item["area"]){
                $list[$key]['area_name'] = Area::get_area([
                    $item["province"],$item["city"],$item["area"]
                ]);
            }else{
                $list[$key]['area_name'] = "";
            }
        }

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setUsernameAttr($value){
        return strip_tags(trim($value));
    }

    public function setCompanyAttr($value){
        return strip_tags(trim($value));
    }

    public function setZipAttr($value){
        return strip_tags(trim($value));
    }

    public function setAddressAttr($value){
        return strip_tags(trim($value));
    }

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhoneAttr($value){
        return strip_tags(trim($value));
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

}