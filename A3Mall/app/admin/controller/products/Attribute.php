<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use app\common\model\products\Attribute as ProductsAttribute;
use app\common\model\products\AttributeData as ProductsAttributeData;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Attribute extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $productsAttribute = new ProductsAttribute();
            $list = $productsAttribute->getList(['pid'=>0],$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("products_attribute")->where("id",$id)->find();

            if(!empty($rs)){
                $rs["attr"] = Db::name("products_attribute_data")->where(["pid"=>$rs["id"]])->order("sort","ASC")->select()->toArray();
            }

            return View::fetch("",[
                "ch"=>Db::name("products_attribute")->where(['pid'=>0])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $productsAttribute = new ProductsAttribute();
        if(($obj=$productsAttribute::find($data["id"])) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $productsAttribute->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
            $data["id"] = $productsAttribute->id;
        }

        $i = 0;
        $arr = [];
        $productsAttributeData = new ProductsAttributeData();
        if(!empty($data["attr"]["name"])){
            foreach($data["attr"]["name"] as $key=>$val){
                $attr = [
                    "pid"=>$data["id"],
                    "value"=>$val,
                    "sort"=>$i
                ];

                $id = intval($data["attr"]["id"][$key]);
                if(($obj=$productsAttributeData::find($id)) == false){
                    $productsAttributeData->insert($attr);
                    $arr[] = $productsAttributeData->id;
                }else{
                    $arr[] = $id;
                    $obj->save($attr);
                }
                $i++;
            }
        }

        if(!empty($arr)){
            $productsAttributeData->where('pid',$data["id"])->where('id','not in',$arr)->delete();
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("products_attribute")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("products_attribute")->delete($id);
            Db::name("products_attribute_data")->where('pid',$id)->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}