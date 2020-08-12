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
use app\common\model\products\Model as ProductsModel;
use app\common\model\products\ModelData as ProductsModelData;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Model extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $productsModel = new ProductsModel();
            $list = $productsModel->getList([],$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("products_model")->where("id",$id)->find();

            if(!empty($rs)){
                $rs["attr"] = Db::name("products_model_data")->where(["pid"=>$rs["id"]])->order("sort","ASC")->select()->toArray();
            }

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $productsModel = new ProductsModel();
        if(($obj=$productsModel::find($data["id"])) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $productsModel->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data["id"] = $productsModel->id;
        }

        $i = 0;
        $arr = [];
        $productsModelData = new ProductsModelData();
        if(!empty($data["attr"]["name"])){
            foreach($data["attr"]["name"] as $key=>$val){
                $attr = [
                    "pid"=>$data["id"],
                    "name"=>$val,
                    "value"=>$data["attr"]["value"][$key],
                    "type"=>$data["attr"]["type"][$key],
                    "sort"=>$i
                ];

                $id = intval($data["attr"]["id"][$key]);
                if(($model = $productsModelData::find($id)) == false){
                    $productsModelData->insert($attr);
                    $arr[] = $productsModelData->id;
                }else{
                    $arr[] = $id;
                    $model->save($attr);
                }
                $i++;
            }
        }

        if(!empty($arr)){
            $productsModelData->where('pid',$data["id"])->where("id","not in",$arr)->delete();
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("products_model")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("products_model")->delete($id);
            Db::name("products_model_data")->where('pid',$id)->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}