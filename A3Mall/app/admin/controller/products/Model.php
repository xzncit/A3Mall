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
use mall\utils\Date;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Model extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("products_model")->count();
            $data = Db::name("products_model")->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item['create_time']);
                $list[$key]['count'] = Db::name("products_model_data")->where(["pid"=>$item["id"]])->count();
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("products_model")->where("id",$id)->find();

            if(!empty($rs)){
                $rs["attr"] = Db::name("products_model_data")->where(["pid"=>$rs["id"]])->order("sort ASC")->select()->toArray();
            }

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        if(!empty($data["id"])){
            try {
                Db::name("products_model")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data['create_time'] = time();
            if(!Db::name("products_model")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }
            $data["id"] = Db::name("products_model")->getLastInsID();
        }

        $i = 0;
        $arr = array();
        if(!empty($data["attr"]["name"])){
            foreach($data["attr"]["name"] as $key=>$val){
                $attr = array(
                    "pid"=>$data["id"],
                    "name"=>$val,
                    "value"=>$data["attr"]["value"][$key],
                    "type"=>$data["attr"]["type"][$key],
                    "sort"=>$i
                );

                $id = intval($data["attr"]["id"][$key]);
                if($id <= 0){
                    Db::name("products_model_data")->insert($attr);
                    $arr[] = Db::name("products_model_data")->getLastInsID();
                }else{
                    $arr[] = $id;
                    Db::name("products_model_data")->where(["id"=>$id])->update($attr);
                }
                $i++;
            }
        }

        if(!empty($arr)){
            $condition = 'pid="'.$data["id"].'" AND id NOT in('.implode(",",$arr).')';
            Db::name("products_model_data")->where($condition)->delete();
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