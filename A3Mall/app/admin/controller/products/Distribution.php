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
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Distribution extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("distribution")->count();
            $data = Db::name("distribution")->order('id ASC')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("distribution")->where("id",$id)->find();

            $weight = [
                "500"=>"500克",
                "1000"=>"1公斤",
                "1500"=>"1.5公斤",
                "2000"=>"2公斤",
                "5000"=>"5公斤",
                "10000"=>"10公斤",
                "20000"=>"20公斤",
                "50000"=>"50公斤"
            ];

            if(!empty($rs['area_group'])){
                $rs["area_group"] = json_decode($rs['area_group'],true);
            }

            if(!empty($rs['first_price_group'])){
                $rs["first_price_group"] = json_decode($rs['first_price_group'],true);
            }

            if(!empty($rs['second_price_group'])){
                $rs["second_price_group"] = json_decode($rs['second_price_group'],true);
            }

            $temp = array();
            if(!empty($rs["area_group"])){
                foreach ($rs["area_group"] as $key => $item) {
                    $area_id = explode(",", $item);
                    $arr = array();
                    foreach ($area_id as $val) {
                        $arr[] = Db::name('area')->where(['id' => $val])->value("name");
                    }
                    $temp[$key]["id"] = $item;
                    $temp[$key]["title"] = implode(",", $arr);
                    $temp[$key]["first"] = $rs["first_price_group"][$key];
                    $temp[$key]["second"] = $rs["second_price_group"][$key];
                }
            }

            $rs["attr"] = $temp;

            View::assign("weight",$weight);
            View::assign("data",$rs);
            return View::fetch();
        }

        $data = Request::post();

        if(!empty($data['area_group'])){
            $data["area_group"] = json_encode($data['area_group']);
        }

        if(!empty($data['first_price_group'])){
            $data["first_price_group"] = json_encode($data['first_price_group']);
        }

        if(!empty($data['second_price_group'])){
            $data["second_price_group"] = json_encode($data['second_price_group']);
        }

        if(!empty($data["id"])){
            try {
                Db::name("distribution")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            if(!Db::name("distribution")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("distribution")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("distribution")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function field(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        $name = strip_tags(trim(Request::get("name")));
        $value = (int)Request::get("value");

        try {
            Db::name("distribution")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}