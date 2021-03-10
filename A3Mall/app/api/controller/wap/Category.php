<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\utils\Data;
use mall\utils\Tool;
use think\facade\Cache;
use think\facade\Db;

class Category extends Base {

    public function index(){
        $category = Data::familyProcess(Db::name("category")
            ->where('module','goods')
            ->where("is_menu",0)
            ->select()->toArray());

        $data = [];
        foreach($category as $key=>$value){
            $data[$value['id']]["title"] = $value["title"];
            foreach($value["children"] as $val){
                $data[$value['id']]["children"][] = [
                    "id"=>$val["id"],
                    "name"=>$val["title"],
                    "thumb_img"=>Tool::thumb($val["photo"],"medium",true)
                ];

                foreach($val["children"] as $v){
                    $data[$value['id']]["children"][] = [
                        "id"=>$v["id"],
                        "name"=>$v["title"],
                        "thumb_img"=>Tool::thumb($v["photo"],"medium",true)
                    ];
                }
            }
        }

        $i = 0;
        $array = [];
        foreach($data as $value){
            $array[$i] = $value;
            $i++;
        }

        return $this->returnAjax("ok",1,$array);
    }

}