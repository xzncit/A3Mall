<?php
namespace app\admin\controller\system;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Users extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("system_users")->count();
            $data = Db::name("system_users")->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]["cat_name"] = Db::name("system_manage")->where(["id"=>$item["role_id"]])->value("title");
                $list[$key]['create_time'] = Date::format($item["time"]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
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

        if(!empty($data["id"])){
            if(!empty($data["password"]) || !empty($data["confirm_password"])){
                if($data["password"] != $data["confirm_password"]){
                    return Response::returnArray("您输入的两次密码不致。",0);
                }
                $data["password"] = md5($data["password"]);
            }

            try {
                Db::name("system_users")->strict(false)->where("id",$data['id'])->update($data);
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
            if(!Db::name("system_users")->strict(false)->insert($data)){
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