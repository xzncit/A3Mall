<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\utils\Tool;
use think\facade\Db;

class Category extends Base {

    public function index(){
        $category = Db::name("category")
            ->where('pid',0)->where('module','goods')
            ->where("is_menu",0)
            ->select()->toArray();

        $data = [];
        foreach ($category as $key=>$value){
            $child = Db::name("category")
                ->where('pid',$value["id"])->where('module','goods')
                ->where("is_menu",0)
                ->select()->toArray();

            foreach($child as $k=>$v){
                $data[$v['id']]["title"] = $v["title"];
                $children = Db::name("category")
                    ->field("id,title as name,photo as thumb_img")
                    ->where('pid',$v["id"])
                    ->where('module','goods')
                    ->where("is_menu",0)
                    ->select()->toArray();
                $data[$v['id']]["children"] = array_map(function ($rs){
                    $rs["thumb_img"] = Tool::thumb($rs["thumb_img"],"medium",true);
                    return $rs;
                },$children);
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