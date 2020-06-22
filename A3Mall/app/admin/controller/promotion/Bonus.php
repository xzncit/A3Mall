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
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Bonus extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition[] = ['name',"like",'%'.$key["title"].'%'];
            }

            $count = Db::name("promotion_bonus")
                ->where($condition)->count();

            $data = Db::name("promotion_bonus")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['total'] = $item["used"] . ' / ' . $item["giveout"];
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['time'] = Date::format($item["start_time"]) . ' ~ ' . Date::format($item["end_time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("promotion_bonus")->where("id",$id)->find();

            if(!empty($rs)){
                $rs["start_time"] = date("Y-m-d H:i:s",$rs["start_time"]);
                $rs["end_time"] = date("Y-m-d H:i:s",$rs["end_time"]);
            }

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);

        if($data["start_time"] > $data["end_time"]){
            return Response::returnArray("开始时间不能小于结束时间",0);
        }

        if(!empty($data["id"])){
            try {
                Db::name("promotion_bonus")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data['create_time'] = time();
            if(!Db::name("promotion_bonus")->strict(false)->insert($data)){
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
            $row = Db::name("promotion_bonus")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_bonus")->delete($id);
            Db::name("users_bonus")->where(["bonus_id"=>$id])->delete();
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
            Db::name("promotion_bonus")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}