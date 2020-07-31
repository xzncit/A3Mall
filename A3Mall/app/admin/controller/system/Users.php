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
use app\common\model\system\Users as SystemUsers;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Users extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $systemUsers = new SystemUsers();
            $list = $systemUsers->getList([],$limit);
            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list["data"],$list["count"]);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("system_users")->where("id",$id)->find();
            $cat = Db::name("system_manage")->where(["status"=>0])->select()->toArray();

            return View::fetch("",[
                "cat"=>Db::name("system_manage")->where(["status"=>0])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $systemUsers = new SystemUsers();
        if(($obj=$systemUsers::find($data["id"])) != false){
            if(!empty($data["password"]) || !empty($data["confirm_password"])){
                if($data["password"] != $data["confirm_password"]){
                    return Response::returnArray("您输入的两次密码不致。",0);
                }
                $data["password"] = md5($data["password"]);
            }else{
                unset($data["password"],$data["confirm_password"]);
            }

            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            if(empty($data["password"])){
                return Response::returnArray("请填写密码",0);
            }else if(empty($data["confirm_password"])){
                return Response::returnArray("请填写确认密码",0);
            }else if($data["password"] != $data["confirm_password"]){
                return Response::returnArray("您输入的两次密码不致。",0);
            }

            $data["password"] = md5($data["password"]);
            $data["time"] = time();

            try{
                $systemUsers->save($data);
            }catch (\Exception $e){
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
            $row = Db::name("system_users")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if($row["lock"] == 1){
                return Response::returnArray("该用户为系统用户，不允许删除。",0);
            }

            Db::name("system_users")->delete($id);
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

        $row = Db::name("system_users")->where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if($row["lock"] == 1){
            return Response::returnArray("该用户为系统用户，不允许修改。",0);
        }

        try {
            Db::name("system_users")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}