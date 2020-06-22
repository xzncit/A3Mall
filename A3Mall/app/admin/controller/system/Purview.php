<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\system;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Purview extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("system_manage")->count();
            $data = Db::name("system_manage")->order('id desc')->paginate($limit);

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

            $data = empty($id) ? [] : Db::name("system_manage")->where("id",$id)->find();

            if (isset($data["lock"]) && $data["lock"] == 1) {
                $this->error("该分组己被锁定，不允许修改");
            }

            if(!empty($data['purview']) && $data['purview'] != '-1'){
                $data['purview'] = json_decode($data['purview'],true);
            }

            $system_menu = Db::name("system_purview")->where(['status'=>0,"pid"=>0])->select()->toArray();
            foreach($system_menu as $key=>$item){
                // $system_menu[$key]["children"] = Db::name("system_purview")->where(['status'=>0,"pid"=>$item["id"]])->select();
                $children = Db::name("system_purview")->where(['status'=>0,"pid"=>$item["id"]])->select()->toArray();
                foreach($children as $k=>$v){
                    $children[$k]["children"] = Db::name("system_purview")->where(['status'=>0,"pid"=>$v["id"]])->select()->toArray();
                }

                $system_menu[$key]["children"] = $children;
            }

            return View::fetch("",[
                "group"=>$system_menu,
                "data"=>$data
            ]);
        }

        $data = Request::post();

        $data["purview"] = json_encode($data["purview"],JSON_UNESCAPED_UNICODE);
        if(!empty($data["id"])){
            try {
                Db::name("system_manage")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{

            if(!Db::name("system_manage")->strict(false)->insert($data)){
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
            $row = Db::name("system_manage")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if($row["lock"] == 1){
                return Response::returnArray("该权限为系统权限，不允许删除。",0);
            }

            Db::name("system_manage")->delete($id);
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

        $row = Db::name("system_manage")->where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if($row["lock"] == 1){
            return Response::returnArray("该权限为系统权限，不允许更改。",0);
        }

        try {
            Db::name("system_manage")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}