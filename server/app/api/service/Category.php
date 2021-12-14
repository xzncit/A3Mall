<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use mall\utils\Data;
use mall\utils\Tool;
use think\facade\Cache;
use app\common\models\Category as CategoryModel;

class Category extends Service {

    /**
     * 获取分类数据
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList(){
        $array = Cache::get("api_category");
        if(empty($array)){
            $category = Data::familyProcess(CategoryModel::where('module','goods')->where("is_menu",0)->select()->toArray());

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

            Cache::set("api_category",$array,strtotime("+10 day"));
        }

        return $array;
    }

}