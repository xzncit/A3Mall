<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\promotion;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Point extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition["title"] = ["like",'%'.$key["title"].'%'];
            }

            $count = Db::name("promotion_point")
                ->alias("r")
                ->join("goods g","r.goods_id=g.id","LEFT")
                ->where($condition)->count();

            $data = Db::name("promotion_point")
                ->alias("r")
                ->field("r.*,g.photo")
                ->join("goods g","r.goods_id=g.id","LEFT")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key]["id"] = $item["id"];
                $list[$key]["title"] = $item["title"];
                $list[$key]["photo"] = Tool::thumb($item["photo"]);
                $list[$key]['create_time'] = Date::format($item["create_time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("promotion_point")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_point")->delete($id);
            Db::name("promotion_point_item")->where('pid',$id)->delete();
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
            Db::name("promotion_point")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}