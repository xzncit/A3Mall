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
use app\common\model\users\Group as UsersGroup;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Group extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $usersGroup = new UsersGroup();
            $list = $usersGroup->getList([],$limit);

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
            $rs = empty($id) ? [] : Db::name("users_group")->where("id",$id)->find();

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $usersGroup = new UsersGroup();
        if(($obj=$usersGroup::find($data["id"])) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $usersGroup->save($data);
            } catch (\Exception $ex) {
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
            $row = Db::name("users_group")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("users_group")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}