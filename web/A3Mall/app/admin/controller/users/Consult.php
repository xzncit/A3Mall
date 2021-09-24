<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use app\common\model\users\Consult as UsersConsult;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Consult extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            $condition[] = ["pid","=",0];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $filed = $key["cat_id"] == 0 ? "users.username" : "goods.title";
                $condition[] = [$filed,"like",'%'.$key["title"].'%'];
            }

            $usersConsult = new UsersConsult();
            $list = $usersConsult->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function detail(){
        if(Request::isAjax()){
            $data = Request::post();

            if(empty($data["content"])){
                return Response::returnArray("请填写回复内容",0);
            }

            $usersConsult = new UsersConsult();
            $data["admin_id"] = Session::get("system_user_id");
            $data["reply_time"] = time();
            $data["status"] = 1;

            if(($obj=$usersConsult::find($data["id"])) != false){
                $obj->save($data);
                $data["user_id"] = $obj["id"];
                $data["goods_id"] = $obj["id"];
            }

            $data["pid"] = $data["id"];
            unset($data["id"]);
            $usersConsult::create($data);
            return Response::returnArray("操作成功");
        }

        $id = Request::param("id","0","intval");
        if(($row=Db::name("users_consult")->where("id",$id)->find())==false){
            $this->error("您要查找的内容不存在！");
        }

        $row["goods_name"] = Db::name("goods")->where("id",$row["goods_id"])->value("title");
        $users = Db::name("users")->where("id",$row["user_id"])->find();
        $row["username"] = getUserName($users);

        $row["children"] = Db::name("users_consult")->where(["pid"=>$row["id"]])->select()->toArray();

        foreach($row["children"] as $key=>$value){
            $row["children"][$key]["username"] = $value["user_id"] == 0 ? "管理回复" : "会员回复";
        }

        return View::fetch("",[
            "data"=>$row
        ]);
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("users_consult")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("users_consult")->delete($id);
            Db::name("users_consult")->where(["pid"=>$id])->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}